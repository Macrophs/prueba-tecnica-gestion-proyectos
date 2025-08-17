<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import axios, { formToJSON } from 'axios';
import { toast } from 'vue-sonner';
import { ref } from 'vue';
import { ProjectType } from '@/types';


const props = defineProps<{
    onClose: (() => void);
    project: ProjectType;
}>();


const emit = defineEmits(['data-changed']);

//for loader button
const processing = ref(false);

const page = usePage();
const token = page.props.auth_token;

//Create project with projectController
const submit = async () => {
    processing.value = true;
    try {
        const res = await axios.delete(`/api/project/${props.project.id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        if (res.status === 200) {
            toast('Proyecto', {
                description: 'Proyecto Eliminado Correctamente',
            })
            props.onClose();
            emit('data-changed'); //reload data
        }
    } catch (error: any) {

        console.error(error);

    } finally {
        processing.value = false;
    }
};


</script>

<template>
    <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
            <DialogTitle>Eliminar Proyecto</DialogTitle>
            <DialogDescription>
                Borre permanentemente un proyecto de la aplicación
            </DialogDescription>
        </DialogHeader>
        <div class="italic tex text-foreground">
            "{{ props.project.name }}"
        </div>
        <div class="text-destructive">
            ¡Esta acción es irreversible!
        </div>
        <DialogFooter>
            <Button type="submit" form="dialogForm" :disabled="processing" variant="destructive" @click="submit">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Eliminar Proyecto
            </Button>
        </DialogFooter>
    </DialogContent>
</template>