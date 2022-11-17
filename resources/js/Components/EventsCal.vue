<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            <div class="overflow-hidden shadow-sm sm:rounded-lg"
                 @keydown.esc="showForm = false"
            >
                <div
                    v-if="showForm"
                    class="z-50 fixed inset-0 w-full h-screen flex items-center justify-center bg-bg-semi-75"
                >
                    <div class=" p-8 rounded-2xl md:w-2/3 ">
                        <form
                            @submit.prevent="submit" class="flex flex-col bg-page rounded-3xl">
                            <div class="bg-green-600 rounded-t-3xl p-2 text-center flex flex-col justify-between">
                                <p>{{form.start + ' - ' + form.end}}</p>
                                <div class="space-x-8">
                                    <button type="button" v-if="form.class === 'busy'" @click.prevent="changeStatus('accepted')" class=" bg-green-700 font-bold text-xl  px-1 rounded-md disabled:bg-gray-500" :disabled="changeStatusForm.processing">Accept</button>
                                    <button type="button" v-if="props.user.id === auth.id" @click.prevent="changeStatus('cancelled')" class=" relative mr-5 bg-red-600 font-bold text-xl px-1  rounded-md disabled:bg-gray-500" :disabled="changeStatusForm.processing">Revoke</button>
                                </div>
                            </div>

                            <div class="flex mx-auto space-x-8 mt-3">

                            </div>
                            <label for=content class="font-bold mx-5 mt-5 bg-page2 rounded-t-md p-1">Subject:/Temat:</label>
                            <input v-model="form.subject" class="bg-page rounded pl-3 p-1 mx-5 border border-default focus:outline-none " />
                            <div class=" font-semibold text-red-500 p-1 mx-5" v-if="form.errors.subject">{{form.errors.subject}}</div>

                            <label for=content class="font-bold mx-5  bg-page2 p-1">Message:/Wiadomość:</label>
                            <textarea v-model="form.message" class="bg-page mx-5 border border-default focus:ring-0 focus:outline-none focus:border-default mb-0.5" />
                            <div class=" font-semibold text-red-500 p-1 mx-5" v-if="form.errors.message">{{form.errors.message}}</div>

                            <label for="room" class="font-bold mx-5  bg-page2 p-1">Room:</label>
                            <input disabled class="mx-5 bg-page pl-3 p-1 border border-default" :value="form.room">

                            <label for="endNew" class="font-bold mx-5 bg-page2 p-1">Start Time:/Czas rozpoczęcia:</label>
                            <select v-model="form.startNew" @change="setEndOptions()" class="bg-page mx-5  border-default">
                                <option v-for="option in startOptions" :value="option">{{option}}</option>
                            </select>
                            <div class=" font-semibold text-red-500 p-1 mx-5" v-if="form.errors.startNew">{{form.errors.startNew}}</div>

                            <label for="endNew" class="font-bold mx-5 bg-page2 p-1">End Time:/Czas zakończenia:</label>
                            <select v-model="form.endNew" class="bg-page mx-5 rounded-b-md border border-default">
                                <option v-for="option in endOptions" :value="option">{{option}}</option>
                            </select>
                            <div class=" font-semibold text-red-500 p-1 mx-5" v-if="form.errors.endNew">{{form.errors.endNew}}</div>

                            <div class="flex justify-between">
                                <button type="submit" class="bg-green-500 w-1/4 h-10 rounded-bl-xl mt-5 disabled:bg-gray-600" :disabled="form.processing || form.class==='busy'">Submit</button>
                                <button type="button"
                                        class="bg-red-600 rounded-br-xl mt-5 w-1/4"
                                        @click="showForm = false">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>



                <div class="p-6 bg-page">


                    <vue-cal
                        :selected-date="currentDate"
                        :disable-views="['years', 'year', 'day',]"
                        today-button
                        :time-from="7 * 60"
                        :time-to="21 * 60"
                        :time-step="15"
                        :min-date="'2022-10-12'"
                        :max-date="'2022-10-14'"
                        :editable-events="{ title: false, drag: false, resize: false, delete: false, create: false }"
                        :locale="chosenLang"
                        :events="props.events.data"
                        :special-hours="specialHours"
                        :on-event-click="onEventClick"
                        :xsmall=true
                        :timeCellHeight=45
                        :resizeX=true
                        @view-change="logEvents($event)"

                    >
                        <template #event="{event, view}">
                            <div class="items-center">
                                <div class="flex justify-center">
                                    <div v-html="event.class"/>
                                </div>
                                <div class="flex flex-col md:flex-row justify-center">
                                    <div>{{event.start.format('HH:mm')}}-</div>
                                    <div>{{event.end.format('HH:mm')}}</div>
                                </div>
                            </div>
                        </template>
                    </vue-cal>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import {ref, watch} from 'vue';
import {throttle} from "lodash/function";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3"
import {useToast} from "vue-toastification";
import Lang from "lang.js";
import loginViewMessages from "../../../lang/login.json";


let lang = ref(new Lang({
    messages: loginViewMessages
}));

let chosenLang = ref(localStorage.getItem('lang') || 'en');

