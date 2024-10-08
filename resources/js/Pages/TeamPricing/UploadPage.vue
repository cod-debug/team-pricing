<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
        }
    },
    components: {
        AuthenticatedLayout,
        InputLabel,
        Head,
        PrimaryButton,
    }
}
</script>

<template>
    <Head title="Team Pricing / Upload" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Team Pricing / Upload</h2>
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
                        <span class="text-lg font-bold text-gray-700">Added Pricing <span class="bg-gray-700 text-white px-1 ml-2 rounded">{{ added.length }}</span></span>
                        <div class="relative max-h-[400px]">
                            <table class="w-full">
                                <thead class="bg-gray-800  text-white border">
                                    <th class="py-2">Part Type</th>
                                    <th class="py-2">Manufacturer</th>
                                    <th class="py-2">Model Number</th>
                                    <th class="py-2">List Price</th>
                                    <th class="py-2">Multiplier</th>
                                    <th class="py-2">Static Price</th>
                                </thead>
                                <tbody class="border" v-if="added.length > 0">
                                    <tr v-for="(item, key) in added" :key="key">
                                        <td>
                                            <p class="p-2">{{ item.part_type }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2">{{ item.manufacturer }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2">{{ item.model_number }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2 text-right">{{ item.list_price }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2 text-right">{{ item.multiplier || '' }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2 text-right">{{ item.static_price || '' }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody class="border" v-else>
                                    <td colspan="5" class="p-2">
                                        <p class="text-gray-400">No added pricing</p>
                                    </td>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-lg font-bold text-gray-700 mt-8">Pricing that already exist<span class="bg-gray-700 text-white px-1 ml-2 rounded">{{ updated.length }}</span></div>
                        <div class="relative max-h-[400px]">
                            <table class="w-full">
                                <thead class="bg-gray-800  text-white border">
                                    <th class="py-2">Part Type</th>
                                    <th class="py-2">Manufacturer</th>
                                    <th class="py-2">Model Number</th>
                                    <th class="py-2">List Price</th>
                                    <th class="py-2">Multiplier</th>
                                    <th class="py-2">Static Price</th>
                                </thead>
                                <tbody class="border" v-if="updated.length > 0">
                                    <tr v-for="(item, key) in updated" :key="key">
                                        <td>
                                            <p class="p-2">{{ item.part_type }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2">{{ item.manufacturer }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2">{{ item.model_number }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2 text-right">{{ item.list_price }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2 text-right">{{ item.multiplier || '' }}</p>
                                        </td>
                                        <td>
                                            <p class="p-2 text-right">{{ item.static_price || '' }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody class="border" v-else>
                                    <td colspan="5" class="p-2">
                                        <p class="text-gray-400">No added pricing</p>
                                    </td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>