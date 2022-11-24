

<template>

        <Head title="Log in" />

        <div class="md:w-1/3 p-5 mx-auto mt-20">
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" :value="lang.get('login.email')" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
<!--                    <InputError class="mt-2" :message="form.errors.email" />-->
                    <!--Do zatwierdzenia!!-->
                    <InputError class="mt-2" v-if="form.errors.email" :message="lang.get('error.email')" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" :value="lang.get('login.password')" />
                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
<!--                    <InputError class="mt-2" :message="form.errors.password" />-->
                    <InputError class="mt-2" v-if="form.errors.password" :message="lang.get('error.password')" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center hover:cursor-pointer md:w-2/5">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm text-default hover:text-default2">{{ lang.get('login.remember') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-default hover:text-default2">
                        {{ lang.get('login.forgot') }}
                    </Link>

                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ lang.get('login.login') }}
                    </PrimaryButton>
                </div>
            </form>
        </div>

</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import {ref} from "vue";
import Lang from "lang.js";
import loginViewMessages from "../../../../lang/login.json";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

let lang = ref(new Lang({
    messages: loginViewMessages
}));

let chosenLang = ref(localStorage.getItem('lang') || 'en');

lang.value.setLocale(chosenLang);
lang.value.setFallback(chosenLang);

</script>
<script>
import Layout from "@/Shared/Layout.vue"
export default {
    layout: Layout,
}
</script>
