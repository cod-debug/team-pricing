<template>
    <Head title="Team Pricing / Upload" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">System-wide parts / Upload</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <p class="bg-green-400 p-2 rounded-lg border-green-200 text-green-800 mb-4" v-if="show_result">{{ message }}</p>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit()" class="rounded p-8 flex flex-col gap-3 max-w-full lg:w-1/2">
                        <div>
                            <InputLabel value="Excel File" />
                            <input type="file" accept=".xlsx" class="border p-2 rounded w-full" required ref="excelFile" :disabled="loading" />
                        </div>
                        <div class="text-right">
                            <PrimaryButton type="submit" :disabled="loading">Upload</PrimaryButton>
                        </div>
                    </form>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-8" v-if="submitted">
                    <div class="text-gray-600 mb-4">Import Result</div>
                    <div v-if="submitted">
                        <span class="text-lg font-bold text-gray-700">System-wide parts added <span class="bg-gray-700 text-white px-1 ml-2 rounded">{{ added.length }}</span></span>
                        <div class="relative max-h-[400px]">
                            <DataTable :value="added" tableStyle="min-width: 50rem" v-if="added.length > 0">
                                <Column field="part_type" header="Part Type"></Column>
                                <Column field="manufacturer" header="Manufacturer"></Column>
                                <Column field="model_number" header="Model Number"></Column>
                                <Column field="list_price" header="List Price" class="text-right"></Column>
                                <Column header="Multiplier">
                                    <template #body="slotProps">
                                        <p class="text-right">{{ slotProps.data.multiplier || '' }}</p>
                                    </template></Column>
                                <Column header="Static Price">
                                    <template #body="slotProps">
                                        <p class="text-right">{{ slotProps.data.static_price || '' }}</p>
                                    </template>
                                </Column>
                                <Column header="Team Price">
                                    <template #body="slotProps">
                                        <div class="text-right">{{ formatNumber(slotProps.data.multiplier ? slotProps.data.multiplier * slotProps.data.list_price : slotProps.data.static_price)}}</div>
                                    </template>
                                </Column>
                            </DataTable>
                            <p class="text-yellow-600 bg-yellow-200 p-1 rounded px-3" v-else>No system-wide parts added.</p>
                        </div>
                        <div class="border-b border-gray-300 w-full my-8"></div>
                        <div class="text-lg font-bold text-gray-700">System-wide parts that already exist<span class="bg-gray-700 text-white px-1 ml-2 rounded">{{ updated.length }}</span></div>
                        <div class="relative max-h-[300px] overflow-auto">
                            <DataTable :value="updated" tableStyle="min-width: 50rem" v-if="updated.length > 0">
                                <Column field="part_type" header="Part Type"></Column>
                                <Column field="manufacturer" header="Manufacturer"></Column>
                                <Column field="model_number" header="Model Number"></Column>
                                <Column field="list_price" header="List Price" class="text-right"></Column>
                                <Column header="Multiplier">
                                    <template #body="slotProps">
                                        <p class="text-right">{{ slotProps.data.multiplier || '' }}</p>
                                    </template></Column>
                                <Column header="Static Price">
                                    <template #body="slotProps">
                                        <p class="text-right">{{ slotProps.data.static_price || '' }}</p>
                                    </template>
                                </Column>
                                <Column header="Team Price">
                                    <template #body="slotProps">
                                        <div class="text-right">{{ formatNumber(slotProps.data.multiplier ? slotProps.data.multiplier * slotProps.data.list_price : slotProps.data.static_price)}}</div>
                                    </template>
                                </Column>
                            </DataTable>
                            <p class="text-yellow-600 bg-yellow-200 p-1 rounded px-3" v-else>No pricing updated.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
export default {
    data(){
        return {
            loading: false,
            added: [],
            updated: [],
            error: [],
            message: "",
            status: null,
            show_result: false,
            submitted: false,
        }
    },
    methods: {
        async submit(){
            let file = this.$refs.excelFile;
            let payload = new FormData();
            payload.append('file', file.files[0]);

            this.loading = true;
            this.show_result = false;
            this.submitted = false;

            let  {data, status} = await this.$axios.post(route('upload_team_pricing'), payload);
            if([200, 201].includes(status)){
                this.message = data.message;
                this.added = data.added;
                this.updated = data.updated;
                this.error = data.error;
                this.status = status;
                this.show_result = true;
                this.submitted = true;
                setTimeout(() => {
                    this.show_result = false;
                }, 5000);
            }
            
            this.loading = false;
        },
        formatNumber(number, decimals = 2){
            return number.toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals}
            )
        }
    },
    components: {
        AuthenticatedLayout,
        InputLabel,
        Head,
        PrimaryButton,
        DataTable,
        Column
    }
}
</script>
