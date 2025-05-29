<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { PenSquare, Trash } from 'lucide-vue-next';
import { router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'

defineProps<{ posts: Array<{ id: number, title: string, description: string, created_at_for_humans: string, updated_at_for_humans: string }> }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/posts' },
];

const postToDelete = ref<number | null>(null);

const deletePost = () => {
    if (postToDelete.value !== null) {
        router.delete(route('posts.destroy', postToDelete.value), {
            onSuccess: () => {
                postToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="my-12 mx-auto w-full max-w-3xl flex flex-col gap-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Posts</h1>
                <Button :as="Link" :href="route('posts.create')" variant="outline" class="bg-green-500 text-white hover:bg-green-600">
                    Add New
                </Button>
            </div>
            <div class="flex flex-col gap-4">
                <Card v-for="post in posts" :key="post.id" class="w-full shadow-sm hover:shadow-md transition-shadow">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-lg">
                            <Link :href="route('posts.show', post)" class="hover:underline text-blue-700 dark:text-blue-400">
                                {{ post.title }}
                            </Link>
                        </CardTitle>
                        <CardDescription class="text-xs mt-1 text-gray-600 dark:text-gray-300">
                            {{ post.description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="py-1">
                        <div class="flex flex-col sm:flex-row sm:justify-between text-xs text-gray-500 dark:text-gray-400 gap-1">
                            <span>Created: {{ post.created_at_for_humans }}</span>
                            <span>Updated: {{ post.updated_at_for_humans }}</span>
                        </div>
                    </CardContent>
                    <CardFooter class="flex gap-2 justify-end pt-2">
                        <Button :as="Link" :href="route('posts.edit', post)" variant="outline" size="icon" title="Edit">
                            <PenSquare class="size-4" />
                        </Button>
                        <Button @click="postToDelete = post.id" variant="destructive" size="icon" title="Delete">
                            <Trash class="size-4" />
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>

        <AlertDialog :open="!!postToDelete">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently delete the post and remove its data from our servers.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="postToDelete = null">Cancel</AlertDialogCancel>
                    <AlertDialogAction @click="deletePost">Delete</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>