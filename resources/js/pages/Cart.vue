<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(amount);
};

const updateCart = (id: number, quantity: number | string) => {
    router.post(route('cart.update'), {
        id: id,
        quantity: quantity,
    });
};
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-6 md:p-12">
            <h1 class="text-3xl font-bold text-gray-100 mb-6">🛒 Cart</h1>
            <div class="bg-gray-800 text-gray-100 shadow-lg rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700 text-gray-300">
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3">Quantity</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, id) in $page.props.cart" :key="id" class="border-b border-gray-700 hover:bg-gray-700 transition duration-200">
                            <td class="flex items-center gap-4 px-4 py-3">
                                <img 
                                    v-if="item.image" 
                                    :src="item.image" 
                                    :alt="item.name" 
                                    class="w-12 h-12 rounded-lg object-cover shadow-md"
                                />
                                <div v-else class="w-12 h-12 flex items-center justify-center bg-gray-600 rounded-lg text-gray-300">
                                    No Image
                                </div>
                                <span class="font-semibold">{{ item.name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-200">{{ formatCurrency(item.price) }}</td>
                            <td class="px-4 py-3">
                                <Input 
                                    class="w-20 text-center bg-gray-700 text-white border border-gray-600 rounded-md hover:bg-gray-600 transition duration-200" 
                                    type="number"
                                    @update:model-value="(value) => updateCart(id, value)"
                                    :min="0" 
                                    :model-value="item.quantity"
                                />
                            </td>
                            <td class="px-4 py-3 text-gray-200">{{ formatCurrency(item.price * item.quantity) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex items-center justify-end text-right mt-6">
                    <p class="text-2xl font-bold text-blue-400">
                        Total: {{ formatCurrency($page.props.cartTotal) }}
                    </p>
                </div>
                <div class="flex justify-end mt-4">
                    <button 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-500 transition duration-200"
                        @click="router.post(route('cart.checkout'))"
                    >
                        Purchase
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
