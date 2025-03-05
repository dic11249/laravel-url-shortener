<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UrlList from '@/Pages/Url/Partials/UrlList.vue';
import { Head, usePage } from '@inertiajs/vue3';

const { props } = usePage();

</script>

<template>
    <Head title="Url" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Url
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UrlList :urls="props.urls" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
