<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Link, PenSquare, Trash } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps(["posts"]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];

const postToDelete = ref();

const deletePost = () => {
    router.delete(route('posts.destroy', postToDelete.value), {
        onSuccess: () => {
            postToDelete.value = undefined;
        },
    });
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto my-12 w-full max-w-screen-lg">
            <div class="flex justify-between">
                <h1 class="text-2xl font-semibold tracking-wide">Posts</h1>
                <Button>Add New</Button>
            </div>

            <Table>
            <TableHeader>
                <TableRow>
                <TableHead>Title</TableHead>
                <TableHead class="tetx-right">Created At</TableHead>
                <TableHead class="sr-only">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="post in posts" :key="post.id">
                <TableCell>
                    <div>
                        <Link class="hover:underline" :href="route('posts.show', post)">{{ post.title }}</Link>
                        <p class="max-w-[35ch] truncate text-muted-foreground text-xs">{{ post.description }}</p>
                    </div>
                </TableCell>
                <TableCell class="text-right">{{ post.created_at_for_humans }}</TableCell>
                <TableCell class="text-right">
                    <div>
                        <Button @click="postToDelete = post" variant="destructive" size="icon">
                            <Trash class="size-4"  />
                        </Button>
                        <Button :as="Link" :href="route('posts.edit', post)" variant="outline" size="icon">
                            <PenSquare class="size-4" />
                        </Button>
                    </div>
                </TableCell>
                </TableRow>
            </TableBody>
            </Table>
        </div>

          <AlertDialog>
            <AlertDialogTrigger :open="!!postToDelete">
              <AlertDialogContent>
                <AlertDialogHeader>
                  <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                  <AlertDialogDescription>
                    This action cannot be undone. This will permanently delete your account
                    and remove your data from our servers.
                  </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                  <AlertDialogCancel @click="postToDelete = undefined"> Cancel</AlertDialogCancel>
                  <AlertDialogAction @click="deletePost">Continue</AlertDialogAction>
                </AlertDialogFooter>
              </AlertDialogContent>
            </AlertDialogTrigger>
          </AlertDialog>
    </AppLayout>
    
</template>