<template>
    <AdminLayout title="Banner Management">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Banner Management
                </h2>
                <Button @click="openDialog()">Add New Banner</Button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Top Banners -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Top Banners</h3>
                            <Button variant="outline" size="sm" @click="openDialog({ position: 'top' })">
                                <Plus class="w-4 h-4 mr-1" />
                                Add Top Banner
                            </Button>
                        </div>

                        <div v-if="topBanners.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                            <ImageOff class="w-12 h-12 mx-auto text-gray-400" />
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No top banners</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new top banner.</p>
                            <div class="mt-6">
                                <Button @click="openDialog({ position: 'top' })">
                                    <Plus class="w-4 h-4 mr-1" />
                                    New Top Banner
                                </Button>
                            </div>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="banner in topBanners" :key="banner.id"
                                class="relative group bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                <img :src="'/storage/' + banner.image" :alt="banner.name"
                                    class="w-full aspect-[21/9] object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100
                                    transition-opacity flex items-center justify-center space-x-2">
                                    <Button variant="secondary" size="sm" @click="openDialog(banner)">
                                        <Pencil class="w-4 h-4 mr-1" />
                                        Edit
                                    </Button>
                                    <Button variant="destructive" size="sm" @click="deleteBanner(banner)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                        Delete
                                    </Button>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ banner.name }}</h4>
                                            <p class="text-sm text-gray-500">Order: {{ banner.order }}</p>
                                            <p v-if="banner.url" class="text-sm text-blue-500 truncate mt-1">
                                                {{ banner.url }}
                                            </p>
                                        </div>
                                        <Badge :variant="banner.is_active ? 'success' : 'secondary'">
                                            {{ banner.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Banners -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Bottom Banners</h3>
                            <Button variant="outline" size="sm" @click="openDialog({ position: 'bottom' })">
                                <Plus class="w-4 h-4 mr-1" />
                                Add Bottom Banner
                            </Button>
                        </div>

                        <div v-if="bottomBanners.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                            <ImageOff class="w-12 h-12 mx-auto text-gray-400" />
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No bottom banners</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new bottom banner.</p>
                            <div class="mt-6">
                                <Button @click="openDialog({ position: 'bottom' })">
                                    <Plus class="w-4 h-4 mr-1" />
                                    New Bottom Banner
                                </Button>
                            </div>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div v-for="banner in bottomBanners" :key="banner.id"
                                class="relative group bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                <img :src="'/storage/' + banner.image" :alt="banner.name"
                                    class="w-full aspect-[16/9] object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100
                                    transition-opacity flex items-center justify-center space-x-2">
                                    <Button variant="secondary" size="sm" @click="openDialog(banner)">
                                        <Pencil class="w-4 h-4 mr-1" />
                                        Edit
                                    </Button>
                                    <Button variant="destructive" size="sm" @click="deleteBanner(banner)">
                                        <Trash2 class="w-4 h-4 mr-1" />
                                        Delete
                                    </Button>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ banner.name }}</h4>
                                            <p class="text-sm text-gray-500">Order: {{ banner.order }}</p>
                                            <p v-if="banner.url" class="text-sm text-blue-500 truncate mt-1">
                                                {{ banner.url }}
                                            </p>
                                        </div>
                                        <Badge :variant="banner.is_active ? 'success' : 'secondary'">
                                            {{ banner.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Dialog -->
        <Dialog v-model:open="showDialog">
            <DialogContent class="sm:max-w-[500px]">
                <DialogHeader>
                    <DialogTitle>{{ dialogTitle }}</DialogTitle>
                </DialogHeader>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <Label for="name">Banner Name</Label>
                        <Input id="name" v-model="form.name" placeholder="Enter banner name" required />
                    </div>

                    <div>
                        <Label for="image">Banner Image</Label>
                        <div class="mt-1 flex items-center">
                            <Input id="image" type="file" @input="handleImageInput"
                                :required="!editingBanner" accept="image/*"
                                class="flex-1" />
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Recommended size: {{ form.position === 'top' ? '2100x900px (21:9)' : '1600x900px (16:9)' }}
                        </p>
                    </div>

                    <div>
                        <Label for="url">URL (optional)</Label>
                        <Input id="url" v-model="form.url" placeholder="https://example.com" />
                    </div>

                    <div>
                        <Label for="position">Position</Label>
                        <Select v-model="form.position">
                            <SelectTrigger>
                                <SelectValue placeholder="Select banner position" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="top">Top Banner</SelectItem>
                                <SelectItem value="bottom">Bottom Banner</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div>
                        <Label for="order">Display Order</Label>
                        <Input id="order" type="number" v-model="form.order" required min="0" />
                        <p class="text-sm text-gray-500 mt-1">Lower numbers will be displayed first</p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox id="is_active" v-model="form.is_active" />
                        <Label for="is_active">Active</Label>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <Button type="button" variant="secondary" @click="showDialog = false">
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="processing">
                            <Loader2 v-if="processing" class="w-4 h-4 mr-1 animate-spin" />
                            {{ editingBanner ? 'Update Banner' : 'Create Banner' }}
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Checkbox } from '@/components/ui/checkbox'
import { Badge } from '@/components/ui/badge'
import { Pencil, Trash2, Loader2, Plus, ImageOff } from 'lucide-vue-next'

const props = defineProps({
    banners: {
        type: Array,
        required: true,
        default: () => []
    }
})

const showDialog = ref(false)
const editingBanner = ref(null)
const processing = ref(false)

const form = ref({
    name: '',
    image: null,
    url: '',
    position: 'top',
    order: 0,
    is_active: true
})

const dialogTitle = computed(() => editingBanner.value ? 'Edit Banner' : 'Add New Banner')

const topBanners = computed(() =>
    props.banners.filter(banner => banner.position === 'top')
        .sort((a, b) => a.order - b.order)
)

const bottomBanners = computed(() =>
    props.banners.filter(banner => banner.position === 'bottom')
        .sort((a, b) => a.order - b.order)
)

function openDialog(banner = null) {
    if (banner && !banner.id) {
        // If banner is passed as an object with position only
        form.value = {
            name: '',
            image: null,
            url: '',
            position: banner.position,
            order: 0,
            is_active: true
        }
        editingBanner.value = null
    } else if (banner) {
        // If editing existing banner
        form.value = { ...banner }
        form.value.image = null
        editingBanner.value = banner
    } else {
        // Default new banner
        form.value = {
            name: '',
            image: null,
            url: '',
            position: 'top',
            order: 0,
            is_active: true
        }
        editingBanner.value = null
    }
    showDialog.value = true
}

function handleImageInput(e) {
    form.value.image = e.target.files[0]
}

async function submitForm() {
    processing.value = true

    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('url', form.value.url || '')
    formData.append('position', form.value.position)
    formData.append('order', form.value.order)
    formData.append('is_active', form.value.is_active ? 1 : 0)

    if (form.value.image) {
        formData.append('image', form.value.image)
    }

    try {
        if (editingBanner.value) {
            formData.append('_method', 'PUT')
            await router.post(`/admin/banners/${editingBanner.value.id}`, formData)
        } else {
            await router.post('/admin/banners', formData)
        }
        showDialog.value = false
    } catch (error) {
        console.error('Error submitting form:', error)
    } finally {
        processing.value = false
    }
}

async function deleteBanner(banner) {
    if (confirm('Are you sure you want to delete this banner?')) {
        try {
            await router.delete(`/admin/banners/${banner.id}`)
        } catch (error) {
            console.error('Error deleting banner:', error)
        }
    }
}
</script>
