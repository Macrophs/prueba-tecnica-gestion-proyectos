<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import ProjectsTable from '@/components/organism/ProjectsTable.vue';
import UsersTable from '@/components/organism/UsersTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestión de Usuarios',
        href: '/dashboard',
    },
];


</script>

<template>

    <Head title="Gestión de Usuarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto ">
            <div class="w-full p-10 flex flex-col gap-10 ">
                <UsersTable />
            </div>
        </div>
    </AppLayout>
</template>
