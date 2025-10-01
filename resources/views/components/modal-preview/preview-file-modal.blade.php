<!-- resources/views/components/preview-modal.blade.php -->
<div
    x-data="{
        open: false,
        fileUrl: '',
        isPreviewable(url) {
            const extensions = ['pdf', 'jpg', 'jpeg', 'png'];
            const ext = url.split('.').pop().toLowerCase();
            return extensions.includes(ext);
        }
    }"
    x-on:preview-file.window="
        if (isPreviewable($event.detail.url)) {
            fileUrl = $event.detail.url;
            open = true;
        } else {
            alert('File tidak dapat dipreview. Hanya pdf, jpg, jpeg, png.');
        }
    "
    x-show="open"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
    x-cloak
>
    <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-3xl p-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold">Preview Dokumen</h2>
            <div class="flex items-center gap-3">
                <!-- Tombol Download -->
                <a
                    :href="fileUrl"
                    download
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 text-sm"
                >
                    <!-- Heroicon download -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021
                              18.75V16.5M7.5 12l4.5 4.5m0 0l4.5-4.5m-4.5
                              4.5V3" />
                    </svg>
                    Download
                </a>

                <!-- Tombol Close -->
                <button @click="open = false"
                        class="text-gray-500 hover:text-black text-xl font-bold leading-none">
                    &times;
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="border rounded p-2 max-h-[70vh] overflow-auto">
            <!-- PDF -->
            <template x-if="fileUrl.endsWith('.pdf')">
                <iframe :src="fileUrl" class="w-full h-[60vh]" frameborder="0"></iframe>
            </template>

            <!-- Image -->
            <template x-if="fileUrl.match(/\.(jpg|jpeg|png)$/)">
                <img :src="fileUrl" class="max-w-full max-h-[60vh] mx-auto" />
            </template>
        </div>
    </div>
</div>
