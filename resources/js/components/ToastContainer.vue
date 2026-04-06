<script setup lang="ts">
import { useNotification } from '@/composables/useNotification';
import { X } from 'lucide-vue-next';

const { notifications, remove } = useNotification();

const variantClasses: Record<string, string> = {
    success: 'bg-green-500',
    danger: 'bg-red-500',
    warning: 'bg-yellow-500',
    info: 'bg-blue-500',
};
</script>

<template>
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
        <TransitionGroup name="toast">
            <div
                v-for="notification in notifications"
                :key="notification.id"
                :class="[
                    'flex items-center gap-3 rounded-lg px-4 py-3 text-white shadow-lg',
                    variantClasses[notification.variant] || 'bg-gray-500',
                ]"
            >
                <span class="flex-1">{{ notification.message }}</span>
                <button
                    @click="remove(notification.id)"
                    class="shrink-0 opacity-70 hover:opacity-100"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
