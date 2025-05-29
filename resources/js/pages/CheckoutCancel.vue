<script setup lang="ts">

import { Link } from '@inertiajs/vue3';

</script>

<template>
  <AppLayout>
    <div class="container mx-auto p-6 md:p-12">
      <h1 class="text-3xl font-bold text-red-500 mb-4">Payment Cancelled</h1>
      <p>Your payment was cancelled. Feel free to try again.</p>
      <Link href="/cart" class="mt-4 inline-block text-blue-600 hover:underline">Back to Cart</Link>
    </div>
  </AppLayout>
</template>
