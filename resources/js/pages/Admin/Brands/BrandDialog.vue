<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner'
const props = defineProps({
    show: Boolean,
    brand: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['close']);

const isEdit = computed(() => !!props.brand?.id);

const form = useForm({
    name: '',
});

watch(
    () => props.brand,
    (newBrand) => {
        if (newBrand?.id) {
            form.name = newBrand.name || '';
        } else {
            form.reset();
            form.clearErrors();
        }
    },
    { immediate: true },
);

const submit = () => {
    if (isEdit.value) {
        form.put(route('admin.brands.update', props.brand), {
            preserveScroll: true,
            onSuccess: () => {
                // emit('close');
                form.reset();
                form.clearErrors();
                toast.success(isEdit.value ? 'Thương hiệu đã được cập nhật thành công' : 'Thương hiệu đã được tạo thành công');
            },
            onError: () => {
                toast.error('Có lỗi xảy ra khi lưu thương hiệu');
            },
        });
    } else {
        form.post(route('admin.brands.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('close');
                form.reset();
                form.clearErrors();
                toast.success(isEdit.value ? 'Thương hiệu đã được cập nhật thành công' : 'Thương hiệu đã được tạo thành công');
            },
            onError: () => {
                toast.error('Có lỗi xảy ra khi lưu thương hiệu');
            },
        });
    }
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="sm:max-w-[600px]">
            <DialogHeader>
                <DialogTitle class="text-xl">{{ isEdit ? 'Cập nhật thương hiệu' : 'Tạo mới thương hiệu' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <div class="space-y-2">
                    <Label for="name" class="text-base">Tên thương hiệu</Label>
                    <Input id="name" v-model="form.name" :disabled="form.processing" class="h-10" autocomplete="off" />
                    <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="emit('close')" :disabled="form.processing" class="h-10">
                        <span>Hủy bỏ</span>
                    </Button>
                    <Button type="submit" :disabled="form.processing" class="h-10">
                        <span v-if="form.processing">{{ isEdit ? 'Đang cập nhật...' : 'Đang tạo...' }}</span>
                        <span v-else>{{ isEdit ? 'Cập nhật' : 'Tạo mới' }}</span>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
