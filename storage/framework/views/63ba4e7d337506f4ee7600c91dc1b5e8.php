<div wire:poll.5s class="mt-8">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('Admin')): ?>
        <div class="mb-6 bg-black/20 border border-dark-border p-4 rounded-2xl flex items-center justify-between">
            <div>
                <h4 class="text-white font-semibold">Tarixni qayta tiklash</h4>
                <p class="text-xs text-dark-muted">Storage'dan yuklab olingan .json/.log faylni yuklab, eski ma'lumotlarni tiklang</p>
            </div>
            <div class="flex items-center space-x-4">
                <input type="file" wire:model="logFile" accept=".json,.log" class="text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 cursor-pointer">
                <button wire:click="restoreLogs" class="px-4 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-xl shadow-[0_0_15px_rgba(155,114,255,0.4)] transition-colors" wire:loading.attr="disabled">Tiklash</button>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
            <div class="mb-4 text-green-400 text-sm bg-green-400/10 p-3 rounded-xl border border-green-400/20"><?php echo e(session('message')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
            <div class="mb-4 text-red-400 text-sm bg-red-400/10 p-3 rounded-xl border border-red-400/20"><?php echo e(session('error')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Activity Widget -->
    <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <!-- Glassmorphism decorations -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-5 h-5 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Xodimlar Faoliyati
            </h3>
            <button wire:click="expandActivity" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-colors text-gray-400 hover:text-white" title="Kattalashtirish">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
        </div>
        
        <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar pr-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex space-x-3 items-start border-l-2 border-accent/30 pl-4 py-1 relative">
                    <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-black border-2 border-accent"></div>
                    <div>
                        <p class="text-sm text-gray-300">
                            <strong class="text-white"><?php echo e($activity->user->name ?? 'Tizim'); ?></strong> 
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->action == 'created'): ?> yaratdi
                            <?php elseif($activity->action == 'deleted'): ?> o'chirdi
                            <?php elseif($activity->action == 'assigned'): ?> biriktirdi
                            <?php elseif($activity->action == 'status_changed'): ?> holatini o'zgartirdi
                            <?php else: ?> amalini bajardi
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="text-accent"><?php echo e(class_basename($activity->subject_type)); ?></span> (ID: <?php echo e($activity->subject_id); ?>)
                        </p>
                        <p class="text-xs text-dark-muted mt-1"><?php echo e($activity->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="text-gray-500 text-sm text-center">Hech qanday faoliyat mavjud emas.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- History Widget -->
    <div class="bg-black/20 isolate border border-dark-border p-6 rounded-[32px] shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-xl relative overflow-hidden group">
        <!-- Glassmorphism decorations -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-400/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                O'zgarishlar Tarixi
            </h3>
            <button wire:click="expandHistory" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-colors text-gray-400 hover:text-white" title="Kattalashtirish">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
        </div>
        
        <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar pr-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                    <div class="flex justify-between mb-2">
                        <p class="text-sm font-semibold text-white"><?php echo e($hist->user->name ?? 'Tizim'); ?></p>
                        <span class="text-xs text-dark-muted"><?php echo e($hist->created_at->diffForHumans()); ?></span>
                    </div>
                    <p class="text-xs text-gray-400 mb-2">
                        Obyekt: <span class="text-blue-400"><?php echo e(class_basename($hist->subject_type)); ?> #<?php echo e($hist->subject_id); ?></span>
                    </p>
                    <div class="bg-black/30 rounded-lg p-2 text-xs font-mono break-words space-y-1">
                        <?php
                            $oldVals = is_string($hist->old_values) ? json_decode($hist->old_values, true) : $hist->old_values;
                            $newVals = is_string($hist->new_values) ? json_decode($hist->new_values, true) : $hist->new_values;
                            $keys = array_unique(array_merge(array_keys($oldVals ?? []), array_keys($newVals ?? [])));
                            $changesCount = 0;
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $keys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key === 'updated_at' || $key === 'created_at'): ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php continue; ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($changesCount >= 2): ?>
                                <?php $changesCount++; continue; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php
                                $oVal = is_array($oldVals[$key] ?? '') ? json_encode($oldVals[$key], JSON_UNESCAPED_UNICODE) : ($oldVals[$key] ?? 'bo\'sh');
                                $nVal = is_array($newVals[$key] ?? '') ? json_encode($newVals[$key], JSON_UNESCAPED_UNICODE) : ($newVals[$key] ?? 'bo\'sh');
                                $changesCount++;
                            ?>
                            <div class="flex items-center space-x-2 flex-wrap">
                                <span class="text-gray-400 font-semibold"><?php echo e($key); ?>:</span>
                                <span class="text-red-400 line-through"><?php echo e(Str::limit((string)$oVal, 20)); ?></span>
                                <svg class="w-3 h-3 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span class="text-green-400"><?php echo e(Str::limit((string)$nVal, 20)); ?></span>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($changesCount > 2): ?>
                            <div class="text-dark-muted text-[10px] mt-1 italic">+ yana <?php echo e($changesCount - 2); ?> ta o'zgarish</div>
                        <?php elseif($changesCount === 0): ?>
                            <div class="text-gray-500 text-xs">Hech qanday ko'rsatiladigan o'zgarish yo'q</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="text-gray-500 text-sm text-center">Tarix bo'sh.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        </div>
    </div>

    <!-- Expanded Activity Modal -->
    <?php if (isset($component)) { $__componentOriginal6ef8dd008d82ca426db4c565227b1725 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ef8dd008d82ca426db4c565227b1725 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.slide-over','data' => ['wire:model' => 'isExpandedActivity','id' => 'expandedActivity','title' => 'Barcha Faoliyatlar','maxWidth' => '4xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('slide-over'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'isExpandedActivity','id' => 'expandedActivity','title' => 'Barcha Faoliyatlar','maxWidth' => '4xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <button wire:click="collapseActivity" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                Yopish
            </button>
         <?php $__env->endSlot(); ?>
        
        <div class="p-6">
            <div class="space-y-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="flex space-x-4 items-start border-b border-dark-border/50 pb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent to-purple-600 flex items-center justify-center text-white font-bold shrink-0 shadow-lg">
                            <?php echo e(substr($activity->user->name ?? 'T', 0, 1)); ?>

                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="text-base text-gray-200">
                                    <strong class="text-white"><?php echo e($activity->user->name ?? 'Tizim'); ?></strong> 
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->action == 'created'): ?> yangi ma'lumot yaratdi
                                    <?php elseif($activity->action == 'deleted'): ?> ma'lumotni o'chirdi
                                    <?php elseif($activity->action == 'assigned'): ?> xodim biriktirdi
                                    <?php elseif($activity->action == 'status_changed'): ?> holatini o'zgartirdi
                                    <?php else: ?> amalini bajardi
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <span class="text-xs text-dark-muted font-mono bg-white/5 px-2 py-1 rounded"><?php echo e($activity->created_at->format('d.m.Y H:i:s')); ?></span>
                            </div>
                            <p class="text-sm text-accent mt-1">Obyekt turi: <?php echo e(class_basename($activity->subject_type)); ?> | Obyekt ID: <?php echo e($activity->subject_id); ?></p>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ef8dd008d82ca426db4c565227b1725)): ?>
