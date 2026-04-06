<script setup lang="ts">
import { ref, watch, onMounted, nextTick } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Sortable from 'sortablejs';

// Layouts y Componentes UI
import WebLayout from '@/layouts/WebLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';

// Importaciones de Wayprint
import {
    store,
    update as todoUpdate,
    destroy,
    status as todoStatus,
    order as todoOrder,
} from '@/actions/App/Http/Controllers/TodoController';

// Props
const props = defineProps<{
    errors: Record<string, any>;
    todos: any[];
}>();

// Estado Reactivo
const dtodos = ref([...props.todos]);
const todoSelected = ref(0);
const confirmDeleteActive = ref(false);
const deleteTodoRow = ref<number | string>('');
const todoListRef = ref<HTMLElement | null>(null);

const form = useForm({
    name: '',
});

onMounted(() => {
    nextTick(() => {
        if (todoListRef.value) {
            Sortable.create(todoListRef.value, {
                handle: '.drag-handle',
                ghostClass: 'opacity-50',
                onEnd: (evt) => {
                    const movedItem = dtodos.value.splice(evt.oldIndex!, 1)[0];
                    dtodos.value.splice(evt.newIndex!, 0, movedItem);
                    order();
                },
            });
        }
    });
});

watch(
    () => props.todos,
    (newTodos) => {
        dtodos.value = [...newTodos];
    },
);

// --- MÉTODOS ACTUALIZADOS A WAYPRINT ---

const create = () => {
    todoSelected.value = 0;
    // Usamos store().url
    form.post(store().url, {
        onSuccess: () => {
            form.reset();
        },
    });
};

const update = (todo: any) => {
    todoSelected.value = todo.id;
    todo.editMode = false;
    // Usamos todoUpdate(id).url e inyectamos los datos en el segundo parámetro
    router.put(todoUpdate(todo.id).url, {
        name: todo.name,
    });
};

const remove = () => {
    confirmDeleteActive.value = false;
    // Usamos destroy(id).url
    router.delete(destroy(deleteTodoRow.value).url, {
        preserveScroll: true,
    });
};

const removeAll = () => {
    if (confirm('Are you sure you want to delete all?')) {
        // En Laravel, si la ruta destroy no recibe ID, suele borrar todo o fallar.
        // Aquí pasamos un valor vacío o nulo si tu controlador lo soporta.
        router.delete(destroy('').url);
    }
};

const status = (todo: any) => {
    todo.status = todo.status == '1' ? '0' : '1';
    // Usamos todoStatus(id).url
    router.post(todoStatus(todo.id).url, {
        status: todo.status,
    });
};

const order = () => {
    const ids = dtodos.value.map((t) => t.id);
    // Usamos todoOrder().url
    router.post(todoOrder().url, { ids });
};
</script>

<template>
    <WebLayout>
        <o-modal v-model:active="confirmDeleteActive">
            <p class="p-4 text-black">
                Are you sure you want to delete the record?
            </p>
            <div class="flex flex-row-reverse gap-2 bg-gray-100 p-3">
                <o-button variant="danger" @click="remove">Delete</o-button>
                <o-button @click="confirmDeleteActive = false">Cancel</o-button>
            </div>
        </o-modal>

        <div class="mycard mx-auto mt-10 max-w-2xl">
            <div class="mycard-body">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-2xl font-bold">App To Do</h3>
                    <Button
                        variant="destructive"
                        size="sm"
                        @click="removeAll"
                        class="gap-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                            />
                        </svg>
                        Delete All
                    </Button>
                </div>

                <form @submit.prevent="create" class="mb-2 flex gap-2">
                    <div class="flex-1">
                        <Input
                            :class="{
                                'border-red-500 bg-red-50':
                                    errors.name && todoSelected == 0,
                            }"
                            v-model="form.name"
                            placeholder="What needs to be done?"
                        />
                    </div>
                    <Button :disabled="form.processing">Send</Button>
                </form>
                <InputError v-if="todoSelected == 0" :message="errors.name" />

                <ul ref="todoListRef" class="mt-6">
                    <li
                        v-for="element in dtodos"
                        :key="element.id"
                        class="group mt-2 flex items-center rounded-lg border bg-white px-4 py-3 shadow-sm dark:bg-gray-800"
                    >
                        <span class="drag-handle mr-2 cursor-grab text-gray-400"
                            >::</span
                        >

                        <button
                            @click="status(element)"
                            class="focus:outline-none"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                :stroke="
                                    element.status == '1'
                                        ? '#10b981'
                                        : 'currentColor'
                                "
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                class="size-6 transition-colors"
                            >
                                <path
                                    v-if="element.status == '1'"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                                <path
                                    v-else
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                        </button>

                        <div class="ml-3 flex-1">
                            <span
                                v-if="!element.editMode"
                                @click="element.editMode = true"
                                class="block w-full cursor-pointer"
                                :class="{
                                    'text-gray-400 line-through':
                                        element.status == '1',
                                }"
                            >
                                {{ element.name }}
                            </span>
                            <Input
                                v-else
                                v-model="element.name"
                                @keyup.enter="update(element)"
                                @blur="element.editMode = false"
                                auto-focus
                            />
                            <InputError
                                v-if="todoSelected == element.id"
                                :message="errors.name"
                            />
                        </div>

                        <button
                            class="ml-2 opacity-0 transition-opacity group-hover:opacity-100"
                            @click="
                                confirmDeleteActive = true;
                                deleteTodoRow = element.id;
                            "
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="#ef4444"
                                class="size-5"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5 0l.5 8.5a.75.75 0 1 0 1.5 0l-.5-8.5Zm4.785 0a.75.75 0 1 0-1.5 0l-.5 8.5a.75.75 0 1 0 1.5 0l.5-8.5Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </WebLayout>
</template>
