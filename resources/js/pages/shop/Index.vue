<script setup lang="ts">
import Cart from '@/fragment/Cart.vue';
import WebLayout from '@/layouts/WebLayout.vue';
import { ShoppingCart } from 'lucide-vue-next';
</script>

<template>
    <WebLayout>
        <div class="mx-auto mt-10 max-w-2xl">
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
                <div
                    class="mb-6 flex items-center gap-3 border-b border-gray-200 pb-4 dark:border-gray-700"
                >
                    <div
                        class="rounded-full bg-violet-100 p-2 dark:bg-violet-900/30"
                    >
                        <ShoppingCart
                            class="h-6 w-6 text-violet-600 dark:text-violet-400"
                        />
                    </div>
                    <h3
                        class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                        Shopping Cart
                    </h3>
                </div>

                <Cart />
            </div>
        </div>
    </WebLayout>
</template>
