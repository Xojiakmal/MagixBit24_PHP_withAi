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

    <div class="h-full w-full p-6">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-white mb-8">Superadmin Panel - Kompaniya So'rovlari</h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="bg-green-500/20 border border-green-500/50 text-green-200 p-4 rounded-2xl mb-8 backdrop-blur-md">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="bg-black/20 border border-dark-border rounded-[32px] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-dark-border/50 text-dark-muted text-sm uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium">Kompaniya Nomi</th>
                            <th class="px-6 py-4 font-medium">Yaratuvchi Ismi</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Telefon</th>
                            <th class="px-6 py-4 font-medium">Sana</th>
                            <th class="px-6 py-4 font-medium text-right">Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border/50 text-sm text-white/90">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendingCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-semibold"><?php echo e($company->name); ?></td>
                            <td class="px-6 py-4"><?php echo e($company->owner->name ?? '-'); ?></td>
                            <td class="px-6 py-4"><?php echo e($company->owner->email ?? '-'); ?></td>
                            <td class="px-6 py-4"><?php echo e($company->owner->phone ?? '-'); ?></td>
                            <td class="px-6 py-4"><?php echo e($company->created_at->format('d.m.Y H:i')); ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form method="POST" action="<?php echo e(route('superadmin.approve', $company->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="bg-green-500/10 hover:bg-green-500/20 text-green-400 border border-green-500/30 px-3 py-1.5 rounded-lg transition-colors font-medium">Tasdiqlash</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('superadmin.reject', $company->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1.5 rounded-lg transition-colors font-medium">Rad etish</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-dark-muted">Kutish holatidagi kompaniyalar mavjud emas.</td>
                        </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
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
<?php /**PATH /var/www/resources/views/superadmin/index.blade.php ENDPATH**/ ?>