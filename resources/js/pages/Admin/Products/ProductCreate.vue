<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useDropzone } from 'vue3-dropzone';
import axios from 'axios';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const page = usePage();
const brands = computed(() => (page.props.brands as any)?.data || []);
const categories = computed(() => (page.props.categories as any)?.data || []);
const selectedImages = ref<{ id: string; url: string; isMain: boolean }[]>([]);

const form = useForm({
    name: 'Mainboard MSI PRO Z790-A DDR4',
    description: `<p><strong>MSI PRO Z790-A DDR4</strong> là bo mạch chủ lý tưởng cho các hệ thống Intel thế hệ 12/13, hỗ trợ RAM DDR4, nhiều khe M.2 tốc độ cao và mạng LAN 2.5G.</p>\
<ul>\
  <li>Thiết kế tản nhiệt tối ưu, bền bỉ</li>\
  <li>Hỗ trợ ép xung CPU và RAM</li>\
  <li>Đầy đủ cổng kết nối hiện đại</li>\
  <li>Phù hợp cho cả game thủ và dân văn phòng</li>\
</ul>\
<p><em>Bảo hành chính hãng 36 tháng.</em></p>`,
    price: 5200000,
    line_price: 5500000,
    import_price: 4000000,
    stock: 10,
    images: [] as string[],
    is_featured: false,
    is_active: true,
    brand_id: '',
    category_id: '',
    specs: '{\n  "Socket": "LGA 1700",\n  "Chipset": "Intel Z790",\n  "Kích thước": "ATX",\n  "RAM hỗ trợ": "4 x DDR4, tối đa 128GB",\n  "Khe mở rộng": "3 x PCIe x16, 1 x PCIe x1",\n  "Lưu trữ": "4 x SATA 6Gb/s, 4 x M.2",\n  "LAN": "Intel 2.5G LAN",\n  "USB": "2 x USB 3.2 Gen2 Type-A, 1 x USB 3.2 Gen2x2 Type-C, 4 x USB 2.0",\n  "Audio": "Realtek ALC897 7.1-Channel High Definition Audio"\n}',
    _method: 'POST',
});

async function uploadFile(file: File): Promise<string> {
    const formData = new FormData();
    formData.append('file', file);

    try {
        const response = await axios.post(route('admin.upload'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data.url;
    } catch (error) {
        console.error('Upload failed:', error);
        throw error;
    }
}

async function onDrop(acceptFiles: File[], rejectReasons: any[]) {
    // Kiểm tra số lượng file
    if (form.images.length + acceptFiles.length > 4) {
        alert('Chỉ được upload tối đa 4 ảnh');
        return;
    }

    // Kiểm tra kích thước và định dạng file
    const validFiles = acceptFiles.filter((file) => {
        if (file.size > 5 * 1024 * 1024) {
            alert(`File ${file.name} vượt quá kích thước cho phép (5MB)`);
            return false;
        }
        if (!file.type.startsWith('image/')) {
            alert(`File ${file.name} không phải là file ảnh`);
            return false;
        }
        return true;
    });

    // Upload files and get URLs
    for (const file of validFiles) {
        try {
            const url = await uploadFile(file);
            form.images.push(url);
            const newImage = {
                id: Math.random().toString(36).substr(2, 9),
                url: url,
                isMain: selectedImages.value.length === 0,
            };
            selectedImages.value.push(newImage);
        } catch (error) {
            alert(`Không thể upload file ${file.name}`);
        }
    }

    if (rejectReasons.length > 0) {
        console.log('Rejected files:', rejectReasons);
    }
}

const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop,
    accept: ['image/*'],
    maxFiles: 4,
    maxSize: 5 * 1024 * 1024, // 5MB
    noClick: true, // Disable default click behavior
});

const handleClick = () => {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.multiple = true;
    input.onchange = async (e) => {
        const files = (e.target as HTMLInputElement).files;
        if (files) {
            await onDrop(Array.from(files), []);
        }
    };
    input.click();
};

const removeImage = (index: number) => {
    form.images.splice(index, 1);
    selectedImages.value.splice(index, 1);
    if (selectedImages.value.length > 0 && !selectedImages.value.some((img) => img.isMain)) {
        selectedImages.value[0].isMain = true;
    }
};

const setMainImage = (index: number) => {
    selectedImages.value.forEach((img, i) => {
        img.isMain = i === index;
    });
};

