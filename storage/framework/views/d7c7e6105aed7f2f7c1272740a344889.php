<div class="p-4 sm:p-8">
    <div class="max-w-2xl mx-auto glass-panel rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-accent rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold text-white mb-6 tracking-tight">Mening Profilim</h2>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/50 text-green-300">
                    <?php echo e($successMessage); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Name (Site Username) -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Saytdagi Ismingiz</label>
                    <input type="text" id="name" wire:model="name" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-sm mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Telefon Raqam</label>
                    <input type="text" id="phone" wire:model="phone" oninput="this.value = this.value.replace(/[^0-9\+\s]/g, '')" placeholder="+998901234567" class="w-full bg-white/5 border border-dark-border rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-accent focus:border-accent transition-all">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-sm mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Telegram Username (Read Only) -->
                <div>
                    <label for="telegram_username" class="block text-sm font-medium text-gray-300 mb-2">Telegram Username</label>
                    <input type="text" id="telegram_username" wire:model="telegram_username" class="w-full bg-black/30 border border-dark-border rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" readonly>
                    <p class="text-xs text-gray-500 mt-2">Ushbu maydonni o'zgartirib bo'lmaydi. U faqat telegram orqali tizimga kirish uchun xizmat qiladi.</p>
                </div>

                <div class="pt-4 flex items-center justify-end">
                    <button type="submit" class="bg-accent hover:bg-accent-hover text-white px-6 py-3 rounded-xl shadow-lg shadow-accent/20 transition-all font-semibold inline-flex items-center space-x-2">
                        <svg wire:loading.delay wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Saqlash</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /var/www/resources/views/livewire/profile-settings.blade.php ENDPATH**/ ?>