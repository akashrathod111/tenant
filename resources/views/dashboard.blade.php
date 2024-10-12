<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-gradient-to-r from-purple-500 to-indigo-600 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold">{{ __('Welcome to Your Dashboard') }}</h2>
                    <p class="mt-2">{{ __("You're logged in and ready to go!") }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @role('super_admin')
                    <div
                        class="bg-gradient-to-br from-red-400 to-red-600 overflow-hidden shadow-lg sm:rounded-lg transform hover:scale-105 transition-transform duration-300">
                        <div class="p-6 text-white h-full flex flex-col justify-between">
                            <h3 class="text-lg font-semibold">Total Super Admins</h3>
                            <p class="text-4xl font-bold my-4">{{ $totalSuperAdmins }}</p>
                            <a href="{{ route('users.index') }}"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-white text-red-600 rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-red-100 active:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Details
                            </a>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-400 to-green-600 overflow-hidden shadow-lg sm:rounded-lg transform hover:scale-105 transition-transform duration-300">
                        <div class="p-6 text-white h-full flex flex-col justify-between">
                            <h3 class="text-lg font-semibold">Total Admins</h3>
                            <p class="text-4xl font-bold my-4">{{ $totalAdmins }}</p>
                            <a href="{{ route('users.index') }}"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-white text-green-600 rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-green-100 active:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Details
                            </a>
                        </div>
                    </div>
                @endrole

                @role('super_admin|admin')
                    <div class="bg-gradient-to-br from-teal-400 to-teal-600 overflow-hidden shadow-lg sm:rounded-lg transform hover:scale-105 transition-transform duration-300">
                        <div class="p-6 text-white h-full flex flex-col justify-between">
                            <h3 class="text-lg font-semibold">Total Users</h3>
                            <p class="text-4xl font-bold my-4">{{ $totalUsers }}</p>
                            <a href="{{ route('users.index') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-white text-teal-600 rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-teal-50 active:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Details
                            </a>
                        </div>
                    </div>
                @endrole
            </div>
        </div>
    </div>
</x-app-layout>
