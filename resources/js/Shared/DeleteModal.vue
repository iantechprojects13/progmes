<template>
    <div v-show="showModal"
        class="fixed h-screen top-0 left-0 w-full bg-black bg-opacity-70 z-100 flex items-center overflow-y-auto"
        @click.self="bgClicked">
        <div class=" bg-white w-full mx-auto max-w-lg bg-opacity-100 rounded-md shadow-md text-sm shadow-gray-500 md:mb-48 border border-gray-300"
            ref="confirmationModalContainer">
            <div class="border-b-2 border-gray-100 p-3 px-5 flex justify-between text-base">
                <div class="font-bold">
                    {{ title }}
                </div>
                <div>
                    <button @click="closeModal" class="hover:bg-gray-200">
                        <i class="fas fa-times cursor-pointer text-gray-400 px-1.5 hover:text-black"></i>
                    </button>
                </div>
            </div>
            <div class="py-4 pb-7 px-7 text-black text-base">
                <slot name="message"></slot>
            </div>
            <div class="py-4 px-5 border-t-2 border-gray-100 text-right">
                <button class="select-none text-gray-500 hover:text-black h-10 w-16 rounded border border-gray-300 mr-1"
                    @click="closeModal">
                    Cancel
                </button>
                <slot name="buttons"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>

    const props = defineProps([
        'title',
        'showModal',
    ]);

    watch(() => props.showModal, (newVal) => {
    if (newVal) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = 'auto'
    }
});


</script>

<script>
    export default {
        methods: {
            closeModal() {
                this.$emit('close')
            },

            handleSubmit() {
                this.$emit('submit')
            },

            bgClicked() {
                let cont = this.$refs.confirmationModalContainer;
                cont.classList.add('animate-shake');
                setTimeout(() => {
                    cont.classList.remove('animate-shake');
                }, 200);
            }
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