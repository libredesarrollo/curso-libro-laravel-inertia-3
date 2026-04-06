<script setup lang="ts">
import CartCount from '@/fragment/CartCount.vue';
import ToastContainer from '@/components/ToastContainer.vue';
</script>

<template>
    <div class="px-4 py-6">
        <slot />
    </div>
    <CartCount />
    <ToastContainer />
</template>
