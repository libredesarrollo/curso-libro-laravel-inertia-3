<script setup lang="ts">
import { InfiniteScroll } from '@inertiajs/vue3';

defineProps<{
    posts: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            date: string;
            image: string | null;
            description: string;
            text: string;
            posted: string;
            type: string;
            category: {
                id: number;
                title: string;
            } | null;
        }>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}>();
</script>

<template>
    <div class="mx-auto max-w-4xl px-4 py-12">
        <h1 class="mb-2 text-3xl font-bold">Posts</h1>
        <p class="mb-8 text-gray-500">
            Ejemplo de Infinite Scroll con Inertia v3
        </p>

        <InfiniteScroll data="posts" :buffer="300">
            <div class="space-y-6">
                <article
                    v-for="post in posts.data"
                    :key="post.id"
                    class="rounded-lg border border-gray-100 bg-white p-6 shadow-md"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-800">
                                {{ post.title }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ post.date }} &bull; {{ post.type }}
                            </p>
                        </div>
                        <span
                            class="rounded px-2 py-1 text-xs font-medium"
                            :class="
                                post.posted === 'yes'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-gray-100 text-gray-800'
                            "
                        >
                            {{
                                post.posted === 'yes' ? 'Publicado' : 'Borrador'
                            }}
                        </span>
                    </div>

                    <p class="mt-3 text-gray-600">{{ post.description }}</p>

                    <div
                        v-if="post.image"
                        class="mt-4 overflow-hidden rounded-lg"
                    >
                        <img
                            :src="'/image/post/' + post.image"
                            :alt="post.title"
                            class="h-48 w-full object-cover"
                        />
                    </div>

                    <div v-if="post.category" class="mt-4">
                        <span
                            class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-800"
                        >
                            {{ post.category.title }}
                        </span>
                    </div>
                </article>
            </div>

            <template #loading>
                <div class="flex justify-center py-8">
                    <div
                        class="h-8 w-8 animate-spin rounded-full border-b-2 border-blue-600"
                    ></div>
                </div>
            </template>
        </InfiniteScroll>
    </div>
</template>
