<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { dashboard, login, register } from '@/routes';
import { index as blogIndex } from '@/routes/web';
import { index as shopIndex } from '@/routes/shop';
import { index as todoIndex } from '@/routes/todo';
import { index as eventDemoIndex } from '@/routes/event-demo';
import { index as localizationIndex } from '@/routes/localization';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const page = usePage();

const dashboardUrl = computed(() =>
    page.props.currentTeam ? dashboard(page.props.currentTeam.slug).url : '/',
);

const sections = computed(() => {
    const team = page.props.currentTeam as { slug: string } | undefined;

    return [
        {
            title: 'Dashboard',
            description:
                'Administración de contenido con CRUD completo de posts, categorías y tags.',
            icon: '📊',
            href: team ? dashboard.url({ current_team: team.slug }) : '/',
            auth: true,
        },
        {
            title: 'Blog',
            description:
                'Blog público con listado de posts, vista detalle y scroll infinito.',
            icon: '📝',
            href: blogIndex.url(),
            auth: false,
        },
        {
            title: 'Tienda',
            description:
                'Carrito de compras con agregado de productos y control de cantidades.',
            icon: '🛒',
            href: shopIndex.url(),
            auth: false,
        },
        {
            title: 'Eventos',
            description:
                'Demostración de eventos Inertia: carga lenta, errores y subida de archivos.',
            icon: '⚡',
            href: eventDemoIndex.url(),
            auth: false,
        },
        {
            title: 'Todo List',
            description:
                'Lista de tareas con ordenamiento drag & drop, estados y CRUD completo.',
            icon: '✅',
            href: todoIndex.url(),
            auth: true,
        },
        {
            title: 'Localización',
            description: 'Soporte multi-idioma con cambio dinámico de locale.',
            icon: '🌐',
            href: localizationIndex.url(),
            auth: false,
        },
    ];
});
</script>

<template>
    <Head title="Inertiastore">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a]"
    >
        <header class="flex items-center justify-end gap-4 px-6 py-5">
            <Link
                v-if="$page.props.auth.user"
                :href="dashboardUrl"
                class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
            >
                Dashboard
            </Link>
            <template v-else>
                <Link
                    :href="login()"
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                >
                    Log in
                </Link>
                <Link
                    v-if="canRegister"
                    :href="register()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Register
                </Link>
            </template>
        </header>

        <main class="flex-1">
            <div class="mx-auto max-w-6xl px-6 pt-12 pb-16 lg:pt-20">
                <div class="mb-16 text-center">
                    <h1
                        class="mb-3 text-4xl font-bold tracking-tight lg:text-5xl"
                    >
                        Inertiastore
                    </h1>
                    <p
                        class="mx-auto max-w-2xl text-lg text-[#706f6c] dark:text-[#A1A09A]"
                    >
                        Aplicación demo construida con Laravel 13, Inertia v3 y
                        Vue 3. Explora los módulos y ejemplos para conocer las
                        funcionalidades del stack.
                    </p>
                </div>

                <ul>
                    <li><strong>User:</strong> admin@admin.com</li>
                    <li><strong>Password:</strong>!a5qRNEtVXyX3s</li>
                </ul>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="section in sections"
                        :key="section.title"
                        class="group rounded-lg border border-[#e3e3e0] bg-white p-6 shadow-sm transition-all hover:border-[#f53003] hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-[#FF4433]"
                    >
                        <div class="mb-3 text-2xl">{{ section.icon }}</div>
                        <h2 class="mb-2 text-lg font-medium">
                            {{ section.title }}
                        </h2>
                        <p
                            class="mb-4 text-sm text-[#706f6c] dark:text-[#A1A09A]"
                        >
                            {{ section.description }}
                        </p>
                        <div class="flex items-center gap-2">
                            <Link
                                :href="section.href"
                                class="inline-flex items-center gap-1 text-sm font-medium text-[#f53003] underline-offset-4 hover:underline dark:text-[#FF4433]"
                            >
                                Explorar
                                <svg
                                    width="12"
                                    height="12"
                                    viewBox="0 0 10 11"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                        stroke="currentColor"
                                        stroke-linecap="square"
                                    />
                                </svg>
                            </Link>
                            <span
                                v-if="section.auth"
                                class="rounded-full bg-[#f5f5f4] px-2 py-0.5 text-[10px] font-medium text-[#706f6c] dark:bg-[#2a2a28] dark:text-[#A1A09A]"
                            >
                                Auth required
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer
            class="border-t border-[#e3e3e0] py-6 text-center text-sm text-[#706f6c] dark:border-[#3E3E3A] dark:text-[#A1A09A]"
        >
            Built with Laravel, Inertia & Vue
        </footer>
    </div>
</template>
