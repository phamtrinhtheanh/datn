<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from 'vue-sonner';

interface Props {
    total: number;
    promotions: {
        value: string;
        label: string;
        data: {
            id: number;
            code: string;
            name: string;
            type: 'percentage' | 'fixed_amount';
            value: number;
            min_order_amount: number;
        };
    }[];
}

const props = defineProps<Props>();
const emit = defineEmits(['promotion-applied']);

const selectedCode = ref('');

const applyPromotion = () => {
    if (!selectedCode.value) {
        toast.error('Vui lòng chọn mã khuyến mãi');
        return;
    }

    const promotion = props.promotions.find(p => p.value === selectedCode.value)?.data;
    if (!promotion) {
        toast.error('Mã khuyến mãi không hợp lệ');
        return;
    }

    const discount = promotion.type === 'percentage'
        ? (props.total * promotion.value / 100)
        : promotion.value;

    emit('promotion-applied', {
        promotion,
        discount,
        final_total: props.total - discount
    });
    toast.success('Áp dụng mã khuyến mãi thành công');
};
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-end gap-2">
            <div class="flex-1">
                <Label for="promotion-code">Mã khuyến mãi</Label>
                <Select v-model="selectedCode">
                    <SelectTrigger id="promotion-code" class="w-full">
                        <SelectValue placeholder="Chọn mã khuyến mãi" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="promo in promotions" :key="promo.data.id" :value="promo.value">
                            {{ promo.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <Button
                @click="applyPromotion"
                :disabled="!selectedCode"
            >
                Áp dụng
            </Button>
        </div>
    </div>
</template> 