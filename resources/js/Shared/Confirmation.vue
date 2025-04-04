<template>
    <div
        @click.self="handleBackdropClick"
        :class="[
            { 'opacity-100': showModal },
            { 'opacity-0 pointer-events-none': !showModal },
        ]"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[994] transition-opacity duration-300 flex items-center justify-center px-3"
    >
        <div
            class="min-h-[200px] w-full mx-auto bg-white rounded-lg shadow-xl transition-all duration-300"
            :class="[
                modalWidth,
                { 'translate-y-0 opacity-100': showModal },
                { 'translate-y-8 opacity-0': !showModal },
            ]"
        >
            <div
                class="flex items-center justify-between px-6 py-4 border-b border-gray-100"
            >
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ title }}
                </h3>
                <button
                    @click="closeModal"
                    class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
                >
                    <svg
                        class="w-5 h-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
            <div class="py-4 pb-7 px-7 text-black text-base">
                <div v-if="modaltype == 'reject'">
                    Are you sure you want to reject
                    <b>{{ selected.name }} </b>'s registration?
                </div>
                <div v-if="modaltype == 'accept'">
                    Are you sure you want to accept
                    <b>{{ selected.name }} </b>'s registration?
                </div>
                <div v-if="modaltype == 'deactivateUser'">
                    Are you sure you want to deactivate
                    <b>{{ selected.name }} </b>'s account?
                </div>
                <div v-if="modaltype == 'activateUser'">
                    Are you sure you want to activate
                    <b>{{ selected.name }} </b>'s account?
                </div>
                <div v-if="modaltype == 'deleteUser'">
                    Are you sure you want to delete
                    <b>{{ selected.name }} </b>'s account?
                </div>
                <div v-if="modaltype == 'deleteCMO'">
                    Are you sure you want to delete this CMO? This action can't
                    be undone.
                </div>
                <div v-if="modaltype == 'publish'">
                    Are you sure you want to publish this CMO? Once published,
                    the document cannot be edited further.
                </div>
                <div v-if="modaltype == 'activate'">
                    Are you sure you want to make
                    <b
                        >CMO No.{{ selected.number }} - Series of
                        {{ selected.series }} - Version
                        {{ selected.version }}</b
                    >
                    as active CMO for
                    <b
                        >{{ selected.program.program
                        }}<span v-if="selected.program.major != null">
                            - {{ selected.program.major }}</span
                        ></b
                    >
                    program?
                </div>
                <div v-if="modaltype == 'deactivate'">
                    Are you sure you want to remove the activation from this
                    CMO?
                </div>
                <div v-if="modaltype == 'deleteHEI'">
                    Are you sure you want to delete <b>{{ selected.name }}</b
                    >? This action can't be undone.
                </div>
                <div v-if="modaltype == 'deleteProgram'">
                    Are you sure you want to delete
                    <b
                        >{{ selected.program
                        }}<span v-if="selected.major != null">
                            - {{ selected.major }}</span
                        ></b
                    >? This action can't be undone.
                </div>

                <div v-if="modaltype == 'deleteDiscipline'">
                    Are you sure you want to delete
                    <b>{{ selected.discipline }}</b
                    >? This action can't be undone.
                </div>

                <div v-if="modaltype == 'confirmDeleteEvidence'">
                    Are you sure you want to delete this evidence file/link?
                    This action can't be undone.
                </div>

                <div v-if="modaltype == 'deleteApplication'">
                    Are you sure you want to delete this program application form?
                </div>

                <slot name="message"></slot>
            </div>
            <div
                class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100"
            >
                <button
                    @click="closeModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                >
                    Cancel
                </button>
                
                <!-- Accept Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'accept'"
                    @click="submit('/register/' + selected.id + '/accept')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20"
                >
                    Accept
                </button>

                <!-- Reject Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'reject'"
                    @click="submit('/register/' + selected.id + '/reject')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-20"
                >
                    Reject
                </button>

                <!-- Delete User Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'deleteUser'"
                    @click="submit('/register/' + selected.id + '/delete')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-20"
                >
                    Delete
                </button>

                <!-- Activate User Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'activateUser'"
                    @click="submit('/register/' + selected.id + '/activate')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20"
                >
                    Activate
                </button>

                <!-- Deactivate User Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'deactivateUser'"
                    @click="submit('/register/' + selected.id + '/deactivate')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-24"
                >
                    Deactivate
                </button>

                <!-- Delete Program Button -->
                <button preserve-scroll
                    :disabled="processing"
                    v-if="modaltype == 'deleteProgram'"
                    @click="submit('/admin/program/' + selected.id + '/delete')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-24"
                >
                    Delete
                </button>

                <!-- Delete Discipline Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'deleteDiscipline'"
                    @click="
                        submit('/admin/discipline/' + selected.id + '/delete')
                    "
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-24"
                >
                    Delete
                </button>

                <!-- Delete HEI Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'deleteHEI'"
                    @click="
                        submit(
                            '/admin/higher-education-institutions/' +
                                selected.id +
                                '/delete'
                        )
                    "
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-24"
                >
                    Delete
                </button>

                <!-- Delete CMO Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'deleteCMO'"
                    @click="submit('/admin/CMOs/delete/' + selected.id)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 h-10 w-24"
                >
                    Delete
                </button>

                <!-- Publish Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'publish'"
                    @click="submit('/admin/CMOs/publish/' + selected.id)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20"
                >
                    Publish
                </button>

                <!-- Activate Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'activate'"
                    @click="submit('/admin/CMOs/activate/' + selected.id)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20"
                >
                    Confirm
                </button>

                <!-- Deactivate Button -->
                <button
                    :disabled="processing"
                    v-if="modaltype == 'deactivate'"
                    @click="submit('/admin/CMOs/deactivate/' + selected.id)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20"
                >
                    Confirm
                </button>

                <button
                    :disabled="processing"
                    v-if="modaltype == 'deleteApplication'"
                    @click="submit(`/application/${selected.id}/delete`)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20"
                >
                    Delete
                </button>
                <slot name="buttons"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted, onBeforeUnmount } from "vue";

const props = defineProps([
    "showModal",
    "width",
    "title",
    "modaltype",
    "selected",
]);

const emit = defineEmits(["close", "submit"]);

const modalWidth = computed(() => ({
    "max-w-sm": props.width === "sm",
    "max-w-md": props.width === "md",
    "max-w-lg": props.width === "lg",
    "max-w-xl": props.width === "xl",
    "max-w-2xl": props.width === "2xl",
    "max-w-3xl": props.width === "3xl",
    "max-w-4xl": props.width === "4xl",
}));

watch(
    () => props.showModal,
    (newVal) => {
        if (newVal) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "auto";
        }
    }
);

const processing = ref(false);

function submit(link) {
    router.get(link, props.selected, {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

const handleBackdropClick = () => {
    // Optional: Add a subtle feedback animation
    emit("close");
};

const handleSubmit = () => {
    // Optional: Add a subtle feedback animation
    emit("submit");
};

const closeModal = () => {
    emit("close");
};

const handleKeydown = (event) => {
    // Only handle Escape key when modal is open
    if (event.key === 'Escape' && props.showModal) {
      closeModal()
    }
  }

  onMounted(() => {
    window.addEventListener('keydown', handleKeydown)
  })

  onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown)
  })
</script>
