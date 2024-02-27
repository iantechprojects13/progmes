<template>
    <div v-show="showModal"
        class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-70 z-100 flex items-center md:px-3 overflow-y-auto select-none"
        @click.self="bgClicked">
        <div class="md:bg-white bg-gray-300  flex flex-col justify-center w-full mx-auto max-w-3xl md:rounded shadow-md shadow-gray-500 opacity-100 md:h-auto h-full"
            ref="modalContainer">
            <div class="p-3 text-gray-700">
                <div class="mb-2 font-bold">{{ title }}</div>
                <slot></slot>
            </div>
            <div class="p-3 text-right">
                <button class=" bg-blue-500 text-white hover:bg-blue-600 p-2 px-3 rounded border border-gray-400 mr-1"
                    @click="closeModal">
                    Done
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps([
    'title',
    'width',
    'showModal',
]);

</script>

<script>
export default {
    methods: {
        closeModal() {
            this.$emit('close')
        },

        submit() {
            this.$emit('submit')
        },

        bgClicked() {
            let cont = this.$refs.modalContainer;
            cont.classList.add('animate-shake');
            setTimeout(() => {
                cont.classList.remove('animate-shake');
            }, 200);
        },
    }
}
</script>

<style scoped>
@keyframes shake {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

.animate-shake {
    animation: shake 200ms 1;
}
</style>