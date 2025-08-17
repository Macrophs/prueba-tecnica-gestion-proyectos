<script setup lang="ts">
import InputError from '@/components/atoms/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import axios, { formToJSON } from 'axios';
import { toast } from 'vue-sonner';
import { ref } from 'vue';

const props = defineProps<{
    onClose: (() => void);
    projectId: string;
}>();

const form = useForm({
    title: '',
    description: '',
});

const emit = defineEmits(['task-created']);

//for loader button
const processing = ref(false);

const page = usePage();
const token = page.props.auth_token;

//Create task with taskController
const submit = async () => {
    processing.value = true;
    form.clearErrors();
    try {
        const res = await axios.post(`/api/project/${props.projectId}/task`, form, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        if (res.status === 201) {
            toast('Tarea', {
                description: 'Tarea Creada Correctamente',
            })
            props.onClose();
            form.reset();
            emit('task-created'); //reload data
        }
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            form.setError(error.response.data.errors);
        } else {
            console.error(error);
        }
    } finally {
        processing.value = false;
    }
};


</script>

<template>
    <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
            <DialogTitle>Nueva Tarea</DialogTitle>
            <DialogDescription>
                Cree un nueva Tarea rellenando los siguientes datos
            </DialogDescription>
        </DialogHeader>

        <form method="POST" id="dialogForm" @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="title">Título del Tarea</Label>
                    <Input id="title" type="text" required autofocus autocomplete="title" v-model="form.title" />
                    <InputError :message="form.errors.title?.[0]" />
                </div>
                <div class="grid gap-2">
                    <Label for="description">Descripción del Tarea</Label>
                    <Textarea id="description" class="max-h-52" required autocomplete="description"
                        v-model="form.description" />
                    <InputError :message="form.errors.description?.[0]" />
                </div>
            </div>

        </form>

        <DialogFooter>
            <Button type="submit" form="dialogForm" :disabled="processing">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Crear Tarea
            </Button>
        </DialogFooter>
    </DialogContent>
</template>