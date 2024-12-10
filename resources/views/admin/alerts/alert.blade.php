@if (session($key ?? 'error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error', // Ikon untuk alert, bisa diubah jadi 'error', 'warning', dll.
                title: 'Gagal',
                text: '{{ session($key ?? 'error') }}',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
