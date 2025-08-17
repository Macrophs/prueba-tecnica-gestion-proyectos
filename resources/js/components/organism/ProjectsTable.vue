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
import { ListCheck, LoaderCircle, PencilLine, Trash, User } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import ScrollArea from '../ui/scroll-area/ScrollArea.vue';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogTrigger from '../ui/dialog/DialogTrigger.vue';
import EditProjectModal from '../molecules/modal/project/EditProjectModal.vue';
import DeleteProject from '../molecules/modal/project/DeleteProjectModal.vue';
import { dialogState } from '@/composables/dialog';
import { Link } from '@inertiajs/vue3';
import ProjectTableHeader from '../molecules/ProjectTableHeader.vue';
import { ProjectType } from '@/types';
import ProgressBar from '../atoms/ProgressBar.vue';
import BagdeTaskStatus from '../atoms/BagdeTaskStatus.vue';


const { items, currentPage, fetchItems, lastPage, goToPage, loading, setSearchQuery } = usePagination<ProjectType>("/api/projects");

const [isOpen, closeDialog] = dialogState();


const handleDataChanged = () => {
    fetchItems(currentPage.value); // reload pagination
};

</script>

<template>
    <ProjectTableHeader @data-changed="handleDataChanged" @search="setSearchQuery" />
    <LoaderCircle v-if="loading" class="h-12 w-12 mt-8 mx-auto animate-spin" />
    <Table v-else>
        <TableHeader class="border-2  bg-secondary rounded-2xl  ">
            <TableRow>
                <TableHead class="w-[100px] font-extrabold ">
                    Nombre del Proyecto
                </TableHead>
                <TableHead class="font-extrabold">Descripción</TableHead>
                <TableHead class="font-extrabold">Progreso</TableHead>
                <TableHead class="font-extrabold">Creador</TableHead>
                <TableHead class="font-extrabold text-center">Tareas</TableHead>
                <TableHead class="text-center font-extrabold">
                    Opciones
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody class="border-2 border-secondary rounded-2xl  ">
            <TableRow v-for="item in items" :key="item.id">
                <TableCell class="font-medium max-w-[300px]">

                    <ScrollArea class="h-10  whitespace-pre-wrap break-all   ">
                        {{ item.name }}

                    </ScrollArea>

                </TableCell>
                <TableCell class="w-[200px] ">
                    <ScrollArea class="h-10  whitespace-pre-wrap break-all   ">

                        {{ item.description }}

                    </ScrollArea>
                </TableCell>
                <TableCell>
                    <div class="flex flex-col gap-1 w-full">
                        <ProgressBar :model-value="Number(item.completion_percentage) || 0" class="h-2" />
                        <span class="text-xs text-gray-500">{{ item.completion_percentage || 0 }}% completado</span>
                    </div>
                </TableCell>
                <TableCell>
                    <div class="flex gap-1 items-center">
                        <User class="w-4" />
                        {{ item?.user?.username || "Usuario no Encontrado" }}
                    </div>


                </TableCell>
                <TableCell>
                    <div class="flex flex-col gap-1">
                        <BagdeTaskStatus :status-counts="item.tasks_by_status" variant="detailed" />
                    </div>
                </TableCell>
                <TableCell class="flex gap-5 items-center justify-center">



                    <Link :href="route('project.task', { id: item.id })">
                    <Button class="gap-1">
                        <ListCheck />
                        Tareas
                    </Button>
                    </Link>


                    <Dialog>
                        <DialogTrigger as-child>

                            <Button class="gap-1 bg-blue-400 hover:bg-blue-400/80">
                                <PencilLine />
                                Editar
                            </Button>
                        </DialogTrigger>
                        <EditProjectModal :project="item" :onClose="() => closeDialog()"
                            @data-changed="handleDataChanged" />
                    </Dialog>

                    <Dialog>
                        <DialogTrigger as-child>
                            <Button class="gap-1" variant="destructive">
                                <Trash />
                                Eliminar
                            </Button>

                        </DialogTrigger>
                        <DeleteProject :project="item" :onClose="() => closeDialog()"
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