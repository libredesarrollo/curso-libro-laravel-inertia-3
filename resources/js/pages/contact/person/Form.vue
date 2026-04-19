<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';


import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Layout from '@/layouts/contact/Layout.vue';
import { store, update } from '@/routes/contact-person';

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
    const options = {
        preserveScroll: true,
        preserveState: true,
    };
    if (!form.id) {
        form.post(store().url, options);
    } else {
        form.put(update(form.id).url, options);
    }
}
</script>

<template>
    <Layout>
        <form @submit.prevent="submit" class="form-container">
            <div class="form-field">
                <Label>Name</Label>
                <Input id="name" v-model="form.name" type="text" autofocus />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div class="form-field">
                <Label>Surname</Label>
                <Input id="surname" v-model="form.surname" type="text" />
                <InputError :message="form.errors.surname" class="mt-2" />
            </div>
            <div class="form-field">
                <Label>Other</Label>
                <textarea v-model="form.other" class="form-textarea"></textarea>
                <InputError :message="form.errors.other" class="mt-2" />
            </div>
            <div class="form-field">
                <Label>Choices</Label>
                <select v-model="form.choices" class="form-select">
                    <option value="advert">Advert</option>
                    <option value="post">Post</option>
                    <option value="course">Course</option>
                    <option value="movie">Movie</option>
                    <option value="other">Other</option>
                </select>
                <InputError :message="form.errors.choices" class="mt-2" />
            </div>
            <div class="form-actions">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save
                </Button>
                <span class="form-back-link" @click="$emit('backStepEvent', 1)"
                    >Back</span
                >
            </div>
        </form>
    </Layout>
</template>
