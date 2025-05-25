<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { router } from '@inertiajs/vue3';
import { Eye, Search, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useSmartPagination } from '@/composables/useSmartPagination';
import { ref, watch } from 'vue';

interface Order {
    id: number;
    order_number: string;
    customer_name: string;
    phone: string;
    email: string;
    total: number;
    status: 'pending' | 'processing' | 'confirmed' | 'completed' | 'cancelled';
    created_at: string;
    user: {
        id: number;
        name: string;
    };
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface OrdersData {
    data: Order[];
    from: number;
    to: number;
    total: number;
    links: PaginationLink[];
    first_page_url?: string;
    prev_page_url?: string;
    next_page_url?: string;
    last_page_url?: string;
}

interface Filters {
    search?: string;
    per_page?: number;
    sort_field?: string;
    sort_direction?: string;
    status?: string;
    [key: string]: string | number | undefined;
}

const props = defineProps<{
    orders: OrdersData;
    filters?: Filters;
}>();

// Refs for filter values
const searchValue = ref(props.filters?.search || '');
const selectedSortField = ref(props.filters?.sort_field || 'created_at');
const selectedSortDirection = ref(props.filters?.sort_direction || 'desc');
const selectedStatus = ref(props.filters?.status || 'all');

// Status configurations
const statusColors = {
    pending: 'bg-yellow-100 text-yellow-800 border-yellow-200',
    processing: 'bg-blue-100 text-blue-800 border-blue-200',
    confirmed: 'bg-green-100 text-green-800 border-green-200',
    completed: 'bg-green-100 text-green-800 border-green-200',
    cancelled: 'bg-red-100 text-red-800 border-red-200'
} as const;

const statusLabels = {
    pending: 'Chờ xử lý',
    processing: 'Đang xử lý',
    confirmed: 'Đã xác nhận',
    completed: 'Hoàn thành',
    cancelled: 'Đã hủy'
} as const;

// Debounced search
let searchTimeout: ReturnType<typeof setTimeout> | null = null;

// Filter update function
const updateFilters = (filters: Partial<Filters>) => {
    const params: Filters = {
        search: searchValue.value,
        sort_field: selectedSortField.value,
        sort_direction: selectedSortDirection.value,
        status: selectedStatus.value === 'all' ? '' : selectedStatus.value,
        ...filters
    };

    // Remove empty values
    Object.keys(params).forEach(key => {
        if (!params[key as keyof Filters]) {
            delete params[key as keyof Filters];
        }
    });

    router.get(
        route('admin.orders.index'),
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onBefore: () => {
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
            }
        }
    );
};

// Watch for search changes
watch(searchValue, (val) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (val !== props.filters?.search) {
            updateFilters({ search: val });
        }
    }, 500);
});

// Apply filters handler
const applyFilters = () => {
    updateFilters({
        sort_field: selectedSortField.value,
        sort_direction: selectedSortDirection.value,
        status: selectedStatus.value === 'all' ? '' : selectedStatus.value
    });
};

// Status update handler
const updateStatus = (orderId: number, status: string) => {
    router.put(route('admin.orders.status', orderId), { status }, {
        preserveScroll: true,
        onSuccess: () => {
            // Có thể thêm thông báo thành công ở đây
        }
    });
};

// Format currency
const formatVND = (price: number) =>
    new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price);

// Pagination
const paginationPages = useSmartPagination(props.orders.links);
</script>

