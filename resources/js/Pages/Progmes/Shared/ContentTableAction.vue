<template>
    <div @mouseleave="hideActionPanel">
        <button class="py-2 px-4 rounded-full hover:bg-gray-300" @click="showActionPanel">
            <i class="fas fa-ellipsis-v cursor-pointer"></i>
        </button>
        <div class="w-72 text-gray-600 absolute right-0 bg-white  shadow-2xl z-10 rounded border border-gray-400 py-2 text-start text-base"
            v-if="showOptions">
            {{ ev }}
            <div class=" flex flex-col py-2 border-gray-400" :class="{ 'border-b': !canCreate }">
                <Link :href="'/admin/form/' + item.evaluation.id + '/view'" v-if="canView"
                    class="pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400">
                <i class="fas fa-eye w-7"></i>
                View
                </Link>
                <Link href="" v-if="canEdit" class="pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400">
                <i class="fas fa-edit w-7"></i>
                Edit
                </Link>
                <button v-if="canCreate" @click="create()"
                    class="pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400 text-start">
                    <i class="fas fa-plus w-7"></i>
                    Create form
                </button>
                <Link href="" v-if="canUnlock" class="pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400">
                <i class="fas fa-unlock w-7"></i>
                Unlock
                </Link>
                <Link href="" v-if="canLock" class="pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400">
                <i class="fas fa-lock w-7"></i>
                Lock
                </Link>
            </div>
            <div v-if="!canCreate" class="flex flex-col py-2">
                <Link href="" v-if="canArchive" class=" pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400">
                <i class="fas fa-archive w-7"></i>
                Archive
                </Link>
                <Link href="" v-if="canDelete" class="pl-5 py-1 px-3 hover:bg-gray-300 active:bg-gray-400">
                <i class="fas fa-trash w-7"></i>
                Delete
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>

defineProps([
    'item',
    'canView',
    'canEdit',
    'canCreate',
    'canLock',
    'canUnlock',
    'canArchive',
    'canDelete',
    'viewLink',
    'ev',
]);

</script>

<script>
export default {
    data() {
        return {
            showOptions: false,
        }
    },

    methods: {
        showActionPanel() {
            this.showOptions = true;
        },
        // hideActionPanel() {
        //     this.showOptions = false;
        // },

        hideActionPanel(event) {
            event.stopPropagation();
            this.showOptions = false;
        },

        create() {
            this.$emit('create');
        }
    }

}
</script>