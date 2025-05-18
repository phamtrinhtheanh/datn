<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    product: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['close']);

const brands = ref([]);
const categories = ref([]);
const isLoading = ref(false);
const categoryTags = ref([]); // Chứa tags của category đã chọn

const isEdit = computed(() => !!props.product?.id);

const form = useForm({
    name: '',
    description: '',
    price: 0,
    line_price: 0,
    import_price: 0,
    stock: 0,
    image: null,
    is_featured: false,
    brand_id: '',
    category_id: '',
    specs: {}, // Thêm trường specs là một đối tượng JSON
    _method: isEdit.value ? 'PUT' : 'POST',
});

// Load brands và categories khi mở dialog
watch(
    () => props.show,
    async (newValue) => {
        if (newValue) {
            isLoading.value = true;
            try {
                const [brandsResponse, categoriesResponse] = await Promise.all([
                    axios.get(route('select.brands')),
                    axios.get(route('select.categories')),
                ]);
                brands.value = brandsResponse.data;
                categories.value = categoriesResponse.data;
            } catch (error) {
                console.error('Error loading data:', error);
            } finally {
                isLoading.value = false;
            }
        }
    },
);

watch(
    () => props.product,
    (newProduct) => {
        if (newProduct?.id) {
            form.name = newProduct.name || '';
            form.description = newProduct.description || '';
            form.price = newProduct.price || 0;
            form.line_price = newProduct.line_price;
            form.import_price = newProduct.import_price;
            form.stock = newProduct.stock || 0;
            form.is_featured = newProduct.is_featured || false;
            form.brand_id = newProduct.brand_id || '';
            form.category_id = newProduct.category_id || '';
            form.specs = newProduct.specs || {}; // Gán lại giá trị specs khi chỉnh sửa
            form.image = null;
            form._method = 'PUT';
        } else {
            form.reset();
            form.clearErrors();
            form._method = 'POST';
        }
    },
    { immediate: true },
);

watch(
    () => form.category_id,
    (newCategoryId) => {
        const selectedCategory = categories.value.find((category) => category.id === newCategoryId);
        console.log('Selected Category:', selectedCategory); // Debug here to check selected category
        if (selectedCategory) {
            // Cập nhật categoryTags và specs
            categoryTags.value = selectedCategory.tags || [];
            form.specs = selectedCategory.tags.reduce((acc: any, tag: string) => {
                acc[tag] = ''; // Initialize each tag as an empty string
                return acc;
            }, {});
        }
    },
);

const submit = () => {
    if (isEdit.value) {
        form.post(route('admin.products.update', props.product), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('close');
                form.reset();
                form.clearErrors();
            },
        });
    } else {
        form.post(route('admin.products.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                emit('close');
                form.reset();
                form.clearErrors();
            },
        });
    }
};

const formatPrice = (field: string) => {
    // Lấy giá trị của trường và nhân với 1000
    if (form[field]) {
        form[field] = form[field] * 1000;
    }
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="max-w-3xl">
            <DialogHeader>
                <DialogTitle>{{ isEdit ? 'Edit Product' : 'Add New Product' }}</DialogTitle>
            </DialogHeader>
            <div class="max-h-[70vh] overflow-y-auto pb-2 pr-4">
                <form @submit.prevent="submit" class="space-y-4 p-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="name">Product Name</Label>
                            <Input id="name" v-model="form.name" placeholder="Enter product name" />
                            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div class="flex items-end space-x-2">
                            <Checkbox id="is_feature" v-model="form.is_featured" />
                            <Label for="is_feature">Feature</Label>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-4">
                        <div class="space-y-2">
                            <Label for="price">Import Price</Label>
                            <Input
                                id="import_price"
                                type="number"
                                v-model="form.import_price"
                                :placeholder="'Enter product import price in thousands (e.g., 1000)'"
                            />
                            <p v-if="form.errors.import_price" class="text-sm text-red-500">{{ form.errors.import_price }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="line_price">Line Price</Label>
                            <Input
                                id="line_price"
                                type="number"
                                v-model="form.line_price"
                                :placeholder="'Enter product line price in thousands (e.g., 1000)'"
                            />
                            <p v-if="form.errors.line_price" class="text-sm text-red-500">{{ form.errors.line_price }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="price">Price</Label>
                            <Input id="price" type="number" v-model="form.price" :placeholder="'Enter product price in thousands (e.g., 1000)'" />
                            <p v-if="form.errors.price" class="text-sm text-red-500">{{ form.errors.price }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="stock">Stock</Label>
                            <Input id="stock" type="number" v-model="form.stock" placeholder="Enter product stock" />
                            <p v-if="form.errors.stock" class="text-sm text-red-500">{{ form.errors.stock }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="brand_id">Brand</Label>
                            <Select v-model="form.brand_id" :disabled="isLoading">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a brand" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="brand in brands" :key="brand.id" :value="brand.id">
                                        {{ brand.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.brand_id" class="text-sm text-red-500">{{ form.errors.brand_id }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="category_id">Category</Label>
                            <Select v-model="form.category_id" :disabled="isLoading">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.category_id" class="text-sm text-red-500">{{ form.errors.category_id }}</p>
                        </div>
                    </div>
                    <div v-if="categoryTags.length > 0">
                        <Label>Product Specifications</Label>
                        <div class="grid grid-cols-4 gap-2">
                            <div v-for="(tag, index) in categoryTags" :key="index">
                                <Input :id="'spec_' + tag" v-model="form.specs[tag]" :placeholder="tag" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="description">Description</Label>
                        <Textarea id="description" v-model="form.description" placeholder="Enter product description" />
                        <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="image">Product Image</Label>
                        <Input id="image" type="file" @change="form.image = $event.target.files[0]" accept="image/*" />
                        <p v-if="form.errors.images" class="text-sm text-red-500">{{ form.errors.image }}</p>
                    </div>
                </form>
            </div>
            <DialogFooter>
                <Button variant="outline" @click="$emit('close')" :disabled="form.processing">Cancel</Button>
                <Button type="submit" @click="submit" :disabled="form.processing">
                    {{ isEdit ? 'Update' : 'Create' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
