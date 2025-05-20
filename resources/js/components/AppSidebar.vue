<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import { Sidebar, SidebarContent } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { BookOpen, Folder, FolderTree, LayoutDashboard, Package, ShoppingCart, Tag } from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutDashboard,
    },
    {
        title: 'Brands',
        href: route('admin.brands.index'),
        icon: Tag,
    },
    {
        title: 'Categories',
        href: route('admin.categories.index'),
        icon: FolderTree,
    },
    {
        title: 'Products',
        href: route('admin.products.index'),
        icon: Package,
    },
    {
        title: 'Orders',
        href: route('admin.orders.index'),
        icon: ShoppingCart,
    },
];

</script>

<template>
    <Sidebar className="h-full" collapsible="icon" variant="inset">
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>
    </Sidebar>
    <slot />
</template>
