<template>
    <Head title="Program Application" />
    <page-title title="Program Application" />
    <content-container page="application">
        <template v-slot:content-title>
            <div class="flex flex-col md:flex-row items-center justify-between w-full">
                <div class="w-full flex flex-row items-center justify-between">
                    <div class="w-full flex flex-row items-center">
                        <Link href="/hei/program-application/">
                            <button
                                class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions"
                                data-tooltip="Back"
                            >
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </Link>
                        <div class="ml-3 font-bold">Application Draft</div>
                    </div>
                </div>
                <div class="text-right md:space-x-2 space-y-2 md:space-y-0 md:whitespace-nowrap w-full md:w-1/2 flex-col xl:flex-row mt-2 md:mt-0">
                    <button @click="toggleConfirmSubmitModal"
                        class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 mr-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M12 16V8" />
                                <path d="M8 12l4-4 4 4" />
                                <path d="M4 20h16v-12l-5-5H4z" />
                            </svg>
                            Submit
                        </span>
                    </button>
                    <button
                        @click="update"
                        class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 mr-2"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
                                />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                            </svg>
                            Save changes
                        </span>
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="p-5">
                <div class="bg-white rounded-xl shadow-lg border p-5">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div
                                class="w-full first-letter:flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                            >
                                <p class="text-sm text-gray-500">Name of HEI</p>
                                <input
                                    type="text"
                                    class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                    :value="application.institution?.name"
                                    disabled
                                />
                            </div>

                            <div
                                class="w-full first-letter:flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                            >
                                <p class="text-sm text-gray-500">
                                    Program Being Applied For
                                </p>
                                <select
                                    v-model="form.program"
                                    class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 pr-10"
                                >
                                    <option :value="null">
                                        Select Program
                                    </option>
                                    <option
                                        v-for="program in programs[0]"
                                        :key="program.id"
                                        :value="program.id"
                                    >
                                        {{ program.program
                                        }}{{
                                            program.major
                                                ? " - " + program.major
                                                : ""
                                        }}
                                    </option>
                                </select>
                                <form-error-message :message="$page.props.errors.program" theme="dark" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div
                                class="w-full first-letter:flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                            >
                                <p class="text-sm text-gray-500">
                                    Type of Application
                                </p>
                                <select
                                    v-model="form.type"
                                    class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                >
                                    <option :value="null">Select type</option>
                                    <option value="Initial Permit (1st year)">
                                        Initial Permit (1st year)
                                    </option>
                                    <option value="Renewal Permit (2nd year)">
                                        Renewal Permit (2nd year)
                                    </option>
                                    <option value="Renewal Permit (3rd year)">
                                        Renewal Permit (3rd year)
                                    </option>
                                    <option value="Government Recognition">
                                        Government Recognition
                                    </option>
                                    <option
                                        value="Certificate of Program Compliance"
                                    >
                                        Certificate of Program Compliance
                                    </option>
                                </select>
                                <form-error-message :message="$page.props.errors.type" theme="dark" />
                            </div>

                            <div
                                class="w-full first-letter:flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                            >
                                <p class="text-sm text-gray-500">
                                    Academic Year
                                </p>
                                <select
                                    v-model="form.academicYear"
                                    class="mt-1 w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                >
                                    <option :value="null">Select A.Y.</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2025-2026">2025-2026</option>
                                    <option value="2026-2027">2026-2027</option>
                                    <option value="2027-2028">2027-2028</option>
                                    <option value="2028-2029">2028-2029</option>
                                    <option value="2029-2030">2029-2030</option>
                                </select>
                                <form-error-message :message="$page.props.errors.academicYear" theme="dark" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5 mt-5">
                <div class="p-5 rounded-xl shadow-lg border">
                    <content-table>
                        <template v-slot:table-head>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Documents
                            </th>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Existing?
                            </th>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Supporting Document(s)
                            </th>
                            <th class="px-6 py-4 text-right w-24">
                                <span class="text-gray-800">
                                    <i class="fas fa-ellipsis-v text-lg"></i>
                                </span>
                            </th>
                        </template>
                        <template v-slot:table-body>
                            <!-- <tr v-if="application.length == 0">
                            <no-search-result text="applications"/>
                        </tr> -->
                            <tr
                                v-for="(item, index) in application.item"
                                :key="item.id"
                                class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                                :class="{ 'bg-slate-200': index % 2 == 1 }"
                            >
                                <td class="px-6 py-2 text-base text-gray-900">
                                    <div
                                        v-html="
                                            item.gpr.no + '. ' + item.gpr.item
                                        "
                                    ></div>
                                </td>
                                <td class="px-6 py-2 text-base text-gray-900">
                                    <select
                                        v-model="form.items[index].isExisting"
                                        id="show"
                                        class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option :value="null">?</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </td>
                                <td class="px-6 py-2 text-base text-gray-900">
                                    {{ item.supporting_document || "-" }}
                                </td>
                                <td class="px-6 py-2 whitespace-nowrap">
                                    <button
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span class="flex items-center">
                                            <i class="fas fa-plus mr-2"></i>

                                            Add Link
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>

    <confirmation
    :showModal="confirmSubmitModal"
    @close="toggleConfirmSubmitModal"
    title="Submit Application"
    width="md"
    height="short"
    >
    <template v-slot:message>
        <div class="text-gray-700">
            Are you sure you want to submit this application? Once submitted, you will not be able to edit the application.
        </div>
    </template>
    <template v-slot:buttons>
        <button
            @click="submit"
            class="px-4 py-2.5 text-white bg-blue-600 rounded-lg hover:bg-blue-700"
        >
        Confirm
        </button>
    </template>
    </confirmation>

    <!-- <pre>
        {{ application }}
    </pre> -->

</template>

<script setup>
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps(["application", "programs"]);
const confirmSubmitModal = ref(false);

function toggleConfirmSubmitModal() {
    confirmSubmitModal.value = !confirmSubmitModal.value;
}

const form = reactive({
    id: props.application.id,
    institution: ref(props.application.institutionId),
    program: ref(props.application.programId),
    type: ref(props.application.type),
    academicYear: ref(props.application.academicYear),
    items: ref(props.application.item),
});

function update() {
    router.post("/application/update", form, {
        preserveScroll: true,
    });
}

function submit() {
    router.post("/application/submit", form, {
        onError: () => {
            toggleConfirmSubmitModal();
        },
        onSuccess: () => {
            toggleConfirmSubmitModal();
        },
    });
}

</script>

<script>
import Layout from "@/Shared/Layout.vue";
import { reactive } from "vue";

export default {
    layout: Layout,
};
</script>
