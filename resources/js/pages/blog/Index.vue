<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import WebLayout from '@/layouts/WebLayout.vue';
import Pagination from '@/components/shared/Pagination.vue';
import { Button } from '@/components/ui/button';

import {
    ArrowLeft,
} from 'lucide-vue-next';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

// Importamos el composable reutilizable
import { useFilters } from '@/composables/useFilters';

import {
    index,
    show
} from '@/actions/App/Http/Controllers/Blog/PostController';

// 1. Definición de Props (Equivale a props: {})
const props = defineProps<{
    posts: any;
    categories: any[];
    // Estos son los filtros iniciales que vienen de Laravel
    filters?: {
        category_id?: string;
        type?: string;
        from?: string;
        to?: string;
        search?: string;
        sortColumn?: string;
        sortDirection?: 'asc' | 'desc';
    };
}>();

// 2. Inicialización de la lógica de filtros
const { filters, applyFilters } = useFilters(index().url, {
    category_id: props.filters?.category_id || '',
    type: props.filters?.type || '',
    from: props.filters?.from || '',
    to: props.filters?.to || '',
    search: props.filters?.search || '',
    sortColumn: props.filters?.sortColumn || 'id',
    sortDirection: props.filters?.sortDirection || 'desc',
});

</script>

<template>
    <WebLayout>
        <section>
            <div class="filters-grid" id="filters">
              
                <Select v-model="filters.type" @update:modelValue="applyFilters">
                    <SelectTrigger class="filter-select">
                        <SelectValue placeholder="Type" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">Type</SelectItem>
                        <SelectItem value="advert">Advert</SelectItem>
                        <SelectItem value="post">Post</SelectItem>
                        <SelectItem value="course">Course</SelectItem>
                        <SelectItem value="movie">Movie</SelectItem>
                    </SelectContent>
                </Select>
                <Select v-model="filters.category_id" @update:modelValue="applyFilters">
                    <SelectTrigger class="filter-select">
                        <SelectValue placeholder="Category" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">Category</SelectItem>
                        <SelectItem v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.title }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <input v-model="filters.search" type="search" placeholder="Search..." class="filter-input"
                    @input="applyFilters()" />
                <input v-model="filters.from" type="date" class="filter-date" />
                <input v-model="filters.to" type="date" class="filter-date" />
                <div>
                    <Button variant="ghost" as-child>
                        <Link :href="index().url">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Clear
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- item -->
            <div class="flex flex-col items-center mt-5">
                <div class="w-full sm:max-w-4xl overflow-hidden">
                    <div v-for="p in posts.data" class="p-3" :key="p">
                        <h4 class="text-center text-4xl mb-3">{{ p.title }}</h4>
                        <p class="
                              text-center text-sm text-gray-500
                              italic
                              font-bold
                              uppercase
                              tracking-widest
                            ">
                            {{ p.date }}
                        </p>

                        <img class="w-full rounded-md shadow-md my-4"
                            :src="p.image ? 'image/post/' + p.image : '/image/default.jpg'" alt="">

                        <p class="mx-4">{{ p.description }}</p>


                        <div class="flex flex-col items-center mt-7">
                            <a class="btn-primary" :href="show(p.slug).url">Read more!</a>
                        </div>

                        <hr class="my-16">

                    </div>
                </div>
            </div>

            <!-- item -->

            <Pagination v-if="posts.data.length > 0" :links="posts.links" />
        </section>

    </WebLayout>
</template>