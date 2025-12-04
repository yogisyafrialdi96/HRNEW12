<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white overflow-hidden">
    <!-- Decorative Elements -->
    <div class="fixed inset-0 pointer-events-none">
        <!-- Stars -->
        <div class="absolute top-20 left-10 w-2 h-2 bg-pink-300 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-3 h-3 bg-blue-300 rounded-full animate-pulse" style="animation-delay: 0.5s"></div>
        <div class="absolute bottom-32 left-20 w-2 h-2 bg-purple-300 rounded-full animate-pulse" style="animation-delay: 1s"></div>
        <div class="absolute top-60 right-40 w-2 h-2 bg-yellow-300 rounded-full animate-pulse" style="animation-delay: 1.5s"></div>
        
        <!-- Planets -->
        <div class="absolute top-10 right-10 w-16 h-16 bg-gradient-to-br from-pink-200 to-purple-200 rounded-full opacity-60"></div>
        <div class="absolute bottom-20 left-10 w-24 h-24 bg-gradient-to-br from-blue-200 to-cyan-200 rounded-full opacity-60"></div>
        <div class="absolute top-1/3 left-1/4 w-12 h-12 bg-gradient-to-br from-yellow-200 to-orange-200 rounded-full opacity-40"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center px-6 py-12 relative">
        <div class="max-w-2xl w-full text-center">
            <!-- 403 Text -->
            <div class="mb-6">
                <h1 class="text-9xl font-bold text-gray-800 mb-2">403</h1>
                <div class="flex justify-center gap-2 mb-4">
                    <div class="w-3 h-3 bg-pink-300 rounded-full"></div>
                    <div class="w-3 h-3 bg-blue-300 rounded-full"></div>
                    <div class="w-3 h-3 bg-purple-300 rounded-full"></div>
                </div>
            </div>

            <!-- Message -->
            <h2 class="text-2xl font-semibold text-gray-700 mb-3">
                Forbidden!
            </h2>
            <p class="text-gray-500 mb-8 text-md leading-relaxed max-w-md mx-auto">
                You are not authorized to view this page
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a 
                    href="javascript:history.back()" 
                    class="px-8 py-3 bg-white border-2 border-purple-200 text-gray-700 rounded-full hover:bg-purple-50 hover:border-purple-300 transition-all duration-300 shadow-sm w-full sm:w-auto">
                    ← Kembali
                </a>
                <a 
                    href="/" 
                    class="px-8 py-3 bg-gradient-to-r from-purple-300 to-pink-300 text-white rounded-full hover:shadow-lg transition-all duration-300 w-full sm:w-auto">
                    🚀 Kembali ke Bumi
                </a>
            </div>

            <!-- Fun Message -->
            <div class="mt-12">
                <p class="text-gray-400 text-sm">
                    ✨ Jangan khawatir, astronot kami akan membantu Anda menemukan jalan pulang
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.4;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</body>
</html>