import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

export function useFilters<T extends object>(baseUrl: string, initialFilters: T) {
    
    // 1. Creamos un ref que contiene EXACTAMENTE las llaves que le pases
    // Si le pasas { name: '' }, tendrá name. Si le pasas { posted: '' }, tendrá posted.
    const filters = ref<T>({ ...initialFilters });

    const applyFilters = (newSortColumn?: string) => {
        const params: any = { ...filters.value };

        // 2. Lógica de ordenamiento genérica
        if (newSortColumn) {
            if (newSortColumn === params.sortColumn) {
                params.sortDirection = params.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                params.sortColumn = newSortColumn;
                params.sortDirection = 'asc';
            }
            // Actualizamos el ref interno para que la UI refleje el cambio
            (filters.value as any).sortColumn = params.sortColumn;
            (filters.value as any).sortDirection = params.sortDirection;
        }

        router.get(baseUrl, params, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    };

    return {
        filters,
        applyFilters,
    };
}