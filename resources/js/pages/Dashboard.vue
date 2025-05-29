<script setup lang="ts">
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { MapMouseEvent } from 'maplibre-gl';
import Radar from 'radar-sdk-js';
import 'radar-sdk-js/dist/radar.css';
import RadarMap from 'radar-sdk-js/dist/ui/RadarMap';
import { onMounted, ref } from 'vue';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps({
    weather: {
        type: Object,
        required: true,
    },
    markers: {
        type: Array,
        required: true,
    },
});

// console.log('Markers:', props.markers);

const form = useForm({
    title: '',
    description: '',
    latitude: 0,
    longitude: 0,
});

const show = ref(false);

const selectedMarker = ref(null);

const isEditing = ref(false);

const map = ref<RadarMap>();

const mapMouseEv = ref<MapMouseEvent>();

Radar.initialize('prj_test_pk_a4a4006fc00a17f9f08ec73995e78a0393b3c22f', {
    /* map config options */
});

onMounted(() => {
    const map = Radar.ui.map({
        container: 'map', // OR document.getElementById('map')
        style: 'radar-dark-v1',
        center: [22.488819593503443, 58.25398239259826], // NYC
        zoom: 14,
    });

for (let marker of props.markers) {
    const radarMarker = Radar.ui
        .marker({
            color: '#000257',
            width: 40,
            height: 80,
            // popup: {
            //     text: marker.title,
            //     text: marker.description,
            // },
        })
        .setLngLat([marker.longitude, marker.latitude])
        .addTo(map);

    radarMarker.getElement().addEventListener('click', () => {
        selectedMarker.value = { ...marker };
        form.title = marker.title || marker.name || '';
        form.description = marker.description || '';
        form.latitude = marker.latitude;
        form.longitude = marker.longitude;
        isEditing.value = false; // <-- Ensure view mode
        show.value = true;
    });
}
    // Radar.ui.marker({
    //   color: '#000257',
    //   width: 40,
    //   height: 80,
    //   popup: {
    //     text: 'My popup.',
    //   }
    // })
    //     .setLngLat([-73.990550, 40.735225])
    //     .addTo(map);

    map.on('click', (e) => {
        selectedMarker.value = null;
        form.title = '';
        form.description = '';
        form.latitude = e.lngLat.lat;
        form.longitude = e.lngLat.lng;
        isEditing.value = true; // Always editing when creating
        show.value = true;
    });
});


function handleSubmit() {
    form.post(route('markers.store'), {
        onSuccess: () => {
            show.value = false;
            // Reload the entire window
            window.location.reload();
        }
    });
}


function handleEdit() {
    form.put(route('markers.update', selectedMarker.value.id), {
        onSuccess: () => {
            show.value = false;
            selectedMarker.value = null;
            window.location.reload();
        }
    });
}

