<template>

    <Head title="New Discipline" />
    <AdminPanel />
    <content-container pageTitle="Create Discipline" hasBackButton="true">
        <template v-slot:back-button>
            <Link href="/admin/program/discipline">
            <button class="w-10 h-10 mr-2 rounded-full hover:bg-gray-300 tooltipForActions" data-tooltip="asd">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:main-content>
            <div>
                <div class="p-5 w-full md:max-w-md md:shadow md:shadow-gray-600 rounded mx-auto md:my-10">
                    <div>
                        <label for="discipline" class="font-black">Discipline</label>
                        <span class="text-sm ml-1 text-red-500">(Required)</span>
                        <input required v-model="form.discipline" type="text" id="discipline"
                            class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500"
                            placeholder="Discipline" />
                        <FormErrorMessage :message="$page.props.errors.discipline" theme="dark" />
                    </div>
                    <div class="text-right mt-5">
                        <button @click="submit" class="rounded text-white bg-blue-500 hover:bg-blue-600 py-2 px-4">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.success" />
</template>

<script setup>
    import { useForm } from "@inertiajs/vue3";

    const form = useForm({
        discipline: null,
    });

    function submit() {
        form.post("/admin/program/discipline/store", form);
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