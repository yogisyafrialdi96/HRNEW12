<div 
    x-data="{
        open: @entangle('confirmingDelete'),
        success: false,
        showCheck: false,
        close() {
            this.open = false;
            this.success = false;
            this.showCheck = false;
        },
        init() {
            this.$watch('success', value => {
                if (value) {
                    setTimeout(() => {
                        this.close();
                        @this.call('resetDeleteModal');
                    }, 1500);
                }
            });
        }
    }"
    x-init="init()"
    x-show="open"
    x-transition.opacity
    class="fixed top-0 left-0 w-full h-full z-50 flex items-center justify-center backdrop-blur-sm bg-black/40"
    style="display: none;"
>
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm text-center relative overflow-hidden transition-all duration-300"
        :class="success 
            ? 'border border-green-300 ring-2 ring-green-200/60' 
            : 'border border-red-300 ring-2 ring-red-200/60'">

        <!-- Success State -->
        <div x-show="success" x-transition.opacity>
            <div class="relative w-16 h-16 mx-auto mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-green-500 border-t-transparent animate-spin" x-show="!showCheck"></div>
                <div x-show="showCheck" x-transition.opacity class="absolute inset-0 w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h2 class="text-lg font-semibold text-green-600 mb-2">Berhasil!</h2>
            <p class="text-sm text-green-700">Data berhasil dihapus</p>
        </div>

        <!-- Confirm State -->
        <div x-show="!success" x-transition.opacity>
            <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex justify-center gap-3">
                <button @click="close()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Batal
                </button>
                <button @click.prevent="
                    setTimeout(() => {
                        @this.call('{{ $onConfirm }}');
                        success = true;
                        setTimeout(() => showCheck = true, 500);
                    }, 100);
                "
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>