<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import ProjectsTable from '@/components/organism/ProjectsTable.vue';
import ProjectsTableDevelop from '@/components/organism/ProjectsTableDevelop.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestión de Proyectos',
        href: '/dashboard',
    },
];

const page = usePage();
const user = page.props.auth.user;

</script>

<template>

    <Head title="Gestión de Proyectos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto ">
            <div class="w-full p-10 flex flex-col gap-10 ">
                <ProjectsTable v-if="user.role === 'Admin'" />

                <ProjectsTableDevelop v-else />
            </div>
        </div>
    </AppLayout>
</template>
