<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3'
import debounce from 'lodash.debounce'

const props = defineProps({
    blogs: {
        type: Object,
        default: () => ({}),
    },
    totalCount: {
        type: Number,
        default: 0
    },
    blog: {
        type: Object,
        default: () => ({}),
    }
});

let search = ref('');
let id = ref('');

const showBlogDetail = ref(false);

router.reload({ only: ['totalCount'] });

watch(search, debounce((value) => {
    router.reload({ data: { search: value }, only: ['blogs'] });
}, 500))

const form = useForm({});

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route("blogs.destroy", id));
    }
}

function view(id) {
    showBlogDetail.value = true;

    router.reload({ data: { id }, only: ['blog'] });
}

const closeModal = () => {
    showBlogDetail.value = false;

    router.get('/blogs');
}
</script>

<template>
    <Head title="Blogs" />

    <Modal :show="showBlogDetail" @close="closeModal">
        <div class="p-6">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ blog.title }}
            </h2>
            <p> {{ blog.content }} </p>
        </div>

        <div class="mt-6 flex justify-end">
            <SecondaryButton @click="closeModal"> Close </SecondaryButton>
        </div>
    </Modal>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Blogs Listing 
            </h2>
            <div :class="{ 'loader' : totalCount == 0}">
                <span v-show="totalCount">Total blogs: {{ totalCount }}</span>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="relative mb-4">
                    <input type="search" v-model="search" id="default-search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Blogs...">
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-2">
                            <Link :href="route('blogs.create')">
                            <PrimaryButton>Add Blog</PrimaryButton>
                            </Link>
                        </div>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#</th>
                                        <th scope="col" class="px-6 py-3">
                                            Title
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Edit
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Delete
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            View
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="blog in blogs" :key="blog.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ blog.id }}
                                        </th>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ blog.title }}
                                        </th>

                                        <td class="px-6 py-4">
                                            <Link :href="route('blogs.edit', blog.id)
                                                " class="px-4 py-2 text-white bg-blue-600 rounded-lg">Edit</Link>
                                        </td>
                                        <td class="px-6 py-4">
                                            <PrimaryButton class="bg-red-700" @click="destroy(blog.id)">
                                                Delete
                                            </PrimaryButton>
                                        </td>
                                        <td>
                                            <PrimaryButton class="bg-green-700" @click="view(blog.id)">
                                                View
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>