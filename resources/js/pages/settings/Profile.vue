<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/molecules/DeleteUser.vue';
import HeadingSmall from '@/components/atoms/HeadingSmall.vue';
import InputError from '@/components/atoms/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type UserType } from '@/types';




const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Ajustes de Perfil',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as UserType;

const form = useForm({
    name: user.name,
    username: user.username,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Ajustes de Perfil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Información de Perfil" description="Actualice su nombre y su nombre de usuario" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="username">Nombre de Usuario</Label>
                        <Input id="username" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" placeholder="Username" />
                        <InputError class="mt-2" :message="form.errors.username" />
                    </div>

                   


                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Guardar</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Guardar</p>
                        </Transition>
                    </div>
                </form>
            </div>

            
        </SettingsLayout>
    </AppLayout>
</template>
