<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitrix24 - Login</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .cosmos-bg {
            background-image: url('https://images.unsplash.com/photo-1462331940025-496dfbfc7564?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="antialiased cosmos-bg min-h-screen flex items-center justify-center text-white">

    <div class="glass-panel p-10 rounded-3xl shadow-2xl max-w-md w-full text-center relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

        <h1 class="text-4xl font-extrabold mb-2 tracking-tight">Xush Kelibsiz</h1>
        <p class="text-gray-300 mb-8">Tizimga xavfsiz va tez kirish</p>

        <div id="login-container" class="space-y-6 relative z-10">
            <p class="text-sm text-gray-200">
                Quyidagi tugmani bosib Telegram botimiz orqali tasdiqdan o'ting.
            </p>
            
            <a href="https://t.me/<?php echo e($botUsername); ?>?start=<?php echo e($loginToken); ?>" target="_blank" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition duration-300 shadow-lg hover:shadow-blue-500/50 transform hover:-translate-y-1 w-full">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.892-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                Telegram orqali kirish
            </a>


        </div>
    </div>

    <script>
        const token = "<?php echo e($loginToken); ?>";
        
        setInterval(() => {
            fetch(`/login/check/${token}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'authenticated') {
                        window.location.href = data.redirect;
                    } else if (data.status === 'expired') {
                        window.location.reload();
                    }
                })
                .catch(err => console.error(err));
        }, 2000);
    </script>
</body>
</html>
<?php /**PATH /var/www/resources/views/auth/login.blade.php ENDPATH**/ ?>