<script setup>
import { ref } from 'vue';
import Notification from './Notification.vue';

const notifications = ref([]);

const addNotification = (notification) => {
    const id = Date.now() + Math.random();
    notifications.value.push({
        id,
        ...notification
    });
    return id;
};

const removeNotification = (id) => {
    const index = notifications.value.findIndex(n => n.id === id);
    if (index > -1) {
        notifications.value.splice(index, 1);
    }
};

// Expose methods for external use
defineExpose({
    addNotification,
    removeNotification,
    success: (message, options = {}) => addNotification({ type: 'success', message, ...options }),
    error: (message, options = {}) => addNotification({ type: 'error', message, ...options }),
    warning: (message, options = {}) => addNotification({ type: 'warning', message, ...options }),
    info: (message, options = {}) => addNotification({ type: 'info', message, ...options })
});
</script>

<template>
    <div class="fixed top-4 right-4 z-50 space-y-3">
        <Notification
            v-for="notification in notifications"
            :key="notification.id"
            :type="notification.type"
            :message="notification.message"
            :title="notification.title"
            :dismissible="notification.dismissible !== false"
            :auto-hide="notification.autoHide !== false"
            :duration="notification.duration || 5000"
            @close="removeNotification(notification.id)"
        />
    </div>
</template>