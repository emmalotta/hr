<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/ui/card/Card.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';

import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed } from 'vue';

const page = usePage();

const cartItems = computed(() => {
  return Object.values(page.props.cart).map((item: any) => ({
    name: item.name,
    price: item.price,
    quantity: item.quantity,
  }));
});

const checkout = async () => {
  try {
    const response = await axios.post('/checkout', { items: cartItems.value });
    window.location.href = response.data.url;
  } catch (error) {
    console.error('Stripe checkout error:', error);
    alert('Checkout failed. Please try again.');
  }
};


const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(amount);
};

const updateCart = (id: number, quantity: number | string) => {
  router.post(route('cart.update'), { id, quantity });
};
</script>

<template>
  <AppLayout>
    <div class="my-12 mx-auto w-full max-w-4xl">
      <Card>
        <CardHeader>
          <CardTitle>🛒 Your Cart</CardTitle>
        </CardHeader>

        <CardContent class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-gray-300 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-300">
                <th class="px-4 py-3">Item</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Quantity</th>
                <th class="px-4 py-3">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, id) in page.props.cart"
                :key="id"
                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200"
              >
                <td class="flex items-center gap-4 px-4 py-3">
                  <img
                    v-if="item.image"
                    :src="item.image"
                    :alt="item.name"
                    class="w-12 h-12 rounded-lg object-cover shadow-md"
                  />
                  <div
                    v-else
                    class="w-12 h-12 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-400"
                  >
                    No Image
                  </div>
                  <span class="font-medium text-gray-800 dark:text-gray-100">{{ item.name }}</span>
                </td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                  {{ formatCurrency(item.price) }}
                </td>
                <td class="px-4 py-3">
                  <Input
                    type="number"
                    :min="0"
                    :model-value="item.quantity"
                    @update:model-value="(value) => updateCart(id, value)"
                    class="w-20 text-center"
                  />
                </td>
                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                  {{ formatCurrency(item.price * item.quantity) }}
                </td>
              </tr>
            </tbody>
          </table>
        </CardContent>

        <CardFooter class="flex flex-col md:flex-row items-end justify-between gap-4">
          <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
            Total: {{ formatCurrency(page.props.cartTotal) }}
          </p>
          <Button @click="checkout">Purchase</Button>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>
