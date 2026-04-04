<template>
    <form @submit.prevent="submit">
    <!-- <form @submit.prevent="form.post(store())"> -->
        <label for="">Title</label>
        <input type="text" v-model="form.title"/>
        <span v-if="errors.title"> {{ errors.title }}</span>
        <label for="">Slug</label>
        <input type="text" v-model="form.slug"/>
        <span v-if="errors.title"> {{ errors.slug }}</span>
        <button type="submit">Send</button>

    </form>
</template>
<script setup>
import {useForm} from '@inertiajs/vue3'

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