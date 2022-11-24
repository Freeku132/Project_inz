<template>
    <div class="mx-auto md:w-1/3 p-5 mt-20">
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-default">
            {{ lang.get('forgot.header')}}
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}

        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{lang.get('forgot.button')}}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
<script>
import Layout from "@/Shared/Layout.vue"
export default {
    layout: Layout,
}
</script>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import {ref} from "vue";
import Lang from "lang.js";
import forgotPasswordView from "../../../../lang/forgotPassword.json";

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};

let lang = ref(new Lang({
    messages: forgotPasswordView
}));

let chosenLang = ref(localStorage.getItem('lang') || 'en');

lang.value.setLocale(chosenLang);
lang.value.setFallback(chosenLang);

</script>
