<div
    x-data="{
        open: @entangle('confirmingForceDelete'),
        success: @entangle('forceDeleteSuccess'),
        close() {
            this.open = false;
            this.success = false;
        },
        init() {
            this.$watch('success', value => {
                if (value) {
                    setTimeout(() => this.close(), 1500);
                }
            });
        }
    }"
    x-show="open"
    x-transition
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="background-color: rgba(0,0,0,0.5); display: none;"
    @keydown.window.escape="close()"
>
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md border-red-500 ring-2 ring-red-300/40 shadow-red-300/50">

        <!-- Konfirmasi -->
        <div x-show="!success" x-cloak class="text-center">
            <div class="mb-4">
                <svg class="w-25 h-25 text-red-600 mx-auto animate-pulse" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Hapus Permanen Data?</h2>
            <p class="text-sm text-gray-600 mt-2">Data akan dihapus selamanya dan tidak dapat dikembalikan.</p>
            <div class="mt-6 flex justify-center gap-4">
                <button @click="close()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    Batal
                </button>
                <button @click.prevent="@this.forceDelete()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Ya, Hapus Permanen
                </button>
            </div>
        </div>

        <!-- Sukses -->
        <div x-show="success" x-cloak class="flex flex-col items-center justify-center space-y-4 py-6">
            <div class="relative w-16 h-16">
                <svg class="absolute inset-0 animate-spin-slow text-green-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"/>
                </svg>
                <svg class="absolute inset-0 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-green-600 text-sm">Data berhasil dihapus permanen.</p>
        </div>
    </div>
</div>