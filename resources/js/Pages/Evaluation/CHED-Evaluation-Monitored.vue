<template>

    <Head title="Program Evaluation Monitored List" />
    <page-title title="Program Evaluation" />
    <content-container hasSearch="true" pageTitle="Monitored Programs" page="monitored" :hasNavigation="true" :hasFilters="true" :data_list="complianceTools">
        <template v-slot:navigation>
            <main-content-nav
                btnText="Evaluation"
                routeName="evaluation.ched"
            />
            <main-content-nav
                btnText="Monitored"
                routeName="evaluation.ched.monitored"
                :isActive="true"
            />
        </template>
        <template v-slot:search>
            <search-box v-model="query.search" @submit="filter" />
        </template>
        <template v-slot:options>
            <options @filter="toggleFilterModal" href="/ched/evaluation/monitored" />
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Program/Institution</th>
                    <th>Monitoring/Evaluation Date</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <no-search-result text="evaluation tool"/>
                    </tr>
                    <tr v-else v-for="item in complianceTools.data" :key="item.id">
                        <td>
                            <div class="flex flex-col">
                                {{ item.program }}
                                <span v-if="item.major != null">
                                    - {{ item.major }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ item.institution }}
                            </div>
                        </td>
                        <td>
                            {{ item.evaluationDate }}
                        </td>
                        <td>
                            <action-button type="view" @click="view(item.id)" />
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        width="md"
        title="Filters"
    >
        <div class="flex flex-col space-y-4">
            <filter-institution v-model="query.institution" :data="institution_list" />
            <filter-program v-model="query.program" :data="program_list" />
            <filter-academic-year v-model="query.academicYear" />
            <filter-page-items v-model="query.show" />
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter" />
        </template>
    </modal>
</template>

<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });

const props = defineProps([
    'complianceTools',
    'program_list',
    'institution_list',
    'filters',
]);

const showFilterModal = ref(false);

const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
    academicYear: props.filters.academicYear != null ? props.filters.academicYear : null,
    institution: props.filters.institution != null ? props.filters.institution : null,
    program: props.filters.program != null ? props.filters.program : null,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function view(tool) {
    router.get('/ched/evaluation/' + tool + '/view');
}



function filter() {
    query.get("/ched/evaluation/monitored", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}


</script>