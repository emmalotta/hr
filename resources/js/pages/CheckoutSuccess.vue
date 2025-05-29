<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

</script>


<template>
  <AppLayout>
    <div class="container mx-auto p-6 md:p-12">
      <h1 class="text-3xl font-bold text-green-500 mb-4">Payment Successful!</h1>
      <p>Thank you for your purchase! Your order is confirmed.</p>
      <Link href="/products" class="mt-4 inline-block text-blue-600 hover:underline">Back to Home</Link>
    </div>
  </AppLayout>
</template>
