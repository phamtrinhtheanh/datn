<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <footer class="bg-gray-900 text-white pt-10 pb-6">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-bold mb-4">Về THEANHCOMPUTER</h3>
          <ul class="space-y-2">
            <li>
              <Link href="/gioi-thieu" class="hover:text-primary-foreground">Giới thiệu</Link>
            </li>
            <li>
              <Link href="/tuyen-dung" class="hover:text-primary-foreground">Tuyển dụng</Link>
            </li>
            <li>
              <Link href="/lien-he" class="hover:text-primary-foreground">Liên hệ</Link>
            </li>
            <li>
              <Link href="/chinh-sach-bao-mat" class="hover:text-primary-foreground">Chính sách bảo mật</Link>
            </li>
            <li>
              <Link href="/dieu-khoan-dich-vu" class="hover:text-primary-foreground">Điều khoản dịch vụ</Link>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">Hỗ trợ khách hàng</h3>
          <ul class="space-y-2">
            <li>
              <Link href="/huong-dan-mua-hang" class="hover:text-primary-foreground">Hướng dẫn mua hàng</Link>
            </li>
            <li>
              <Link href="/huong-dan-thanh-toan" class="hover:text-primary-foreground">Hướng dẫn thanh toán</Link>
            </li>
            <li>
              <Link href="/chinh-sach-bao-hanh" class="hover:text-primary-foreground">Chính sách bảo hành</Link>
            </li>
            <li>
              <Link href="/chinh-sach-van-chuyen" class="hover:text-primary-foreground">Chính sách vận chuyển</Link>
            </li>
            <li>
              <Link href="/chinh-sach-doi-tra" class="hover:text-primary-foreground">Chính sách đổi trả</Link>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">Thông tin liên hệ</h3>
          <ul class="space-y-2 text-gray-300">
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Số 123 Đường ABC, Quận XYZ, Hà Nội</span>
            </li>
            <li class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span>Hotline: 0912.074.444</span>
            </li>
            <li class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>Email: info@kccshop.vn</span>
            </li>
            <li class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Giờ làm việc: 8:00 - 22:00 (Thứ 2 - Chủ nhật)</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
        <p>© {{ new Date().getFullYear() }} THEANHCOMPUTER - Chuyên kinh doanh máy tính và thiết bị game. All Rights Reserved.</p>
      </div>
    </div>
  </footer>
</template>
