<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

// UI Components
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

// Oruga hooks
import { useOruga } from '@oruga-ui/oruga-next';

// Props
const props = defineProps<{
    post: {
        id: number;
        title: string;
    };
    pcount?: string;
    active?: boolean;
}>();

// Oruga instance
const oruga = useOruga();

// Estado Reactivo
const count = ref(props.pcount || '1');

// Métodos
const submit = () => {
    const url = `/shop/add/${props.post.id}/${count.value}`;

    router.post(
        url,
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                oruga.notification.open({
                    message: 'Cart updated',
                    variant: 'success',
                    position: 'top-right',
                    duration: 2000,
                    closable: true,
                });
            },
        },
    );
};
</script>

<template>
    <div
        class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800"
    >
        <div class="min-w-0 flex-1">
            <Label
                :class="{ 'font-bold text-red-600': active }"
                class="block truncate text-sm font-medium"
            >
                {{ post.title.substring(0, 20)
                }}{{ post.title.length > 20 ? '...' : '' }}
            </Label>
            <Input
                v-model="count"
                type="number"
                min="1"
                class="mt-1 h-9 w-24"
            />
        </div>
        <Button class="shrink-0" size="sm" @click="submit"> Update </Button>
    </div>
</template>
