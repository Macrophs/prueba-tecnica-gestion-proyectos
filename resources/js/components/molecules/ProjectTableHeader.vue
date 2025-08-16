<script setup lang="ts">

import { ChevronDown, PlusCircle } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import DropdownMenu from '../ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuCheckboxItem from '../ui/dropdown-menu/DropdownMenuCheckboxItem.vue';
import DropdownMenuContent from '../ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuTrigger from '../ui/dropdown-menu/DropdownMenuTrigger.vue';
import Input from '../ui/input/Input.vue';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import NewProjectModal from './modal/project/NewProjectModal.vue';
import { dialogState } from '@/composables/dialog';
import Select from '../ui/select/Select.vue';
import SelectTrigger from '../ui/select/SelectTrigger.vue';
import SelectContent from '../ui/select/SelectContent.vue';
import SelectValue from '../ui/select/SelectValue.vue';
import SelectGroup from '../ui/select/SelectGroup.vue';
import SelectLabel from '../ui/select/SelectLabel.vue';
import SelectItem from '../ui/select/SelectItem.vue';

const [isOpen, closeDialog] = dialogState();

</script>


<template>

    <div class="flex gap-2 items-start justify-start py-4 ">
        <Input class="max-w-sm" placeholder="Buscar..." />
        <div class="mr-auto">
            <Select>
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
                        <SelectItem value="Finalizado">
                            Finalizado
                        </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
        <Dialog v-model:open="isOpen">

            <DialogTrigger as-child>
                <Button variant="default" class="w-42 ml-auto">
                    Nuevo Proyecto
                    <PlusCircle />
                </Button>
            </DialogTrigger>

            <NewProjectModal :onClose="() => closeDialog()" />

        </Dialog>
    </div>
</template>