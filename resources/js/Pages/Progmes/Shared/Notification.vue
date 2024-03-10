<template>
    <div v-show="message != null" class="w-full">
        <div class="bg-stone-800 text-white fixed p-5 md:rounded-lg -bottom-20 md:left-auto md:right-3 md:w-max w-full left-0 right-0 md:max-w-md"
            :class="{ 'show md:-translate-y-3': openNotification, 'hide': !openNotification }">
            <div class="flex justify-between items-center text-sm">
                <div class="mr-3">
                    <div v-show="type == 'success'"><i class="fas fa-check-circle text-xl text-green-400"></i></div>
                    <div v-show="type == 'failed'"><i class="fas fa-times-circle text-xl text-red-400"></i></div>
                </div>
                <div>
                    {{ message }}
                </div>
                <div>
                    <button class="ml-5 hover:underline text-sm" @click="closeNotification">
                        <!-- <i class="fas fa-close"></i> -->
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                openNotification: true,
            }
        },

        props: {
            message: String,
            type: String,
        },

        methods: {
            closeNotification() {
                this.openNotification = false;

            }
        }
    }
</script>

<style scoped>
    @keyframes show {
        0% {
            bottom: -0.75rem;
            opacity: 0;
            display: block;
        }

        2% {
            bottom: 0rem;
            opacity: 1;
        }

        98% {
            bottom: 0rem;
            opacity: 1;
        }

        100% {
            bottom: -0.75rem;
            opacity: 0;
            display: none;
        }
    }

    @keyframes hide {
        0% {
            bottom: 0rem;
            opacity: 1;
        }

        100% {
            bottom: -0.75rem;
            opacity: 0;
            display: none;
        }
    }

    .show {
        animation: show 6s forwards;
    }

    .hide {
        animation: hide 150ms forwards;
    }
</style>