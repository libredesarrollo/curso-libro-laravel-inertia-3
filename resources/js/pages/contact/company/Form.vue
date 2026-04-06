<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { store, update } from '@/routes/contact-company';

import Layout from '@/layouts/contact/Layout.vue';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';


const props = defineProps<{
    errors: Record<string, string>;
    contactGeneralId?: number;
    contactCompany?: {
        id: string;
        name: string;
        identification: string;
        email: string;
        choices: string;
        extra: string;
        contact_general_id: number;
    };
}>();

const form = useForm({
    id: props.contactCompany?.id,
    name: props.contactCompany?.name,
    identification: props.contactCompany?.identification,
    email: props.contactCompany?.email,
    choices: props.contactCompany?.choices,
    extra: props.contactCompany?.extra,
    contact_general_id:
        props.contactCompany?.contact_general_id ?? props.contactGeneralId,
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
                <Label>Identification</Label>
                <Input
                    id="identification"
                    v-model="form.identification"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="errors?.identification" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Email</Label>
                <Input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />
                <InputError :message="errors?.email" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Extra</Label>
                <textarea
                    v-model="form.extra"
                    class="mt-1 block w-full rounded-md border-gray-300"
                ></textarea>
                <InputError :message="errors?.extra" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Choices</Label>
                <select
                    v-model="form.choices"
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="Advert">Advert</option>
                    <option value="post">Post</option>
                    <option value="course">Course</option>
                    <option value="movie">Movie</option>
                    <option value="other">Other</option>
                </select>
                <InputError :message="errors?.type" class="mt-2" />
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
