{{-- resources/views/partials/alert.blade.php --}}

{{-- Container for Alerts: Fixed at the top-right corner or at the top of the content --}}
<div class="mb-4 space-y-3">
    
    {{-- 1. Pesan Sukses (Success Message) --}}
    @if (session('success') || (session('message') && session('message_type') == 'success'))
        <div class="alert-box flex items-start p-4 border-l-4 border-green-500 bg-green-50 rounded-lg shadow-sm transition-opacity duration-300 relative" role="alert">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
            </div>
            <div class="ml-3 w-full">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') ?? session('message') }}
                </p>
            </div>
            <button type="button" class="ml-auto flex-shrink-0 -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex h-8 w-8 transition close-alert-btn">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- 2. Pesan Error Tunggal (Single Error Message) --}}
    @if (session('error') || (session('message') && session('message_type') == 'error'))
        <div class="alert-box flex items-start p-4 border-l-4 border-red-500 bg-red-50 rounded-lg shadow-sm transition-opacity duration-300 relative" role="alert">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
            </div>
            <div class="ml-3 w-full">
                <p class="text-sm font-medium text-red-800">
                    {{ session('error') ?? session('message') }}
                </p>
            </div>
            <button type="button" class="ml-auto flex-shrink-0 -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex h-8 w-8 transition close-alert-btn">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- 3. Pesan Validasi Form (Multiple Errors from $errors->any()) --}}
    @if ($errors->any())
        <div class="alert-box flex items-start p-4 border-l-4 border-orange-500 bg-orange-50 rounded-lg shadow-sm transition-opacity duration-300 relative" role="alert">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-orange-500 text-xl mt-0.5"></i>
            </div>
            <div class="ml-3 w-full">
                <h3 class="text-sm font-bold text-orange-800 mb-1">Terdapat kesalahan pada input Anda:</h3>
                <ul class="list-disc list-inside text-sm text-orange-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="ml-auto flex-shrink-0 -mx-1.5 -my-1.5 bg-orange-50 text-orange-500 rounded-lg focus:ring-2 focus:ring-orange-400 p-1.5 hover:bg-orange-100 inline-flex h-8 w-8 transition close-alert-btn">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

</div>

{{-- Vanilla JS untuk menutup alert --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mengambil semua tombol close pada alert
        const closeButtons = document.querySelectorAll('.close-alert-btn');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function () {
                const alertBox = this.closest('.alert-box');
                // Menambahkan transisi fade out
                alertBox.style.opacity = '0';
                // Menghapus elemen dari DOM setelah animasi transisi selesai (300ms)
                setTimeout(() => {
                    alertBox.remove();
                }, 300);
            });
        });

        // Opsi Auto-Hide: Menutup alert otomatis setelah 5 detik
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-box');
            alerts.forEach(alertBox => {
                alertBox.style.opacity = '0';
                setTimeout(() => {
                    alertBox.remove();
                }, 300);
            });
        }, 5000);
    });
</script>