const submit = () => {
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('description', form.description);
    formData.append('price', form.price.toString());
    formData.append('line_price', form.line_price.toString());
    formData.append('import_price', form.import_price.toString());
    formData.append('stock', form.stock.toString());
    formData.append('is_featured', form.is_featured ? '1' : '0');
    formData.append('is_active', form.is_active ? '1' : '0');
    formData.append('brand_id', form.brand_id);
    formData.append('category_id', form.category_id);
    formData.append('specs', form.specs);
    formData.append('_method', 'POST');
    form.images.forEach((url, index) => {
        formData.append(`images[${index}]`, url);
        if (selectedImages.value[index].isMain) {
            formData.append('main_image_index', index.toString());
        }
    });
    router.post(route('admin.products.store'), formData, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            router.visit(route('admin.products.index'));
        },
        onError: (errors) => {
            console.error('Upload failed:', errors);
        },
    });
};

const editorOptions = {
    theme: 'snow',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ header: 1 }, { header: 2 }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ script: 'sub' }, { script: 'super' }],
            [{ indent: '-1' }, { indent: '+1' }],
            [{ direction: 'rtl' }],
            [{ size: ['small', false, 'large', 'huge'] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ color: [] }, { background: [] }],
            [{ font: [] }],
            [{ align: [] }],
            ['clean'],
        ],
    },
};
</script>

