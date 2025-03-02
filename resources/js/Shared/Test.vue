<template>

    <Head title="Test" />
    <div class="w-full p-10">
        <div class="text-lg font-bold mb-10">ALL PROGRAMS</div>
        <div class="text-left w-full">
            <table class="w-full">
                <thead>
                    <tr class="border border-gray-400">
                        <th class="p-3">ID</th>
                        <th class="p-3">Program</th>
                        <th class="p-3">Major</th>
                        <th class="p-3">Discipline</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(program, index) in programs" :key="program.id" :class="{'bg-gray-300': index % 2 == 0}">
                        <td class="p-3">{{ program.id }}</td>
                        <td class="p-3">{{ program.program }}</td>
                        <td class="p-3">
                            <div v-if="program.major != null">
                                {{ program.major }}
                            </div>
                            <div v-else>
                                -
                            </div>
                        </td>
                        <td class="p-3">
                            {{ program.discipline.discipline }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>

const props = defineProps([
    'programs',
]);

</script>

<script>
import LayoutVue from "./Layout.vue";

export default {
    layout: LayoutVue,
}

</script>