
<template>
        <Head title="Register" />

    <div class="w-1/3 mt-20 mx-auto">
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" :value="lang.get('register.name')" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus autocomplete="name" />
                <InputError class="mt-2" v-if="form.errors.name" :message="lang.get('error.name')" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" :value="lang.get('register.email')" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"  autocomplete="username" />
                <InputError class="mt-2" v-if="form.errors.email" :message="lang.get('error.email')" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="lang.get('register.password')" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"  autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" :value="lang.get('register.confirmPassword')" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation"  autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-default hover:text-default2">
                    {{ lang.get('register.registered') }}
                </Link>

                <PrimaryButton class="ml-4" id="register" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ lang.get('register.register') }}
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
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import {ref} from "vue";
import Lang from "lang.js";
import registerViewMessage from "../../../../lang/register.json";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
let lang = ref(new Lang({
    messages: registerViewMessage
}));

let chosenLang = ref(localStorage.getItem('lang') || 'en');

lang.value.setLocale(chosenLang);
lang.value.setFallback(chosenLang);

</script>
