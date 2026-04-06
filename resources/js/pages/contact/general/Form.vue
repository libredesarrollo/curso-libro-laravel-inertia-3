<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';


import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Layout from '@/layouts/contact/Layout.vue';
import { store, update } from '@/routes/contact-general';

const props = defineProps<{
    errors: object;
    contactGeneral?: {
        // Objeto opcional
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
        <form @submit.prevent="submit" class="form-container">
            <div class="form-field">
                <Label>Subject</Label>
                <Input
                    id="subject"
                    v-model="form.subject"
                    type="text"
                    autofocus
                />
                <InputError :message="errors?.subject" class="mt-2" />
            </div>
            <div class="form-field">
                <Label>Message</Label>
                <textarea
                    v-model="form.message"
                    class="form-textarea"
                ></textarea>
                <InputError :message="errors?.message" class="mt-2" />
            </div>
            <div class="form-field">
                <Label>Type</Label>
                <select v-model="form.type" class="form-select">
                    <option value="company">Company</option>
                    <option value="person">Person</option>
                </select>
                <InputError :message="errors?.type" class="mt-2" />
            </div>

            <div class="form-actions">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save
                </Button>
            </div>
        </form>
    </Layout>
</template>
