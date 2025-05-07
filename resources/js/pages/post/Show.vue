<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3';

const props = defineProps(['post'])

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Show post',
        href: '/posts/show',
    },
];

const form = useForm({
    comment: '',

});



const submit = () => {
    form.post(route("comments.store", props.post), {
        preserveScroll: true,
        onSuccess:() => {
            form.reset();
        },

    });
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="my-12 mx-auto w-full max-w-2xl">
           <div>
             <h1 class="text-2xl font-semibold tracking wide">{{ post.title }}</h1>
             <p class="">{{ post.description }}</p>
            <!-- {{ post }} -->
           </div>
           <div>
                <form @submit.prevent="submit">
                    <div class="relative">
                        <Textarea v-model="form.comment" class="h-full w-full"></Textarea>
                        <Button class="absolute bottom-4 right-4 z-10">Submit</Button>
                    </div>
                </form>
            </div>
            <div class="mt-8">
                <h2 class="text-lg font-semibold border-b pb-2">Comments</h2>
                <ul class="divide-y divide-gray-200">
                    <li v-for="comment in post.comments" :key="comment.id" class="py-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <img 
                                    v-if="comment.user.avatar" 
                                    :src="comment.user.avatar" 
                                    alt="User Avatar" 
                                    class="h-10 w-10 rounded-full"
                                />
                                <div v-else class="h-10 w-10 rounded-full bg-gray-300"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <p class="font-medium text-white">{{ comment.user.name }}</p>
                                    <p class="text-sm text-gray-500">{{ comment.created_at_for_humans }}</p>
                                </div>
                                <p class="text-gray-600 mt-1">{{ comment.comment }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- <pre>{{ post.comments }}</pre> -->
        </div>
    </AppLayout>
</template>