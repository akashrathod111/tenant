@push('custom-scripts')
    <script type="text/javascript">
        $(".btn-delete").click(function(e) {
            e.preventDefault();
            var form = $(this).parents("form");

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    </script>
@endpush
