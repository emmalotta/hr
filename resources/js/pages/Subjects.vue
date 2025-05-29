<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Item {
  id: number;
  title: string;
  description: string;
  image?: string;
  price: number;
  [key: string]: any;
}

const props = defineProps<{
  data: Item[];
  customFields: string[];
}>();

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat("de-DE", {
    style: "currency",
    currency: "EUR",
  }).format(amount);
};
</script>

<template>
  <AppLayout :breadcrumbs="[]">
    <div class="p-12">
      <h1 class="text-4xl font-bold text-center mb-8">API</h1>
      <!-- API Buttons Section -->
      <div class="mb-8 flex flex-wrap gap-4 justify-center">
        <Button
          as="a"
          href="/display-subjects"
          class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded shadow"
        >
          Minu API
        </Button>
        <Button
          as="a"
          href="/display-subjects?type=andrus"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow"
        >
          Andrus API
        </Button>
        <Button
          as="a"
          href="/display-subjects?type=kert"
          class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded shadow"
        >
          Kert API
        </Button>
        <Button
          as="a"
          href="/display-subjects?type=katrin"
          class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded shadow"
        >
          Katrin API
        </Button>
        <Button
          as="a"
          href="/display-subjects?type=jaanika"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow"
        >
          Jaanika API
        </Button>
      </div>
      <!-- Cards Section -->
      <div class="flex flex-wrap gap-4">
        <Card
          v-for="item in data"
          :key="item.id"
          class="product.item flex w-80 flex-col p-4"
        >
          <!-- Image Section -->
          <div class="size-72 object-contain bg-gray-100 dark:bg-gray-800 rounded mb-4 flex items-center justify-center">
            <img
              v-if="item.image"
              :src="item.image"
              :alt="item.title"
              class="object-contain h-full max-h-64"
            />
            <div v-else class="text-gray-700 dark:text-gray-400">No Image</div>
          </div>
          <!-- Content Section -->
          <div class="flex-1 flex flex-col">
            <h2 class="mb-2 mt-2 text-lg font-semibold text-gray-700 dark:text-gray-400 truncate">
              {{ item.title }}
            </h2>
            <p class="mb-4 text-gray-700 dark:text-gray-400">
              {{ item.description }}
            </p>
            <!-- Custom Fields -->
            <div class="mb-2 space-y-1 text-sm">
              <p v-if="customFields[0]" class="font-semibold text-blue-400">
                {{ customFields[0] }}:
                <span class="font-normal text-gray-700 dark:text-gray-400">
                  {{ item[customFields[0]] }}
                </span>
              </p>
              <p v-if="customFields[1]" class="font-semibold text-blue-400">
                {{ customFields[1] }}:
                <span class="font-normal text-gray-700 dark:text-gray-400">
                  {{ formatCurrency(item.price) }}
                </span>
              </p>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

