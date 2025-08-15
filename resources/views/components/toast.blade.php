<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'info',
        toastElement: null,
        init() {
            // Store reference to toast element
            this.toastElement = this.$el;
            
            // Move toast to body to ensure it's always on top
            document.body.appendChild(this.toastElement);
            
            // Watch for show changes to manage body positioning
            this.$watch('show', (value) => {
                if (value) {
                    // Ensure toast is always at the end of body (highest z-index context)
                    document.body.appendChild(this.toastElement);
                }
            });
        }
    }"
    x-init="init()"
    x-on:toast.window="
        const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
        message = data.message;
        type = data.type;
        show = true;
        setTimeout(() => show = false, 5000);
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
    x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
    x-transition:leave-end="opacity-0 transform translate-x-full scale-95"
    class="fixed top-5 right-5 z-[99999] flex items-center w-full max-w-xs p-4 text-gray-500 bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-gray-200/50 dark:text-gray-400 dark:bg-gray-800/95 dark:border-gray-700/50"
    style="display: none;"
    role="alert"
    aria-live="polite"
>
    <!-- Enhanced Progress Bar -->
    <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r rounded-b-xl transition-all duration-[5000ms] ease-linear"
         :class="{
             'from-blue-400 to-blue-600': type === 'info',
             'from-green-400 to-green-600': type === 'success',
             'from-yellow-400 to-yellow-600': type === 'warning',
             'from-red-400 to-red-600': type === 'error',
         }"
         x-show="show"
         x-transition:enter="transition-all ease-linear duration-[5000ms]"
         x-transition:enter-start="w-full"
         x-transition:enter-end="w-0"
    ></div>

    <!-- ENHANCED ICON with Animation -->
    <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-xl shadow-sm transition-transform duration-200 hover:scale-110"
         :class="{
             'text-blue-600 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/50 dark:text-blue-400': type === 'info',
             'text-green-600 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/50 dark:text-green-400': type === 'success',
             'text-yellow-600 bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/50 dark:to-yellow-800/50 dark:text-yellow-400': type === 'warning',
             'text-red-600 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/50 dark:text-red-400': type === 'error',
         }"
    >
        <template x-if="type === 'success'">
            <div class="relative">
                <!-- Success checkmark with animation -->
                <svg class="w-6 h-6 animate-bounce" fill="currentColor" stroke="none" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <!-- Success ring animation -->
                <div class="absolute inset-0 rounded-full border-2 border-green-400 animate-ping opacity-20"></div>
            </div>
        </template>
        
        <template x-if="type === 'error'">
            <div class="relative">
                <!-- Error X with shake animation -->
                <svg class="w-6 h-6 animate-pulse" fill="currentColor" stroke="none" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <!-- Error ring animation -->
                <div class="absolute inset-0 rounded-full border-2 border-red-400 animate-ping opacity-20"></div>
            </div>
        </template>
        
        <template x-if="type === 'warning'">
            <div class="relative">
                <!-- Warning triangle with pulse -->
                <svg class="w-6 h-6 animate-pulse" fill="currentColor" stroke="none" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
                <!-- Warning ring animation -->
                <div class="absolute inset-0 rounded-full border-2 border-yellow-400 animate-ping opacity-20"></div>
            </div>
        </template>
        
        <template x-if="type === 'info'">
            <div class="relative">
                <!-- Info icon with subtle animation -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <!-- Info ring animation -->
                <div class="absolute inset-0 rounded-full border-2 border-blue-400 animate-ping opacity-20"></div>
            </div>
        </template>
    </div>

    <!-- ENHANCED MESSAGE -->
    <div class="ms-4 flex-1">
        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1"
             :class="{
                 'text-blue-800 dark:text-blue-200': type === 'info',
                 'text-green-800 dark:text-green-200': type === 'success',
                 'text-yellow-800 dark:text-yellow-200': type === 'warning',
                 'text-red-800 dark:text-red-200': type === 'error',
             }"
        >
            <span x-show="type === 'success'">Berhasil!</span>
            <span x-show="type === 'error'">Error!</span>
            <span x-show="type === 'warning'">Peringatan!</span>
            <span x-show="type === 'info'">Informasi</span>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed" x-text="message"></div>
    </div>

    <!-- ENHANCED CLOSE BUTTON -->
    <button 
        @click="show = false" 
        class="ms-3 flex-shrink-0 inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 bg-gray-100/50 dark:bg-gray-700/50 hover:bg-gray-200/80 dark:hover:bg-gray-600/80 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:focus:ring-gray-600 group"
        aria-label="Close notification"
    >
        <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <!-- Toast Actions (Optional) -->
    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200" x-show="false">
        <!-- Pause/Resume button for long messages -->
        <button class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full hover:bg-gray-600 dark:hover:bg-gray-300 transition-colors duration-200"></button>
        <button class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full hover:bg-gray-600 dark:hover:bg-gray-300 transition-colors duration-200"></button>
        <button class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full hover:bg-gray-600 dark:hover:bg-gray-300 transition-colors duration-200"></button>
    </div>
</div>

<style>
/* Ensure toast always stays on top of everything, including flux modals */
[x-data*="show"] {
    position: fixed !important;
    z-index: 999999 !important;
    pointer-events: auto !important;
}

/* Animation for shake effect on error */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Enhanced backdrop blur for better visibility */
.toast-backdrop-blur {
    backdrop-filter: blur(12px) saturate(1.8);
    -webkit-backdrop-filter: blur(12px) saturate(1.8);
}
</style>