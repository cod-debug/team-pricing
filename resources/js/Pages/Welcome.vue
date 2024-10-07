<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    errors: Object,
});

const form = useForm({
    email: null,
    password: null,
});

const login = ()=>{
    form.post(route('login_post'));
    form.reset();
}
</script>

<template>
    <Head title="Login" />
    <div class="h-[100vh] w-full flex justify-center items-center">
        <form @submit.prevent="login()" class="min-w-[450px] max-w-[90%] border rounded p-8 flex flex-col gap-3 shadow-xl">
            <div>
                <span class="text-gray-800 text-2xl">Login Form</span>
            </div>
            <InputError :message="errors.email" />
            <div>
                <InputLabel value="Email Address" />
                <TextInput class="w-full" required type="email" v-model="form.email" />
            </div>
            <div>
                <InputLabel value="Password" />
                <TextInput class="w-full" type="password" v-model="form.password" required />
            </div>
            <div class="text-right">
                <PrimaryButton type="submit">Login</PrimaryButton>
            </div>
        </form>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
