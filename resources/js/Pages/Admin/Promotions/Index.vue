<template>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Quản lý khuyến mãi</h1>
            <Link :href="route('admin.promotions.create')" class="btn btn-primary">
                Tạo khuyến mãi mới
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b">Mã</th>
                        <th class="px-6 py-3 border-b">Tên</th>
                        <th class="px-6 py-3 border-b">Loại</th>
                        <th class="px-6 py-3 border-b">Giá trị</th>
                        <th class="px-6 py-3 border-b">Ngày bắt đầu</th>
                        <th class="px-6 py-3 border-b">Ngày kết thúc</th>
                        <th class="px-6 py-3 border-b">Trạng thái</th>
                        <th class="px-6 py-3 border-b">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="promotion in promotions.data" :key="promotion.id">
                        <td class="px-6 py-4 border-b">{{ promotion.code }}</td>
                        <td class="px-6 py-4 border-b">{{ promotion.name }}</td>
                        <td class="px-6 py-4 border-b">
                            {{ promotion.type === 'percentage' ? 'Phần trăm' : 'Số tiền cố định' }}
                        </td>
                        <td class="px-6 py-4 border-b">
                            {{ promotion.type === 'percentage' 
                                ? `${promotion.value}%` 
                                : `${formatCurrency(promotion.value)}đ` }}
                        </td>
                        <td class="px-6 py-4 border-b">{{ formatDate(promotion.start_date) }}</td>
                        <td class="px-6 py-4 border-b">{{ formatDate(promotion.end_date) }}</td>
                        <td class="px-6 py-4 border-b">
                            <span :class="[
                                'px-2 py-1 rounded-full text-sm',
                                promotion.is_active 
                                    ? 'bg-green-100 text-green-800' 
                                    : 'bg-red-100 text-red-800'
                            ]">
                                {{ promotion.is_active ? 'Đang hoạt động' : 'Đã tắt' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 border-b">
                            <div class="flex space-x-2">
                                <Link 
                                    :href="route('admin.promotions.edit', promotion.id)"
                                    class="btn btn-outline btn-sm"
                                >
                                    Sửa
                                </Link>
                                <button 
                                    @click="deletePromotion(promotion)"
                                    class="btn btn-danger btn-sm"
                                >
                                    Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { format } from 'date-fns';

const props = defineProps({
    promotions: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return format(new Date(date), 'dd/MM/yyyy');
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN').format(value);
};

const deletePromotion = (promotion) => {
    if (confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')) {
        router.delete(route('admin.promotions.destroy', promotion.id));
    }
};
</script> 