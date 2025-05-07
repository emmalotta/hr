<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ShoppingCart } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

const props = defineProps(['products']);

const addToCart = (product: any) => {
    router.post(route('cart.add', product), undefined, {
        preserveScroll: true,

    });
};
</script>
<template>
    <AppLayout :breadcrumbs="[]">

        <div class="p-12">
            <div class="p-4 w-full justify-end flex">
                {{ $page.props.cart }}
                <Button size="icon" variant="outline" class="relative">
                    <ShoppingCart class="size-6"></ShoppingCart>
                    <div class="absolute -top-1 -right-1 flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-medium ">
                        {{ Object.entries($page.props.cart ?? {})?.length }}
                    </div>
                </Button>
            </div>
            <div class="product-list flex flex-wrap gap-4">
                <Card
                    v-for="product in products"
                    :key="product.id"
                    class="product.item flex w-80 flex-col p-4"
                >

                    <div class="size-72 object-contain">
                        <img :src="product.image" alt="product Image" >
                    </div>

                    <div class="flex-1"> 
                        <h2 class="mb-2 mt-4 text-lg font-semibold text-gray-100">{{ product.name }}</h2>
                        <p class="mb-4 text-gray-200">{{ product.description }}</p>
                        <p class="font-bold text-gray-400">Price: ${{ product.price }}</p>
                    </div>
                    <Button class="mt-2 w-full" @click="addToCart(product)">Add to cart</Button>
                </Card>
            </div>
        </div>

    </AppLayout>
</template>