<template>
    <Head title="Quản lý đơn hàng" />

    <AdminLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6">
                <h1 class="text-3xl font-extrabold">Quản lý đơn hàng</h1>
            </div>

            <!-- Filters Section -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <Label>Sắp xếp theo:</Label>
                        <Select v-model="selectedSortField">
                            <SelectTrigger class="w-[140px]">
                                <SelectValue placeholder="Chọn trường" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="created_at">Mới nhất</SelectItem>
                                <SelectItem value="total">Tổng tiền</SelectItem>
                                <SelectItem value="status">Trạng thái</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="flex items-center gap-2">
                        <Select v-model="selectedSortDirection">
                            <SelectTrigger class="w-[140px]">
                                <SelectValue placeholder="Thứ tự" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="asc">Tăng dần</SelectItem>
                                <SelectItem value="desc">Giảm dần</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="flex items-center gap-2">
                        <Label>Trạng thái:</Label>
                        <Select v-model="selectedStatus">
                            <SelectTrigger class="w-[140px]">
                                <SelectValue placeholder="Tất cả" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Tất cả</SelectItem>
                                <SelectItem v-for="(label, status) in statusLabels" :key="status" :value="status">
                                    <Badge :class="statusColors[status]" class="flex items-center gap-1.5 px-1.5 py-0.5 text-xs">
                                        {{ label }}
                                    </Badge>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <Button @click="applyFilters" class="ml-2">
                        Áp dụng
                    </Button>
                </div>

                <div class="relative w-full max-w-xs">
                    <Input
                        v-model="searchValue"
                        type="text"
                        placeholder="Tìm kiếm đơn hàng..."
                        class="pl-10"
                    />
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500" />
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-hidden border-y">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[15%]">
                                <div class="px-3 py-4">Mã đơn hàng</div>
                            </TableHead>
                            <TableHead class="w-[20%]">Khách hàng</TableHead>
                            <TableHead class="w-[15%]">Người đặt</TableHead>
                            <TableHead class="w-[15%]">Trạng thái</TableHead>
                            <TableHead class="w-[15%] text-right">Tổng tiền</TableHead>
                            <TableHead class="w-[10%]">Ngày đặt</TableHead>
                            <TableHead class="w-[10%] text-right">Thao tác</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="orders.data.length === 0">
                            <TableRow>
                                <TableCell colspan="7" class="text-center text-gray-500">
                                    Không tìm thấy đơn hàng nào.
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="order in orders.data" :key="order.id" class="hover:bg-muted/30">
                                <TableCell class="font-medium">
                                    <div class="px-3 py-4">{{ order.order_number }}</div>
                                </TableCell>
                                <TableCell>
                                    <div>
                                        <p class="font-medium">{{ order.customer_name }}</p>
                                        <p class="text-sm text-muted-foreground">{{ order.phone }}</p>
                                        <p class="text-sm text-muted-foreground">{{ order.email }}</p>
                                    </div>
                                </TableCell>
                                <TableCell>{{ order.user.name }}</TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="outline" class="w-[180px] justify-between">
                                                <Badge :class="statusColors[order.status]" class="flex items-center gap-1.5 px-1.5 py-0.5 text-xs">
                                                    {{ statusLabels[order.status] }}
                                                </Badge>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent class="w-[180px]">
                                            <DropdownMenuItem
                                                v-for="(label, status) in statusLabels"
                                                :key="status"
                                                @click="updateStatus(order.id, status)"
                                                :class="{'bg-muted': order.status === status}"
                                            >
                                                <Badge :class="statusColors[status]" class="flex items-center gap-1.5 px-1.5 py-0.5 text-xs">
                                                    {{ label }}
                                                </Badge>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                                <TableCell class="text-right font-semibold">
                                    {{ formatVND(order.total) }}
                                </TableCell>
                                <TableCell>{{ new Date(order.created_at).toLocaleString() }}</TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="ghost" size="icon">
                                                <Eye class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem asChild>
                                                <Link :href="route('admin.orders.show', order.id)" class="flex items-center">
                                                    <Eye class="mr-2 h-4 w-4" />
                                                    <span>Xem chi tiết</span>
                                                </Link>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="text-sm text-muted-foreground">
                    Hiển thị từ {{ orders.from }} đến {{ orders.to }} của {{ orders.total }} kết quả
                </div>
                <div class="flex items-center gap-1">
                    <Button
                        variant="ghost"
                        size="icon"
                        :disabled="!orders.prev_page_url"
                        @click="orders.prev_page_url && router.get(orders.prev_page_url)"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </Button>

                    <template v-for="(page, index) in paginationPages" :key="page.type === 'ellipsis' ? 'ellipsis-' + index : 'page-' + page.page">
                        <Button
                            v-if="page.type === 'page'"
                            variant="ghost"
                            size="sm"
                            :class="{ 'bg-primary text-primary-foreground': page.active }"
                            @click="router.get(page.url)"
                            v-html="page.label"
                        />
                        <span v-else class="px-2 text-muted-foreground">...</span>
                    </template>

                    <Button
                        variant="ghost"
                        size="icon"
                        :disabled="!orders.next_page_url"
                        @click="orders.next_page_url && router.get(orders.next_page_url)"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
