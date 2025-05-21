<template>
    <Head title="Settings" />
    <content-container
        pageTitle="Settings"
        :hasAdminPanel="true"
        :hideTopBorder="true"
    >
        <template #main-content>
            <div class="md:p-5">
                <div
                    class="w-full p-2 md:p-8 mt-3 md:shadow-lg md:border rounded-xl"
                >
                    <div class="font-bold text-gray-700 mb-3">
                        Default Academic Year
                    </div>
                    <div class="flex flex-col gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 rounded-lg bg-gray-200 hover:bg-gray-300 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Academic Year
                                    </p>
                                    <select v-if="isEditing"
                                        v-model="form.settings[0].setting_value"
                                        class="mt-1 w-full p-2 h-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 pr-10"
                                    >
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2024-2025">2024-2025</option>
                                        <option value="2025-2026">2025-2026</option>
                                        <option value="2026-2027">2026-2027</option>
                                        <option value="2027-2028">2027-2028</option>
                                        <option value="2028-2029">2028-2029</option>
                                        <option value="2029-2030">2029-2030</option>
                                    </select>
                                    <div v-else>
                                        {{
                                            form.settings[0].setting_value
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="font-bold text-gray-700 mt-8 mb-3">
                        Default Signatories
                    </div>
                    <div class="flex flex-col gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 rounded-lg bg-gray-200 hover:bg-gray-300 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Noted by
                                    </p>
                                    <div class="w-full" v-if="isEditing">
                                        <input
                                            maxlength="255"
                                            type="text"
                                            class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                            placeholder="Name"
                                            v-model="form.settings[1].setting_value"
                                        />
                                        <input
                                            maxlength="255"
                                            type="text"
                                            class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                            placeholder="Title"
                                            v-model="form.settings[2].setting_value"
                                        />
                                    </div>
                                    <div class="w-full" v-else>
                                        <div>
                                            {{
                                                form.settings[1].setting_value
                                            }}
                                        </div>
                                        <div>
                                            {{
                                                form.settings[2].setting_value
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center p-4 rounded-lg bg-gray-200 hover:bg-gray-300 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Reviewed by
                                    </p>
                                    <div class="w-full" v-if="isEditing">
                                        <input
                                            maxlength="255"
                                            type="text"
                                            class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                            placeholder="Name"
                                            v-model="form.settings[3].setting_value"
                                        />
                                        <input
                                            maxlength="255"
                                            type="text"
                                            class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                            placeholder="Title"
                                            v-model="form.settings[4].setting_value"
                                        />
                                    </div>
                                    <div class="w-full" v-else>
                                        <div>
                                            {{
                                                form.settings[3].setting_value
                                            }}
                                        </div>
                                        <div>
                                            {{
                                                form.settings[4].setting_value
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row mt-3 md:gap-2">
                        <button @click="edit" :disabled="isEditing" v-if="!isEditing"
                            :class="{'opacity-50 cursor-not-allowed': isEditing}"
                            class="mt-5 w-full md:w-1/2 md:max-w-8 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
                        >
                        <i class="fas fa-edit"></i> Edit
                        </button>
                        <div v-else class="w-full whitespace-nowrap space-x-2">
                            <button @click="update" :disabled="!isEditing"
                                :class="{'opacity-50 cursor-not-allowed': !isEditing}"
                                class="mt-5 w-full md:w-1/2 md:max-w-8 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
                            >
                            <i class="fas fa-save"></i> Update
                            </button>
                            <button @click="isEditing = false;" :disabled="!isEditing"
                                :class="{'opacity-50 cursor-not-allowed': !isEditing}"
                                class="mt-5 w-full md:w-1/2 md:max-w-8 border border-gray-300 text-gray-600 hover:border-gray-400 hover:text-black hover:bg-gray-100 py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
                            >
                                Cancel
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="md:p-5">
                <div
                    class="w-full p-2 md:p-8 md:shadow-lg md:border rounded-xl"
                >
                    <div class="font-bold text-gray-700 mb-3">
                        Program Assignment
                    </div>
                    <content-table>
                        <template v-slot:table-head>
                            <th>Education Supervisor</th>
                            <th>
                                <i class="fas fa-ellipsis-v text-lg"></i>
                            </th>
                        </template>
                        <template v-slot:table-body>
                            <tr v-if="supervisor_list.length == 0">
                                <no-search-result text="supervisor"></no-search-result>
                            </tr>
                            <tr 
                                v-else
                                v-for="supervisor in supervisor_list" :key="supervisor.id"
                            >
                                <td>{{ supervisor.name }}</td>
                                <td>
                                    <action-button type="view" @click="viewPrograms(supervisor.id); modalTitle = supervisor.name" />
                                    <action-button type="edit" @click="assign(supervisor.id)" />
                                </td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>

    <modal
        :showModal="viewProgramsModal"
        :title="modalTitle"
        @close="viewProgramsModal = false"
        width="4xl"
        hasNoCancelButton="true"
    >
        <div class="h-52 flex items-center justify-center" v-if="fetchingData">
            <svg class="h-12 w-12 text-blue-500 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 20v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M4.93 4.93l1.41 1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M17.66 17.66l1.41 1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M2 12h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M20 12h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M4.93 19.07l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>           
        </div>
        <div class="space-y-6 max-h-96 overflow-y-auto" v-else>
            <content-table>
                <template v-slot:table-head>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >Programs</th>
                    <th class="px-6 py-4 text-right w-24">
                        <span class="text-gray-800">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </span>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="programs?.data?.length == 0">
                        <no-search-result text="programs" />
                    </tr>
                    <tr
                        v-else
                        v-for="(program, index) in programs.data"
                        :key="program.id"
                        class="hover:bg-blue-200 border-b border-gray-200"
                        :class="{ 'bg-slate-200': index % 2 === 1 }"
                    >
                        <td class="px-6 py-2 text-gray-900 align-top">
                            {{ program.program }}
                            <span v-if="program?.major">
                                - {{ program?.major }}
                            </span>
                        </td>
                        <td></td>
                    </tr>
                </template>
            </content-table>
        </div>

        <template #custom-button>
            <button
            @click="viewProgramsModal = false"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
          >
            Close
          </button>
        </template>
    </modal>
</template>

<script setup>
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps([
    "settings",
    "supervisor_list",
]);

const isEditing = ref(false);
const programs = ref({});
const viewProgramsModal = ref(false);
const fetchingData = ref(false);
const modalTitle = ref('');

const viewPrograms = async (id) => {
  viewProgramsModal.value = true;
    fetchingData.value = true;
  try {
    const response = await axios.get(`/api/admin/settings/${id}/assignedprograms`);
    if (!response.data) {
        console.error("No data received from API");
        fetchingData.value = false;
      return;
    }
      programs.value = response.data;
      fetchingData.value = false;
      
  } catch (error) {
    console.error("Error fetching data:", error.response?.data || error.message);
  } finally {
    fetchingData.value = false;
  }
};

const form = reactive({
    settings: ref(props.settings),
});

function update() {
    router.post("/admin/settings/update", form, {
        onFinish: () => {
            isEditing.value = false;
        },
        replace: true,
        preserveState: false,
        preserveScroll: true,
    });
}

function assign(id){
    router.get(`/admin/settings/${id}/assignment`, {
        replace: true,
    })
}

function edit() {
    isEditing.value = true;
}
</script>
