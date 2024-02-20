<template>
    <div>
        <button type="submit" :disabled="buttonDisabled"
            class="w-32 py-2 px-3 mx-auto bg-blue-500 hover:bg-blue-600 text-white rounded" @click="buttonDisabled = true"
            :class="{ 'hover:bg-blue-500': buttonDisabled }">
            <span v-if="!buttonDisabled">
                {{ text }}
            </span>
            <span v-else>
                <i class="fas fa-spinner animate-spin"></i>
            </span>
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            buttonDisabled: false,
        }
    },

    props: {
        text: String,
    },

    disableButton() {
        this.buttonDisabled = true;
    }
}
</script>