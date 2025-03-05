<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    urls: Object,
});

</script>

<template>

    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            Your Shortened URLs  
        </h3>

        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Short URL</th>
                    <th class="border p-2">Original URL</th>
                    <th class="border p-2">Created At</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="url in urls?.data" :key="url.id" class="border-b">
                    <td class="border p-2">
                        <a :href="url.short_url" class="text-blue-500 hover:underline" target="_blank">
                            {{ url.code }}
                        </a>
                    </td>
                    <td class="border p-2">
                        <a :href="url.original_url" class="text-blue-500 hover:underline" target="_blank">
                            {{ url.original_url }}
                        </a>
                    </td>
                    <td class="border p-2">{{ new Date(url.created_at).toLocaleDateString() }}</td>
                </tr>
            </tbody>
        </table>

       <!-- 分頁 -->
       <div v-if="urls?.meta?.last_page > 1" class="mt-4 flex justify-between">
            <Link
                v-if="urls?.links?.prev"
                :href="urls.links.prev"
                class="text-blue-500 hover:underline"
            >
                Previous
            </Link>

            <span>Page {{ urls.meta.current_page }} of {{ urls.meta.last_page }}</span>

            <Link
                v-if="urls?.links?.next"
                :href="urls.links.next"
                class="text-blue-500 hover:underline"
            >
                Next
            </Link>
        </div>
    </div>
</template>
