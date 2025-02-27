<template>
    <Link :href="route('admin.users.list')">
        <button class="select-none h-12 w-24" :class="[{'text-blue-500 border-b-4 relative font-bold border-blue-500': page == 'list'}, {'hover:bg-gray-100 text-gray-700 hover:text-black': page != 'list'}]">
            Active
        </button>
        </Link>
    <Link :href="route('admin.users.request')">
    <button class="select-none h-12 w-24" :class="[{'text-blue-500 border-b-4 relative font-bold border-blue-500': page == 'request'}, {'hover:bg-gray-100 text-gray-700 hover:text-black': page != 'request'}]">
        Request <span v-show="requestCount != 0" class="relative bottom-2 right-1 text-red-500">{{ requestCount}}</span>
    </button>
    </Link>
    <Link :href="route('admin.users.inactive')" v-show="showInactive">
    <button class="select-none h-12 w-24" :class="[{'text-blue-500 border-b-4 relative font-bold border-blue-500': page == 'inactive'}, {'hover:bg-gray-100 text-gray-700 hover:text-black': page != 'inactive'}]">
        Inactive
    </button>
    </Link>

</template>


<script setup>

const props = defineProps([
    'requestCount',
    'showInactive',
    'page',
])
</script>

