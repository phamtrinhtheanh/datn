<script setup lang="ts">
import { onMounted } from 'vue';

const props = defineProps({
    paymentUrl: String,
});

onMounted(() => {
    if (props.paymentUrl) {
        // Tạo form để gửi dữ liệu đến VNPAY
        const form = document.createElement("form");
        form.method = "POST";
        form.action = props.paymentUrl;

        // Tạo các trường input ẩn và thêm vào form
        const params = new URLSearchParams(props.paymentUrl.split('?')[1]);  // Lấy tham số query từ URL
        params.forEach((value, key) => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = key;
            input.value = value;
            form.appendChild(input);
        });

        // Gửi form
        document.body.appendChild(form);
        form.submit();
    }
});
</script>

<template>
    <Head title="Payment Processing" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="text-center">
            <h1 class="text-2xl font-bold mb-4">Redirecting to VNPAY...</h1>
            <p v-if="paymentUrl">
                If you are not redirected automatically, <a :href="paymentUrl" class="text-blue-600">click here</a>.
            </p>
            <p v-else>
                There was an error. Please try again later.
            </p>
        </div>
    </div>
</template>
