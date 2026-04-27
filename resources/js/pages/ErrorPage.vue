<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{ status: number }>();

const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[props.status];
});

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[props.status];
});
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white dark:bg-zinc-900">
        <header class="bg-white dark:bg-zinc-900">
            <nav
                class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >
                <span class="text-xl font-bold text-zinc-900 dark:text-white">
                    {{ title }}
                </span>
            </nav>
        </header>

        <main
            class="flex flex-1 items-center justify-center px-4 py-12 sm:px-6 lg:px-8"
        >
            <div class="text-center">
                <h1 class="text-8xl font-bold text-zinc-900 dark:text-white">
                    {{ status }}
                </h1>
                <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
                    {{ description }}
                </p>
                <div class="mt-8">
                    <Link
                        href="/"
                        class="inline-flex items-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Go back home
                    </Link>
                </div>
            </div>
        </main>

        <footer class="bg-white dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-zinc-500 dark:text-zinc-400">
                    &copy; {{ new Date().getFullYear() }}
                </p>
            </div>
        </footer>
    </div>
</template>
