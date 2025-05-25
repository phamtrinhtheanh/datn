<template>
    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">
                    {{ promotion ? 'Chỉnh sửa khuyến mãi' : 'Tạo khuyến mãi mới' }}
                </h1>

                <Card>
                    <CardHeader>
                        <CardTitle>Thông tin khuyến mãi</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="code">Mã khuyến mãi</Label>
                                    <Input
                                        id="code"
                                        v-model="form.code"
                                        :class="{ 'border-red-500': form.errors.code }"
                                    />
                                    <div v-if="form.errors.code" class="text-sm text-red-600">
                                        {{ form.errors.code }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="name">Tên khuyến mãi</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        :class="{ 'border-red-500': form.errors.name }"
                                    />
                                    <div v-if="form.errors.name" class="text-sm text-red-600">
                                        {{ form.errors.name }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="type">Loại khuyến mãi</Label>
                                    <Select
                                        id="type"
                                        v-model="form.type"
                                        :class="{ 'border-red-500': form.errors.type }"
                                    >
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="percentage">Phần trăm</SelectItem>
                                            <SelectItem value="fixed_amount">Số tiền cố định</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <div v-if="form.errors.type" class="text-sm text-red-600">
                                        {{ form.errors.type }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="value">Giá trị</Label>
                                    <Input
                                        id="value"
                                        v-model="form.value"
                                        type="number"
                                        step="0.01"
                                        :class="{ 'border-red-500': form.errors.value }"
                                    />
                                    <div v-if="form.errors.value" class="text-sm text-red-600">
                                        {{ form.errors.value }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="min_order_amount">Giá trị đơn hàng tối thiểu</Label>
                                    <Input
                                        id="min_order_amount"
                                        v-model="form.min_order_amount"
                                        type="number"
                                        step="0.01"
                                        :class="{ 'border-red-500': form.errors.min_order_amount }"
                                    />
                                    <div v-if="form.errors.min_order_amount" class="text-sm text-red-600">
                                        {{ form.errors.min_order_amount }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="max_uses">Số lần sử dụng tối đa</Label>
                                    <Input
                                        id="max_uses"
                                        v-model="form.max_uses"
                                        type="number"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="start_date">Ngày bắt đầu</Label>
                                    <Input
                                        id="start_date"
                                        v-model="form.start_date"
                                        type="date"
                                        :class="{ 'border-red-500': form.errors.start_date }"
                                    />
                                    <div v-if="form.errors.start_date" class="text-sm text-red-600">
                                        {{ form.errors.start_date }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="end_date">Ngày kết thúc</Label>
                                    <Input
                                        id="end_date"
                                        v-model="form.end_date"
                                        type="date"
                                        :class="{ 'border-red-500': form.errors.end_date }"
                                    />
                                    <div v-if="form.errors.end_date" class="text-sm text-red-600">
                                        {{ form.errors.end_date }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox
                                    id="is_active"
                                    v-model="form.is_active"
                                />
                                <Label for="is_active">Đang hoạt động</Label>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <Link :href="route('admin.promotions.index')">
                                    <Button variant="outline">Hủy</Button>
                                </Link>
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    {{ promotion ? 'Cập nhật' : 'Tạo mới' }}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { PropType } from 'vue';

interface Promotion {
    id?: number;
    code: string;
    name: string;
    type: 'percentage' | 'fixed_amount';
    value: number;
    min_order_amount: number;
    max_uses?: number;
    start_date: string;
    end_date: string;
    is_active: boolean;
}

const props = defineProps({
    promotion: {
        type: Object as PropType<Promotion>,
        default: null
    }
});

const form = useForm({
    code: props.promotion?.code || '',
    name: props.promotion?.name || '',
    type: props.promotion?.type || 'percentage',
    value: props.promotion?.value || 0,
    min_order_amount: props.promotion?.min_order_amount || 0,
    max_uses: props.promotion?.max_uses || undefined,
    start_date: props.promotion?.start_date 
        ? new Date(props.promotion.start_date).toISOString().slice(0, 10)
        : '',
    end_date: props.promotion?.end_date
        ? new Date(props.promotion.end_date).toISOString().slice(0, 10)
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Khuyến mãi',
        href: route('admin.promotions.index'),
    },
    {
        title: props.promotion ? 'Chỉnh sửa khuyến mãi' : 'Tạo khuyến mãi mới',
        href: '#',
    },
];
</script> 