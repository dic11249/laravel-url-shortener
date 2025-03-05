<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    originalUrl: null,
    code: null,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Short Url Create
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Create your short url.
            </p>
        </header>

        <form
            @submit.prevent="form.post(route('urls.store'), {
                onSuccess: () => {
                    form.reset(), // 使用 Inertia 提供的 reset() 方法清空表單
                    router.get(route('dashboard')); // 重新載入網址列表
                }
            })"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="originalUrl" value="OriginalUrl" />

                <TextInput
                    id="originalUrl"
                    type="url"
                    class="mt-1 block w-full"
                    v-model="form.originalUrl"
                    required
                    autocomplete="originalUrl"
                />

                <InputError class="mt-2" :message="form.errors.originalUrl" />
            </div>

            <div>
                <InputLabel for="code" value="Code" />

                <TextInput
                    id="code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.code"
                    required
                    autocomplete="code"
                />

                <InputError class="mt-2" :message="form.errors.code" />
            </div>
            
            

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>