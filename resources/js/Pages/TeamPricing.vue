<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import 'primevue/resources/themes/aura-light-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';   

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

defineProps({
    teamPricing: Object,
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Team Pricing</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-gray-900 font-bold text-lg">List of team pricing</div>
                        <div class="p-6 flex gap-2">
                            <SecondaryButton>Download</SecondaryButton>
                            <PrimaryButton>Upload</PrimaryButton>
                        </div>
                    </div>
                    <div class="p-4">
                        <DataTable :value="teamPricing.data" tableStyle="min-width: 50rem">
                            <Column field="part_type" header="Part Type"></Column>
                            <Column field="manufacturer" header="Manufacturer"></Column>
                            <Column field="model_number" header="Model Number"></Column>
                            <Column field="list_price" header="List Price"></Column>
                            <Column field="multiplier" header="Multiplier"></Column>
                            <Column field="static_price" header="Static Price"></Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
