<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { usePagination } from '@/composables/usePagination';
import { ListCheck, LoaderCircle, PencilLine, Trash, User } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import { dialogState } from '@/composables/dialog';
import { Link } from '@inertiajs/vue3';
import { UserType } from '@/types';
import UsersTableHeader from '../molecules/UsersTableHeader.vue';
import Badge from '../ui/badge/Badge.vue';
import { formatDate, useDateFormat } from '@vueuse/core';
import EditUserModal from '../molecules/modal/user/EditUserModal.vue';
import DeleteUserModal from '../molecules/modal/user/DeleteUserModal.vue';


const { items, currentPage, fetchItems, lastPage, goToPage, loading, setSearchQuery } = usePagination<UserType>("/api/users");

const [isOpen, closeDialog] = dialogState();


const handleDataChanged = () => {
    fetchItems(currentPage.value); // reload pagination
};

</script>

<template>
    <UsersTableHeader @data-changed="handleDataChanged" @search="setSearchQuery" />
    <LoaderCircle v-if="loading" class="h-12 w-12 mt-8 mx-auto animate-spin" />
    <Table v-else>
        <TableHeader class="border-2  bg-secondary rounded-2xl  ">
            <TableRow>
                <TableHead class="w-[100px] font-extrabold ">
                    Nombre
                </TableHead>
                <TableHead class="font-extrabold">Nombre de Usuario</TableHead>
                <TableHead class="font-extrabold">Rol</TableHead>
                <TableHead class="font-extrabold">Creado el</TableHead>
                <TableHead class="text-center font-extrabold">
                    Opciones
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody  class="border-2 border-secondary rounded-2xl ">
            <TableRow v-for="item in items" :key="item.id" >
                <TableCell class="font-medium ">
                    {{ item.name }}
                </TableCell>
                <TableCell>
                    <div class="flex gap-1 items-center">
                        <User class="w-4" />
                        {{ item.username }}
                    </div>
                </TableCell>
                <TableCell>
                    <Badge :class="[
                        'text-white',
                        item.role === 'Admin' ? 'bg-red-500' : '',
                        item.role === 'Develop' ? 'bg-blue-500' : '',

                    ]">
                        {{ item.role }}
                    </Badge>
                </TableCell>
                <TableCell>

                    {{ useDateFormat(item.created_at,'YYYY-MM-DD HH:mmaa') }}

                </TableCell>
                <TableCell class="flex gap-5 items-center justify-center">
                    <Dialog>
                        <DialogTrigger as-child>

                            <Button class="gap-1 bg-blue-400 hover:bg-blue-400/80" :disabled="item.role === 'Admin'">
                                <PencilLine />
                                Editar
                            </Button>
                        </DialogTrigger>
                        <EditUserModal :user="item" :onClose="() => closeDialog()"  @data-changed="handleDataChanged"  />
                    </Dialog>

                    <Dialog>
                        <DialogTrigger as-child>
                            <Button class="gap-1" variant="destructive" :disabled="item.role === 'Admin'">
                                <Trash />
                                Eliminar
                            </Button>
                            <DeleteUserModal :user="item" :onClose="() => closeDialog()"  @data-changed="handleDataChanged" />
                        </DialogTrigger>
                    </Dialog>
                </TableCell>
            </TableRow>
        </TableBody>
        <div class="flex justify-center gap-2 mt-4">
            <button class="px-3 py-1 border rounded" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
                Anterior
            </button>

            <button v-for="page in lastPage" :key="page" class="px-3 py-1 border rounded"
                :class="{ 'bg-blue-500 text-white': currentPage === page }" @click="goToPage(page)">
                {{ page }}
            </button>

            <button class="px-3 py-1 border rounded" :disabled="currentPage === lastPage"
                @click="goToPage(currentPage + 1)">
                Siguiente
            </button>
        </div>
    </Table>
</template>