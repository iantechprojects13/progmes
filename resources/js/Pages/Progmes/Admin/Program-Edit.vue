<template>

    <Head title="Edit Program" />
    <AdminPanel />
    <content-container pageTitle="Update Program" :hasBackButton="true">
        <template v-slot:back-button>
            <Link href="/admin/program">
            <button class="w-10 h-10 mr-2 rounded-full hover:bg-gray-300 tooltipForActions" data-tooltip="Back">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:main-content>
            <div class="p-5 w-full md:max-w-md md:shadow md:shadow-gray-600 rounded mx-auto md:my-10">
                <div>
                    <span class="text-red-500">*</span>
                    <span class="italic text-sm"> indicates required field</span>
                </div>
                <div class="mt-3">
                    <label for="discipline" class="font-bold text-gray-600">Discipline</label>
                    <span class="text-red-500">*</span>
                    <select v-model="form.discipline" id="discipline"
                        class="rounded text-sm border-gray-400 w-full my-0.5"
                        :class="[{ 'text-gray-500': form.discipline == null }, { 'text-gray-700': form.discipline }]">
                        <option :value="null">Select discipline</option>
                        <option v-for="item in props.discipline_list" :key="item.id" :value="item.id"
                            class="text-black">
                            {{ item.discipline }}
                        </option>
                    </select>
                    <FormErrorMessage :message="$page.props.errors.discipline" theme="dark" />
                </div>

                <div class="mt-3">
                    <label for="program" class="font-bold text-gray-600">Program</label>
                    <span class="text-red-500">*</span>
                    <input v-model="form.program" type="text" id="program"
                        class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500"
                        placeholder="Program">
                    <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
                </div>
                <div class="mt-3">
                    <label for="major" class="font-bold text-gray-600">Major</label>
                    <input v-model="form.major" type="text" id="major"
                        class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500" placeholder="Major">
                </div>
                <div class="mt-5">
                    <button :disabled="processing" @click="submit"
                        class="rounded w-full text-white bg-blue-500 hover:bg-blue-600 py-2 px-4">
                        <span v-if="processing"><i class="fas fa-spinner animate-spin"></i></span>
                        <span v-else>Update</span>
                    </button>
                </div>
            </div>
        </template>
    </content-container>
    <pre>
        {{ program }}
    </pre>
</template>

<script setup>
    import { useForm, router } from '@inertiajs/vue3';
    import { ref, reactive } from 'vue';

    const props = defineProps([
        'program',
        'discipline_list',
    ]);

    const processing = ref(false);


    const form = reactive({
        id: ref(props.program.id),
        discipline: ref(props.program.disciplineId) ? ref(props.program.disciplineId) : null,
        program: ref(props.program.program) ? ref(props.program.program) : null,
        major: ref(props.program.major) ? ref(props.program.major) : null,
    })

    function submit() {
        router.post('/admin/program/update', form, {
            onStart: () => {
                processing.value = true;
            },
            onFinish: () => {
                processing.value = false;
            }
        });
    }

</script>

<script>
    import FormErrorMessage from '../Shared/FormErrorMessage.vue';
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
        data() {
            return {
                openCreateDropdown: false,
            };
        },
        components: {
            FormErrorMessage,
        },
    }
</script>