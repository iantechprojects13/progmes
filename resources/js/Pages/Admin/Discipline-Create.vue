<template>

    <Head title="New Discipline" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
                <div class="flex flex-row items-center justify-between">
                    <div class="w-full flex flex-row items-center">
                        <Link href="/admin/program/discipline">
                        <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                        </Link>
                        <div class="ml-3 font-bold">Create Discipline</div>
                    </div>
                </div>
        </template>
        <template v-slot:main-content>
            <div class="w-full max-w-lg p-2 md:p-8 my-3 md:m-5 md:shadow-lg md:border rounded-xl">
              <!-- Form Header -->
              <div class="mb-4 text-sm text-gray-600">
                <span class="text-red-500">*</span>
                <span class="italic">indicates required field</span>
              </div>
          
              <!-- Form Content -->
              <div class="space-y-6">
                <div>
                  <label for="discipline" class="block font-medium text-gray-700">
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
                  <form-error-message :message="$page.props.errors.discipline" theme="dark" />
                </div>
          
                <!-- Submit Button -->
                <div>
                  <button
                    @click="submit"
                    :disabled="processing"
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                  >Add
                  </button>
                </div>
              </div>
            </div>
          </template>
    </content-container>
</template>

<script setup>
    import { router, useForm } from "@inertiajs/vue3";
    import { ref, reactive } from 'vue';

    const form = reactive({
        discipline: null,
    });

    const processing = ref(false);

    function submit() {
        router.post('/admin/program/discipline/store', form, {
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
    import FormErrorMessage from "@/Shared/Component/FormErrorMessage.vue";
    import Layout from "@/Shared/Layout.vue";

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