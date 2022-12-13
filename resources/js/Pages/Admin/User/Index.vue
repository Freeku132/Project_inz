<template>

    <Head>
        <title>Admin Panel</title>
    </Head>
    <div class="flex md:flex-row flex-col min-h-screen">

        <SideBar/>

        <div class="border-l-2 w-full border-default">
            <div class="mt-20 w-2/3 mx-auto text-default">
                <div class="flex flex-col md:flex-row justify-between items-center p-3">
                    <h1 class="text-3xl font-semibold">    {{ lang.get('teachersIndex.header')}}</h1>
                    <button type="button" class="text-right rounded px-1 min-w-fit md:mr-5 bg-blue-500 m-3 md:m-0" @click="showNewUser = true">Add new</button>
                    <input v-model="search" type="text" :placeholder="lang.get('teachersIndex.search')" class="rounded-xl bg-page max-h-10">
                </div>
                <table class="w-full border-collapse block mx-auto md:table">
                    <thead class="block hidden md:static md:table-header-group ">
                    <tr class="border border-grey-500 md:border-none block md:table-row absolute top-full md:top-auto left-full md:left-auto  md:relative ">
                        <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('teachersIndex.name')}}</th>
                        <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('teachersIndex.email')}}</th>
                        <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    Role</th>
                        <th class="bg-page2 p-2  font-bold md:border md:border-default text-left block md:table-cell">    {{ lang.get('teachersIndex.others')}}</th>
                    </tr>
                    </thead>
                    <tbody class="block md:table-row-group">

                    <tr v-for="user in users.data" class="bg-page border border-default md:border-none block md:table-row">
                        <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">{{ lang.get('teachersIndex.name')}}</span>
                           {{user.name}}
                        </td>
                        <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">{{ lang.get('teachersIndex.email')}}</span>{{user.email}}</td>
                        <td class="p-2 md:border md:border-default text-left block md:table-cell"><span class="inline-block w-1/3 md:hidden font-bold">Role</span>{{user.role_id === 3 ? 'Student' : 'Teacher'}}</td>
                        <td class="p-2 md:border md:border-default text-center block  sm:space-y-1 md:table-cell">
                            <span class="inline-block  w-1/3 md:hidden font-bold">{{ lang.get('teachersIndex.others')}}</span>
                            <button class="text-right rounded mr-3 px-2 bg-blue-500" @click="editUser(user)">Edit</button>
                            <button class="text-right rounded px-2 bg-red-500" @click="deleteUser(user)">Delete</button>
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
                        <label>Name</label>
                        <input type="text" v-model="editUserForm.user.name" class="bg-page text-default" required>
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.name">Error name</div>

                        <label>Email</label>
                        <input type="text" v-model="editUserForm.user.email" class="bg-page text-default" required>
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.email">Error email</div>

                        <label>Password</label>
                        <input type="text" v-model="editUserForm.user.password" class="bg-page text-default">
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.password">Error password</div>

                        <label>Role</label>
                        <select class="text-default bg-page" v-model="editUserForm.user.role_id">
                            <option value="3">Student</option>
                            <option value="2">Teacher</option>
                        </select>
                        <div class=" font-semibold text-red-500" v-if="editUserForm.errors.role_id">Error role</div>




                    </div>

                    <div class="flex flex-row justify-between mx-auto w-3/4 md:w-1/2">
                        <button type="button" class="text-right rounded px-2 bg-green-600" @click="acceptEdit">Edit</button>
                        <button type="button" class="text-right rounded px-2 bg-red-500" @click="showEditUser = false">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="showDeleteUser">
            <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                <div class="md:w-1/3 flex flex-col bg-page3 rounded-xl m-2 p-2">
                    <div class="flex flex-col bg-page rounded-xl m-2 p-2">
                        <div class="text-xl text-center">
                            Are you sure to delete
                            <p class="font-bold">
                                {{deleteUserForm.user.name}}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-row justify-between mx-auto w-3/4 md:w-1/2">
                        <button type="button" class="text-right rounded px-2 bg-red-500" @click="acceptDelete">Delete</button>
                        <button type="button" class="text-right rounded px-2 bg-page" @click="showDeleteUser = false">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="showNewUser">
            <div class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75">
                <div class="md:w-1/3 items-center justify-center flex flex-col bg-page3 rounded-xl m-2 p-2">
                    <div class="flex flex-col bg-page rounded-xl m-2 p-2">
                        <label>Name</label>
                        <input type="text" v-model="newUserForm.name" class="bg-page text-default" required>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.name">Error name</div>

                        <label>Email</label>
                        <input type="text" v-model="newUserForm.email" class="bg-page text-default" required>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.email">Error email</div>

                        <label>Password</label>
                        <input type="password" v-model="newUserForm.password" class="bg-page text-default" required>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.password">Error password</div>

                        <label>Role</label>
                        <select class="text-default bg-page" v-model="newUserForm.role_id">
                            <option value="3">Student</option>
                            <option value="2">Teacher</option>
                        </select>
                        <div class=" font-semibold text-red-500" v-if="newUserForm.errors.role_id">Error role</div>
                    </div>
                    <div class="flex flex-row justify-between w-3/4 md:w-1/2 mx-auto ">
                        <button type="button" class="text-right rounded px-2 bg-green-600" @click="acceptNew">Add New</button>
                        <button type="button" class="text-right rounded px-2 bg-red-500" @click="showNewUser = false">Cancel</button>
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
import teacherIndex from "../../../../../lang/teachersIndex.json";
import {useForm} from "@inertiajs/inertia-vue3";
import {debounce} from "lodash/function";
import {Inertia} from "@inertiajs/inertia";


let lang = ref(new Lang({
    messages: teacherIndex
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
    user : Object
})
let editUserForm = useForm({
    user: Object,
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
    deleteUserForm.submit('delete', route('adminPanel.users.delete', deleteUserForm.user))
}

let editUser = (user) => {
    showEditUser.value = true
    editUserForm.user = user

}
let acceptEdit = () => {
    editUserForm.submit('patch', route('adminPanel.users.update', editUserForm.user))
}

let acceptNew = () => {
    newUserForm.submit('post', route('adminPanel.users.store', newUserForm.user))
}


let Pagination = defineAsyncComponent( () => {
    return import("@/Components/Pagination.vue");
} )

let search = ref(props.filters.search);

watch(search, debounce( (value) => {
    Inertia.get(props.routing, {
        search: value,
    }, {
        replace: true,
        preserveState : true,
    });
}, 300));

</script>

<style scoped>

</style>
