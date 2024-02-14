<template>
    <div class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-70 z-100 p-2 flex items-center overflow-y-auto"
        @click.self="bgClicked">
        <div class="bg-white w-full mx-auto max-w-lg bg-opacity-100 rounded-md shadow-md text-sm shadow-gray-500 md:mb-48 border border-gray-300"
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
            <div class="py-4 pb-7 px-5 text-gray-700">
                <div v-if="modaltype == 'reject'">
                    Are you sure you want to reject <b>{{ selected.name }}</b>?
                </div>
                <div v-else-if="modaltype == 'accept'">
                    Are you sure you want to accept <b>{{ selected.name }}</b>?
                </div>
                <slot></slot>
            </div>
            <div class="py-4 px-5 border-t-2 border-gray-100 text-right">
                <button
                    class=" text-gray-500 hover:text-black hover:bg-stone-200 p-2 px-3 rounded border border-stone-300 mr-1"
                    @click="closeModal">
                    Cancel
                </button>
                <Link v-if="modaltype == 'accept'" @click="handleSubmit" :href="route('register.accept', [selected])"
                    class=" text-gray-100 bg-green-500 hover:text-white hover:bg-green-600 p-2.5 px-3 rounded border"
                    preserve-scroll>
                Accept
                </Link>
                <Link v-if="modaltype == 'reject'" @click="handleSubmit" :href="route('register.reject', [selected])"
                    class=" text-gray-100 bg-red-500 hover:text-white hover:bg-red-600 p-2.5 px-3 rounded border"
                    preserve-scroll>
                Reject
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    'title': String,
    'message': String,
    'modaltype': String,
    'selected': Object,
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