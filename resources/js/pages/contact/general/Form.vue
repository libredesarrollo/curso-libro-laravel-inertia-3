<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { store, update } from '@/routes/contact-general';

import Layout from '@/layouts/contact/Layout.vue';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    errors: Object
    contactGeneral?: {              // Objeto opcional
        id: string;
        subject: string;
        type: string;
        message: string;
    };
}>();

const form = useForm({
    id: props.contactGeneral?.id,
    subject: props.contactGeneral?.subject,
    type: props.contactGeneral?.type,
    message: props.contactGeneral?.message,
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
                <Label>Subject</Label>
                <Input
                    id="subject"
                    v-model="form.subject"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                />
                <InputError :message="errors?.subject" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Message</Label>
                <textarea
                    v-model="form.message"
                    class="mt-1 block w-full rounded-md border-gray-300"
                ></textarea>
                <InputError :message="errors?.message" class="mt-2" />
            </div>
            <div class="col-span-6">
                <Label>Type</Label>
                <select
                    v-model="form.type"
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="company">Company</option>
                    <option value="person">Person</option>
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
        </form>
    </Layout>
</template>
