<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="h-full w-full flex items-center justify-center p-6">
        <div class="max-w-md w-full">
            <h2 class="text-3xl font-bold text-white text-center mb-8">
                <?php echo e($superadminExists ? 'Superadmin Kirish' : 'Superadmin Yaratish'); ?>

            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-2xl mb-6 backdrop-blur-md">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="bg-black/20 border border-dark-border p-8 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                <p class="text-gray-400 mb-6 text-center text-sm">
                    <?php echo e($superadminExists ? 'Tizimga kirish uchun Superadmin email va parolingizni kiriting.' : 'Tizimda birinchi marta Superadmin o\'rnatmoqdasiz. Email va parol yarating.'); ?>

                </p>

                <form method="POST" action="<?php echo e(route('admin.local.authenticate')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email manzil</label>
                        <input type="email" id="email" name="email" required
                            class="w-full bg-dark-bg border border-dark-border text-white rounded-xl px-4 py-3 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            placeholder="admin@example.com">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-8">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Parol</label>
                        <input type="password" id="password" name="password" required
                            class="w-full bg-dark-bg border border-dark-border text-white rounded-xl px-4 py-3 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"
                            placeholder="••••••••">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <button type="submit" class="w-full bg-accent hover:bg-accent/90 text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(var(--accent-rgb),0.3)] hover:shadow-[0_0_25px_rgba(var(--accent-rgb),0.5)]">
                        <?php echo e($superadminExists ? 'Kirish' : 'O\'rnatish va Kirish'); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/admin/local_login.blade.php ENDPATH**/ ?>