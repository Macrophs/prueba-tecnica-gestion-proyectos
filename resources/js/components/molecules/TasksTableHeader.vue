<script setup lang="ts">

import { PlusCircle } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import Input from '../ui/input/Input.vue';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import { dialogState } from '@/composables/dialog';
import Select from '../ui/select/Select.vue';
import SelectTrigger from '../ui/select/SelectTrigger.vue';
import SelectContent from '../ui/select/SelectContent.vue';
import SelectValue from '../ui/select/SelectValue.vue';
import SelectGroup from '../ui/select/SelectGroup.vue';
import SelectItem from '../ui/select/SelectItem.vue';
import NewTaskModal from './modal/task/NewTaskModal.vue';
import { watchDebounced } from '@vueuse/core';
import { ref } from 'vue';

const [isOpen, closeDialog] = dialogState();

const props = defineProps<{
    projectId: string;
}>();

const emit = defineEmits(['data-changed','search','filter']);

const searchTerm = ref('');
const selectedStatus = ref('Todos');

const handleTaskCreated = () => {
    emit('data-changed');
};

watchDebounced(
    searchTerm,
    (term) => { emit('search', term); },
    { debounce: 500, maxWait: 1000 },
)

watchDebounced(
    selectedStatus,
    (status) => { emit('filter', status === 'Todos' ? null : status); },
    { debounce: 500, maxWait: 1000 },
)

</script>


<template>

    <div class="flex gap-2 items-start justify-start py-4 ">
        <Input v-model="searchTerm" class="max-w-sm" placeholder="Buscar..." />
        <div class="mr-auto">
            <Select v-model="selectedStatus">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Seleccionar Estatus" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectItem value="Todos">
                            Seleccionar Estatus
                        </SelectItem>
                        <SelectItem value="Pendiente">
                            Pendiente
                        </SelectItem>
                        <SelectItem value="En Proceso">
                            En Proceso
                        </SelectItem>
                        <SelectItem value="Finalizada">
                            Finalizada
                        </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
        <Dialog v-model:open="isOpen">

            <DialogTrigger as-child>
                <Button variant="default" class="w-42 ml-auto">
                    Nueva Tarea
                    <PlusCircle />
                </Button>
            </DialogTrigger>

            <NewTaskModal :onClose="() => closeDialog()" :project-id="props.projectId" @task-created="handleTaskCreated" />

        </Dialog>
    </div>
</template>