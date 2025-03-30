<template>
    <Head title="Update Discipline" />
    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="font-bold">Edit Discipline</div>
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
                    <div>
                        <label
                            for="discipline"
                            class="block font-medium text-gray-700"
                        >
                            Discipline
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            required
                            maxlength="255"
                            v-model="form.discipline"
                            type="text"
                            id="discipline"
                            placeholder="Enter discipline"
                            class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <form-error-message
                            :message="$page.props.errors.discipline"
                            theme="dark"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            @click="submit"
                            :disabled="processing"
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
// -----------------------------------------------------------------------------------
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -----------------------------------------------------------------------------------
const props = defineProps(["discipline"]);

// -----------------------------------------------------------------------------------
const processing = ref(false);

const form = reactive({
    id: ref(props.discipline.id),
    discipline: ref(props.discipline.discipline),
});

// -----------------------------------------------------------------------------------
function submit() {
    router.post("/admin/program/discipline/update", form, {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}
</script>
