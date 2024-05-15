<template>

    <Head title="Update Discipline" />
    <AdminPanel />
    <content-container>
        <template v-slot:content-title>
                <div class="p-3 flex flex-row items-center justify-between border-b border-gray-400">
                    <div class="flex flex-row">
                        <Link href="/admin/program/discipline/">
                        <button><i class="fas fa-arrow-left ml-2 mr-3"></i></button>
                        </Link>
                        <div class="font-bold">Edit Discipline</div>
                    </div>
                </div>
        </template>
        <template v-slot:main-content>
            <div>
                <div class="p-5 w-full md:max-w-md md:shadow md:shadow-gray-600 rounded mx-auto md:my-10">
                    <div>
                        <span class="text-red-500">*</span>
                        <span class="italic text-sm"> indicates required field</span>
                    </div>
                    <div class="mt-7">
                        <label for="discipline" class="font-black">Discipline</label>
                        <span class="text-red-500">*</span>
                        <input required maxlength="255" v-model="form.discipline" type="text" id="discipline"
                            class="rounded text-sm border-gray-500 w-full my-0.5 placeholder-gray-400"
                            placeholder="Discipline" />
                        <form-error-message :message="$page.props.errors.discipline" theme="dark" />
                        <form-error-message :message="$page.props.flash.failed" theme="dark" />
                    </div>
                    <div class="mt-5 w-full">
                        <button @click="submit" :disabled="processing"
                            class="w-full rounded text-white bg-blue-500 hover:bg-blue-600 py-2 px-4">
                            <span v-if="!processing">Update</span>
                            <span v-else><i class="fas fa-spinner animate-spin"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" />
</template>

<script setup>
    import { router, useForm } from "@inertiajs/vue3";
    import { ref, reactive } from 'vue';

    const props = defineProps([
        'discipline',
    ]);

    const form = reactive({
        id: ref(props.discipline.id),
        discipline: ref(props.discipline.discipline),
    });

    const processing = ref(false);

    function submit() {
        router.post('/admin/program/discipline/update', form, {
            onStart: () => {
                processing.value = true;
            },
            onFinish: () => {
                processing.value = false;
            },
            preserveScroll: true,
            preserveState: true,
        });
    }
</script>

<script>
    import FormErrorMessage from "../Shared/FormErrorMessage.vue";
    import Layout from "../Shared/Layout.vue";

    export default {
        layout: Layout,
        data() {
            return {
                openCreateDropdown: false,
            };
        },
        components: {
            FormErrorMessage,
        },
    };
</script>