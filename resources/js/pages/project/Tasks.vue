<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ProjectType, type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';

import TasksTable from '@/components/organism/TasksTable.vue';
import { useRoute } from 'ziggy-js';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import { LoaderCircle } from 'lucide-vue-next';

const route = useRoute();
const projectId = route().params.id;
const page = usePage();
const token = page.props.auth_token;

const loading = ref<Boolean>(false);
const project = ref<ProjectType>();

onMounted(async () => {
    loading.value = true;
    try {
        const { data } = await axios.get(`/api/project/${projectId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        console.log(data);
        project.value = data.data;
        if (!project.value)
            router.visit('/dashboard');

    } catch (error) {
        console.log(error)
        router.visit('/dashboard');
    } finally {
        loading.value = false;
    }
});

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: project.value?.name + " / Tareas" || "Cargando...",
        href: `/project/${projectId}/task`,
    },
]);

</script>

<template>

    <Head title="Tasks" />
    <LoaderCircle v-if="loading" class="h-12 w-12 mt-8 mx-auto animate-spin" />
    <AppLayout v-else :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto ">
            <div class="w-full p-10 flex flex-col gap-10 ">

                <TasksTable :projectId="projectId" />
            </div>
        </div>
    </AppLayout>
</template>
