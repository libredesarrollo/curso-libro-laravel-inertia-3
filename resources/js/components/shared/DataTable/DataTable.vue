<script setup lang="ts">
import DataTableHeader from './DataTableHeader.vue';

interface Column {
    [key: string]: string;
}

interface Props {
    columns: Column;
    data: Array<Record<string, unknown>>;
    sortColumn: string;
    sortDirection: 'asc' | 'desc';
    emptyMessage?: string;
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
    <div class="overflow-x-auto">
        <table class="w-full">
            <DataTableHeader
                :columns="columns"
                :sort-column="sortColumn"
                :sort-direction="sortDirection"
                @sort="handleSort"
            />
            <tbody class="divide-y">
                <tr
                    v-for="(row, index) in data"
                    :key="index"
                    class="transition-colors hover:bg-muted/30"
                >
                    <slot :row="row" :index="index" />
                </tr>
                <tr v-if="data.length === 0">
                    <td
                        :colspan="Object.keys(columns).length + 1"
                        class="px-4 py-8 text-center text-sm text-muted-foreground"
                    >
                        {{ emptyMessage || 'No data found.' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
