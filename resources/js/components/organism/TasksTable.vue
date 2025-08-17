<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { usePagination } from '@/composables/usePagination';
import { ArrowLeftCircle, ListCheck, LoaderCircle, PencilLine, Trash, User } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import ScrollArea from '../ui/scroll-area/ScrollArea.vue';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import EditProjectModal from '../molecules/modal/project/EditProjectModal.vue';
import DeleteProject from '../molecules/modal/project/DeleteProjectModal.vue';
import { dialogState } from '@/composables/dialog';
import { Link } from '@inertiajs/vue3';
import TasksTableHeader from '../molecules/TasksTableHeader.vue';
import { ProjectType, TaskType } from '@/types';
import Badge from '../ui/badge/Badge.vue';
import EditTaskModal from '../molecules/modal/task/EditTaskModal.vue';
import DeleteTaskModal from '../molecules/modal/task/DeleteTaskModal.vue';
import StatusTaskModal from '../molecules/modal/task/StatusTaskModal.vue';
import AssignTaskModal from '../molecules/modal/task/AssignTaskModal.vue';

const props = defineProps<{
    projectId: string;
}>();


const { items, currentPage, fetchItems, lastPage, goToPage, loading, setSearchQuery, setStatusFilter } = usePagination<TaskType>(`/api/project/${props.projectId}/tasks`);

const [isOpen, closeDialog] = dialogState();


const handleDataChanged = () => {
    fetchItems(currentPage.value); // reload pagination
};


</script>

<template>
    <Link :href="route('dashboard')">
    <Button variant="ghost" class="w-fit">
        <ArrowLeftCircle class="size-4" />
        Regresar
    </Button>
    </Link>

    <TasksTableHeader :projectId="props.projectId" @data-changed="handleDataChanged" @search="setSearchQuery"
        @filter="setStatusFilter" />
    <LoaderCircle v-if="loading" class="h-12 w-12 mt-8 mx-auto animate-spin" />
    <Table v-else>
        <TableHeader class="border-2 border-secondary rounded-2xl">
            <TableRow>
                <TableHead class="w-[100px] font-extrabold ">
                    Tarea
                </TableHead>
                <TableHead class="font-extrabold">Descripción</TableHead>
                <TableHead class="font-extrabold">Estatus</TableHead>
                <TableHead class="font-extrabold">Usuario Asignado</TableHead>
                <TableHead class="text-center font-extrabold">
                    Opciones
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody class="border-2 border-secondary rounded-2xl ">
            <TableRow v-for="item in items" :key="item.id">
                <TableCell class="font-medium max-w-[300px]">

                    <ScrollArea class="h-10  whitespace-pre-wrap break-all   ">
                        {{ item.title }}

                    </ScrollArea>

                </TableCell>
                <TableCell class="w-[200px] ">
                    <ScrollArea class="h-10  whitespace-pre-wrap break-all   ">

                        {{ item.description }}

                    </ScrollArea>
                </TableCell>
                <TableCell>
                    <Badge :class="[
                        'text-white',
                        item.status === 'Pendiente' ? 'bg-amber-500' : '',
                        item.status === 'En Proceso' ? 'bg-blue-500' : '',
                        item.status === 'Finalizada' ? 'bg-green-500' : ''
                    ]">
                        {{ item.status }}
                    </Badge>

                </TableCell>
                <TableCell>
                    <div class="flex gap-1 items-center">
                        <User class="w-4" />
                        {{ item.user?.username || "No Asginado" }}
                    </div>


                </TableCell>
                <TableCell class="flex gap-5 items-center justify-center">

                    <Dialog>
                        <DialogTrigger as-child>

                            <Button class="gap-1">
                                <PencilLine />
                                Asignar
                            </Button>
                        </DialogTrigger>

                        <AssignTaskModal :task="item" :onClose="() => closeDialog()"
                            @data-changed="handleDataChanged" />

                    </Dialog>

                    <Dialog>
                        <DialogTrigger as-child>

                            <Button class="gap-1 bg-orange-400 hover:bg-orange-400/80">
                                <PencilLine />
                                Estatus
                            </Button>
                        </DialogTrigger>

                        <StatusTaskModal :task="item" :onClose="() => closeDialog()"
                            @data-changed="handleDataChanged" />

                    </Dialog>

                    <Dialog>
                        <DialogTrigger as-child>

                            <Button class="gap-1 bg-blue-400 hover:bg-blue-400/80">
                                <PencilLine />
                                Editar
                            </Button>
                        </DialogTrigger>

                        <EditTaskModal :task="item" :onClose="() => closeDialog()" @data-changed="handleDataChanged" />

                    </Dialog>

                    <Dialog>
                        <DialogTrigger as-child>
                            <Button class="gap-1" variant="destructive">
                                <Trash />
                                Eliminar
                            </Button>

                        </DialogTrigger>

                        <DeleteTaskModal :task="item" :onClose="() => closeDialog()"
                            @data-changed="handleDataChanged" />

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