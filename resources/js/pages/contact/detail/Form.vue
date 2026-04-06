<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import Layout from '@/layouts/contact/Layout.vue';

import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';

import {
    update,
    store,
} from '@/actions/App/Http/Controllers/Contact/DetailController';

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
    if (!form.id) {
        form.post(store().url);
    } else {
        form.put(update(form.id).url);
    }
}
</script>

<template>
    <Layout>
        <HeadingSmall
            title="Create Contact"
            description="Form to create contact"
        />
        <form @submit.prevent="submit">
            <div class="col-span-6">
                <Label>Extra</Label>
                <textarea
                    v-model="form.extra"
                    class="mt-1 block w-full rounded-md border-gray-300"
                ></textarea>
                <InputError :message="errors?.extra" class="mt-2" />
            </div>
            <Button
                class="mt-2"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </Button>
             <span class="mt-1 ml-3 cursor-pointer" @click="$emit('backStepEvent', 2)">Back</span>
        </form>
    </Layout>
</template>
