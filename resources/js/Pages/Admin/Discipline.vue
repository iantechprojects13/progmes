<template>

    <Head title="Discipline List" />
    <content-container :hasAdminPanel="true" pageTitle="Discipline List" page="discipline" :hasTopButton="canAdd" :hasNavigation="true" :hasSearch="true"
        :hasFilters="true" :data_list="discipline_list">
        <template v-slot:top-button>
            <Link href="/admin/program/discipline/create">
                <button className="inline-flex items-center px-4 py-2.5 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition-colors duration-200 ease-in-out shadow-sm">
                    &plus; Add Discipline
                </button>
              </Link>
        </template>
        <template v-slot:navigation>
            <main-content-nav page="discipline" managementType="program"></main-content-nav>
        </template>
        <template v-slot:search>
            <div class="relative">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                
                <input
                  @keydown.enter="submit"
                  v-model="query.search"
                  type="search"
                  id="content-search"
                  placeholder="Search"
                  class="w-full py-2.5 pl-11 pr-4 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                />
              </div>
        </template>
        <template v-slot:options>
            <div class="flex gap-2">
                <button 
                    @click="toggleFilterModal"
                    class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                    data-tooltip="Filters"
                >
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 ">
                        Filters
                    </span>
                </button>

                <Link href="/admin/program/discipline">
                    <button 
                        class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                        data-tooltip="Refresh page"
                    >
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                  <th class="px-6 py-4 text-left text-base font-bold text-gray-800">
                    Discipline
                  </th>
                  <th class="px-6 py-4 text-right w-24">
                    <span class="text-gray-800">
                      <i class="fas fa-ellipsis-v text-lg"></i>
                    </span>
                  </th>
                </template>
                <template v-slot:table-body>
                  <tr v-if="discipline_list.data.length === 0">
                      <no-search-result text="discipline" />
                  </tr>
            
                  <tr v-else v-for="(discipline, index) in discipline_list.data" 
                      :key="discipline.id"
                      class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                      :class="{'bg-slate-200': index % 2 === 1}">
                    <td class="px-6 py-2 text-base text-gray-900">
                      {{ discipline.discipline }}
                    </td>
                    <td class="px-6 py-2">
                      <div class="flex justify-end gap-0">
                        <button v-if="canEdit"
                                @click="edit(discipline.id)"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 transition-colors tooltipForActions"
                                data-tooltip="Edit">
                          <i class="fas fa-edit text-lg"></i>
                        </button>
                        
                        <button v-if="canDelete"
                                @click="toggleConfirmationModal(discipline, 'deleteDiscipline', 'Delete Discipline')"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 transition-colors tooltipForActions"
                                data-tooltip="Delete">
                          <i class="fas fa-trash text-lg"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </template>
              </content-table>
        </template>
    </content-container>

    <modal 
    :showModal="showFilterModal" title="Filters" 
    @close="toggleFilterModal" 
    width="sm" 
    class="antialiased"
  >
    <div class="space-y-6">
      <div class="flex flex-col space-y-2">
        <label 
          for="show" 
          class="text-sm font-medium text-gray-700"
        >
          Items per page
        </label>
        <select 
          v-model="query.show" 
          id="show" 
          class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="150">150</option>
          <option value="200">200</option>
          <option value="300">300</option>
        </select>
      </div>
    </div>

    <template #custom-button>
      <button 
        @click="filter"
        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
      >Apply
      </button>
    </template>
  </modal>

    <Confirmation :showModal="confirmationModal" @close="closeModal" :title="title" :modaltype="modaltype" :selected="selectedDiscipline" width="md" height="short" />
    

    
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps(['discipline_list', 'canEdit', 'canAdd', 'canDelete', 'filters']);

const showFilterModal = ref(false);
const processing = ref(false);


const query = useForm({
    show: props.filters.show != null ? props.filters.show : null,
    search: props.filters.search,
});

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function submit() {
    query.get("/admin/program/discipline", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

function filter() {
    query.get("/admin/program/discipline", {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
            toggleFilterModal();
        },
        preserveState: true,
        preserveScroll: true,
    });
}


function edit(id) {
    router.get('/admin/program/discipline/' + id + '/edit');
}


</script>

<script>
    import Layout from "@/Shared/Layout.vue";
    export default {
        data() {
            return {
                confirmationModal: false,
                selectedDiscipline: '',
                modaltype: '',
                title: '',
            }
        },
        layout: Layout,
        methods: {
            toggleConfirmationModal(id, type, title) {
                this.confirmationModal = !this.confirmationModal;
                this.selectedDiscipline = id;
                this.modaltype = type;
                this.title = title;
            },

            closeModal() {
                this.confirmationModal = false;
                this.uploadModal = false;
            }
        }
    };
</script>