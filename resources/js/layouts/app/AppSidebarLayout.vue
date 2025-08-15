<script setup lang="ts">
import AppContent from '@/components/templates/AppContent.vue';
import AppShell from '@/components/templates/AppShell.vue';
import AppSidebar from '@/components/organism/AppSidebar.vue';
import AppSidebarHeader from '@/components/molecules/AppSidebarHeader.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
