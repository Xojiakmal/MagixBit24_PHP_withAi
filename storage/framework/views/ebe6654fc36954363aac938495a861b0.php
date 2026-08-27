<div class="h-full bg-transparent text-white pb-10">
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight"><?php echo e(__('Bitimlar Voronkasi')); ?></h2>
                <p class="text-dark-muted mt-1 text-sm"><?php echo e(__('Savdo jarayonini interaktiv Kanban orqali kuzating')); ?></p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create_deal')): ?>
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-accent hover:bg-accent-hover focus:outline-none transition-all duration-300 shadow-[0_0_20px_rgba(155,114,255,0.4)]">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <?php echo e(__('Yangi Bitim')); ?>

            </button>
            <?php endif; ?>
        </div>

        <!-- View Tabs -->
        <div class="mb-6 flex items-center space-x-1 bg-white/5 p-1 rounded-xl w-fit border border-dark-border">
            <button wire:click="setView('kanban')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($currentView === 'kanban' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <?php echo e(__('Kanban')); ?>

            </button>
            <button wire:click="setView('list')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($currentView === 'list' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                <?php echo e(__('Ro\'yxat')); ?> (List)
            </button>
            <button wire:click="setView('activities')" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($currentView === 'activities' ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <?php echo e(__('Faoliyatlar')); ?> (Activities)
            </button>
        </div>
        
        <!-- View Content -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentView === 'kanban'): ?>
        <!-- Kanban Board -->
            <div x-data
                 @wheel="if (Math.abs($event.deltaY) > Math.abs($event.deltaX) && !$event.shiftKey) { $el.scrollLeft += $event.deltaY; $event.preventDefault(); }"
                 class="flex overflow-x-auto space-x-6 pb-6 custom-scrollbar h-full w-full">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="flex-shrink-0 w-[350px] bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md flex flex-col"
                 x-data
                 @drop.prevent="$wire.updateDealStage(
                    event.dataTransfer.getData('deal_id'),
                    <?php echo e($stage->id); ?>

                 )"
                 @dragover.prevent>
                 
                <div class="px-5 py-4 border-b border-dark-border/50 bg-slate-900/60 rounded-t-3xl flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white tracking-wider flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full mr-2 bg-accent shadow-[0_0_8px_rgba(155,114,255,0.8)]"></span>
                        <?php echo e($stage->name); ?>

                    </h3>
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                        <?php echo e(count($deals[$stage->id] ?? [])); ?> ta
                    </span>
                </div>
                
                <div class="p-4 flex-1 space-y-4 overflow-y-auto min-h-[500px]">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $deals[$stage->id] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="bg-slate-800/60 backdrop-blur-md p-5 rounded-2xl shadow-sm border border-dark-border cursor-pointer hover:border-accent hover:shadow-[0_0_15px_rgba(155,114,255,0.2)] transition-all duration-300 group relative overflow-hidden"
                         draggable="true"
                         @dragstart="event.dataTransfer.setData('deal_id', <?php echo e($deal->id); ?>)"
                         wire:click="viewDeal(<?php echo e($deal->id); ?>)">
                         
                        <!-- Glassmorphism decorations -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                         
                        <!-- Delete Button (Visible on hover) -->
                        <button wire:click="deleteDeal(<?php echo e($deal->id); ?>)" class="absolute top-4 right-4 text-dark-muted hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="font-semibold text-white pr-6 text-lg"><?php echo e($deal->title); ?></div>
                        <div class="text-sm font-bold text-accent mt-2">$ <?php echo e(number_format($deal->amount, 0, ',', ' ')); ?></div>
                        
                        <?php
                            $assignedUsers = \App\Models\User::whereIn('id', $deal->assigned_users ?? [])->pluck('name')->toArray();
                            $assignedRoles = \App\Models\Role::whereIn('id', $deal->assigned_roles ?? [])->pluck('name')->toArray();
                            $assignedTeams = \App\Models\Team::whereIn('id', $deal->assigned_teams ?? [])->pluck('name')->toArray();
                            $assignees = array_merge($assignedUsers, $assignedRoles, $assignedTeams);
                        ?>
                        <div class="mt-3 text-xs text-gray-400 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="truncate"><?php echo e(!empty($assignees) ? implode(', ', $assignees) : 'Biriktirilmagan'); ?></span>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal->contact): ?>
                            <div class="text-xs text-dark-muted mt-4 pt-4 border-t border-dark-border/50 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-dark-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <?php echo e($deal->contact->name); ?>

                                </div>
                                <a href="<?php echo e(route('projects.tasks', ['dealId' => $deal->id])); ?>" class="text-accent hover:text-accent-hover text-xs font-semibold flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    <?php echo e(__('Vazifalar')); ?>

                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-xs text-dark-muted mt-4 pt-4 border-t border-dark-border/50 flex justify-end">
                                <a href="<?php echo e(route('projects.tasks', ['dealId' => $deal->id])); ?>" class="text-accent hover:text-accent-hover text-xs font-semibold flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    <?php echo e(__('Vazifalar')); ?>

                                </a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <?php elseif($currentView === 'list'): ?>
        <!-- List View -->
        <div class="bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-slate-900/60 text-xs uppercase text-gray-400 border-b border-dark-border/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Deal Name</th>
                            <th scope="col" class="px-6 py-4 font-bold">Stage</th>
                            <th scope="col" class="px-6 py-4 font-bold">Client</th>
                            <th scope="col" class="px-6 py-4 font-bold">Amount</th>
                            <th scope="col" class="px-6 py-4 font-bold">Responsible</th>
                            <th scope="col" class="px-6 py-4 font-bold">Created</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border/50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $listDeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white"><?php echo e($deal->title); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                                        <?php echo e($deal->stage->name ?? '-'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal->contact): ?>
                                        <div class="text-white"><?php echo e($deal->contact->name); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($deal->contact->phone); ?></div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-medium text-accent">
                                    $ <?php echo e(number_format($deal->amount, 0, ',', ' ')); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($deal->assignee->name ?? '-'); ?>

                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    <?php echo e($deal->created_at->diffForHumans()); ?>

                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?php echo e(route('projects.tasks', ['dealId' => $deal->id])); ?>" class="text-accent hover:text-accent-hover text-xs font-semibold inline-flex items-center">
                                        Vazifalar
                                    </a>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <?php echo e(__('Hech qanday bitim topilmadi.')); ?>

                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php elseif($currentView === 'activities'): ?>
        <!-- Activities View -->
            <div x-data
                 @wheel="if (Math.abs($event.deltaY) > Math.abs($event.deltaX) && !$event.shiftKey) { $el.scrollLeft += $event.deltaY; $event.preventDefault(); }"
                 class="flex overflow-x-auto space-x-6 pb-6 custom-scrollbar h-full w-full">
            <?php
                $columns = [
                    'overdue' => ['title' => 'Overdue'],
                    'today' => ['title' => 'Due today'],
                    'this_week' => ['title' => 'Due this week'],
                    'next_week' => ['title' => 'Due next week'],
                    'idle' => ['title' => 'Idle'],
                    'later' => ['title' => 'Due later'],
                ];
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="flex-shrink-0 w-[350px] bg-slate-900/40 rounded-3xl border border-dark-border shadow-[0_8px_32px_rgba(0,0,0,0.3)] backdrop-blur-md flex flex-col">
                <div class="px-5 py-4 border-b border-dark-border/50 bg-slate-900/60 rounded-t-3xl flex justify-between items-center">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full mr-2 
                            <?php if($key == 'overdue'): ?> bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]
                            <?php elseif($key == 'today'): ?> bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.8)]
                            <?php elseif($key == 'this_week'): ?> bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]
                            <?php elseif($key == 'next_week'): ?> bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]
                            <?php elseif($key == 'idle'): ?> bg-gray-500 shadow-[0_0_8px_rgba(107,114,128,0.8)]
                            <?php else: ?> bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)] <?php endif; ?>
                        "></span>
                        <?php echo e($col['title']); ?>

                    </h3>
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-dark-surface border border-dark-border text-dark-text">
                        <?php echo e(count($activityDeals[$key] ?? [])); ?>

                    </span>
                </div>
                
                <div class="p-4 flex-1 space-y-4 overflow-y-auto min-h-[500px]">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activityDeals[$key] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="bg-slate-800/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-dark-border hover:border-accent transition-colors group relative overflow-hidden">
                        <!-- Glassmorphism decorations -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="font-semibold text-white pr-6 text-base"><?php echo e($deal->title); ?></div>
                        <div class="text-xs font-bold text-accent mt-1">$ <?php echo e(number_format($deal->amount, 0, ',', ' ')); ?></div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deal->contact): ?>
                            <div class="text-xs text-blue-400 mt-2"><?php echo e($deal->contact->name); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <div class="text-xs text-dark-muted mt-3 pt-3 border-t border-dark-border/50 flex justify-between items-center">
                            <span class="text-gray-500"><?php echo e($deal->stage->name ?? 'Bosqichsiz'); ?></span>
                            <a href="<?php echo e(route('projects.tasks', ['dealId' => $deal->id])); ?>" class="text-gray-400 hover:text-white flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <?php echo e(__('Vazifalar')); ?>

                            </a>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Create Deal Slide-Over -->
        <?php if (isset($component)) { $__componentOriginal6ef8dd008d82ca426db4c565227b1725 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ef8dd008d82ca426db4c565227b1725 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.slide-over','data' => ['wire:model' => 'isCreatingDeal','id' => 'createDealPanel','title' => ''.e(__('Yangi bitim (Deal) qo\'shish')).'','maxWidth' => '6xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('slide-over'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'isCreatingDeal','id' => 'createDealPanel','title' => ''.e(__('Yangi bitim (Deal) qo\'shish')).'','maxWidth' => '6xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

             <?php $__env->slot('actions', null, []); ?> 
                <button wire:click="saveDeal" class="px-5 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-lg transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                    <?php echo e(__('Saqlash')); ?>

                </button>
             <?php $__env->endSlot(); ?>

            <!-- Split Pane Layout -->
            <div class="flex flex-col lg:flex-row w-full h-full">
                <!-- Left Pane: General Data -->
                <div class="w-full lg:w-2/3 p-6 space-y-6 overflow-y-auto border-r border-white/5">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Bitim nomi')); ?></label>
                            <input type="text" wire:model="newDealTitle" placeholder="<?php echo e(__('Masalan: Web sayt yaratish xizmati')); ?>" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-lg font-medium">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newDealTitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Summasi va Valyuta (Amount and currency)')); ?></label>
                            <div class="flex space-x-2 relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">$</span>
                                <input type="tel" wire:model="newDealAmount" placeholder="0.00" class="w-full bg-white/5 border border-dark-border rounded-xl pl-8 pr-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-lg font-medium">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-white/5 rounded-2xl border border-white/5">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Mijoz (Client)')); ?> *</label>
                            <input type="text" wire:model="newDealClientName" placeholder="<?php echo e(__('Mijoz ismi')); ?>" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newDealClientName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Telefon raqam (Phone)')); ?> *</label>
                            <input type="tel" wire:model="newDealClientPhone" oninput="this.value = this.value.replace(/[^0-9\+\s]/g, '')" placeholder="+998 90 123 45 67" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newDealClientPhone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-white/5 rounded-2xl border border-white/5">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Boshlanish vaqti')); ?></label>
                            <input type="datetime-local" wire:model="newDealStartDate" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Tugash vaqti')); ?></label>
                            <input type="datetime-local" wire:model="newDealEndDate" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm">
                        </div>
                    </div>

                    <div class="p-5 bg-white/5 rounded-2xl border border-white/5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Mas\'ullar (Xodimlar, Rollar, Jamoalar)')); ?></label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                    <h5 class="text-xs font-bold text-gray-400 mb-2"><?php echo e(__('Xodimlar')); ?></h5>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                            <input type="checkbox" wire:model="newDealAssignedUsers" value="<?php echo e($u->id); ?>" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="group-hover:translate-x-0.5 transition-transform"><?php echo e($u->name); ?></span>
                                        </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                                <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                    <h5 class="text-xs font-bold text-gray-400 mb-2"><?php echo e(__('Rollar')); ?></h5>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Role::whereNotIn('name', ['Admin'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                            <input type="checkbox" wire:model="newDealAssignedRoles" value="<?php echo e($r->id); ?>" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="group-hover:translate-x-0.5 transition-transform"><?php echo e($r->name); ?></span>
                                        </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                                <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                    <h5 class="text-xs font-bold text-gray-400 mb-2"><?php echo e(__('Jamoalar')); ?></h5>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Team::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                            <input type="checkbox" wire:model="newDealAssignedTeams" value="<?php echo e($t->id); ?>" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                            <span class="group-hover:translate-x-0.5 transition-transform"><?php echo e($t->name); ?></span>
                                        </label>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="text-white font-semibold mt-4"><?php echo e(__('Qo\'shimcha parametrlar')); ?></h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Custom Select for Deal Type -->
                            <div x-data="{ open: false, selected: <?php if ((object) ('newDealType') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('newDealType'->value()); ?>')<?php echo e('newDealType'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('newDealType'); ?>')<?php endif; ?>.defer }" class="relative">
                                <label class="block text-sm font-medium text-white/60 mb-2"><?php echo e(__('Bitim turi')); ?> (Deal Type)</label>
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                    <span x-text="selected === 'regular' ? '<?php echo e(__('Oddiy savdo')); ?>' : (selected === 'service' ? '<?php echo e(__('Xizmat ko\'rsatish')); ?>' : (selected === 'complex' ? '<?php echo e(__('Kompleks sotuv')); ?>' : (selected === 'delivery' ? '<?php echo e(__('Yetkazib berish')); ?>' : '<?php echo e(__('Tanlang')); ?>')))"></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden" style="display: none;">
                                    <div @click="selected = 'regular'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Oddiy savdo')); ?></div>
                                    <div @click="selected = 'service'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Xizmat ko\'rsatish')); ?></div>
                                    <div @click="selected = 'complex'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Kompleks sotuv')); ?></div>
                                    <div @click="selected = 'delivery'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Yetkazib berish')); ?></div>
                                </div>
                            </div>
                            
                            <!-- Custom Select for Source -->
                            <div x-data="{ open: false, selected: <?php if ((object) ('newDealSource') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('newDealSource'->value()); ?>')<?php echo e('newDealSource'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('newDealSource'); ?>')<?php endif; ?>.defer }" class="relative">
                                <label class="block text-sm font-medium text-white/60 mb-2"><?php echo e(__('Manba')); ?> (Source)</label>
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex justify-between items-center bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                    <span x-text="
                                        selected === 'telegram' ? 'Telegram' : 
                                        (selected === 'call' ? '<?php echo e(__('Qo\'ng\'iroq')); ?>' : 
                                        (selected === 'email' ? 'Email' : 
                                        (selected === 'website' ? '<?php echo e(__('Veb-sayt')); ?>' : 
                                        (selected === 'admin' ? '<?php echo e(__('Admin (Kompaniya egasi)')); ?>' : 
                                        (selected === 'other' ? '<?php echo e(__('Boshqa')); ?>' : '<?php echo e(__('Tanlang')); ?>')))))
                                    "></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-50 w-full mt-1 bg-slate-900 border border-dark-border rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto custom-scrollbar" style="display: none;">
                                    <div @click="selected = 'telegram'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Telegram</div>
                                    <div @click="selected = 'call'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Qo\'ng\'iroq')); ?></div>
                                    <div @click="selected = 'email'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors">Email</div>
                                    <div @click="selected = 'website'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Veb-sayt')); ?></div>
                                    <div @click="selected = 'admin'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Admin (Kompaniya egasi)')); ?></div>
                                    <div @click="selected = 'other'; open = false" class="px-4 py-2 text-sm text-gray-300 hover:bg-accent/20 hover:text-white cursor-pointer transition-colors"><?php echo e(__('Boshqa')); ?></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2"><?php echo e(__('Tafsilotlar (Description)')); ?></label>
                            <textarea wire:model="newDealDescription" rows="4" placeholder="<?php echo e(__('Bitim tafsilotlari...')); ?>" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all custom-scrollbar"></textarea>
                        </div>
                        
                    </div>
                </div>

                <!-- Right Pane: Products -->
                <div class="w-full lg:w-1/3 p-6 bg-black/20 space-y-6">
                    <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-3"><?php echo e(__('Mahsulotlar')); ?> (Products)</h3>
                    
                    <div class="flex space-x-2 border-b border-dark-border mb-4">
                        <button wire:click="$set('productTab', 'select')" class="pb-2 text-sm font-medium transition-colors border-b-2 <?php echo e($productTab === 'select' ? 'text-accent border-accent' : 'text-gray-500 border-transparent hover:text-gray-300'); ?>"><?php echo e(__('Tanlash')); ?></button>
                        <button wire:click="$set('productTab', 'create')" class="pb-2 text-sm font-medium transition-colors border-b-2 <?php echo e($productTab === 'create' ? 'text-accent border-accent' : 'text-gray-500 border-transparent hover:text-gray-300'); ?>"><?php echo e(__('Yangi qo\'shish')); ?></button>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($productTab === 'select'): ?>
                        <div class="space-y-2 max-h-64 overflow-y-auto custom-scrollbar pr-2">
                            <label class="block text-sm font-medium text-white/60 mb-2"><?php echo e(__('Barcha mahsulotlar')); ?> (All Products)</label>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->allProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex justify-between items-center p-3 bg-white/5 border border-dark-border rounded-lg hover:border-accent/50 transition-colors group">
                                    <div class="flex-1 cursor-pointer" wire:click="addProductFromList(<?php echo e($product->id); ?>)" title="<?php echo e(__('Bitimga qo\'shish')); ?>">
                                        <div class="text-sm font-medium text-white"><?php echo e($product->name); ?></div>
                                        <div class="text-xs text-gray-400 mt-1"><?php echo e(__('O\'lchov')); ?>: <?php echo e($product->unit); ?></div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Add button (visible on hover) -->
                                        <button wire:click="addProductFromList(<?php echo e($product->id); ?>)" class="text-accent opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-white/10 rounded" title="<?php echo e(__('Qo\'shish')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                        <!-- Delete from DB button -->
                                        <button wire:click.stop="deleteProductFromDb(<?php echo e($product->id); ?>)" class="text-gray-500 hover:text-red-400 p-1 hover:bg-white/10 rounded transition-colors" title="<?php echo e(__('Bazadan o\'chirish')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <div class="text-sm text-gray-500 text-center py-4 border border-dashed border-dark-border rounded-lg">
                                    <?php echo e(__('Hozircha mahsulot yo\'q. "Yangi qo\'shish" orqali yarating.')); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4 bg-white/5 p-4 rounded-xl border border-white/10">
                            <div>
                                <label class="block text-sm font-medium text-white/60 mb-2"><?php echo e(__('Mahsulot nomi')); ?></label>
                                <input type="text" wire:model="newProductName" placeholder="<?php echo e(__('Masalan: Veb sayt yaratish')); ?>" class="w-full bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newProductName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white/60 mb-2"><?php echo e(__('O\'lchov birligi')); ?></label>
                                <select wire:model="newProductUnit" class="w-full bg-black/20 border border-dark-border rounded-lg px-3 py-2 text-white text-sm focus:border-accent outline-none">
                                    <option value="dona">dona</option>
                                    <option value="kg">kg</option>
                                    <option value="litr">litr</option>
                                    <option value="sentner">sentner</option>
                                    <option value="tonna">tonna</option>
                                    <option value="metr">metr</option>
                                    <option value="kv.m">kv.m</option>
                                    <option value="oy">oy</option>
                                </select>
                            </div>
                            <button wire:click="createAndAddProduct" class="w-full py-2 bg-accent/20 text-accent border border-accent/50 rounded-lg hover:bg-accent hover:text-white transition-colors text-sm font-medium">
                                <?php echo e(__('Yaratish va Biriktirish')); ?>

                            </button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Selected Products List -->
                    <div class="pt-6 border-t border-white/10 space-y-4">
                        <label class="block text-sm font-medium text-white/60 mb-2"><?php echo e(__('Biriktirilgan mahsulotlar')); ?></label>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($dealProducts) > 0): ?>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dealProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="flex justify-between items-center p-3 bg-white/5 border border-dark-border rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-white"><?php echo e($product['name']); ?></div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center space-x-1 border border-dark-border rounded px-2 py-1 bg-black/30">
                                                <input type="tel" wire:model.live="dealProducts.<?php echo e($index); ?>.quantity" class="w-12 bg-transparent text-white text-sm text-center outline-none">
                                                <span class="text-xs text-gray-400"><?php echo e($product['unit'] ?? 'dona'); ?></span>
                                            </div>
                                            <button wire:click="removeProduct(<?php echo e($index); ?>)" class="text-gray-500 hover:text-red-400 transition-colors" title="<?php echo e(__('Ro\'yxatdan olib tashlash')); ?>">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-sm text-gray-500 text-center py-4 border border-dashed border-dark-border rounded-lg">
                                <?php echo e(__('Hozircha mahsulot qo\'shilmagan')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
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

    <!-- Deal Details Slide-Over -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedDeal): ?>
    <?php if (isset($component)) { $__componentOriginal6ef8dd008d82ca426db4c565227b1725 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ef8dd008d82ca426db4c565227b1725 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.slide-over','data' => ['wire:model' => 'isViewingDeal','id' => 'viewDealPanel','title' => ''.e(__('Bitim Tafsilotlari')).'','maxWidth' => '4xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('slide-over'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'isViewingDeal','id' => 'viewDealPanel','title' => ''.e(__('Bitim Tafsilotlari')).'','maxWidth' => '4xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('projects.tasks', ['dealId' => $selectedDeal->id])); ?>" class="px-5 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-lg transition-colors shadow-[0_0_15px_rgba(155,114,255,0.4)]">
                <?php echo e(__('Vazifalarga o\'tish')); ?>

            </a>
            <button wire:click="closeDealView" class="px-5 py-2 bg-dark-surface hover:bg-white/5 border border-dark-border text-white text-sm font-semibold rounded-lg transition-colors">
                <?php echo e(__('Yopish')); ?>

            </button>
         <?php $__env->endSlot(); ?>

        <div class="p-6 space-y-8">
            <!-- Header Section -->
            <div class="bg-black/20 border border-dark-border rounded-3xl p-6 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-accent/20 rounded-full blur-[50px] pointer-events-none"></div>
                
                <h2 class="text-3xl font-bold text-white mb-2"><?php echo e($selectedDeal->title); ?></h2>
                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-black text-accent">$ <?php echo e(number_format($selectedDeal->amount, 0, ',', ' ')); ?></span>
                    <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-gray-300 border border-white/10">
                        <?php echo e($selectedDeal->stage->name ?? 'Noma\'lum'); ?>

                    </span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8">
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1"><?php echo e(__('Mijoz')); ?></p>
                        <p class="text-white font-medium"><?php echo e($selectedDeal->contact->name ?? __('Noma\'lum')); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1"><?php echo e(__('Telefon')); ?></p>
                        <p class="text-white font-medium"><?php echo e($selectedDeal->contact->phone ?? __('Noma\'lum')); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1"><?php echo e(__('Boshlanish')); ?></p>
                        <p class="text-white font-medium"><?php echo e($selectedDeal->start_date ? $selectedDeal->start_date->format('d.m.Y H:i') : '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-dark-muted font-semibold uppercase mb-1"><?php echo e(__('Tugash')); ?></p>
                        <p class="text-white font-medium"><?php echo e($selectedDeal->end_date ? $selectedDeal->end_date->format('d.m.Y H:i') : __('Cheklanmagan')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Assignments Section -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4"><?php echo e(__('Mas\'ullar (Biriktirilganlar)')); ?></h3>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->hasRole('Admin') || auth()->user()->can('assign_deal') || auth()->user()->managerOf): ?>
                    <!-- Multi-assignment Form Component pattern -->
                    <div x-data="{ 
                        users: <?php echo e(json_encode($selectedDeal->assigned_users ?? [])); ?>, 
                        roles: <?php echo e(json_encode($selectedDeal->assigned_roles ?? [])); ?>, 
                        teams: <?php echo e(json_encode($selectedDeal->assigned_teams ?? [])); ?>,
                        save() {
                            $wire.updateDealAssignments(<?php echo e($selectedDeal->id); ?>, this.users, this.roles, this.teams);
                        }
                    }">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                <h5 class="text-xs font-bold text-gray-400 mb-2"><?php echo e(__('Xodimlar')); ?></h5>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                        <input type="checkbox" value="<?php echo e($u->id); ?>" x-model.number="users" @change="save()" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                        <span class="group-hover:translate-x-0.5 transition-transform"><?php echo e($u->name); ?></span>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                            <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                <h5 class="text-xs font-bold text-gray-400 mb-2"><?php echo e(__('Rollar')); ?></h5>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Role::whereNotIn('name', ['Admin'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                        <input type="checkbox" value="<?php echo e($r->id); ?>" x-model.number="roles" @change="save()" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                        <span class="group-hover:translate-x-0.5 transition-transform"><?php echo e($r->name); ?></span>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                            <div class="bg-black/20 border border-dark-border rounded-xl p-3 max-h-48 overflow-y-auto custom-scrollbar">
                                <h5 class="text-xs font-bold text-gray-400 mb-2"><?php echo e(__('Jamoalar')); ?></h5>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Team::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <label class="flex items-center space-x-3 text-sm text-gray-300 hover:text-white cursor-pointer py-1.5 px-2 hover:bg-white/5 rounded-lg transition-colors group">
                                        <input type="checkbox" value="<?php echo e($t->id); ?>" x-model.number="teams" @change="save()" class="rounded bg-black/50 border-white/20 text-accent focus:ring-accent focus:ring-offset-0 focus:ring-2 focus:border-accent w-4 h-4 transition-all cursor-pointer">
                                        <span class="group-hover:translate-x-0.5 transition-transform"><?php echo e($t->name); ?></span>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-gray-300 text-sm">
                            <?php
                                $assignedUsers = \App\Models\User::whereIn('id', $selectedDeal->assigned_users ?? [])->pluck('name')->toArray();
                                $assignedRoles = \App\Models\Role::whereIn('id', $selectedDeal->assigned_roles ?? [])->pluck('name')->toArray();
                                $assignedTeams = \App\Models\Team::whereIn('id', $selectedDeal->assigned_teams ?? [])->pluck('name')->toArray();
                                $assignees = array_merge($assignedUsers, $assignedRoles, $assignedTeams);
                            ?>
                            <?php echo e(!empty($assignees) ? implode(', ', $assignees) : 'Biriktirilmagan'); ?>

                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-bold text-white mb-4">Qo'shimcha ma'lumotlar</h3>
                    <div class="bg-black/20 border border-dark-border rounded-xl p-5 space-y-4">
                        <div>
                            <span class="text-xs text-dark-muted uppercase font-bold">Bitim turi</span>
                            <p class="text-white mt-1 capitalize"><?php echo e($selectedDeal->deal_type); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-dark-muted uppercase font-bold">Manba (Source)</span>
                            <p class="text-white mt-1 capitalize"><?php echo e($selectedDeal->source); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-dark-muted uppercase font-bold"><?php echo e(__('Tafsilotlar')); ?></span>
                            <p class="text-white mt-1 text-sm whitespace-pre-line"><?php echo e($selectedDeal->description ?: __('Kiritilmagan')); ?></p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white mb-4"><?php echo e(__('Mahsulotlar')); ?> (Products)</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedDeal->products->count() > 0): ?>
                        <div class="bg-black/20 border border-dark-border rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm text-gray-300">
                                <thead class="bg-black/40 text-xs text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold"><?php echo e(__('Nomi')); ?></th>
                                        <th class="px-4 py-3 font-semibold text-center"><?php echo e(__('Miqdori')); ?></th>
                                        <th class="px-4 py-3 font-semibold text-right"><?php echo e(__('Narxi')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-dark-border/50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $selectedDeal->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <tr>
                                            <td class="px-4 py-3"><?php echo e($product->name); ?></td>
                                            <td class="px-4 py-3 text-center"><?php echo e($product->pivot->quantity); ?> <?php echo e($product->unit); ?></td>
                                            <td class="px-4 py-3 text-right text-accent font-semibold">$<?php echo e(number_format($product->price, 0, ',', ' ')); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="bg-white/5 border border-dashed border-white/10 rounded-xl p-6 text-center text-gray-400 text-sm">
                            <?php echo e(__('Mahsulotlar qo\'shilmagan')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /var/www/resources/views/livewire/deal-kanban-board.blade.php ENDPATH**/ ?>