<template>

    <div class="px-4 py-6">

        <Head title="Create category" />

        <Heading title="Save category" description="Save your category" />

        <form @submit.prevent="submit" class="space-y-6">

            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input id="title" name="title" v-model="form.title" :default-value="form.title" required />
                <InputError :message="errors.title" />

                <Label for="name">Slug</Label>
                <Input id="title" name="slug" v-model="form.slug" :default-value="form.slug" required />
                <InputError :message="errors.slug" />
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" data-disabled="processing">
                    Save
                </Button>

                <!-- <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-show="recentlySuccessful" class="text-sm text-neutral-600">
                    Saved.
                </p>
            </Transition> -->
            </div>


        </form>
    </div>
</template>
<script setup>
import { Head, useForm } from '@inertiajs/vue3'

import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';

import { store } from '@/actions/App/Http/Controllers/Dashboard/CategoryController'

// props
const props = defineProps(
    {
        errors: Object
    }
);

const form = useForm({
    title: '',
    slug: ''
})



function submit() {

    form.post(store(), {
        onSuccess: () => form.reset(), // Opcional: limpiar al terminar
    });
}
</script>