<?php $attributes = $__attributesOriginal6ef8dd008d82ca426db4c565227b1725; ?>
<?php unset($__attributesOriginal6ef8dd008d82ca426db4c565227b1725); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ef8dd008d82ca426db4c565227b1725)): ?>
<?php $component = $__componentOriginal6ef8dd008d82ca426db4c565227b1725; ?>
<?php unset($__componentOriginal6ef8dd008d82ca426db4c565227b1725); ?>
<?php endif; ?>

    <!-- Expanded History Modal -->
    <?php if (isset($component)) { $__componentOriginal6ef8dd008d82ca426db4c565227b1725 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ef8dd008d82ca426db4c565227b1725 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.slide-over','data' => ['wire:model' => 'isExpandedHistory','id' => 'expandedHistory','title' => 'Barcha O\'zgarishlar Tarixi','maxWidth' => '5xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('slide-over'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'isExpandedHistory','id' => 'expandedHistory','title' => 'Barcha O\'zgarishlar Tarixi','maxWidth' => '5xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <button wire:click="collapseHistory" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                Yopish
            </button>
         <?php $__env->endSlot(); ?>
        
        <div class="p-6">
            <div class="bg-black/20 border border-dark-border rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-black/40 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-1/6">Sana va Vaqt</th>
                            <th class="px-6 py-4 font-semibold w-1/6">Xodim</th>
                            <th class="px-6 py-4 font-semibold w-1/6">Obyekt</th>
                            <th class="px-6 py-4 font-semibold w-1/2">O'zgarishlar (Eski -> Yangi)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border/50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-dark-muted">
                                    <?php echo e($hist->created_at->format('d.m.Y')); ?><br>
                                    <span class="text-white"><?php echo e($hist->created_at->format('H:i:s')); ?></span>
                                </td>
                                <td class="px-6 py-4 font-medium text-white"><?php echo e($hist->user->name ?? 'Tizim'); ?></td>
                                <td class="px-6 py-4 text-blue-400">
                                    <?php echo e(class_basename($hist->subject_type)); ?> #<?php echo e($hist->subject_id); ?>

                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    <?php
                                        $oldVals = is_string($hist->old_values) ? json_decode($hist->old_values, true) : $hist->old_values;
                                        $newVals = is_string($hist->new_values) ? json_decode($hist->new_values, true) : $hist->new_values;
                                        $keys = array_unique(array_merge(array_keys($oldVals ?? []), array_keys($newVals ?? [])));
                                    ?>
                                    <div class="space-y-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $keys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key === 'updated_at'): ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php continue; ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div class="bg-black/30 p-2 rounded border border-white/5">
                                                <div class="text-gray-400 font-bold mb-1"><?php echo e($key); ?>:</div>
                                                <div class="flex items-center space-x-2 flex-wrap">
                                                    <span class="text-red-400 bg-red-400/10 px-1 rounded break-all"><?php echo e(is_array($oldVals[$key] ?? '') ? json_encode($oldVals[$key]) : ($oldVals[$key] ?? 'null')); ?></span>
                                                    <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    <span class="text-green-400 bg-green-400/10 px-1 rounded break-all"><?php echo e(is_array($newVals[$key] ?? '') ? json_encode($newVals[$key]) : ($newVals[$key] ?? 'null')); ?></span>
                                                </div>
                                            </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ef8dd008d82ca426db4c565227b1725)): ?>
<?php $attributes = $__attributesOriginal6ef8dd008d82ca426db4c565227b1725; ?>
<?php unset($__attributesOriginal6ef8dd008d82ca426db4c565227b1725); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ef8dd008d82ca426db4c565227b1725)): ?>
<?php $component = $__componentOriginal6ef8dd008d82ca426db4c565227b1725; ?>
<?php unset($__componentOriginal6ef8dd008d82ca426db4c565227b1725); ?>
<?php endif; ?>

</div>
<?php /**PATH /var/www/resources/views/livewire/admin-dashboard-activities.blade.php ENDPATH**/ ?>