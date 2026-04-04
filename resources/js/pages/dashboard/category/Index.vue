<template>

    <div class="px-4 py-6">

        <Head title="Categories" />

        <Heading title="Categories" description="Your categories" />

        <div v-if="page.flash.message" class="toast">
            {{ page.flash.message }}
        </div>

        <Button as="a" :href="create().url">
            Create
        </Button>

        <table class="w-full">
            <thead>
                <tr class="bg-gray-700">
                    <th>
                        Title
                    </th>
                    <th>
                        Slug
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="c in categories.data" :key="c">
                    <td>
                        {{ c.title }}
                    </td>
                    <td>
                        {{ c.slug }}
                    </td>
                    <td class="flex gap-2">
                        <Button size="sm" as="a" :href="edit(c.id).url" variant="secondary">
                            Edit
                        </Button>

                        <Form v-bind="destroy.form(c.id)" v-slot="{ processing }">
                            <Button type="submit" :disabled="processing" variant="destructive" size="sm">
                                Delete
                            </Button>
                        </Form>

                    </td>
                </tr>
            </tbody>
        </table>

        <pagination :links="categories.links" />
    </div>
</template>
<script setup>
import { Head, useForm, Form, usePage} from '@inertiajs/vue3'

import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';

import Pagination from '@/components/shared/Pagination.vue'

import { create, edit, destroy } from '@/actions/App/Http/Controllers/Dashboard/CategoryController'

// props no hace falta, viene desde el Form
const props = defineProps(
    {
        categories: Object
    }
);

// const form = useForm({
//     title: '',
//     slug: ''
// })

const page = usePage();

// function submit() {

//     form.post(store(), {
//         onSuccess: () => form.reset(), // Opcional: limpiar al terminar
//     });
// }
</script>