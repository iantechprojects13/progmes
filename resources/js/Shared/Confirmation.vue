<template>
    <div
        @mousedown.self="handleBackdropClick"
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
                    class=" select-none px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                >
                    Cancel
                </button>

                
                <button
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
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            document.body.style.overflow = "hidden";
            document.body.style.paddingRight = `${scrollbarWidth}px`;
        } else {
            document.body.style.overflow = "auto";
            document.body.style.paddingRight = "0px";
        }
    }
);

function submit(link) {
    router.get(link, props.selected, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

const handleBackdropClick = () => {
    emit("close");
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