<template>
    <Head title="Create Product" />

    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-start justify-between">
                <h1 class="mb-4 text-3xl font-extrabold">Thêm sản phẩm mới</h1>
                <Button variant="outline" @click="router.visit(route('admin.products.index'))">Quay lại</Button>
            </div>

            <div class="bg-card container rounded-lg border p-6 shadow-sm" id="form-tb">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="gap-4 space-y-4">
                            <div class="col-span-4 space-y-2">
                                <Label for="name">Tên sản phẩm</Label>
                                <Input id="name" v-model="form.name" placeholder="Nhập tên sản phẩm" />
                                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label for="brand_id" class="mb-2">Thương hiệu</Label>
                                    <Select v-model="form.brand_id" class="w-0">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Chọn thương hiệu" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="brand in brands" :key="brand.id" :value="brand.id">
                                                {{ brand.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <p v-if="form.errors.brand_id" class="text-sm text-red-500">{{ form.errors.brand_id }}</p>
                                </div>

                                <div>
                                    <Label for="category_id" class="mb-2">Danh mục</Label>
                                    <Select v-model="form.category_id" class="w-0">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Chọn danh mục" />
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
                            <div class="space-y-2">
                                <Label>Thông số kỹ thuật</Label>
                                <div class="space-y-2">
                                    <textarea
                                        v-model="form.specs"
                                        class="w-full rounded-md border border-gray-300 p-2 font-mono text-sm"
                                        rows="10"
                                        placeholder='{
  "key1": "value1",
  "key2": "value2"
}'
                                    ></textarea>
                                    <p class="text-sm text-gray-500">Nhập thông số kỹ thuật theo định dạng JSON</p>
                                    <p v-if="form.errors.specs" class="text-sm text-red-500">{{ form.errors.specs }}</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-end space-x-2">
                                <Checkbox id="is_feature" v-model="form.is_featured" />
                                <Label for="is_feature">Sản phẩm nổi bật</Label>
                            </div>
                            <div class="col-span-2 flex items-end space-x-2">
                                <Checkbox id="is_feature" v-model="form.is_active" />
                                <Label for="is_feature">Hoạt động</Label>
                            </div>
                            <div class="grid grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <Label for="import_price">Giá nhập</Label>
                                    <Input id="import_price" type="number" v-model="form.import_price" placeholder="Nhập giá nhập (nghìn đồng)" />
                                    <p v-if="form.errors.import_price" class="text-sm text-red-500">{{ form.errors.import_price }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="line_price">Giá niêm yết</Label>
                                    <Input id="line_price" type="number" v-model="form.line_price" placeholder="Nhập giá niêm yết (nghìn đồng)" />
                                    <p v-if="form.errors.line_price" class="text-sm text-red-500">{{ form.errors.line_price }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="price">Giá bán</Label>
                                    <Input id="price" type="number" v-model="form.price" placeholder="Nhập giá bán (nghìn đồng)" />
                                    <p v-if="form.errors.price" class="text-sm text-red-500">{{ form.errors.price }}</p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="stock">Số lượng</Label>
                                    <Input id="stock" type="number" v-model="form.stock" placeholder="Nhập số lượng" />
                                    <p v-if="form.errors.stock" class="text-sm text-red-500">{{ form.errors.stock }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label for="images">Hình ảnh sản phẩm</Label>
                            <div class="space-y-4">
                                <div class="grid grid-cols-4 gap-4">
                                    <!-- Main large image preview -->
                                    <div class="group relative col-span-3 aspect-square">
                                        <div v-if="selectedImages.length > 0" class="relative h-full w-full">
                                            <img
                                                :src="selectedImages.find((img) => img.isMain)?.url"
                                                alt="Main preview"
                                                class="h-full w-full rounded-lg border object-cover"
                                            />
                                            <button
                                                type="button"
                                                class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white opacity-0 transition-opacity group-hover:opacity-100"
                                                @click="removeImage(selectedImages.findIndex((img) => img.isMain))"
                                            >
                                                ×
                                            </button>
                                        </div>
                                        <div
                                            v-else
                                            v-bind="getRootProps()"
                                            @click="handleClick"
                                            class="flex h-full w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed bg-gray-50 transition-colors hover:bg-gray-100"
                                        >
                                            <input v-bind="getInputProps()" />
                                            <svg class="mb-2 h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                />
                                            </svg>
                                            <p v-if="isDragActive" class="text-sm text-gray-500">Thả ảnh vào đây...</p>
                                            <p v-else class="text-sm text-gray-500">Kéo thả ảnh vào đây hoặc click để chọn</p>
                                        </div>
                                    </div>

                                    <!-- Three smaller previews -->
                                    <div class="space-y-4">
                                        <div
                                            v-for="(image, index) in selectedImages.filter((img) => !img.isMain).slice(0, 3)"
                                            :key="image.id"
                                            class="group relative aspect-square cursor-pointer"
                                            @click="setMainImage(selectedImages.findIndex((img) => img.id === image.id))"
                                        >
                                            <img :src="image.url" alt="Preview" class="h-full w-full rounded-lg border object-cover" />
                                            <button
                                                type="button"
                                                class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white opacity-0 transition-opacity group-hover:opacity-100"
                                                @click.stop="removeImage(selectedImages.findIndex((img) => img.id === image.id))"
                                            >
                                                ×
                                            </button>
                                        </div>
                                        <div
                                            v-for="i in 3 - selectedImages.filter((img) => !img.isMain).length"
                                            :key="i"
                                            v-bind="getRootProps()"
                                            @click="handleClick"
                                            class="flex aspect-square cursor-pointer items-center justify-center rounded-lg border-2 border-dashed bg-gray-50 transition-colors hover:bg-gray-100"
                                        >
                                            <input v-bind="getInputProps()" />
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <p v-if="form.errors.images" class="text-sm text-red-500">{{ form.errors.images }}</p>
                            </div>
                        </div>
                        <div class="col-span-full w-full space-y-2">
                            <Label for="description">Mô tả</Label>
                            <QuillEditor v-model:content="form.description" :options="editorOptions" contentType="html" class="quill-full-width" />
                            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4">
                        <Button variant="outline" type="button" @click="router.visit(route('admin.products.index'))">Hủy</Button>
                        <Button type="submit" :disabled="form.processing">Lưu</Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.vue-dropzone {
    border: 2px dashed #e2e8f0;
    border-radius: 0.5rem;
    background-color: #f8fafc;
    transition: all 0.2s;
}

.vue-dropzone:hover {
    background-color: #f1f5f9;
}

.vue-dropzone .dz-message {
    margin: 0;
    font-size: 0.875rem;
    color: #64748b;
}

.vue-dropzone .dz-preview {
    margin: 0;
}

.vue-dropzone .dz-preview .dz-image {
    border-radius: 0.375rem;
}

.vue-dropzone .dz-preview .dz-remove {
    font-size: 1.25rem;
    color: #ef4444;
    margin-top: 0.5rem;
}

.vue-dropzone .dz-preview .dz-remove:hover {
    text-decoration: none;
    color: #dc2626;
}

.quill-full-width {
    width: 100% !important;
    min-width: 0;
    height: 24rem;
}

.quill-full-width .ql-container {
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    background: #fff;
    min-height: 18rem;
}

.quill-full-width .ql-toolbar {
    border-radius: 0.5rem 0.5rem 0 0;
    border: 1px solid #e5e7eb;
    background: #f8fafc;
}
</style>
