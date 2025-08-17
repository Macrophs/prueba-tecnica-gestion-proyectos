<script setup lang="ts">
import { Clock, LoaderCircle, Check, AlertCircle } from 'lucide-vue-next';

const props = defineProps<{
    statusCounts?: Record<string, number>;
}>();

const statuses = ['Pendiente', 'En Progreso', 'Finalizada'];

const countForStatus = (status: string) => {
    return props.statusCounts?.[status] || 0;
};

const statusIcon = (status: string) => {
    const icons: Record<string, any> = {
        'Pendiente': Clock,
        'En Progreso': LoaderCircle,
        'Finalizada': Check,
    };
    return icons[status] || AlertCircle;
};

const statusContainerClasses = (status: string) => {
    const base = 'border';
    const variants: Record<string, string> = {
        'Pendiente': 'border-blue-200 bg-blue-50',
        'En Progreso': 'border-yellow-200 bg-yellow-50',
        'Finalizada': 'border-green-200 bg-green-50',
    };
    return `${base} ${variants[status] || 'bg-gray-50'}`;
};

const statusIconClasses = (status: string) => {
    const variants: Record<string, string> = {
        'Pendiente': 'bg-blue-100 text-blue-600',
        'En Progreso': 'bg-yellow-100 text-yellow-600',
        'Finalizada': 'bg-green-100 text-green-600',
    };
    return variants[status] || 'bg-gray-100 text-gray-600';
};

const statusCountClasses = (status: string) => {
    const variants: Record<string, string> = {
        'Pendiente': 'text-blue-600',
        'En Progreso': 'text-yellow-600',
        'Finalizada': 'text-green-600',
    };
    return variants[status] || 'text-gray-600';
};
</script>

<template>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-2">
        <div v-for="status in statuses" :key="status" class="flex items-center gap-2 p-1 rounded-lg border"
            :class="statusContainerClasses(status)">

            <span class="flex items-center justify-center w-6 h-6 rounded-full" :class="statusIconClasses(status)">
                <component :is="statusIcon(status)" class="w-3 h-3" />
            </span>

            <span class="text-xs font-medium text-gray-700 hidden 2xl:block">{{ status }}</span>

            <span class="ml-auto font-bold" :class="statusCountClasses(status)">
                {{ countForStatus(status) }}
            </span>
        </div>
    </div>
</template>
