<script setup lang="ts">
type Props = {
    breadcrumbs?: { title: string; href?: string }[];
};


</script>

<template>
    <div class="px-4 py-6">
        <slot />
    </div>
</template>
