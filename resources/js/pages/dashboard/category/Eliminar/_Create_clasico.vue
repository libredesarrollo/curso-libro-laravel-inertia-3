<template>
    <!-- <form @submit.prevent="submit"> -->
    <form @submit.prevent="form.post('/category')">
        <label for="">Title</label>
        <input type="text" v-model="form.title"/>

        <label for="">Slug</label>
        <input type="text" v-model="form.slug"/>

        <button type="submit">Send</button>

    </form>
</template>
<script setup>
import {useForm} from '@inertiajs/vue3'

const form = useForm({
    title: '',
    slug: ''
})

function submit(){
    form.post('/category', { onSuccess: () => form.reset() })
}

</script>