<template>
    <div>
        <div class="flex bg-gray-300 mb-2" v-for="(user, index) in list" :key="index">
            <div class="mr-3">{{ user.name }}</div>
            <button @click="openModal(user.name)">Open Model</button>
        </div>
    </div>
    <Notification :message="$page.props.flash.success" />
    <Notification :message="$page.props.flash.error" />
    <modal v-show="open" @close="openModal">
        {{ form.name }}
        <button class="bg-yellow-500 p-1 m-1" @click="submit()">Update</button>
    </modal>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    id: null,
    name: null,
});

const open = ref(false);

defineProps([
    'list',
]);

function openModal(name) {
    form.name = name;
    open.value = !open.value;
}

function submit() {
    form.post('/hei/tool/update', form);
}


</script>