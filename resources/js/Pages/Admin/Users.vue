<template>

    <Head>
        <title>Admin Panel</title>
    </Head>
    <div class="flex md:flex-row flex-col min-h-screen">

        <SideBar/>

        <div class="border-l-2 w-full border-default">
            <div class="mt-20 w-2/3 mx-auto text-default">
                <div class="flex flex-col md:flex-row justify-between items-center p-3">
                    <h1 class="text-3xl font-semibold"> {{ lang.get('adminPanelUsers.header')}}</h1>
                    <div class="flex items-center">
                        <button type="button" class="rounded-md px-1 min-w-fit mr-4 h-8 bg-blue-500 " @click="showNewUser = true">{{ lang.get('adminPanelUsers.add_new')}}</button>
                        <input v-model="search" type="text" :placeholder="lang.get('adminPanelUsers.search')" class="rounded-xl bg-page max-h-10">
                    </div>
                </div>
                <table class="w-full border-collapse block mx-auto md:table">
                    <thead class="block hidden md:static md:table-header-group ">
                    <tr class="border border-grey-500 md:border-none block md:table-row absolute top-full md:top-auto left-full md:left-auto  md:relative ">
                        <th class="bg-page2 p-2 font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('adminPanelUsers.name')}}</th>
                        <th class="bg-page2 p-2 font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('adminPanelUsers.email')}}</th>
                        <th class="bg-page2 p-2 font-bold md:border md:border-default text-left block md:table-cell">    Role</th>
                        <th class="bg-page2 p-2 font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('adminPanelUsers.others')}}</th>
                    </tr>
                    </thead>
                    <tbody class="block md:table-row-group">

                    <tr v-for="user in users.data" class="bg-page border border-default md:border-none block md:table-row">
                        <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">{{ lang.get('adminPanelUsers.name')}}</span>
                           {{user.name}}
                        </td>
                        <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">{{ lang.get('adminPanelUsers.email')}}</span>{{user.email}}</td>
                        <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Role</span>{{user.role_id === 3 ? 'Student' : 'Teacher'}}</td>
                        <td class="p-2 md:border md:border-default text-left block sm:space-y-1 md:table-cell">
                            <span class="inline-block text-left w-1/3 md:hidden font-bold">{{ lang.get('adminPanelUsers.others')}}</span>
                            <button class="text-right rounded mr-3 px-2 bg-blue-500" @click="editUser(user)">{{ lang.get('adminPanelUsers.edit')}}</button>
                            <button class="text-right rounded px-2 bg-red-500" @click="deleteUser(user)">{{ lang.get('adminPanelUsers.delete')}}</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center">
                <div v-for="link in users.links">

                    <Pagination :link="link"/>

                </div>
            </div>
        </div>

        <div v-show="showEditUser">
            <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                <div class="md:w-1/3 flex flex-col bg-page3 rounded-xl m-2 p-2">
                    <div class="flex flex-col bg-page rounded-xl m-2 p-2">
                        <label>{{ lang.get('adminPanelUsers.name')}}</label>
                        <input type="text" v-model="editUserForm.name" class="bg-page rounded-md text-default" required>
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.name">{{ lang.get('errors.'+editUserForm.errors.name)}}</div>

                        <label>{{ lang.get('adminPanelUsers.email')}}</label>
                        <input type="text" v-model="editUserForm.email" class="bg-page rounded-md text-default" required>
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.email">{{ lang.get('errors.'+editUserForm.errors.email)}}</div>


                        <label>{{ lang.get('adminPanelUsers.password')}}</label>
                        <input type="password" v-model="editUserForm.password" class="bg-page rounded-md text-default">
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.password">{{ lang.get('errors.'+editUserForm.errors.password)}}</div>


                        <label>{{ lang.get('adminPanelUsers.role')}}</label>
                        <select class="text-default rounded-md bg-page" v-model="editUserForm.role_id">
                            <option class="rounded-md" value="3">Student</option>
                            <option class="rounded-md" value="2">Teacher</option>
                        </select>
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.role_id">{{ lang.get('errors.role')}}</div>




                    </div>

                    <div class="flex flex-row justify-between mx-auto w-3/4 md:w-1/2">
                        <button type="button" class="text-right rounded px-2 bg-green-600" @click="acceptEdit">{{ lang.get('adminPanelUsers.edit')}}</button>
                        <button type="button" class="text-right rounded px-2 bg-red-500" @click="showEditUser = false">{{ lang.get('adminPanelUsers.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="showDeleteUser">
            <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                <div class="md:w-1/3 flex flex-col bg-page3 rounded-xl m-2 p-2">
                    <div class="flex flex-col bg-page rounded-xl m-2 p-2">
                        <div class="text-xl text-center">
                            {{ lang.get('adminPanelUsers.delete_info')}}
                            <p class="font-bold">
                                {{deleteUserForm.user.name}}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-row justify-between mx-auto w-3/4 md:w-1/2">
                        <button type="button" class="text-right rounded px-2 bg-red-500" @click="acceptDelete">{{ lang.get('adminPanelUsers.delete')}}</button>
                        <button type="button" class="text-right rounded px-2 bg-page" @click="showDeleteUser = false">{{ lang.get('adminPanelUsers.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="showNewUser">
            <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                <div class=" items-center justify-center flex flex-col bg-page3 rounded-xl m-2 p-2">
                    <div class="flex flex-col bg-page rounded-xl m-2 p-2">
                        <label>{{ lang.get('adminPanelUsers.name')}}</label>
                        <input type="text" v-model="newUserForm.name" class="bg-page text-default rounded-md" required>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.name">{{ lang.get('errors.'+newUserForm.errors.name)}}</div>

                        <label>{{ lang.get('adminPanelUsers.email')}}</label>
                        <input type="text" v-model="newUserForm.email" class="bg-page text-default rounded-md " required>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.email">{{lang.get('errors.'+newUserForm.errors.email)}}</div>

                        <label>{{ lang.get('adminPanelUsers.password')}}</label>
                        <input type="password" v-model="newUserForm.password" class="bg-page text-default rounded-md " required>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.password">{{lang.get('errors.'+newUserForm.errors.password)}}</div>

                        <label>{{ lang.get('adminPanelUsers.role')}}</label>
                        <select class="text-default bg-page rounded-md " v-model="newUserForm.role_id">
                            <option class="rounded-md" value="3">Student</option>
                            <option class="rounded-md" value="2">Teacher</option>
                        </select>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.role_id">{{ lang.get('errors.role')}}</div>
                    </div>
                    <div class="flex flex-row justify-between w-3/4 md:w-1/2 mx-auto ">
                        <button type="button" class="text-right rounded px-2 bg-green-600" @click="acceptNew">{{ lang.get('adminPanelUsers.add')}}</button>
                        <button type="button" class="text-right rounded px-2 bg-red-500" @click="showNewUser = false">{{ lang.get('adminPanelUsers.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue'
export default {
    layout: Layout
}
</script>

<script setup>
import SideBar from "@/Components/SideBar.vue";
import {defineAsyncComponent, ref, watch} from "vue";
import Lang from "lang.js";
import adminPanelUsers from "../../../../lang/adminPanelUsers.json";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {debounce} from "lodash/function";
import {Inertia} from "@inertiajs/inertia";
import {useToast} from "vue-toastification";

const toast = useToast();

let lang = ref(new Lang({
    messages: adminPanelUsers
}));
let chosenLang = ref(localStorage.getItem('lang') || 'en');
lang.value.setLocale(chosenLang)

let props = defineProps({
    users: Object,
    filters : Object,
})

let showDeleteUser = ref(false);
let showEditUser = ref(false);
let showNewUser = ref(false);

let deleteUserForm = useForm({
    user : Object,
})
let editUserForm = useForm({
    id:'',
    name: '',
    email: '',
    password: '',
    role_id: '',
})
let newUserForm = useForm({
    name: '',
    email: '',
    password: '',
    role_id: '',
})



let deleteUser = (user) => {
    showDeleteUser.value = true
    deleteUserForm.user = user
}
let acceptDelete = () => {
    deleteUserForm.submit('delete', route('adminPanel.users.delete', deleteUserForm.user),{
        onSuccess: () => {
            showDeleteUser.value = false
            toast.success(lang.value.get('toast.'+usePage().props.value.flash.success_message))
        }
    })
}

let editUser = (user) => {
    showEditUser.value = true
    editUserForm.id = user.id,
    editUserForm.name = user.name,
    editUserForm.email= user.email,
    editUserForm.password = user.password,
    editUserForm.role_id= user.role_id

}
let acceptEdit = () => {
    editUserForm.submit('patch', route('adminPanel.users.update', editUserForm.id),{
        onSuccess: () => {
            toast.success(lang.value.get('toast.'+usePage().props.value.flash.success_message))

            }
        })
}

let acceptNew = () => {
    newUserForm.submit('post', route('adminPanel.users.store', newUserForm.user), {
        onSuccess: () => {
                    showNewUser.value = false
                    toast.success(lang.value.get('toast.'+usePage().props.value.flash.success_message))
                    newUserForm.reset()
            }
        })



}


let Pagination = defineAsyncComponent( () => {
    return import("@/Components/Pagination.vue");
} )

let search = ref(props.filters.search);

watch(search, debounce( (value) => {
    Inertia.get(route('adminPanel.users'), {
        search: value,
    }, {
        replace: true,
        preserveState : true,
        preserveScroll: true

    });
}, 300));

</script>

<style scoped>

</style>
