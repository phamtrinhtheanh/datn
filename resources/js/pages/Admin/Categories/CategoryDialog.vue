<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Badge } from '@/components/ui/badge';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    category: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['close']);

const isEdit = computed(() => !!props.category?.id);

const form = useForm({
    name: '',
    description: '',
    icon: null,
    is_active: true,
    order: 0,
    tags: [], // <-- thêm tags vào form
    _method: 'PUT'
});

const newTag = ref('');

// Cập nhật form data khi category thay đổi
watch(() => props.category, (newCategory) => {
    if (newCategory?.id) {
        form.name = newCategory.name || '';
        form.description = newCategory.description || '';
        form.is_active = newCategory.is_active ?? true;
        form.order = newCategory.order || 0;
        form.tags = newCategory.tags || []; // <-- nếu có sẵn tags
        form.icon = null; // Reset icon khi edit
    } else {
        form.reset();
        form.clearErrors();
    }
}, { immediate: true });

// Thêm tag mới
const addTag = () => {
    const trimmed = newTag.value.trim();
    if (trimmed && !form.tags.includes(trimmed)) {
        form.tags.push(trimmed);
    }
    newTag.value = '';
};

// Xóa tag
const removeTag = (tag) => {
    form.tags = form.tags.filter(t => t !== tag);
};

const submit = () => {
    if (isEdit.value) {
        form.post(route('admin.categories.update', props.category), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('close');
                form.reset();
                form.clearErrors();
            }
        });
    } else {
        form.post(route('admin.categories.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('close');
                form.reset();
                form.clearErrors();
            }
        });
    }
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="sm:max-w-[600px]">
            <DialogHeader>
                <DialogTitle class="text-xl">{{ isEdit ? 'Edit Category' : 'Create Category' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <div class="space-y-2">
                    <Label for="name" class="text-base">Name</Label>
                    <Input id="name" v-model="form.name" :disabled="form.processing" class="h-10" />
                    <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="description" class="text-base">Description</Label>
                    <Textarea id="description" v-model="form.description" :disabled="form.processing" class="min-h-[100px]" />
                    <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="icon" class="text-base">Icon</Label>
                    <div v-if="isEdit && props.category.icon" class="mb-2">
                        <img :src="'/storage/' + props.category.icon" class="w-20 h-20 object-cover rounded" />
                    </div>
                    <Input id="icon" type="file" @input="form.icon = $event.target.files[0]" :disabled="form.processing" class="h-10" accept="image/*" />
                    <p v-if="form.errors.icon" class="text-sm text-red-500">{{ form.errors.icon }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="order" class="text-base">Order</Label>
                    <Input id="order" type="number" v-model="form.order" :disabled="form.processing" class="h-10" />
                    <p v-if="form.errors.order" class="text-sm text-red-500">{{ form.errors.order }}</p>
                </div>

                <div class="space-y-2">
                    <Label class="text-base">Tags (Thông số kỹ thuật)</Label>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <Badge v-for="tag in form.tags" :key="tag" variant="secondary" class="flex items-center">
                            {{ tag }}
                            <button type="button" @click="removeTag(tag)" class="ml-1">
                                <X class="w-3 h-3" />
                            </button>
                        </Badge>
                    </div>
                    <Input
                        v-model="newTag"
                        @keydown.enter.prevent="addTag"
                        placeholder="Nhập tag rồi nhấn Enter"
                        :disabled="form.processing"
                        class="h-10"
                    />
                </div>

                <div class="flex items-center space-x-2">
                    <Switch id="is_active" v-model:checked="form.is_active" :disabled="form.processing" />
                    <Label for="is_active" class="text-base">Active</Label>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="emit('close')" :disabled="form.processing" class="h-10">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing" class="h-10">
                        <span v-if="form.processing">{{ isEdit ? 'Updating...' : 'Creating...' }}</span>
                        <span v-else>{{ isEdit ? 'Update Category' : 'Create Category' }}</span>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
