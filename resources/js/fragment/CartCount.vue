<script setup lang="ts">
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

import { index } from '@/actions/App/Http/Controllers/Shop/CartController';

// Accedemos a las props globales de Inertia
const page = usePage();

// Convertimos el computed 'total'
const total = computed(() => {
    const cart = page.props.cart as Record<string, any>;
    let n = 0;

    // Sumamos las cantidades del carrito
    Object.keys(cart).forEach((k) => {
        n += parseInt(cart[k][1]);
    });

    return n;
});
</script>

<template>
    <Link :href="index().url" class="group fixed right-6 bottom-6 z-50">
        <div
            v-if="total > 0"
            class="absolute -top-1 -right-1 flex h-5 w-5 animate-pulse items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-lg"
        >
            {{ total }}
        </div>

        <div
            class="flex h-14 w-14 items-center justify-center rounded-full border-2 border-white bg-violet-600 p-3 shadow-xl transition-all duration-200 group-hover:scale-110 group-hover:bg-violet-700"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="white"
                width="26"
                height="26"
                viewBox="0 0 24 24"
            >
                <path
                    d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm1.336-5l1.977-7h-16.813l2.938 7h11.898zm4.969-10l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z"
                />
            </svg>
        </div>
    </Link>
</template>
