<script setup lang="ts">

const props = defineProps<{
    user: User;
    roles: Role[],
    userRoles: userRoles[],
}>();

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User, type Role, type userRoles } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Actualizar usuario',
        href: '/user',
    },
    {
        title: 'Editar usuario',
        href: `/user/${props.user.id}/edit`,
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    selectedRoles: props.userRoles,
});

const submit = () => {
    form.put(`/users/${props.user.id}`);
};
</script>

<template>

    <Head title="Create User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-bold mb-2">Nombre:</label>
                        <input type="text" id="name" v-model="form.name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="form.errors.name" class="text-red-500 text-xs italic">{{ form.errors.name }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block  text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" v-model="form.email"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="form.errors.email" class="text-red-500 text-xs italic">{{ form.errors.email }}</p>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-bold mb-2">Contraseña:</label>
                        <input type="password" id="password" v-model="form.password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="form.errors.password" class="text-red-500 text-xs italic">{{ form.errors.password }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-bold mb-2">Confirmar
                            Contraseña:</label>
                        <input type="password" id="password_confirmation" v-model="form.password_confirmation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-6" v-if="roles">
                        <label for="roles" class="block text-sm font-bold mb-2">Roles:</label>
                        <div v-for="role in roles" :key="role.id" class="mb-2">
                            <input type="checkbox" :id="`role-${role.id}`" :value="role.name"
                                v-model="form.selectedRoles" class="mr-2">
                            <label :for="`role-${role.id}`">{{ role.name }}</label>
                        </div>
                        <div v-if="form.errors.selectedRoles" class="text-red-500 text-xs italic mt-1">
                            {{ form.errors.selectedRoles }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" :disabled="form.processing"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Crear Usuario
                        </button>
                        <Link :href="route('users.index')"
                            class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
