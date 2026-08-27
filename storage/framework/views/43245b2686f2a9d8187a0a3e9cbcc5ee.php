<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitrix24 - 2-Bosqichli Himoya (Google)</title>
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
<body class="antialiased cosmos-bg min-h-screen flex items-center justify-center text-white p-4">

    <div class="glass-panel p-10 rounded-3xl shadow-2xl max-w-md w-full text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

        <h1 class="text-3xl font-extrabold mb-2 tracking-tight">Superadmin Tasdiqlash</h1>
        <p class="text-gray-300 mb-8">Telegram orqali kirish muvaffaqiyatli! Endi Google orqali 2-bosqich tasdig'idan o'ting.</p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-xl mb-6 backdrop-blur-md text-sm">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="space-y-6 relative z-10">
            <a href="<?php echo e(route('admin.google.redirect')); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 transition duration-300 shadow-lg hover:shadow-red-500/50 transform hover:-translate-y-1 w-full">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                </svg>
                Google orqali kirish
            </a>
            
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-gray-400 hover:text-white transition-colors text-sm underline">
                    Boshqa akkauntga o'tish (Chiqish)
                </button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/resources/views/admin/google_login.blade.php ENDPATH**/ ?>