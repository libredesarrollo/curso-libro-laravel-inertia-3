<script setup lang="ts">
import CartCount from '@/fragment/CartCount.vue';
import ToastContainer from '@/components/ToastContainer.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';

</script>

<template>
    <div>
        <div class="px-4 py-6">
            <slot />
        </div>
        <CartCount />
        <ToastContainer />
    </div>
</template>
