<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->orderBy('id', 'DESC')->paginate(10);
        return view('tenant.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'domain_name' => 'required|string|max:255|unique:domains,domain',
            'password' => 'required',
            'confirmed',
            Rules\Password::defaults(),
        ]);

        $tenant = Tenant::create($validated);
        $tenant->domains()->create([
            'domain' => $validated['domain_name'] . '.' . config('app.domain'),
        ]);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        $tenant->loadMissing(['domains']);
        return view('tenant.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'domain_name' => 'required|string|max:255|unique:domains,domain,' . $tenant->domains->first()->id,
        ]);

        $tenant->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($validated['domain_name'] !== $tenant->domains->first()->domain) {
            $tenant->domains()->update([
                'domain' => $validated['domain_name'] . '.' . config('app.domain'),
            ]);
        }

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->domains()->delete();
        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant deleted successfully');
    }
}
