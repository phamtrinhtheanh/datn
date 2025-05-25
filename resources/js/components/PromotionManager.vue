<script setup lang="ts">
import { ref } from 'vue';
import PromotionCodeInput from './PromotionCodeInput.vue';
import PromotionApplied from './PromotionApplied.vue';

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
const emit = defineEmits(['update:discount']);

const appliedPromotion = ref<{
    promotion: {
        code: string;
        name: string;
        type: 'percentage' | 'fixed_amount';
        value: number;
    };
    discount: number;
} | null>(null);

const handlePromotionApplied = (data: any) => {
    appliedPromotion.value = {
        promotion: data.promotion,
        discount: data.discount,
    };
    emit('update:discount', data.discount);
};

const handleRemovePromotion = () => {
    appliedPromotion.value = null;
    emit('update:discount', 0);
};
</script>

<template>
    <div class="space-y-4">
        <h3 class="text-lg font-medium">Mã khuyến mãi</h3>
        
        <PromotionCodeInput
            v-if="!appliedPromotion"
            :total="total"
            :promotions="promotions"
            @promotion-applied="handlePromotionApplied"
        />

        <PromotionApplied
            v-else
            :promotion="appliedPromotion.promotion"
            :discount="appliedPromotion.discount"
            :on-remove="handleRemovePromotion"
        />
    </div>
</template> 