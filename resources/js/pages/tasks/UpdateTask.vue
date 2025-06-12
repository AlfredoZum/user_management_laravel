<script setup lang="ts">
import { type BreadcrumbItem, type User, type Task } from '@/types';

const props = defineProps<{
    users: User[];
    task: Task;
}>();

import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tareas',
        href: '/tasks',
    },
    {
        title: 'Editar Tarea',
        href: `/tasks/${props.task.id}/edit`,
    },
];

const form = useForm({
    title: props.task.title,
    description: props.task.description,
    user_id: props.task.user_id,
});

const submit = () => {
    form.put(route('tasks.update', props.task.id));
};
</script>

<template>
    <Head :title="`Editar Tarea: ${form.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-bold mb-2">Titulo:</label>
                        <input type="text" id="title" v-model="form.title"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="form.errors.title" class="text-red-500 text-xs italic">{{ form.errors.title }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-bold mb-2">Descripcion:</label>
                        <input type="text" id="description" v-model="form.description"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="form.errors.description" class="text-red-500 text-xs italic">{{ form.errors.description }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-bold mb-2">Asignar a:</label>
                        <select id="user_id" v-model="form.user_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                            :class="{ 'border-red-500': form.errors.user_id }">
                            <option value="" disabled>Selecciona un usuario</option>
                            <option class="text-black hover:text-black" v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.user_id" class="text-red-500 text-xs italic">{{ form.errors.user_id }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" :disabled="form.processing"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Actualizar Tarea
                        </button>
                        <Link :href="route('tasks.index')"
                            class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>