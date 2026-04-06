<script setup lang="ts">
import { Head, usePage, Form } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import {
    MoreHorizontal,
    Plus,
    Pencil,
    Trash2,
    FileText,
    ArrowLeft,
} from 'lucide-vue-next';
import { watch } from 'vue';
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
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

import { useFilters } from '@/composables/useFilters';

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

const { filters, applyFilters } = useFilters(index().url, {
    posted: props.filters?.posted || '',
    type: props.filters?.type || '',
    category_id: props.filters?.category_id || '',
    search: props.filters?.search || '',
    to: props.filters?.to || '',
    from: props.filters?.from || '',
    sortColumn: props.filters?.sortColumn || 'id',
    sortDirection: props.filters?.sortDirection || 'desc',
});

// Para el buscador con debounce
watch(
    () => filters.value.search,
    () => applyFilters(),
);

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

        <div
            v-if="page.props.flash?.message"
            class="rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-400"
        >
            {{ page.props.flash.message }}
        </div>

        <div class="filters-grid" id="filters">
            <Select v-model="filters.posted" @update:modelValue="applyFilters">
                <SelectTrigger class="filter-select">
                    <SelectValue placeholder="Posted" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Posted</SelectItem>
                    <SelectItem value="not">Not</SelectItem>
                    <SelectItem value="yes">Yes</SelectItem>
                </SelectContent>
            </Select>
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
            <Select
                v-model="filters.category_id"
                @update:modelValue="applyFilters"
            >
                <SelectTrigger class="filter-select">
                    <SelectValue placeholder="Category" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Category</SelectItem>
                    <SelectItem
                        v-for="cat in categories"
                        :key="cat.id"
                        :value="cat.id"
                    >
                        {{ cat.title }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <input
                v-model="filters.search"
                type="search"
                placeholder="Search..."
                class="filter-input"
                @input="applyFilters()"
            />
            <input v-model="filters.from" type="date" class="filter-date" />
            <input v-model="filters.to" type="date" class="filter-date" />
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
            <DataTable
                :columns="columns"
                :data="posts.data"
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
                        <Badge
                            :variant="
                                row.posted === 'yes' ? 'default' : 'secondary'
                            "
                        >
                            {{ row.posted === 'yes' ? 'Posted' : 'Draft' }}
                        </Badge>
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
                                    <span class="sr-only">Open menu</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-[160px]">
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
                                <Form
                                    v-bind="destroy.form(row.id)"
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