function deleteMarker(id) {
    router.delete(route('markers.destroy', id), {
        onSuccess: () => {
            show.value = false;
            selectedMarker.value = null;
            window.location.reload();
        },
    });
}

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                
                <Card>
                    <CardHeader class="flex h-16 flex-row items-center justify-between space-y-0 pb-2">
                        <div>
                            <CardTitle class="text-sm font-medium">Weather</CardTitle>
                            <div class="text-lg text-gray-600 dark:text-gray-300 font-bold pt-2">
                                {{ weather.name }}
                            </div>
                        </div>
                        <img :src="'http://openweathermap.org/img/wn/' + weather.weather[0].icon + '@2x.png'" alt="Weather icon" class="h-12 w-12" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ weather.main.temp }}°C</div>
                        <p class="text-xs text-muted-foreground">{{ weather.wind.speed }} m/s ( {{ weather.weather[0].description }} )</p>
                    </CardContent>
                </Card>

                <Card
                    @click="router.visit('/posts')"
                    class="cursor-pointer hover:shadow-xl transition-shadow group"
                >
                    <CardHeader class="flex flex-row items-center gap-3 pb-2">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800 group-hover:bg-gray-200 transition">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-300 group-hover:text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2 2 2h4a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <CardTitle class="text-lg font-semibold text-gray-600 dark:text-gray-300">Posts</CardTitle>
                            <CardDescription class="text-sm text-gray-500 dark:text-gray-400">View and manage all posts</CardDescription>
                        </div>
                    </CardHeader>
                    <CardContent class="pt-2 pb-4 text-gray-700 dark:text-gray-200">
                        Go to the Posts page to see all posts.
                    </CardContent>
                    <CardFooter>
                        <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-300 font-medium transition">
                            Go to Posts
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </CardFooter>
                </Card>

                <Card
                    @click="router.visit('/products')"
                    class="cursor-pointer hover:shadow-xl transition-shadow group"
                >
                    <CardHeader class="flex flex-row items-center gap-3 pb-2">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-800 group-hover:bg-gray-200 transition">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-300 group-hover:text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                            </svg>
                        </div>
                        <div>
                            <CardTitle class="text-lg font-semibold text-gray-600 dark:text-gray-300">Products</CardTitle>
                            <CardDescription class="text-sm text-gray-500 dark:text-gray-400">View all products</CardDescription>
                        </div>
                    </CardHeader>
                    <CardContent class="pt-2 pb-4 text-gray-700 dark:text-gray-200">
                        Go to the Products page to see all products.
                    </CardContent>
                    <CardFooter>
                        <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-300 font-medium transition">
                            Go to Products
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </CardFooter>
                </Card>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min"
            >
                <div id="map" style="width: 100%; height: 100%" />
            </div>
            
            <Dialog :open="show" @update:open="val => { show = val; if (!val) { selectedMarker.value = null; isEditing.value = false; } }">
                <DialogContent class="max-w-md w-full rounded-xl shadow-lg bg-white dark:bg-gray-900 p-6">
                    <DialogHeader>
                        <DialogTitle class="text-xl font-semibold mb-2">
                            <span v-if="selectedMarker">
                                <span v-if="isEditing">Edit Marker</span>
                                <span v-else>Marker Details</span>
                            </span>
                            <span v-else>Create Marker</span>
                        </DialogTitle>
                        <DialogDescription v-if="selectedMarker && !isEditing" class="text-gray-500 mb-4">
                            View details or edit/delete this marker.
                        </DialogDescription>
                        <DialogDescription v-else-if="isEditing" class="text-gray-500 mb-4">
                            Fill in the details and save your changes.
                        </DialogDescription>
                        <DialogDescription v-else class="text-gray-500 mb-4">
                            Add a new marker to the map.
                        </DialogDescription>
                    </DialogHeader>
                    <form class="space-y-5" @submit.prevent="selectedMarker ? handleEdit() : handleSubmit()">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Title</label>
                            <input
                                v-if="isEditing"
                                id="title"
                                type="text"
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 dark:text-gray-100"
                                placeholder="Enter title"
                                v-model="form.title"
                                name="title"
                                required
                            />
                            <div v-else class="mt-1 p-2 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700">
                                {{ form.title || '—' }}
                            </div>
                        </div>
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Description</label>
                            <textarea
                                v-if="isEditing"
                                id="description"
                                rows="3"
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 dark:text-gray-100"
                                placeholder="Enter description"
                                v-model="form.description"
                                name="description"
                            ></textarea>
                            <div v-else class="mt-1 p-2 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 whitespace-pre-line">
                                {{ form.description || '—' }}
                            </div>
                        </div>
                        <!-- Coordinates -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Coordinates</label>
                            <div class="mt-1 p-2 rounded bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 text-xs">
                                <span class="font-semibold">Lat:</span> {{ form.latitude }}<br>
                                <span class="font-semibold">Lng:</span> {{ form.longitude }}
                            </div>
                        </div>
                        <!-- Buttons (Edit, Save, Delete) -->
                        <div class="flex flex-wrap gap-2 mt-6 justify-end">
                            <input
                                v-if="isEditing"
                                type="submit"
                                value="Save"
                                class="inline-flex justify-center rounded-md bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 font-medium shadow transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button
                                v-if="selectedMarker && !isEditing"
                                type="button"
                                @click="isEditing = true"
                                class="inline-flex justify-center rounded-md bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 font-medium shadow transition"
                            >
                                Edit
                            </button>
                            <button
                                v-if="selectedMarker"
                                type="button"
                                @click="deleteMarker(selectedMarker.id)"
                                class="inline-flex justify-center rounded-md bg-red-600 hover:bg-red-700 text-white px-5 py-2 font-medium shadow transition"
                            >
                                Delete
                            </button>
                            <button
                                type="button"
                                @click="show = false"
                                class="inline-flex justify-center rounded-md bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-5 py-2 font-medium shadow transition"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
