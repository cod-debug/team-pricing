<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
export default {
    methods: {
        async submit(){
            let file = this.$refs.excelFile;
            let payload = new FormData();
            payload.append('file', file.files[0]);

            let  {data, status} = this.$axios.post(route('upload_team_pricing'), payload);
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit()" class="rounded p-8 flex flex-col gap-3 max-w-full lg:w-1/2">
                        <div>
                            <InputLabel value="Excel File" />
                            <input type="file" accept=".xlsx" class="border p-2 rounded w-full" required ref="excelFile" />
                        </div>
                        <div class="text-right">
                            <PrimaryButton type="submit">Upload</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>