
<template>
    <Head title="Team Pricing" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">System-wide parts</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-gray-900 font-bold text-lg">List of system-wide parts</div>
                        <div class="p-6 flex gap-2" v-if="allowUpload">
                            <SecondaryButton @click="exportCSV($event)">
                                <i class="pi pi-download"></i>
                                <span class="ml-2">Download</span>
                            </SecondaryButton>
                            <Link :href="route('team_pricing_upload')">
                                <PrimaryButton>
                                    <i class="pi pi-upload"></i>
                                    <span class="ml-2">Upload</span>
                                </PrimaryButton>
                            </Link>
                        </div>
                    </div>
                    <DataTable v-model:filters="filters" 
                            :value="teamPricing" 
                            paginator 
                            showGridlines 
                            :rows="10" 
                            dataKey="id"
                            ref="dt"
                            filterDisplay="menu" 
                            :globalFilterFields="['part_type', 'manufacturer', 'model_number', 'list_price', 'multipler', 'static_price', 'team_price']"
                    >
                        <template #header>
                            <div class="flex justify-between">
                                <button type="button" label="Clear" outlined @click="clearFilter()">
                                    <i class="pi pi-filter-slash"></i> Clear Search
                                </button>
                                <IconField>
                                    <InputIcon class="mr-2">
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <input v-model="filters['global'].value" placeholder="Keyword Search" />
                                </IconField>
                            </div>
                        </template>
                        <template #empty> No system-wide parts found. </template>
                        <template #loading> Loading system-wide parts data. Please wait. </template>
                        <Column field="part_type" header="Part Type" style="min-width: 12rem">
                            <template #body="{ data }">
                                {{ data.part_type }}
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by part type" />
                            </template>
                        </Column>
                        <Column field="manufacturer" header="Manufacturer" style="min-width: 12rem">
                            <template #body="{ data }">
                                {{ data.manufacturer }}
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by manufacturer" />
                            </template>
                        </Column>
                        <Column field="model_number" header="Model Number" style="min-width: 12rem">
                            <template #body="{ data }">
                                {{ data.model_number }}
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by model number" />
                            </template>
                        </Column>
                        <Column field="list_price" header="List Price" style="min-width: 12rem">
                            <template #body="{ data }">
                                <div class="text-right">{{ data.list_price }}</div>
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by list price" />
                            </template>
                        </Column>
                        <Column field="multiplier" header="Multiplier" style="min-width: 12rem">
                            <template #body="{ data }">
                                <div class="text-right">{{ data.multiplier ? data.multiplier : '' }}</div>
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by list multiplier" />
                            </template>
                        </Column>
                        <Column field="static_price" header="Static Price" style="min-width: 12rem">
                            <template #body="{ data }">
                                <div class="text-right">{{ data.static_price > 0 ? data.static_price : ''  }}</div>
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by list static price" />
                            </template>
                        </Column>
                        <Column field="team_price" header="Team Price" style="min-width: 12rem">
                            <template #body="{ data }">
                                <div class="text-right">{{ data.team_price > 0 ? formatNumber(data.team_price, 2, 10) : ''  }}</div>
                            </template>
                            <template #filter="{ filterModel }">
                                <input v-model="filterModel.value" type="text" placeholder="Search by list static price" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

defineProps({
    teamPricing: Object,
    allowUpload: Boolean,
});

function formatNumber(number, min = 2, max = 2){
    return number.toLocaleString(undefined, {
        minimumFractionDigits: min,
        maximumFractionDigits: max}
    )
}

const filters = ref();

const dt = ref();

const exportCSV = () => {
    dt.value.exportCSV();
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        part_type: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        manufacturer: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        model_number: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        list_price: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        multiplier: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        static_price: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};
</script>
