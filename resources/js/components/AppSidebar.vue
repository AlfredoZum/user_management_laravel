<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const permissions = page.props.auth.permissions;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        permission: permissions.viewUsers
    },
    {
        title: 'Usuarios',
        href: '/users',
        icon: LayoutGrid,
        permission: permissions.viewUsers
    },
    {
        title: 'Tareas',
        href: '/tasks',
        icon: LayoutGrid,
        permission: permissions.viewTasks
    },
];

const navItems: NavItem[] = mainNavItems.filter((n) => {

    if (!n.permission && n.title === 'Dashboard') {
        return n;
    }

    if (n.permission) {
        return n;
    }

    return false;

});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="navItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
