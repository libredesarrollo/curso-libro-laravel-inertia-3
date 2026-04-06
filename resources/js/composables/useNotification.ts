import { ref, readonly } from 'vue';

interface Notification {
    id: number;
    message: string;
    variant: 'success' | 'danger' | 'warning' | 'info';
}

let notificationId = 0;
const notifications = ref<Notification[]>([]);

export function useNotification() {
    const add = (
        message: string,
        variant: Notification['variant'] = 'success',
    ) => {
        const id = ++notificationId;
        notifications.value.push({ id, message, variant });

        setTimeout(() => {
            remove(id);
        }, 3000);
    };

    const remove = (id: number) => {
        notifications.value = notifications.value.filter((n) => n.id !== id);
    };

    return {
        notifications: readonly(notifications),
        add,
        remove,
    };
}
