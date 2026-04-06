<script setup lang="ts">

import { Head, router, usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { MoreHorizontal, Plus, Pencil, Trash2 } from 'lucide-vue-next';
import { watch } from 'vue';
import { ref } from 'vue';
import {
    create,
    edit,
    destroy,
    index
} from '@/actions/App/Http/Controllers/Dashboard/CategoryController';
import Heading from '@/components/Heading.vue';
import { DataTable } from '@/components/shared/DataTable';
import Pagination from '@/components/shared/Pagination.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

import { useFilters } from '@/composables/useFilters';

const props = defineProps<{
    categories: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
    filters?: {
        search?: string;
        sortColumn?: string;
        sortDirection?: 'asc' | 'desc';
    };
}>();

const page = usePage();

const confirmDeleteActive = ref(false);
const deleteCategoryRow = ref<number | string>(""); // Guardamos el ID aquí

const columns = {
    id: 'Id',
    title: 'Title'
};

const { filters, applyFilters } = useFilters(index().url, {
    search: props.filters?.search || '',
    sortColumn: props.filters?.sortColumn || 'id',
    sortDirection: props.filters?.sortDirection || 'desc',
});

// Para el buscador con debounce
watch(
    () => filters.value.search,
    () => applyFilters(),
);

// MODAL ELIMINAR
const deleteCategory = () => {
    // 1. Usamos .value para obtener el ID guardado
    // 2. Ejecutamos la acción de borrado
    router.delete(destroy(deleteCategoryRow.value).url, {
        preserveScroll: true,
        onSuccess: () => {
            confirmDeleteActive.value = false;
            deleteCategoryRow.value = "";
        }
    });
};


</script>

<template>
    <Head title="Categories" />

    <o-modal v-model:active="confirmDeleteActive">
    <p class="p-4 text-black">Are you sure you want to delete the selected record?</p>
    <div class="flex flex-row-reverse gap-2 bg-gray-100 p-3">
      <o-button variant="danger" @click="deleteCategory">Delete</o-button>
      <o-button @click="confirmDeleteActive = false">Cancel</o-button>
    </div>
  </o-modal>

    <div class="space-y-6 px-4 py-6">
        <div class="flex items-center justify-between">
            <Heading title="Categories" description="Manage your categories" />
            <Button as-child>
                <Link :href="create().url">
                    <Plus class="mr-2 h-4 w-4" />
                    Create
                </Link>
            </Button>
        </div>

        <div
            v-if="page.props.flash?.message"
            class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-400"
        >
            {{ page.props.flash.message }}
        </div>

         <div class="filters-grid" id="filters">
            
            <input
                v-model="filters.search"
                type="search"
                placeholder="Search..."
                class="filter-input"
                @input="applyFilters()"
            />
            
            <div>
                <Button variant="ghost" as-child >
                <Link :href="index().url">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Clear
                </Link>
            </Button>
            </div>
        </div>

        <Card class="overflow-hidden">
            <div class="overflow-x-auto">
                <DataTable
                :columns="columns"
                :data="categories.data"
                :sort-column="filters.sortColumn"
                :sort-direction="filters.sortDirection"
                @sort="applyFilters"
            >
                <template #default="{ row }">
                    <td class="px-4 py-3">{{ row.id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <FileText class="h-4 w-4 text-muted-foreground" />
                            <div>
                                <p class="text-sm font-medium">
                                    {{ row.title }}
                                </p>
                                <p
                                    class="font-mono text-xs text-muted-foreground"
                                >
                                    {{ row.slug }}
                                </p>
                            </div>
                        </div>
                    </td>
                   
                    <td class="px-4 py-3 text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8"
                                        >
                                            <MoreHorizontal class="h-4 w-4" />
                                            <span class="sr-only"
                                                >Open menu</span
                                            >
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent
                                        align="end"
                                        class="w-[160px]"
                                    >
                                        <DropdownMenuItem as-child>
                                            <Link
                                                :href="edit(row.id).url"
                                                class="cursor-pointer"
                                            >
                                                <Pencil class="mr-2 h-4 w-4" />
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                         <DropdownMenuItem as-child>
                                            <Button
                                                variant="destructive"
                                                @click="confirmDeleteActive = true; deleteCategoryRow = row.id"
                                                class="w-full"
                                            >
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Delete
                                         </Button>
                                        </DropdownMenuItem>
                                        <!-- <Form
                                            v-bind="destroy.form(category.id)"
                                            v-slot="{ processing }"
                                        >
                                            <DropdownMenuItem
                                                class="cursor-pointer text-destructive focus:text-destructive"
                                                :disabled="processing"
                                                as="button"
                                                type="submit"
                                            >
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Delete
                                            </DropdownMenuItem>
                                        </Form> -->
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </td>
                </template>
            </DataTable>
        
            </div>
        </Card>

        <Pagination
            v-if="categories.data.length > 0"
            :links="categories.links"
        />
    </div>
</template>
