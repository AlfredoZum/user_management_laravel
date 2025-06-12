<script setup lang="ts">
import { type BreadcrumbItem, type User } from '@/types';
const props = defineProps<{
    users: User[];
}>();
import AppLayout from '@/layouts/AppLayout.vue';

import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    roles: { id: number; name: string; }[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuario',
        href: '/user',
    },
];

const editUser = (id: number) => {
    router.visit(route('users.edit', id));
};

const deleteUser = (userId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar a este usuario? Esta acción no se puede deshacer.')) {
        router.delete(route('users.destroy', userId), {
            onSuccess: () => alert('Usuario eliminado exitosamente.'),
            onError: (errors) => alert('Hubo un error al eliminar el usuario.'),
        });
    }
};
</script>

<template>

    <Head title="User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Lista de Usuarios</h1>
                    <Link :href="route('users.create')"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Crear Usuario
                    </Link>
                </div>
                <table class="min-w-full shadow rounded-xl">
                    <thead>
                        <tr class="bg-gray-800 text-left text-sm font-semibold">
                            <th class="p-3">ID</th>
                            <th class="p-3">Nombre</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Role</th>
                            <th class="p-3">Creado</th>
                            <th class="p-3">Editar</th>
                            <th class="p-3">Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="border-b text-sm hover:bg-gray-800">
                            <td class="p-3">{{ user.id }}</td>
                            <td class="p-3">{{ user.name }}</td>
                            <td class="p-3">{{ user.email }}</td>
                            <td class="p-3">{{ user.roles[0].name }}</td>
                            <td class="p-3">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                            <td class="p-3">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded"
                                    @click="editUser(user.id)">
                                    Editar
                                </button>
                            </td>
                            <td class="p-3">
                                <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded"
                                    @click="deleteUser(user.id)">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
