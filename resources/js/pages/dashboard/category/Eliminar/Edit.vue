<template>

    <div class="px-4 py-6">

        <Head title="Create category" />

        <Heading title="Save category" description="Save your category" />

        <!-- <Form @submit.prevent="submit" class="space-y-6"> -->
        <!-- <Form :action="form.put(update(props.category.id))" class="space-y-6" -->
        <Form v-bind="update.form(props.category.id)" class="space-y-6"
            v-slot="{ errors, processing, recentlySuccessful }"
        >

            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input id="title" name="title" v-model="form.title" :default-value="form.title" required />
                <InputError :message="errors.title" />

                <Label for="name">Slug</Label>
                <Input id="title" name="slug" v-model="form.slug" :default-value="form.slug" />
                <InputError :message="errors.slug" />
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :disabled="processing">
                    Save
                </Button>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-show="recentlySuccessful" class="text-sm text-neutral-600">
                    Saved.
                </p>
            </Transition>
            </div>


        </Form>
    </div>
</template>
<script setup>
import { Head, useForm, Form } from '@inertiajs/vue3'

import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';

import { update } from '@/actions/App/Http/Controllers/Dashboard/CategoryController'

// props no hace falta, viene desde el Form
const props = defineProps(
    {
        category: Object
    }
);

const form = useForm({
    title: props.category.title,
    slug: props.category.slug
})

//console.log(update)
console.log(update.form(props.category.id))

function submit() {
    // update(props.category.id).url
    form.put(put(props.category.id), {
        onSuccess: () => form.reset(), // Opcional: limpiar al terminar
    });
}
</script>