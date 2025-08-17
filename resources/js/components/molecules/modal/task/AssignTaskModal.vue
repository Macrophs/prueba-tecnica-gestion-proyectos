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
import AssignUserSelect from '@/components/atoms/AssignUserSelect.vue';

const props = defineProps<{
    onClose: (() => void);
    task: TaskType;
}>();

const form = useForm({
    user_id: props.task.user_id,
});

const emit = defineEmits(['data-changed']);


//for loader button
const processing = ref(false);

const page = usePage();
const token = page.props.auth_token;

const handleUserChange = (value: string) => {
    form.user_id = value;
};

//Create project with projectController
const submit = async () => {
    processing.value = true;
    form.clearErrors();
    try {
        const res = await axios.put(`/api/task/${props.task.id}/assign-user`, form, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        if (res.status === 200) {
            toast('Asignación', {
                description: 'Usuario Asignado Correctamente',
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
            <DialogTitle>Asignar Usuario</DialogTitle>
            <DialogDescription>
                Asigne un usuario a la tarea
            </DialogDescription>
        </DialogHeader>

        <form method="POST" id="dialogForm" @submit.prevent="submit">
            <div class="grid gap-6">
                <AssignUserSelect :project-id="props.task.project_id" :model-value="form.user_id"
                    @update:model-value="handleUserChange" />
            </div>

        </form>

        <DialogFooter>
            <Button type="submit" form="dialogForm" :disabled="processing || (form.user_id === props.task.user_id)">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Asignar
            </Button>
        </DialogFooter>
    </DialogContent>
</template>