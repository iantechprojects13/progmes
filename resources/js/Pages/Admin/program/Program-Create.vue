<template>
    <Head title="New Program" />
    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="font-bold">New Program</div>
        </template>
        <template v-slot:main-content>
            <div
                class="w-full max-w-lg p-2 md:p-8 my-3 md:m-5 md:shadow-lg md:border rounded-xl"
            >
                <!-- Form Header -->
                <div class="mb-4 text-sm text-gray-600">
                    <span class="text-red-500">*</span>
                    <span class="italic">indicates required field</span>
                </div>

                <!-- Form Content -->
                <div class="space-y-6">
                    <!-- Discipline -->
                    <div>
                        <label
                            class="block font-medium text-gray-700"
                        >
                            Discipline
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.discipline"
                            class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            :class="[
                                { 'text-gray-500': form.discipline == null },
                                { 'text-gray-800': form.discipline },
                            ]"
                        >
                            <option :value="null">Select discipline</option>
                            <option
                                v-for="item in props.discipline_list"
                                :key="item.id"
                                :value="item.id"
                                class="text-black"
                            >
                                {{ item.discipline }}
                            </option>
                        </select>
                        <form-error-message
                            :message="$page.props.errors.discipline"
                            theme="dark"
                        />
                    </div>

                    <!-- Program -->
                    <div>
                        <label
                            class="block font-medium text-gray-700"
                        >
                            Program
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            required
                            maxlength="255"
                            v-model="form.program"
                            type="text"
                            placeholder="Enter program"
                            class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <form-error-message
                            :message="$page.props.errors.program"
                            theme="dark"
                        />
                    </div>

                    <!-- Major -->
                    <div>
                        <label
                            class="block font-medium text-sm text-gray-700"
                        >
                            Major
                        </label>

                        <input
                            required
                            maxlength="255"
                            v-model="form.major"
                            type="text"
                            placeholder="Enter discipline"
                            class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <form-error-message
                            :message="$page.props.errors.major"
                            theme="dark"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            @click="submit"
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
// -------------------------------------------------------------------------
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------
const props = defineProps({
    discipline_list: Array,
});

// -------------------------------------------------------------------------
const form = reactive({
    discipline: null,
    program: null,
    major: null,
});

// -------------------------------------------------------------------------
function submit() {
    router.post("/admin/program/store", form, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}
</script>