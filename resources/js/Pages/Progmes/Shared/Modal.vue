<template>
    <div v-show="showModal"
        class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-70 z-100 p-2 flex items-center overflow-y-auto select-none"
        @click.self="bgClicked">
        <div class="bg-white w-full mx-auto max-w-md rounded-md shadow-md shadow-gray-500 md:mb-24 opacity-100 select-none"
            ref="modalContainer">
            <div class="border-b-2 border-gray-100 p-3 px-5 flex justify-between items-center text-lg">
                <div class="font-bold">
                    {{ title }}
                </div>
                <div>
                    <button @click="closeModal" class="hover:bg-gray-200 rounded-full relative left-2">
                        <i class="fas fa-times cursor-pointer text-gray-400 py-2.5 px-3 hover:text-black"></i>
                    </button>
                </div>
            </div>
            <div class="py-4 pb-7 px-5 text-gray-700">
                <slot></slot>
            </div>
            <div class="py-4 px-5 border-t-2 border-gray-100 text-right">
                <button
                    class=" text-gray-600 hover:text-black hover:bg-stone-200 p-2 px-3 rounded border border-gray-400 mr-1"
                    @click="closeModal">
                    Cancel
                </button>
                <button v-if="type == 'upload'" @click="upload"
                    class="text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
                    Upload
                </button>
                <button v-if="type == 'import'" @click="upload"
                    class="text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
                    Import
                </button>
                <button v-if="type == 'deploy'" @click="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
                    Deploy
                </button>
                <Link v-if="type == 'create'" @click="save"
                    class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-3 rounded border">
                Create
                </Link>
                <button v-if="type == 'uploadEvidenceFile'" @click="upload"
                    class="text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
                    Upload
                </button>
                <slot name="custom-button"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>

    const props = defineProps([
        'title',
        'type',
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
                this.$emit('submit');
            },

            upload() {
                this.$emit('upload');
            },

            save() {
                this.$emit('create');
            },

            bgClicked() {
                let cont = this.$refs.modalContainer;
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