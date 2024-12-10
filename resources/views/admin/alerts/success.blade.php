@if (session($key ?? 'status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success', // Ikon untuk alert, bisa diubah jadi 'error', 'warning', dll.
                title: 'Berhasil',
                text: '{{ session($key ?? 'status') }}',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
