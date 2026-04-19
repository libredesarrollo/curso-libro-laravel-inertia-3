<script setup lang="ts">
import WebLayout from '@/layouts/WebLayout.vue';
import GeneralStep from "@/pages/contact/general/Step.vue";
import Cart from "@/fragment/Cart.vue";
import CartItem from '@/fragment/CartItem.vue';

import { usePage } from '@inertiajs/vue3';

// 1. Definición de props con tipos
const props = withDefaults(
    defineProps<{
        post: {
            id: number;
            title: string;
            image?: string;
            date: string;
            text: string;
            type: string;
            category: {
                title: string;
            };
        };
        errors?: Record<string, any>; // Marcado como opcional con ?
        contactGeneral?: {
            // Objeto opcional
            id: string;
            subject: string;
            type: string;
            message: string;
        };
    }>(), 
    {
        // Definimos el valor por defecto como un objeto vacío
        errors: () => ({}) 
    }
);

const page = usePage();

</script>

<template>
    <WebLayout>
        <div class="container mx-auto px-4">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <img 
                        class="w-full rounded-md shadow-md my-4 mx-auto object-cover" 
                        style="max-width:500px"
                        :src="post.image ? '/image/post/' + post.image : '/image/default.jpg'" 
                        :alt="post.title" 
                    />

                    <h4 class="text-center text-5xl mb-3 font-bold text-gray-900 dark:text-white">
                        {{ post.title }}
                    </h4>

                    <p class="my-4 flex items-center justify-center gap-4">
                        <span class="text-sm text-gray-500 italic font-bold uppercase tracking-widest">
                            {{ post.date }}
                        </span>
                        <span class="rounded-md bg-purple-500 py-1 px-2 text-white text-sm font-semibold">
                            {{ post.category.title }}
                        </span>
                        <span class="rounded-md bg-purple-500 py-1 px-2 text-white text-sm font-semibold capitalize">
                            {{ post.type }}
                        </span>
                    </p>

                    <div v-html="post.text" class="my-5 prose dark:prose-invert max-w-none"></div>

                    <template v-if="post.type === 'advert'">
                        <div class="mycard mb-5 ms-auto block max-w-96 shadow-lg">
                            <div class="mycard-body">
                                <h3 class="text-xl mb-3 font-semibold">Mis productos</h3>
                                <Cart :myItemId="post.id" />
                            </div>
                        </div>

                        <transition name="fade-slide">
                            <div 
                                class="mycard-primary mb-5 block max-w-96 border-l-4 border-primary" 
                                 v-if="!page.props.cart[post.id]">
                                 
                                <div class="mycard-body p-4">
                                    <h3 class="text-xl mb-3 font-semibold">Añadir este artículo</h3>
                                    <CartItem :post="post" />
                                </div>
                            </div>
                        </transition>
                    </template>

                    <hr class="border-gray-200 dark:border-gray-700" />
                    <br>

                    </div>
            </div>
   
            <GeneralStep :contactGeneral="contactGeneral" />
        </div>
    </WebLayout>
</template>

<style scoped>
/* Scoped para evitar que las transiciones afecten a otros componentes */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.4s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>