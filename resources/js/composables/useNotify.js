import { useToast } from 'vue-toastification';

export default function useNotify() {
    const toast = useToast();

    return {
        success: (msg) => toast.success(msg),
        error: (msg) => toast.error(msg),
        warning: (msg) => toast.warning(msg),
        info: (msg) => toast.info(msg),
    };
}
