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
import { ProjectType, TaskType } from '@/types';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';

const props = defineProps<{
    onClose: (() => void);
    task: TaskType;
}>();

const form = useForm({
    status: props.task.status || '',
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
        const res = await axios.put(`/api/task/${props.task.id}/status`, form, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        if (res.status === 200) {
            toast('Tarea', {
                description: 'Estatus Editado Correctamente',
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
            <DialogTitle>Cambiar Estatus Tarea</DialogTitle>
            <DialogDescription>
                Cambie el estatus actual de la tarea
            </DialogDescription>
        </DialogHeader>

        <form method="POST" id="dialogForm" @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="title">Estatus</Label>
                    <Select id="status" v-model="form.status" required>
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Seleccionar Estatus" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
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

                    <InputError :message="form.errors.status?.[0]" />
                </div>
            </div>

        </form>

        <DialogFooter>
            <Button type="submit" form="dialogForm" :disabled="processing || (form.status === props.task.status)">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Cambiar Estatus
            </Button>
        </DialogFooter>
    </DialogContent>
</template>