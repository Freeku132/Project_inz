import {ref} from "vue";


export function useClipboard(text){

    let copied = ref(false);
    let supported = navigator && 'clipboard' in navigator;

    let copy = () => {

        if ( supported ){
            navigator.clipboard.writeText(text);

            copied.value = true;

            setTimeout( () => {
                copied.value = false
            }, 3000)
            return;
        }
        alert('Browser error')
    };

    return { copy, copied, supported};
}
