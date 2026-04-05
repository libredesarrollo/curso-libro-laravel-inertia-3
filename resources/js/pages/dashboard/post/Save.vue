<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ArrowLeft, Save } from 'lucide-vue-next';
import {
    update,
    store,
    index
} from '@/actions/App/Http/Controllers/Dashboard/PostController';

import { Ckeditor } from '@ckeditor/ckeditor5-vue';

import { ClassicEditor, Bold, Essentials, Italic, Mention, Paragraph, Undo, Heading as CHeading } from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

defineProps<{
    post: {
        id?: number;
        title?: string;
        slug?: string;
        description?: string;
        text?: string;
        posted?: 'yes' | 'not';
        type?: 'advert' | 'post' | 'course' | 'movie';
        category_id?: number;
    };
    categories?: Array<{ id: number; title: string }>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'List',
                href: index(),
            },
            {
                title: 'Save'
            }
        ],
    },
});

const editor = ClassicEditor
const editorConfig = {
    licenseKey: 'GPL',
    plugins: [Bold, Essentials, Italic, Mention, Paragraph, Undo, CHeading],
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', 'heading',],
}

</script>

<template>

    <Head :title="post.id ? 'Edit Post' : 'Create Post'" />

    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="sm" as-child>
                <Link :href="store().url">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back
                </Link>
            </Button>
        </div>

        <Heading :title="post.id ? 'Edit Post' : 'Create Post'" description="Fill in the details below" />
        
        <o-button @click="console.log('click')">Oruga</o-button>

        <o-button>oruga-ui</o-button>

        <Form v-bind="post.id ? update.form(post.id) : store.form()" class="space-y-0"
            v-slot="{ errors, processing, recentlySuccessful }">
            <Card>
                <CardHeader class="space-y-1 pb-4">
                    <h3 class="text-lg font-medium">Post Information</h3>
                    <p class="text-sm text-muted-foreground">
                        Enter the post details.
                    </p>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="title"
                                class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Title <span class="text-destructive">*</span>
                            </label>
                            <input id="title" name="title" v-model="post.title" required
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50" />
                            <InputError :message="errors.title" />
                        </div>

                        <div class="space-y-2">
                            <label for="slug"
                                class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Slug
                            </label>
                            <input id="slug" name="slug" v-model="post.slug"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 font-mono text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50" />
                            <InputError :message="errors.slug" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description"
                            class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Description <span class="text-destructive">*</span>
                        </label>
                        <textarea id="description" name="description" v-model="post.description" rows="3" required
                            class="flex w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                        <InputError :message="errors.description" />
                    </div>

                    <div class="space-y-2">
                        <label for="text"
                            class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Content
                        </label>
                        <ckeditor v-model="post.text" :editor="editor" :config="editorConfig" />
                        <textarea id="text" name="text" v-model="post.text" rows="6"
                            class="flex w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                        <InputError :message="errors.text" />
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-2">
                            <label for="category_id"
                                class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Category <span class="text-destructive">*</span>
                            </label>
                            <input name="category_id" type="hidden" v-model="post.category_id" />
                            <Select name="category_id" v-model="post.category_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="category in categories" :key="category.id"
                                        :value="String(category.id)">
                                        {{ category.title }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.category_id" />
                        </div>

                        <div class="space-y-2">
                            <label for="type"
                                class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Type <span class="text-destructive">*</span>
                            </label>

                            <Select name="type" v-model="post.type">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="post">Post</SelectItem>
                                    <SelectItem value="advert">Advert</SelectItem>
                                    <SelectItem value="course">Course</SelectItem>
                                    <SelectItem value="movie">Movie</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.type" />
                        </div>

                        <div class="space-y-2">
                            <label for="posted"
                                class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Status <span class="text-destructive">*</span>
                            </label>
                            <Select name="posted" v-model="post.posted">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="not">Draft</SelectItem>
                                    <SelectItem value="yes">Posted</SelectItem>
                                </SelectContent>
                            </Select>
                            {{ post.posted }}
                            <InputError :message="errors.posted" />
                        </div>
                    </div>
                </CardContent>
                <CardFooter class="flex items-center justify-between border-t pt-6">
                    <p class="text-sm text-muted-foreground">
                        Fields marked with
                        <span class="text-destructive">*</span> are required.
                    </p>
                    <div class="flex items-center gap-3">
                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <span v-if="recentlySuccessful" class="text-sm text-green-600 dark:text-green-400">
                                Saved.
                            </span>
                        </Transition>
                        <Button type="submit" :disabled="processing">
                            <Save class="mr-2 h-4 w-4" />
                            {{ processing ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </CardFooter>
            </Card>
        </Form>
    </div>
</template>
