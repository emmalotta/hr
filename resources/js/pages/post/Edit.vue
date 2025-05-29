<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3';

// Accept the post prop from the backend
const props = defineProps<{ post: { id: number, title: string, description: string } }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Posts', href: '/posts' },
    { title: 'Edit post', href: '' },
];

const form = useForm({
    title: props.post.title,
    description: props.post.description,
});

const submit = () => {
    form.put(route('posts.update', props.post.id));
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="my-12 mx-auto w-full max-w-2xl">
            <form @submit.prevent="submit">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit post</CardTitle>
                        <CardDescription>Edit your blog post below</CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-4">
                        <div>
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" />
                            <InputError :message="form.errors.title" />
                        </div>
                        <div>
                            <Label for="description">Description</Label>
                            <Textarea id="description" v-model="form.description" />
                            <InputError :message="form.errors.description" />
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button type="submit" :disabled="form.processing">Update Post</Button>
                        <a
                            href="/posts"
                            class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded font-medium"
                        >
                            Cancel
                        </a>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>