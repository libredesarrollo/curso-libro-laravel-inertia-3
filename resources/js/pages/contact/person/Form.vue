<script setup lang="ts">
import {  useForm } from '@inertiajs/vue3';
import { store, update } from '@/routes/contact-person';

import Layout from '@/layouts/contact/Layout.vue';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    errors: Record<string, string>;
    contactGeneralId?: number;
    contactPerson?: {
        id: string;
        name: string;
        surname: string;
        choices: string;
        other: string;
        contact_general_id: number;
    };
}>();

const form = useForm({
    id: props.contactPerson?.id,
    name: props.contactPerson?.name,
    surname: props.contactPerson?.surname,
    choices: props.contactPerson?.choices,
    other: props.contactPerson?.other,
    contact_general_id:
        props.contactPerson?.contact_general_id ?? props.contactGeneralId,
});

function submit() {
    if (!form.id) {
        form.post(store().url);
    } else {
        form.put(update(form.id).url);
    }
}
</script>

<template>
    <Layout>
 
        <form @submit.prevent="submit">
            <div class="col-span-6">
                <Label>Name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                />
                <InputError :message="errors?.name" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Surname</Label>
                <Input
                    id="surname"
                    v-model="form.surname"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="errors?.surname" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Other</Label>
                <textarea
                    v-model="form.other"
                    class="mt-1 block w-full rounded-md border-gray-300"
                ></textarea>
                <InputError :message="errors?.other" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Choices</Label>
                <select
                    v-model="form.choices"
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="advert">Advert</option>
                    <option value="post">Post</option>
                    <option value="course">Course</option>
                    <option value="movie">Movie</option>
                    <option value="other">Other</option>
                </select>
                <InputError :message="errors?.choices" class="mt-2" />
            </div>
            <Button
                class="mt-2"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </Button>
             <span class="mt-1 ml-3 cursor-pointer" @click="$emit('backStepEvent', 1)">Back</span>
        </form>
    </Layout>
</template>
