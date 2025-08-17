<script setup lang="ts">
const props = defineProps<{
    modelValue: number
}>();
</script>

<template>
    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
        <div class="bg-green-500 h-full border border-foreground rounded-full transition-all duration-300"
            :style="{ width: `${props.modelValue}%` }">
        </div>
    </div>
</template>
