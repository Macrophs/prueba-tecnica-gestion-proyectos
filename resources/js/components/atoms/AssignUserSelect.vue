<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import Select from '../ui/select/Select.vue';
import SelectContent from '../ui/select/SelectContent.vue';
import SelectGroup from '../ui/select/SelectGroup.vue';
import SelectItem from '../ui/select/SelectItem.vue';
import SelectTrigger from '../ui/select/SelectTrigger.vue';
import SelectValue from '../ui/select/SelectValue.vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import { UserType } from '@/types';

const props = defineProps<{
    projectId: string;
    modelValue: string | undefined ;
}>();

//obtain token
const page = usePage();
const token = page.props.auth_token;

const projectUsers = ref<UserType>();

const emit = defineEmits(['update:modelValue']); 

const selectedValue = ref(props.modelValue);


watch(() => props.modelValue, (newValue) => {
    selectedValue.value = newValue;
});


watch(selectedValue, (newValue) => {
    emit('update:modelValue', newValue);
});


onMounted(async () => {
    try {
        const { data } = await axios.get(`/api/project/${props.projectId}/users`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        projectUsers.value = data;

    } catch (error) {
        console.log(error)
    }
});

</script>

<template>
    <div class="grid gap-2">
        <Label for="user">Usuario</Label>
        <Select v-model="selectedValue" required>
            <SelectTrigger class="w-full">
                <SelectValue placeholder="Seleccionar usuario" />
            </SelectTrigger>
            <SelectContent v-if="projectUsers">
                <SelectGroup>
                    <SelectItem :key="projectUsers.id" :value="projectUsers.id.toString()">
                        {{ projectUsers.username }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>