<script setup>
import { onMounted } from 'vue'

const props = defineProps({
    paymentUrl: String,
})

onMounted(() => {
    if (props.paymentUrl) {
        window.location.href = props.paymentUrl
    }
})
</script>

<template>
    <div class="text-center py-10">
        <h1 class="text-xl font-semibold">Đang chuyển đến cổng thanh toán...</h1>
        <p>Vui lòng chờ trong giây lát.</p>
    </div>
</template>