lang.value.setLocale(chosenLang);
lang.value.setFallback(chosenLang);


let props = defineProps({
    events: Object,
    currentDate: String,
    user: Object
})

const toast = useToast();

let showForm = ref(false);

let auth = usePage().props.value.auth.user

let form = useForm({
    id:'',
    start:'',
    end:'',
    subject:'',
    message:'',
    room:'',
    class:'',
    startNew:'',
    endNew:'',
    teacher:'',
    student:'',
})
let submit = () =>{
    form.post('/event/store',
        {
            preserveState:true,
            onSuccess: () => {
                showForm.value = false;
                toast.success(usePage().props.value.flash.success_message);

            }
        })
}


// Hard coded closed hours (Sunday)
let specialHours = {
    7: {
        from: 7*60,
        to: 21*60,
        class: 'closed',
        label: 'Closed'
    }
}
let startOptions = ref([]); // Available hours to choose on book
let endOptions = ref([]); // Available hours to choose on book

function onEventClick(event, e) {
    if (usePage().props.value.auth.user) {
        if (event.class === 'free' || usePage().props.value.auth.user.id === props.user.id
        ) {
            let start = event.start.format('YYYY-MM-DD HH:mm')
            let end = event.end.format('YYYY-MM-DD HH:mm')
            let diff = ((event.end.getTime() - event.start.getTime()) / 60000) / 15;
            startOptions.value = [];
            endOptions.value = [];

            for (let i = 0; i < diff; i++) {
                if (event.end.getTime() === new Date(event.start.getTime() + 15 * 60000).format('YYYY-MM-DD HH:mm')) {
                    return;
                }
                startOptions.value.push(new Date(event.start.getTime() + (i * 15 * 60000)).format('YYYY-MM-DD HH:mm'))
            }

            showForm.value = true
            form.id = event.id
            form.room = event.room
            form.startNew = ''
            form.endNew = end
            form.start = start
            form.end = end
            form.subject = event.subject
            form.message = event.message
            form.class = event.class
            form.teacher = props.user.id
            form.student = usePage().props.value.auth.user.id
            return;
        }
        toast.error('admin')
        return;
    }
    toast.error('auth');
    return;
}


function setEndOptions() {

    endOptions.value = [
        new Date((new Date(form.startNew).getTime() +15*60000)).format('YYYY-MM-DD HH:mm')
    ]
    if (form.end !== new Date((new Date(form.startNew).getTime() +15*60000)).format('YYYY-MM-DD HH:mm')){
        endOptions.value.push(new Date((new Date(form.startNew).getTime() +30*60000)).format('YYYY-MM-DD HH:mm'))
    }
}


let startDate = ref();
let endDate = ref();


function logEvents(event){

    startDate.value = event.startDate.format('YYYY-MM-DD HH:mm')
    endDate.value = event.endDate.format('YYYY-MM-DD HH:mm')
}


watch(startDate, throttle( (value) => {
    Inertia.get('/teachers/'+props.user.id, {
        currentDate: value,
        startDate: startDate.value,
        endDate: endDate.value
    }, {
        replace: true,
        preserveScroll: true,
        preserveState:true
    });
}, 300));

let changeStatusForm = useForm({
    id:'',
    class:'',
})


let changeStatus = (value) => {
    changeStatusForm.id = form.id
    changeStatusForm.class = value
    changeStatusForm.patch('/teachers/'+props.user.id+'/events/'+form.id+'/update',{
        onSuccess: () => {
            toast.success(usePage().props.value.flash.success_message, {})
        },
    })
}


</script>

<style>
.vuecal__event {cursor: pointer;}

.vuecal__event-title {
    font-size: 0.9em;
    font-weight: bold;
}


.closed {
    background:
        #f5baba
        repeating-linear-gradient(
            -45deg,
            rgba(248, 162, 92, 0.25),
            rgba(255, 162, 87, 0.25) 5px,
            rgba(255, 255, 255, 0) 5px,
            rgba(255, 255, 255, 0) 15px
        );
    color: #f67f4c;
}

.free {background-color: forestgreen;color:black;}
.busy {background-color: darkorange;color: black;}
.cancelled {background-color: red;color: black;}
.accept {background-color: gray;color: black;}


/* Green-theme. */
.vuecal__menu, .vuecal__cell-events-count {background-color: #11a911;}
.vuecal__title-bar {background-color: -moz-default-background-color;}
.vuecal__cell--today, .vuecal__cell--current {background-color: rgba(240, 240, 255, 0.4);}
.vuecal:not(.vuecal--day-view) .vuecal__cell--selected {background-color: rgba(235, 255, 245, 0.4);}
.vuecal__cell--selected:before {border-color: rgba(66, 185, 131, 0.5);}
/* Cells and buttons get highlighted when an event is dragged over it. */
.vuecal__cell--highlighted:not(.vuecal__cell--has-splits),
.vuecal__cell-split--highlighted {background-color: rgba(195, 255, 225, 0.5);}
.vuecal__arrow.vuecal__arrow--highlighted,
.vuecal__view-btn.vuecal__view-btn--highlighted {background-color: rgba(136, 236, 191, 0.25);}

</style>
