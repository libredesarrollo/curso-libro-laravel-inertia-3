<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import {
    update,
    store,
} from '@/actions/App/Http/Controllers/Contact/DetailController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import Layout from '@/layouts/contact/Layout.vue';



declare function route(name: string, params?: Record<string, unknown>): string;

const props = defineProps<{
    errors: Record<string, string>;
    contactGeneralId?: number;
    contactDetail?: {
        id: string;
        extra: string;
        contact_general_id: number;
    };
}>();

const form = useForm({
    id: props.contactDetail?.id,
    extra: props.contactDetail?.extra,
    contact_general_id:
        props.contactGeneralId ?? props.contactDetail?.contact_general_id,
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
                <Label>Extra</Label>
                <textarea v-model="form.extra" class="form-textarea"></textarea>
                <!-- <InputError :message="errors?.extra" class="mt-2" /> -->
                <InputError :message="form.errors.extra" class="mt-2" />
            </div>
            <div class="form-actions">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save
                </Button>
                <span class="form-back-link" @click="$emit('backStepEvent', 2)"
                    >Back</span
                >
            </div>
        </form>
    </Layout>
</template>
