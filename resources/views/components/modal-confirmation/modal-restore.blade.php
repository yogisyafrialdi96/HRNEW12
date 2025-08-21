<style>
    [x-cloak] { display: none !important; }
</style>
<div 
    x-data="confirmRestoreModal()" 
    x-cloak 
    x-show="open"
    x-transition 
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="background-color: rgba(0,0,0,0.5); display: none;"
    @keydown.window.escape="close()"
>
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <!-- Konfirmasi -->
        <div x-show="!success" x-cloak>
            <div class="text-center">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-12 h-12 text-blue-600 mx-auto animate-spin"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v6h6M20 20v-6h-6M4 10a8.001 8.001 0 0114.32-2.906M20 14a8.001 8.001 0 01-14.32 2.906" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">Restore Data?</h2>
                <p class="text-sm text-gray-600 mt-2">Data akan dipulihkan dan tersedia kembali di daftar utama.</p>
                <div class="mt-6 flex justify-center gap-4">
                    <button @click="close()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button @click.prevent="
                        @this.call('restore');
                        success = true;
                        setTimeout(() => showCheck = true, 400);
                    "
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Ya, Restore
                    </button>
                </div>
            </div>
        </div>

        <!-- Sukses -->
        <div x-show="success" x-cloak class="...">
            <div class="flex flex-col items-center justify-center space-y-4 py-6">
                <div class="relative w-16 h-16">
                    <svg class="absolute inset-0 animate-spin-slow text-green-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"/>
                    </svg>
                    <svg x-show="showCheck" x-transition.opacity class="absolute inset-0 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-green-600 text-sm">Data berhasil direstore.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmRestoreModal() {
        return {
            open: @entangle('confirmingRestore'),
            success: @entangle('restoreSuccess'),
            showCheck: false,
            close() {
                this.open = false;
                this.success = false;
                this.showCheck = false;
            },
            init() {
                this.$watch('success', value => {
                    if (value) {
                        setTimeout(() => this.close(), 1500);
                    }
                });
            }
        }
    }
</script>
