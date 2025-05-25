<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import { Sidebar, SidebarContent } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import {
    BookOpen,
    Folder,
    FolderTree,
    LayoutDashboard,
    Package,
    ShoppingCart,
    Tag,
    Percent,
    HomeIcon
} from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Thống kê',
        href: route('dashboard'),
        icon: LayoutDashboard,
    },
    {
        title: 'Danh mục hàng',
        href: route('admin.categories.index'),
        icon: FolderTree,
    },
    {
        title: 'Sản phẩm',
        href: route('admin.products.index'),
        icon: Package,
    },
    {
        title: 'Đơn hàng',
        href: route('admin.orders.index'),
        icon: ShoppingCart,
    },
    {
        title: 'Khuyến mãi',
        href: route('admin.promotions.index'),
        icon: Percent,
    },
    {
        title: 'Trang chủ',
        href: route('home'),
        icon: HomeIcon,
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
