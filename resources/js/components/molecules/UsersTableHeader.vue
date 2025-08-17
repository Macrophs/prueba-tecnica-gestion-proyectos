<script setup lang="ts">

import { PlusCircle } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import Input from '../ui/input/Input.vue';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import NewProjectModal from './modal/project/NewProjectModal.vue';
import { dialogState } from '@/composables/dialog';
import { ref } from 'vue';
import { watchDebounced } from '@vueuse/core';
import NewUserModal from './modal/user/NewUserModal.vue';

const [isOpen, closeDialog] = dialogState();

const emit = defineEmits(['data-changed', 'search']);

const searchTerm = ref('');

const handleProjectCreated = () => {
    emit('data-changed');
};


watchDebounced(
    searchTerm,
    (term) => { emit('search', term); },
    { debounce: 500, maxWait: 1000 },
)

</script>


<template>

    <div class="flex gap-2 items-start justify-start py-4 ">
        <Input v-model="searchTerm" class="max-w-sm" placeholder="Buscar..." />

        <Dialog v-model:open="isOpen">

            <DialogTrigger as-child>
                <Button variant="default" class="w-42 ml-auto">
                    Nuevo Usuario
                    <PlusCircle />
                </Button>
            </DialogTrigger>

            <NewUserModal :onClose="() => closeDialog()" @user-created="handleProjectCreated" />

        </Dialog>
    </div>
</template>