<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { X } from 'lucide-vue-next';

interface Props {
    promotion: {
        code: string;
        name: string;
        type: 'percentage' | 'fixed_amount';
        value: number;
    };
    discount: number;
    onRemove: () => void;
}

defineProps<Props>();
</script>

<template>
    <div class="flex items-center justify-between p-4 bg-muted rounded-lg">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <Badge variant="secondary">{{ promotion.code }}</Badge>
                <span class="font-medium">{{ promotion.name }}</span>
            </div>
            <p class="text-sm text-muted-foreground">
                Giảm {{ promotion.type === 'percentage' ? `${promotion.value}%` : `${promotion.value.toLocaleString()}đ` }}
                ({{ discount.toLocaleString() }}đ)
            </p>
        </div>
        <Button
            variant="ghost"
            size="icon"
            @click="onRemove"
        >
            <X class="h-4 w-4" />
        </Button>
    </div>
</template> 