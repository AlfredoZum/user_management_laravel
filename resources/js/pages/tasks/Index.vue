<script setup lang="ts">
import { type BreadcrumbItem, type Task, type Permissions } from '@/types';
const props = defineProps<{
    tasks: Task[];
    can: Permissions;
}>();
import AppLayout from '@/layouts/AppLayout.vue';

import { Head, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tarea',
        href: '/user',
    },
];

const editUser = (taskId: number) => {
    router.visit(route('tasks.edit', taskId));
};

const deleteUser = (taskId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar la tarea? Esta acción no se puede deshacer.')) {
        router.delete(route('tasks.destroy', taskId), {
            onSuccess: () => alert('Tarea eliminado exitosamente.'),
            onError: (errors) => alert('Hubo un error al eliminar la tarea.'),
        });
    }
};

</script>

<template>

    <Head title="Tarea" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Lista de tareas</h1>
                    <Link v-if="can.createTasks" :href="route('tasks.create')"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Crear Usuario
                    </Link>
                </div>
                <table class="min-w-full shadow rounded-xl">
                    <thead>
                        <tr class="bg-gray-800 text-left text-sm font-semibold">
                            <th class="p-3">ID</th>
                            <th class="p-3">Nombre</th>
                            <th class="p-3">Descripcion</th>
                            <th class="p-3">Usuario</th>
                            <th class="p-3">Creado</th>
                            <th v-if="can.updateTasks" class="p-3">Editar</th>
                            <th v-if="can.deleteTasks" class="p-3">Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id" class="border-b text-sm hover:bg-gray-800">
                            <td class="p-3">{{ task.id }}</td>
                            <td class="p-3">{{ task.title }}</td>
                            <td class="p-3">{{ task.description }}</td>
                            <td class="p-3">{{ task.user ? task.user.name : 'Sin asignar' }}</td>
                            <td class="p-3">{{ new Date(task.created_at).toLocaleDateString() }}</td>
                            <td v-if="can.updateTasks" class="p-3">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded"
                                    @click="editUser(task.id)">
                                    Editar
                                </button>
                            </td>
                            <td v-if="can.deleteTasks" class="p-3">
                                <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded"
                                    @click="deleteUser(task.id)">
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
