<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import BrandDialog from './BrandDialog.vue';
import { ref } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { MoreVertical } from 'lucide-vue-next';
// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    brands: Object,
});

const showDialog = ref(false);
const selectedBrand = ref(null);

const openCreateDialog = () => {
    selectedBrand.value = null;
    showDialog.value = true;
};

const openEditDialog = (brand) => {
    selectedBrand.value = brand;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedBrand.value = null;
};

const deleteBrand = (brand) => {
    if (confirm('Are you sure you want to delete this brand?')) {
        router.delete(route('admin.brands.destroy', brand));
    }
};

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString();
};

</script>

<template>
    <Head title="Brands" />

    <AdminLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Brands</h1>
                <Button @click="openCreateDialog">Add New Brand</Button>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow">


                <Table>
                    <TableHeader>
                        <TableRow class="bg-gray-50">
                            <TableHead class="font-medium"></TableHead>
                            <TableHead class="font-medium">Name</TableHead>
                            <TableHead class="font-medium">Created At</TableHead>
                            <TableHead class="text-right font-medium">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="brands.data.length === 0">
                            <TableRow>
                                <TableCell colspan="6" class="py-4 text-center text-gray-500"> No brands found </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="brand in brands.data" :key="brand.id" class="hover:bg-gray-50">
                                <TableCell class="pl-4">
                                    <Checkbox class="w-4 h-4"></Checkbox>
                                </TableCell>
                                <TableCell>{{ brand.name }}</TableCell>
                                <TableCell>{{ formatDate(brand.created_at) }}</TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <MoreVertical class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem @click="openEditDialog(brand)">Edit</DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteBrand(brand)" class="text-red-500"> Delete </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>

                <div class="flex items-center justify-between border-t p-4">
                    <div class="text-sm text-gray-500">Showing {{ brands.from }} to {{ brands.to }} of {{ brands.total }} entries</div>
                    <div class="flex space-x-2">
                        <Button variant="outline" size="sm" :disabled="!brands.prev_page_url" @click="router.visit(brands.prev_page_url)">
                            Previous
                        </Button>
                        <Button variant="outline" size="sm" :disabled="!brands.next_page_url" @click="router.visit(brands.next_page_url)">
                            Next
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <BrandDialog :show="showDialog" :brand="selectedBrand" @close="closeDialog" />
    </AdminLayout>
</template>
