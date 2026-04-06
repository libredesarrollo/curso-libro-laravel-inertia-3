<script setup lang="ts">
import CartItem from '@/fragment/CartItem.vue';

// Definimos las props con TypeScript
interface Props {
    myItemId?: number;
}

// Aplicamos valores por defecto (myItemId será 0 si no se recibe)
withDefaults(defineProps<Props>(), {
    myItemId: 0,
});
</script>

<template>
    <div class="space-y-3">
        <div
            v-for="(item, key) in $page.props.cart"
            :key="key"
            class="-mx-2 rounded-lg px-2 transition-all duration-300 hover:bg-gray-50 dark:hover:bg-gray-700/50"
        >
            <CartItem
                :post="item[0]"
                :pcount="item[1].toString()"
                :active="myItemId === item[0].id"
            />
        </div>
    </div>
</template>
