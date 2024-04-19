<template>
    <div class="fixed top-0 left-0 w-full h-screen bg-black bg-opacity-70 z-100 p-2 flex items-center overflow-y-auto"
        @click.self="bgClicked">
        <div class="bg-white w-full mx-auto max-w-lg bg-opacity-100 rounded-md shadow-md text-base shadow-gray-500 md:mb-48 border border-gray-300"
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
                <div v-if="modaltype == 'reject'">
                    Are you sure you want to reject <b>{{ selected.name }} </b>'s registration?
                </div>
                <div v-if="modaltype == 'accept'">
                    Are you sure you want to accept <b>{{ selected.name }} </b>'s registration?
                </div>
                <div v-if="modaltype == 'deactivateUser'">
                    Are you sure you want to deactivate <b>{{ selected.name }} </b>'s account?
                </div>
                <div v-if="modaltype == 'activateUser'">
                    Are you sure you want to activate <b>{{ selected.name }} </b>'s account?
                </div>
                <div v-if="modaltype == 'deleteUser'">
                    Are you sure you want to delete <b>{{ selected.name }} </b>'s account?
                </div>
                <div v-if="modaltype == 'deleteCMO'">
                    Are you sure you want to delete this CMO? This action can't be undone.
                </div>
                <div v-if="modaltype == 'publish'">
                    Are you sure you want to publish this CMO? Once published, the document cannot be edited further.
                </div>
                <div v-if="modaltype == 'activate'">
                    Are you sure you want to make
                    <b>CMO No.{{ selected.number }} - Series of {{ selected.series }} - Version {{
                        selected.version }}</b>
                    as active CMO for <b>{{ selected.program.program }}</b> program?
                </div>
                <div v-if="modaltype == 'deactivate'">
                    Are you sure you want to remove the activation from this CMO?
                </div>
                <div v-if="modaltype == 'deleteHEI'">
                    Are you sure you want to delete <b>{{ selected.name }}</b>? This action can't be undone.
                </div>
                <div v-if="modaltype == 'deleteProgram'">
                    Are you sure you want to delete <b>{{ selected.program }}</b>? This action can't be undone.
                </div>

                <div v-if="modaltype == 'deleteDiscipline'">
                    Are you sure you want to delete <b>{{ selected.discipline }}</b>? This action can't be undone.
                </div>

                <div v-if="modaltype == 'confirmDeleteEvidence'">
                    Are you sure you want to delete this evidence file/link? This action can't be undone.
                </div>
                <slot name="message"></slot>
            </div>
            <div class="py-4 px-5 border-t-2 border-gray-100 text-right">
                <button
                    class="select-none text-gray-700 hover:text-black hover:bg-stone-100 active:text-blue-500 w-20 h-10 rounded border border-gray-300 mr-1"
                    @click="closeModal">
                    Cancel
                </button>

                <button :disabled="processing" v-if="modaltype == 'accept'"
                    @click="submit('/register/'+selected.id+ '/accept')"
                    class="select-none text-white bg-blue-500 hover:text-white hover:bg-blue-600 h-10 w-20 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Accept</span>
                </button>

                <button :disabled="processing" v-if="modaltype == 'reject'"
                    @click="submit('/register/'+selected.id+ '/reject')"
                    class="select-none text-white bg-red-500 hover:text-white hover:bg-red-600 h-10 w-20 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Reject</span>
                </button>

                <button :disabled="processing" v-if="modaltype == 'deleteUser'"
                    @click="submit('/register/'+selected.id+ '/delete')"
                    class="select-none text-white bg-red-500 hover:text-white hover:bg-red-600 h-10 w-20 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Delete</span>
                </button>

                <button :disabled="processing" v-if="modaltype == 'activateUser'"
                    @click="submit('/register/'+selected.id+ '/activate')"
                    class="select-none text-white bg-blue-500 hover:text-white hover:bg-blue-600 h-10 w-20 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Activate</span>
                </button>


                <button :disabled="processing" v-if="modaltype == 'deactivateUser'"
                    @click="submit('/register/'+selected.id+ '/deactivate')"
                    class="select-none text-white bg-red-500 hover:text-white hover:bg-red-600 h-10 w-24 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Deactivate</span>
                </button>


                <button :disabled="processing" v-if="modaltype == 'deleteProgram'"
                    @click="submit('/admin/program/'+selected.id+ '/delete')"
                    class="select-none text-white bg-red-500 hover:text-white hover:bg-red-600 h-10 w-24 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Delete</span>
                </button>


                <button :disabled="processing" v-if="modaltype == 'deleteDiscipline'"
                    @click="submit('/admin/discipline/'+selected.id+ '/delete')"
                    class="select-none text-white bg-red-500 hover:text-white hover:bg-red-600 h-10 w-24 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Delete</span>
                </button>


                <button :disabled="processing" v-if="modaltype == 'deleteHEI'"
                    @click="submit('/admin/higher-education-institutions/'+selected.id+ '/delete')"
                    class="select-none text-white bg-red-500 hover:text-white hover:bg-red-600 h-10 w-24 rounded border">
                    <span v-if="processing">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Delete</span>
                </button>


                <Link method="get" v-if="modaltype == 'deleteCMO'" @click="closeModal"
                    :href="'/admin/CMOs/delete/' + selected"
                    class=" text-white bg-red-500 hover:text-white hover:bg-red-600 p-2.5 px-3 rounded border cursor-pointer"
                    preserve-scroll>
                Delete
                </Link>

                <Link method="get" v-if="modaltype == 'publish'" :href="'/admin/CMOs/publish/' + selected"
                    @class="closeModal"
                    class=" text-white bg-blue-500 hover:text-white hover:bg-blue-600 p-2.5 px-3 rounded border cursor-pointer"
                    preserve-scroll>
                Publish
                </Link>
                <Link method="get" v-if="modaltype == 'activate'" :href="'/admin/CMOs/activate/' + selected.id"
                    @click="closeModal"
                    class=" text-white bg-blue-500 hover:text-white hover:bg-blue-600 p-2.5 px-3 rounded border cursor-pointer"
                    preserve-scroll>
                Confirm
                </Link>
                <Link method="get" v-if="modaltype == 'deactivate'" :href="'/admin/CMOs/deactivate/' + selected.id"
                    @click="closeModal"
                    class=" text-white bg-blue-500 hover:text-white hover:bg-blue-600 p-2.5 px-3 rounded border cursor-pointer"
                    preserve-scroll>
                Confirm
                </Link>
                <slot name="buttons"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import { ref } from 'vue';

    const props = defineProps({
        'title': String,
        'modaltype': String,
        'selected': Object,
    });

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
            replace: true,
        });
    }

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