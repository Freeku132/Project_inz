<template>
    <tr  :class="'uppercase border border-default text-default '+ event.class">
        <th scope="row" class="py-4 px-6 font-medium text-default whitespace-nowrap">
            {{event.start}} - {{event.end}}
        </th>
        <th  class="py-4 px-6 text-default">
            {{event.subject}}
        </th>
        <th  class="py-4 px-6 text-default">
            {{event.student.name}}-{{event.student.email}}
        </th>
        <th  class="py-4 px-6 text-default">
            {{event.class}}
        </th>
        <th  class="py-4 px-6 text-default">
            <div class="items-center flex flex-col text-center">
                <button @click.prevent="showModal(event.id, props.filters.page)" class="p-1 rounded bg-green-600"> show</button>
            </div>

        </th>
    </tr>
    <div @keydown.esc="show = false">
        <div
            v-if="show"
            class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75"
        >
            <div class=" p-4 bg-page rounded-2xl md:w-2/3 flex flex-col">
                <div class="text-right">
                <button class="bg-red-500  px-2 mx-auto rounded-md" @click.prevent="showModal( NULL, props.filters.page)">x</button>
                </div>
                    <label for=content class="font-bold mx-5 mt-5 bg-page2 rounded-t-md p-1">Subject:/Temat:</label>
                    <div class="bg-page rounded pl-3 p-1 mx-5 border border-default focus:outline-none ">
                        {{event.subject}}
                    </div>
                    <label for="endNew" class="font-bold mx-5 bg-page2 p-1">Student</label>
                    <div class="mx-5 bg-page pl-3 p-1 border border-default">
                         {{event.student.email}} - {{event.student.name}}
                    </div>

                    <label for=content class="font-bold mx-5  bg-page2 p-1">Message:/Wiadomość:</label>
                    <div class="bg-page mx-5 border border-default focus:ring-0 pl-3 p-1 focus:outline-none focus:border-default mb-0.5" >
                        {{event.message}}
                    </div>

                    <label for="room" class="font-bold mx-5  bg-page2 p-1">Room: </label>
                    <input disabled class="mx-5 bg-page pl-3 p-1 border border-default" :value="event.room">

                    <label for="endNew" class="font-bold mx-5 bg-page2 p-1">Start Time:/Czas rozpoczęcia:</label>
                    <div class="mx-5 bg-page pl-3 p-1 border border-default">
                        {{event.start}}
                    </div>
                    <label for="endNew" class="font-bold mx-5 bg-page2 p-1">End Time:/Czas zakończenia:</label>
                    <div class="mx-5 bg-page pl-3 p-1 border border-default">
                        {{event.end}}
                    </div>
                    <div class="text-center space-x-8">
                        <button @click.prevent="submit('accepted')" class="p-1 rounded bg-green-600 disabled:bg-gray-500" :disabled="form.processing">
                            Accept
                        </button>
                        <button @click.prevent="submit('cancelled')" class="p-1 rounded bg-red-600 mt-3 disabled:bg-gray-500" :disabled="form.processing">
                            Decline
                        </button>
                    </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {useToast} from "vue-toastification";


const toast = useToast();

let props = defineProps({
    event: Object,
    user: Object,
    filters: Object,
    selected: Object,
});


let show = ref(props.selected.event == props.event.id);



let showModal = (value, second) => {
    Inertia.get('/teachers/'+props.user.id +'/events/', {
        event : value,
        page : second
    })
}



let form = useForm({
    id:'',
    class:'',
})


let submit = (value) => {
    form.id = props.event.id
    form.class = value
    form.patch('/teachers/'+props.user.id+'/events/'+props.event.id+'/update',{
        onSuccess: () => {
            toast.success(usePage().props.value.flash.success_message, {})
            showModal();
        },
    })
}

</script>

<style scoped>
.free {background-color: forestgreen;}
.busy {background-color: orange;}
.accepted {background-color: gray;}
.cancelled {background-color: red;}
</style>
