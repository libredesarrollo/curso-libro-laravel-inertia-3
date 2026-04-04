<script setup lang="ts">
import { Head, Form, usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/shared/Pagination.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    MoreHorizontal,
    Plus,
    Pencil,
    Trash2,
    Tag as TagIcon,
} from 'lucide-vue-next';
import {
    create,
    edit,
    destroy,
} from '@/actions/App/Http/Controllers/Dashboard/TagController';

defineProps<{
    tags: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            posts_count: number;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
}>();

const page = usePage();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'List'
            }
        ],
    },
});

</script>

<template>
    <Head title="Tags" />

    <div class="space-y-6 px-4 py-6">
        <div class="flex items-center justify-between">
            <Heading title="Tags" description="Manage your tags" />
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

        <Card class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-sm font-medium text-muted-foreground"
                            >
                                Title
                            </th>
                            <th
                                class="px-4 py-3 text-left text-sm font-medium text-muted-foreground"
                            >
                                Slug
                            </th>
                           
                            <th
                                class="px-4 py-3 text-right text-sm font-medium text-muted-foreground"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="tag in tags.data"
                            :key="tag.id"
                            class="transition-colors hover:bg-muted/30"
                        >
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <TagIcon
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    <span class="text-sm font-medium">{{
                                        tag.title
                                    }}</span>
                                </div>
                            </td>
                            <td
                                class="px-4 py-3 font-mono text-sm text-muted-foreground"
                            >
                                {{ tag.slug }}
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
                                                :href="edit(tag.id).url"
                                                class="cursor-pointer"
                                            >
                                                <Pencil class="mr-2 h-4 w-4" />
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <Form
                                            v-bind="destroy.form(tag.id)"
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
                        </tr>
                        <tr v-if="tags.data.length === 0">
                            <td
                                colspan="4"
                                class="px-4 py-8 text-center text-sm text-muted-foreground"
                            >
                                No tags found. Create your first one.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <Pagination v-if="tags.data.length > 0" :links="tags.links" />
    </div>
</template>
