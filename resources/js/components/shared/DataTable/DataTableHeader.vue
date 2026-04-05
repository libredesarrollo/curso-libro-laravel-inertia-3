<script setup lang="ts">
import { ArrowUp, ArrowDown } from 'lucide-vue-next';

interface Column {
    [key: string]: string;
}

interface Props {
    columns: Column;
    sortColumn: string;
    sortDirection: 'asc' | 'desc';
}

defineProps<Props>();

const emit = defineEmits<{
    sort: [column: string];
}>();

function handleSort(column: string) {
    emit('sort', column);
}
</script>

<template>
    <thead class="border-b bg-muted/50">
        <tr>
            <th
                v-for="(label, key) in columns"
                :key="key"
                class="cursor-pointer px-4 py-3 text-left text-sm font-medium text-muted-foreground transition-colors select-none hover:bg-muted"
                @click="handleSort(key)"
            >
                <div class="flex items-center gap-2">
                    {{ label }}
                    <span v-if="key === sortColumn" class="inline-flex">
                        <ArrowUp
                            v-if="sortDirection === 'asc'"
                            class="h-4 w-4 text-primary"
                        />
                        <ArrowDown v-else class="h-4 w-4 text-primary" />
                    </span>
                </div>
            </th>
            <th
                class="px-4 py-3 text-left text-sm font-medium text-muted-foreground"
            >
                Actions
            </th>
        </tr>
    </thead>
</template>
