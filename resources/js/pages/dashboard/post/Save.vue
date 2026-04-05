<script setup lang="ts">
import { Ckeditor } from '@ckeditor/ckeditor5-vue';


import { Head, Form, Link, router } from '@inertiajs/vue3';
import {
    ClassicEditor,
    Bold,
    Essentials,
    Italic,
    Mention,
    Paragraph,
    Undo,
    Heading as CHeading,
} from 'ckeditor5';
import { ArrowLeft, Save, Upload, Download, Trash } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import {
    update,
    store,
    index,
    upload,
    imageDelete
} from '@/actions/App/Http/Controllers/Dashboard/PostController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

import 'ckeditor5/ckeditor5.css';

const imageFile = ref<File | null>(null);
const dropFiles = ref("")

const props = defineProps<{
    post: {
        id?: number;
        title?: string;
        slug?: string;
        description?: string;
        text?: string;
        posted?: 'yes' | 'not';
        type?: 'advert' | 'post' | 'course' | 'movie';
        category_id?: number;
        image?: any; // Upload
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
                title: 'Save',
            },
        ],
    },
});

const editor = ClassicEditor;
const editorConfig = {
    licenseKey: 'GPL',
    plugins: [Bold, Essentials, Italic, Mention, Paragraph, Undo, CHeading],
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', 'heading'],
};

function uploadImage() {
    // Verificamos que exista un ID y una imagen antes de intentar subir
    if (!props.post.id || !imageFile.value) {
        console.error(
            'No hay ID de post o no se ha seleccionado ninguna imagen',
        );

        return;
    }

    router.post(
        upload(props.post.id).url,
        {
            _method: 'post', // A veces útil si el backend espera un spoofing de método
            image: imageFile.value,
        },
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                // Opcional: limpiar el input o mostrar mensaje
                console.log('Imagen subida con éxito');
            },
            onError: (errors) => {
                console.error('Error al subir:', errors);
            },
        },
    );
}

watch(() => dropFiles.value, (newFiles) => {
    // 1. Verificamos que haya archivos y que el post tenga ID
    console.log(newFiles);

    if (props.post.id) {
        if (newFiles.length > 0) {
            // multiple

            // 2. Tomamos el último archivo añadido
            imageFile.value = newFiles[newFiles.length - 1];

            uploadImage()
        } else {
            // sipo single
            imageFile.value = newFiles as File;
            console.log(imageFile.value);
        }
    }
}, { deep: true });

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

        <Form v-bind="post.id ? update.form(post.id) : store.form()" class="space-y-0"
            v-slot="{ errors, processing, recentlySuccessful, defaults }">
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
                            <Label for="title">
                                Title <span class="text-destructive">*</span>
                            </Label>
                            <Input id="title" name="title" v-model="post.title" required />
                            <InputError :message="errors.title" />
                        </div>

                        <div class="space-y-2">
                            <Label for="slug"> Slug </Label>
                            <Input id="slug" name="slug" v-model="post.slug" />
                            <InputError :message="errors.slug" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="description">
                            Description <span class="text-destructive">*</span>
                        </Label>
                        <textarea id="description" name="description" v-model="post.description" rows="3" required
                            class="flex w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                        <InputError :message="errors.description" />
                    </div>

                    <div class="space-y-2">
                        <Label for="text"> Content </Label>
                        <ckeditor v-model="post.text" :editor="editor" :config="editorConfig" />
                        <textarea id="text" name="text" v-model="post.text" rows="6"
                            class="flex w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                        <InputError :message="errors.text" />
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-2">
                            <Label for="category_id">
                                Category <span class="text-destructive">*</span>
                            </Label>
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
                            <Label for="type">
                                Type <span class="text-destructive">*</span>
                            </Label>

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
                            <Label for="posted">
                                Status <span class="text-destructive">*</span>
                            </Label>
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

                        <div class="flex flex-col col-span-3 gap-2" v-if="post.id">

                            <div class=" flex flex-col">
                                <Label>Image</Label>
                                <Input type="file" name="image" @change="
                                    (e) => {
                                        const file =
                                            e.target.files?.[0];
                                        if (file) {
                                            imageFile = file;
                                        }
                                    }
                                " />
                                <InputError :message="errors.image" class="mt-2" />
                                <Button @click="uploadImage">Upload</Button>
                            </div>

                            <o-upload x-model="post.image" v-model="dropFiles" name="image">
                                <o-button tag="a" variant="primary">
                                    <Upload class="h-4 w-4 text-primary" />
                                </o-button>
                            </o-upload>

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

        <div class="px-4 py-6 max-w-xl" v-if="post.image">
            <img :src="'/image/post/' + post.image" :alt="post.title" class="max-w-sm rounded-md shadow-sm">

            <div class="flex gap-2 mt-2">
                <Button variant="outline" size="sm" as-child>
                    <a :href="'/image/post/' + post.image" :download="post.image">
                        <Download class="mr-2 h-4 w-4" />
                        Descargar Imagen
                    </a>
                </Button>

                <Button variant="destructive" size="sm" @click="router.delete(imageDelete(post.id).url)">
                    <Trash class="mr-2 h-4 w-4" />
                    Delete
                </Button>
            </div>

        </div>


        <o-upload class="" v-model="dropFiles" multiple drag-drop>
            <section class="ex-center text-black flex flex-col items-center justify-center gap-3">
                <p>
                    <Upload class="h-8 w-8 text-black" />
                </p>
                <p>Drop your files here or click to upload</p>
            </section>
        </o-upload>

    </div>
</template>
