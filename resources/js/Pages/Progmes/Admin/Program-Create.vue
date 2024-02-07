<template>
    <Head title="Program List" />
    <AdminPanel />
    <content-container placeholder="Search program" :data_list="program_list">
        <template v-slot:channel>
            <Link :href="route('admin.program.list')">
            <button class="text-gray-500 hover:text-gray-800 mr-5">List</button>
            </Link>
            <button class="text-blue-500 underline font-bold ">Create</button>
        </template>
        <template v-slot:content>
            <div class="mx-3 border-2 border-gray-300 flex flex-col lg:flex-row text-sm">
                <div class="py-3 px-5 w-full lg:w-1/2 xl:w-2/3 lg:border-r-2 border-gray-300">
                    <div class="text-base font-bold">
                        Add Program
                    </div>
                    <div class="flex flex-col mt-3">
                        <input type="text" placeholder="Input new program" class="w-full lg:w-2/3 rounded text-sm" />
                        <select class="mt-4 w-full lg:w-2/3 text-sm rounded">
                            <option value="">Select discipline</option>
                            <option v-for="discipline in discipline_list" :key="discipline.id" :value="discipline.id">
                                {{ discipline.discipline }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="py-3 px-5 w-full lg:w-1/2 xl:w-1/3">
                    <div class="text-base font-bold">
                        Add Discipline
                    </div>
                    <div class="flex flex-row mt-3 border-b-2 border-gray-300 pb-4">
                        <input v-model="discipline.value" type="text" placeholder="Input new discipline"
                            class="w-full rounded text-sm" />
                        <button class="w-1/5 bg-blue-500 text-white ml-2 h-8 rounded"
                            @click.prevent="addDiscipline">Add</button>
                    </div>
                    <div class="mt-3 font-bold">
                        Discipline List
                    </div>
                    <div class="h-96 overflow-y-auto mt-3">
                        <div v-for="discipline in discipline_list" :key="discipline.id"
                            class="border-b py-2 border-gray-300">
                            {{ discipline.discipline }}
                            <span class="ml-2 cursor-pointer text-gray-400 hover:text-orange-500"><i
                                    class="fas fa-pen"></i></span>
                            <span class="ml-2 cursor-pointer text-gray-400 hover:text-red-500"
                                @click.prevent="deleteDiscipline(discipline)"><i class="fas fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
    <div v-if="$page.props.flash.success">
        <Notification :message="this.$page.props.flash.success" />
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps([
    'discipline_list',
]);

const discipline = useForm({
    value: null,
});

function addDiscipline() {
    discipline.post('/discipline/create', discipline);
}

function deleteDiscipline(id) {
    router.post('/discipline/delete', id)
}

</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>
