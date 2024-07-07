<template>
    <div :class="[{'visible' : showModal}, {'invisible': !showModal}]" ref="modalContainer"
        class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-75 z-100 p-2 overflow-y-auto select-none transition-all duration-300"
        @click.self="bgClicked">
        <div class="bg-white w-full mx-auto max-w-lg rounded-md shadow-md md:mb-24 select-none transition-all duration-300"
        :class="[{ 'max-w-sm': width == 'sm' }, { ' max-w-md': width == 'md' }, { 'max-w-lg': width == 'lg' }, { ' max-w-xl': width == 'xl' },
                        {'max-w-2xl': width == '2xl'}, {'max-w-3xl': width == '3xl'}, {'max-w-4xl': width == '4xl'},
                        {' translate-y-24 opacity-100': showModal && height == 'short'}, {'-translate-y-24 opacity-0': !showModal && height == 'short'},
                        {' translate-y-10 opacity-100' : showModal && height == 'long'}, {'-translate-y-10 opacity-0': !showModal && height == 'long'}
                ]">
            <div class="border-b-2 border-gray-100 p-3 px-5 flex justify-between items-center text-lg">
                <div class="font-bold">
                    {{ title }}
                </div>
                <div>
                    <button @click="closeModal" class="hover:bg-gray-200 rounded-full relative left-2">
                        <i class="fas fa-times cursor-pointer text-gray-700 py-2.5 px-3 hover:text-black"></i>
                    </button>
                </div>
            </div>
            <div class="py-4 pb-7 px-5 text-gray-700">
                <slot></slot>
            </div>
            <div class="py-4 px-5 border-t-2 border-gray-100 text-right">
                <button
                    class="select-none text-gray-700 hover:text-black hover:bg-stone-200 h-10 w-20 rounded border border-gray-400 mr-1"
                    @click="closeModal">
                    Cancel
                </button>
                <button v-if="type == 'upload'" @click="upload"
                    class="select-none text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
                    Upload
                </button>
                <button v-if="type == 'import'" @click="upload"
                    class="select-none text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
                    Import
                </button>
                <Link v-if="type == 'create'" @click="save"
                    class="select-none text-white bg-blue-500 hover:bg-blue-600 py-2 px-3 rounded border">
                Create
                </Link>
                <button v-if="type == 'uploadEvidenceFile'" @click="upload"
                    class="select-none text-white bg-blue-600 hover:bg-blue-700 py-2 px-3 rounded border">
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
        'width',
        'height',
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
                cont.classList.add('animate-pulse');
                setTimeout(() => {
                    cont.classList.remove('animate-pulse');
                }, 200);
            }
        }
    }
</script>