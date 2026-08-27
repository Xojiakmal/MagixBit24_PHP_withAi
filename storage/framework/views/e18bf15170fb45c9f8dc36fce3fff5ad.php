<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id', 'title' => '', 'maxWidth' => '4xl']));

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

foreach (array_filter((['id', 'title' => '', 'maxWidth' => '4xl']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    '6xl' => 'max-w-6xl',
    '7xl' => 'max-w-7xl',
    'full' => 'max-w-full',
    default => 'max-w-4xl',
};
?>

<div
    x-data="{ show: <?php if ((object) ($attributes->wire('model')) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($attributes->wire('model')->value()); ?>')<?php echo e($attributes->wire('model')->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($attributes->wire('model')); ?>')<?php endif; ?> }"
    x-show="show"
    id="<?php echo e($id); ?>"
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;"
    x-on:keydown.escape.window="show = false"
>
    <!-- Background backdrop -->
    <div 
        x-show="show" 
        class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
        x-transition:enter="ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="show = false"
    ></div>

    <!-- Slide-over panel -->
    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
        <div 
            x-show="show" 
            class="w-screen <?php echo e($maxWidthClass); ?> transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:enter="translate-x-full"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="translate-x-full"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
            <div class="h-full flex flex-col bg-[#1c1c1e] border-l border-white/10 shadow-2xl isolate relative">
                <!-- Decorative background elements -->
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-accent/10 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

                <!-- Header -->
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between relative z-10 bg-black/20">
                    <h2 class="text-xl font-bold text-white"><?php echo e($title); ?></h2>
                    <div class="flex items-center space-x-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actions)): ?>
                            <?php echo e($actions); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button x-on:click="show = false" class="text-gray-400 hover:text-white transition-colors bg-white/5 hover:bg-white/10 p-2 rounded-full">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto relative z-10 custom-scrollbar flex">
                    <?php echo e($slot); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
<?php /**PATH /var/www/resources/views/components/slide-over.blade.php ENDPATH**/ ?>