<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['task']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['task']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bg-slate-800/60 backdrop-blur-md p-5 rounded-2xl shadow-sm border border-dark-border cursor-move hover:border-accent hover:shadow-[0_0_15px_rgba(155,114,255,0.2)] transition-all duration-300 group relative overflow-hidden"
     draggable="true"
     @dragstart="event.dataTransfer.setData('task_id', <?php echo e($task->id); ?>)">
     
    <!-- Glassmorphism decorations -->
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
     
    <!-- Delete Button (Visible on hover) -->
    <button wire:click="deleteTask(<?php echo e($task->id); ?>)" class="absolute top-4 right-4 text-dark-muted hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
    </button>

    <div class="font-semibold text-white pr-6 text-lg"><?php echo e($task->title); ?></div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->description): ?>
        <div class="text-xs text-dark-muted mt-2 line-clamp-2 leading-relaxed"><?php echo e($task->description); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <div class="flex items-center justify-between mt-5 pt-4 border-t border-dark-border/50">
        <div class="inline-flex items-center px-2 py-1 rounded text-xs font-bold uppercase tracking-wider
            <?php if($task->priority == 'high'): ?> bg-red-500/10 text-red-400 border border-red-500/20
            <?php elseif($task->priority == 'medium'): ?> bg-amber-500/10 text-amber-400 border border-amber-500/20
            <?php else: ?> bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 <?php endif; ?>
        ">
            <?php echo e($task->priority); ?>

        </div>
        
        <div class="flex items-center space-x-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->assigned_to): ?>
                <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs mr-2 text-white" title="User #<?php echo e($task->assigned_to); ?>">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->due_date): ?>
                <div class="text-xs flex items-center font-medium <?php echo e(\Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-400' : 'text-dark-muted'); ?>">
                    <svg class="w-4 h-4 mr-1 <?php echo e(\Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-500' : 'text-accent'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /var/www/resources/views/components/task-card.blade.php ENDPATH**/ ?>