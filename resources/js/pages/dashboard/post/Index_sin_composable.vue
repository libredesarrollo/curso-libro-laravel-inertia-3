<script setup lang="ts">
import { Head, usePage, Form } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';
import {
    MoreHorizontal,
    Plus,
    Pencil,
    Trash2,
    FileText,
    ArrowLeft,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { ref } from 'vue';
import {
    create,
    edit,
    destroy,
    index,
} from '@/actions/App/Http/Controllers/Dashboard/PostController';
import Heading from '@/components/Heading.vue';
import { DataTable } from '@/components/shared/DataTable';
import Pagination from '@/components/shared/Pagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'List',
            },
        ],
    },
});

const props = defineProps<{
    posts: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            description: string;
            posted: 'yes' | 'not';
            type: 'advert' | 'post' | 'course' | 'movie';
            category: { id: number; title: string };
            date: string;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
    categories: Array<{ id: number; title: string }>;
    filters?: {
        posted?: string;
        type?: string;
        category_id?: string;
        search?: string;
        to?: string;
        from?: string;
        sortColumn?: string;
        sortDirection?: 'asc' | 'desc';
    };
}>();

const page = usePage();

const columns = {
    id: 'Id',
    title: 'Title',
    date: 'Date',
    category: 'Category',
    type: 'Type',
    posted: 'Status',
};

const sortColumn = computed(() => props.filters?.sortColumn || 'id');
const sortDirection = computed(() => props.filters?.sortDirection || 'desc');

const posted = ref(props.filters?.posted || '');
const type = ref(props.filters?.type || '');
const category_id = ref(props.filters?.category_id || '');
const search = ref(props.filters?.search || '');
const to = ref(props.filters?.to || '');
const from = ref(props.filters?.from || '');

function customSearch(newSortColumn?: string) {
    const sortCol = newSortColumn || sortColumn.value;

    /*
        ¿Existe newSortColumn? (¿El usuario hizo clic en un encabezado?)

        NO (Filtro normal): Entonces mantén la dirección que ya teníamos (sortDirection.value).

        SÍ (Clic en tabla): Pasa a la siguiente pregunta...

        ¿Es la misma columna que ya estaba activa?

        NO (Columna nueva): Por defecto, ponemos el orden en 'asc'.

        SÍ (Misma columna): Pasa a la siguiente pregunta...

        ¿Cuál era el orden actual?

        Si era 'asc', cámbialo a 'desc'.

        Si era 'desc', cámbialo a 'asc'.
    */
    const sortDir = newSortColumn // asc o desc
        ? (newSortColumn === sortColumn.value ? (sortDirection.value === 'asc' ? 'desc' : 'asc') : 'asc')
        : sortDirection.value;

    router.get(index().url, {
        category_id: category_id.value,
        type: type.value,
        posted: posted.value,
        search: search.value,
        to: to.value,
        from: from.value,
        sortColumn: sortCol,
        sortDirection: sortDir,
    },
        {
            preserveScroll: true, // Evita que la página salte al inicio al recargar los datos
            preserveState: true,  // Mantiene el estado de los componentes (foco, valores de inputs, etc.)
            replace: true         // Opcional: actualiza la URL sin crear una nueva entrada en el historial
        });
}

function handleSort(column: string) {
    customSearch(column);
}

const typeColors: Record<string, string> = {
    post: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    advert: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    course: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
    movie: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
};

</script>

<template>

    <Head title="Posts" />

    <div class="space-y-6 px-4 py-6">
        <div class="flex items-center justify-between">
            <Heading title="Posts" description="Manage your posts" />
            <Button as-child>
                <Link :href="create().url">
                    <Plus class="mr-2 h-4 w-4" />
                    Create
                </Link>
            </Button>
        </div>

        <div v-if="page.props.flash?.message"
            class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-400">
            {{ page.props.flash.message }}
        </div>

        <div class="my-3 grid grid-cols-2 gap-2">
            <select v-model="posted" @change="customSearch()" class="block w-full">
                <option value="">{{ 'Posted' }}</option>
                <option value="not">{{ 'Not' }}</option>
                <option value="yes">{{ 'Yes' }}</option>
            </select>
            <select v-model="type" @change="customSearch()" class="block w-full">
                <option value="">{{ 'Type' }}</option>
                <option value="advert">{{ 'Advert' }}</option>
                <option value="post">{{ 'Post' }}</option>
                <option value="course">{{ 'Course' }}</option>
                <option value="movie">{{ 'Movie' }}</option>
            </select>
            <select v-model="category_id" @change="customSearch()" class="block w-full">
                <option value="">{{ 'Category' }}</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.title }}
                </option>
            </select>
            <input v-model="search" type="search" placeholder="Search..."
                class="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                @input="customSearch()" />
            <input v-model="from" type="date" class="block w-full" />
            <input v-model="to" type="date" class="block w-full" />
            <Button variant="ghost" as-child class="col-span-2">
                <Link :href="index().url">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Clear
                </Link>
            </Button>
        </div>

        <Card class="overflow-hidden">
            <DataTable :columns="columns" :data="posts.data" :sort-column="sortColumn" :sort-direction="sortDirection"
                @sort="handleSort">
                <template #default="{ row }">
                    <td class="px-4 py-3">{{ row.id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <FileText class="h-4 w-4 text-muted-foreground" />
                            <div>
                                <p class="text-sm font-medium">
                                    {{ row.title }}
                                </p>
                                <p class="font-mono text-xs text-muted-foreground">
                                    {{ row.slug }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>{{ row.date }}</td>
                    <td class="px-4 py-3 text-sm">
                        <Badge variant="outline">{{
                            row.category?.title || 'Uncategorized'
                        }}</Badge>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <Badge :class="typeColors[row.type as string] || ''">
                            {{ row.type }}
                        </Badge>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <Badge :variant="row.posted === 'yes' ? 'default' : 'secondary'
                            ">
                            {{ row.posted === 'yes' ? 'Posted' : 'Draft' }}
                        </Badge>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" size="icon" class="h-8 w-8">
                                    <MoreHorizontal class="h-4 w-4" />
                                    <span class="sr-only">Open menu</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-[160px]">
                                <DropdownMenuItem as-child>
                                    <Link :href="edit(row.id).url" class="cursor-pointer">
                                        <Pencil class="mr-2 h-4 w-4" />
                                        Edit
                                    </Link>
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <Form v-bind="destroy.form(row.id)" v-slot="{ processing }">
                                    <DropdownMenuItem class="cursor-pointer text-destructive focus:text-destructive"
                                        :disabled="processing" as="button" type="submit">
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        Delete
                                    </DropdownMenuItem>
                                </Form>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </td>
                </template>
            </DataTable>
        </Card>

        <Pagination v-if="posts.data.length > 0" :links="posts.links" />
    </div>
</template>
