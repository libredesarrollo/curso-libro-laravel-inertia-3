<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { vueLang } from '@erag/lang-sync-inertia';

const { trans, __ } = vueLang();

const currentLocale = ref('en');

const availableLocales = [
    { code: 'en', name: 'English', flag: '🇺🇸' },
    { code: 'es', name: 'Español', flag: '🇪🇸' },
];

function changeLocale(locale) {
    currentLocale.value = locale;
    router.visit(`/localization/lang/${locale}`, {
        preserveState: true,
    });
}
</script>

<template>
    <div class="min-h-screen p-8">
        <div class="mx-auto max-w-4xl">
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-800">
                    {{ __('messages.title') }}
                </h1>
                <p class="text-gray-600">
                    {{ __('messages.description') }}
                </p>
            </div>

            <div class="mb-8 rounded-lg  p-6 shadow">
                <h2 class="mb-4 text-lg font-semibold">
                    {{ __('messages.current_language') }}
                </h2>
                <div class="flex gap-2">
                    <button
                        v-for="locale in availableLocales"
                        :key="locale.code"
                        @click="changeLocale(locale.code)"
                        class="rounded px-4 py-2 transition-colors"
                        :class="
                            currentLocale === locale.code
                                ? 'bg-blue-500 text-white'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                        "
                    >
                        {{ locale.flag }} {{ locale.name }}
                    </button>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <h2 class="mb-4 text-xl font-bold text-gray-800">
                    {{ trans('messages.welcome') }}
                </h2>

                <p class="mb-4 text-gray-600">
                    {{ __('messages.content.intro') }}
                </p>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">
                        {{ __('messages.content.features') }}
                    </h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="text-green-500">✓</span>
                            {{ __('messages.content.feature_1') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-500">✓</span>
                            {{ __('messages.content.feature_2') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-500">✓</span>
                            {{ __('messages.content.feature_3') }}
                        </li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">
                        {{ trans('messages.greeting', { name: 'Developer' }) }}
                    </h3>
                </div>

                <div class="flex gap-4">
                    <button
                        class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                    >
                        {{ __('messages.buttons.submit') }}
                    </button>
                    <button
                        class="rounded bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300"
                    >
                        {{ __('messages.buttons.cancel') }}
                    </button>
                    <button
                        class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"
                    >
                        {{ __('messages.buttons.save') }}
                    </button>
                </div>

                <div class="mt-8 border-t pt-4">
                    <p class="text-gray-500">
                        {{ __('messages.content.footer') }}
                    </p>
                </div>
            </div>

            <div class="mt-8 rounded-lg bg-blue-50 p-6">
                <h3 class="mb-2 font-semibold text-blue-800">How it works</h3>
                <p class="text-sm text-blue-700">
                    This page uses the <code>__()</code> and
                    <code>trans()</code> functions from
                    <code>@erag/lang-sync-inertia</code> to display translations
                    synced from Laravel's language files.
                </p>
                <ul class="mt-2 text-sm text-blue-700">
                    <li>
                        <code>__('messages.title')</code> - Simple translation
                    </li>
                    <li>
                        <code
                            >trans('messages.greeting', {'{'} name: 'Developer'
                            {'}'})</code
                        >
                        - Translation with placeholders
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
