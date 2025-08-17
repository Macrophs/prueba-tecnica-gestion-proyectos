import { Ref, ref } from 'vue';

/*Ref for close dialogs */
export const dialogState = (): [Ref<boolean>, () => void] => {
    const isOpen = ref(false);

    function closeDialog() {
        isOpen.value = false;
    }

    return [isOpen, closeDialog];
};
