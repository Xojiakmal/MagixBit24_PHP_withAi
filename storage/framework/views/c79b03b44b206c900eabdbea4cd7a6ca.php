<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'BitrixClone')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            .cosmos-bg {
                background-image: url('https://images.unsplash.com/photo-1534447677768-be436bb09401?q=80&w=2000&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            /* Override tailwind config background to make it transparent */
            .bg-dark-bg { background-color: transparent !important; }
            
            .glass-panel {
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
    </head>
    <body class="font-sans antialiased cosmos-bg text-white overflow-hidden">
        <div class="flex h-screen w-full bg-black/10"> <!-- Dark overlay -->
            <!-- Sidebar Navigation -->
            <div class="glass-panel m-4 rounded-[32px] w-64 shadow-2xl flex flex-col">
                <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0 flex flex-col p-4 pl-0 relative">
                <!-- Inner container with glassmorphism surface -->
                <div class="flex-1 min-w-0 rounded-[32px] shadow-2xl relative overflow-hidden flex flex-col">
                    
                    <!-- Background Glass Layer (Separated to fix Chrome white background bug with overflow) -->
                    <div class="absolute inset-0 glass-panel pointer-events-none"></div>

                    <!-- Scrollable Content Layer -->
                    <div class="flex-1 overflow-y-auto relative z-10 custom-scrollbar flex flex-col">
                        <!-- Top Navigation / Header -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($header)): ?>
                            <header class="pt-8 px-10 pb-4 border-b border-white/10 bg-slate-900/40 backdrop-blur-md relative z-20 shrink-0">
                                <div class="flex items-center justify-between">
                                    <?php echo e($header); ?>

                                    
                                    <!-- User Profile Mini -->
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center font-bold shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                                            <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                        </div>
                                    </div>
                                </div>
                            </header>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Page Content -->
                        <main class="flex-1 p-10">
                            <?php echo e($slot); ?>

                        </main>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>