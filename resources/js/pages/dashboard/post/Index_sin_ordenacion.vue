<script setup lang="ts">
import { Head, Form, usePage } from '@inertiajs/vue3';


import { Link, router } from '@inertiajs/vue3';
import {
    MoreHorizontal,
    Plus,
    Pencil,
    Trash2,
    FileText,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import {
    create,
    edit,
    destroy,
    index
} from '@/actions/App/Http/Controllers/Dashboard/PostController';
import Heading from '@/components/Heading.vue';
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
    };
}>();

const page = usePage();

const typeColors: Record<string, string> = {
    post: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    advert: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    course: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
    movie: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'List'
            }
        ],
    },
});

const posted = ref(props.filters?.posted || '');
const type = ref(props.filters?.type || '');
const category_id = ref(props.filters?.category_id || '');
const search = ref(props.filters?.search || '');
const to = ref(props.filters?.to || '');
const from = ref(props.filters?.from || '');

watch([from, to], () => {
    if(from.value && to.value){
        customSearch()
    }
});

function customSearch() {
    router.get(index(), {
        category_id: category_id.value,
        type: type.value,
        posted: posted.value,
        search: search.value,
        to: to.value,
        from: from.value,
    })
}

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

        <div class="grid grid-cols-2 gap-2 my-3">
            <select @change="customSearch" class="block w-full" v-model='posted'>
                <option value="">{{ 'Posted' }}</option>
                <option value="not">{{ 'Not' }}</option>
                <option value="yes">{{ 'Yes' }}</option>
            </select>
            <select @change="customSearch" class="block w-full" v-model='type'>
                <option value="">{{ 'Type' }}</option>
                <option value="advert">{{ 'Advert' }}</option>
                <option value="post">{{ 'Post' }}</option>
                <option value="course">{{ 'Course' }}</option>
                <option value="movie">{{ 'Movie' }}</option>
            </select>
            <select @change="customSearch" class="block w-full" v-model='category_id'>
                <option value="">{{ 'Category' }}</option>
                <option v-for="id, title in categories" :value="id">{{ title }}</option>
            </select>
            <o-input v-model="search" placeholder="Buscar..." type="search" :debounce="500"
                @update:model-value="customSearch" />
            <o-input type="date" v-model="from" />
            <o-input type="date" v-model="to" />
             <Button variant="ghost" as-child>
                <Link :href="index().url">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Clear
                </Link>
            </Button>
        </div>

        <Card class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">
                                Id
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">
                                Title
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">
                                Date
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">
                                Category
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">
                                Type
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">
                                Status
                            </th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-muted-foreground">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="post in posts.data" :key="post.id" class="transition-colors hover:bg-muted/30">
                            <td class="px-4 py-3">{{ post.id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <FileText class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">
                                            {{ post.title }}
                                        </p>
                                        <p class="font-mono text-xs text-muted-foreground">
                                            {{ post.slug }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ post.date }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <Badge variant="outline">{{
                                    post.category?.title || 'Uncategorized'
                                    }}</Badge>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <Badge :class="typeColors[post.type] || ''">
                                    {{ post.type }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <Badge :variant="post.posted === 'yes'
                                    ? 'default'
                                    : 'secondary'
                                    ">
                                    {{
                                        post.posted === 'yes'
                                            ? 'Posted'
                                            : 'Draft'
                                    }}
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
                                            <Link :href="edit(post.id).url" class="cursor-pointer">
                                                <Pencil class="mr-2 h-4 w-4" />
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <Form v-bind="destroy.form(post.id)" v-slot="{ processing }">
                                            <DropdownMenuItem
                                                class="cursor-pointer text-destructive focus:text-destructive"
                                                :disabled="processing" as="button" type="submit">
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Delete
                                            </DropdownMenuItem>
                                        </Form>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </td>
                        </tr>
                        <tr v-if="posts.data.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-muted-foreground">
                                No posts found. Create your first one.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <Pagination v-if="posts.data.length > 0" :links="posts.links" />
    </div>
</template>
