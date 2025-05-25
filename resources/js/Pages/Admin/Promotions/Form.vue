<template>
    <div class="container mx-auto py-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">
                {{ promotion ? 'Chỉnh sửa khuyến mãi' : 'Tạo khuyến mãi mới' }}
            </h1>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mã khuyến mãi</label>
                    <input
                        v-model="form.code"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.code }"
                    />
                    <div v-if="form.errors.code" class="mt-1 text-sm text-red-600">
                        {{ form.errors.code }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên khuyến mãi</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.name }"
                    />
                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Loại khuyến mãi</label>
                    <select
                        v-model="form.type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.type }"
                    >
                        <option value="percentage">Phần trăm</option>
                        <option value="fixed_amount">Số tiền cố định</option>
                    </select>
                    <div v-if="form.errors.type" class="mt-1 text-sm text-red-600">
                        {{ form.errors.type }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Giá trị</label>
                    <input
                        v-model="form.value"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.value }"
                    />
                    <div v-if="form.errors.value" class="mt-1 text-sm text-red-600">
                        {{ form.errors.value }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Giá trị đơn hàng tối thiểu</label>
                    <input
                        v-model="form.min_order_amount"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.min_order_amount }"
                    />
                    <div v-if="form.errors.min_order_amount" class="mt-1 text-sm text-red-600">
                        {{ form.errors.min_order_amount }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Số lần sử dụng tối đa</label>
                    <input
                        v-model="form.max_uses"
                        type="number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Ngày bắt đầu</label>
                    <input
                        v-model="form.start_date"
                        type="datetime-local"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.start_date }"
                    />
                    <div v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">
                        {{ form.errors.start_date }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Ngày kết thúc</label>
                    <input
                        v-model="form.end_date"
                        type="datetime-local"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{ 'border-red-500': form.errors.end_date }"
                    />
                    <div v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">
                        {{ form.errors.end_date }}
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <span class="ml-2 text-sm text-gray-600">Đang hoạt động</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <Link
                        :href="route('admin.promotions.index')"
                        class="btn btn-outline"
                    >
                        Hủy
                    </Link>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="form.processing"
                    >
                        {{ promotion ? 'Cập nhật' : 'Tạo mới' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    promotion: {
        type: Object,
        default: null
    }
});

const form = useForm({
    code: props.promotion?.code || '',
    name: props.promotion?.name || '',
    description: props.promotion?.description || '',
    type: props.promotion?.type || 'percentage',
    value: props.promotion?.value || 0,
    min_order_amount: props.promotion?.min_order_amount || 0,
    max_uses: props.promotion?.max_uses || null,
    start_date: props.promotion?.start_date 
        ? new Date(props.promotion.start_date).toISOString().slice(0, 16)
        : '',
    end_date: props.promotion?.end_date
        ? new Date(props.promotion.end_date).toISOString().slice(0, 16)
        : '',
    is_active: props.promotion?.is_active ?? true
});

const submit = () => {
    if (props.promotion) {
        form.put(route('admin.promotions.update', props.promotion.id));
    } else {
        form.post(route('admin.promotions.store'));
    }
};
</script> 