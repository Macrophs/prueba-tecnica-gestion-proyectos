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
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';

const props = defineProps<{
    onClose: (() => void);
}>();

const form = useForm({
    name: '',
    username: '',
    role: '',
});

const emit = defineEmits(['user-created']);

//for loader button
const processing = ref(false);

const page = usePage();
const token = page.props.auth_token;

//Create user with userController
const submit = async () => {
    processing.value = true;
    form.clearErrors();
    try {
        const res = await axios.post('/api/user', form, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        if (res.status === 201) {
            toast('Usuario', {
                description: 'Usuario Creado Correctamente',
            })
            props.onClose();
            form.reset();
            emit('user-created'); //reload data
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
            <DialogTitle>Nuevo Usuario</DialogTitle>
            <DialogDescription>
                Cree un nuevo Usuario rellenando los siguientes datos
            </DialogDescription>
        </DialogHeader>

        <form method="POST" id="dialogForm" @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nombre</Label>
                    <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name" />
                    <InputError :message="form.errors.name?.[0]" />
                </div>
                <div class="grid gap-2">
                    <Label for="username">Nombre de Usuario</Label>
                    <Input id="username" type="text" required autofocus autocomplete="username"
                        v-model="form.username" />
                    <InputError :message="form.errors.username?.[0]" />
                </div>
                <div class="grid gap-2">
                    <Label for="username">Rol del Usuario</Label>
                    <Select v-model="form.role" required>
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Seleccionar usuario" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="Develop">
                                    Develop
                                </SelectItem>
                                <SelectItem value="Admin">
                                    Admin
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.role?.[0]" />
                </div>



            </div>

        </form>
        <span class="text-sm text-gray-500  text-center italic">
            La contraseña por defecto será el mismo nombre de usuario
        </span>
        <DialogFooter>

            <Button type="submit" form="dialogForm" :disabled="processing || form.role === ''">
                <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                Crear Usuario
            </Button>
        </DialogFooter>
    </DialogContent>
</template>