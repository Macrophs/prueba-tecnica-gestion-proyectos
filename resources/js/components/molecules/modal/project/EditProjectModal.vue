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
import { ProjectType } from '@/types';


const props = defineProps<{
    onClose: (() => void) ;
    project: ProjectType;
}>();

const form = useForm({
    name: props.project.name ||'',
    description: props.project.description || '',
});

const emit = defineEmits(['data-changed']);

//for loader button
const processing = ref(false);

const page = usePage();
const token = page.props.auth_token;

//Create project with projectController
const submit = async () => {
    processing.value = true;
    form.clearErrors();
    try {
        const res = await axios.put(`/api/project/${props.project.id}`, form, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        console.log(res)
        if (res.status === 200) {
            toast('Proyecto', {
                description: 'Proyecto Actualizado Correctamente',
            })
            props.onClose();
            form.reset();
            emit('data-changed'); //reload data
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
            <DialogTitle>Editar Proyecto</DialogTitle>
            <DialogDescription>
              Cambie la información general del proyecto
            </DialogDescription>
        </DialogHeader>

        <form method="POST" id="dialogForm" @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nombre del Proyecto</Label>
                    <Input id="name" type="text" required autocomplete="name" v-model="form.name" />
                    <InputError :message="form.errors.name?.[0]" />
                </div>
                <div class="grid gap-2">
                    <Label for="description">Descripción del Proyecto</Label>
                    <Textarea id="description" class="max-h-52" required autocomplete="description"
                        v-model="form.description" />
                    <InputError :message="form.errors.description?.[0]" />
                </div>
            </div>

        </form>

        <DialogFooter>
            <Button type="submit" form="dialogForm" :disabled="processing">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Editar Proyecto
            </Button>
        </DialogFooter>
    </DialogContent>
</template>