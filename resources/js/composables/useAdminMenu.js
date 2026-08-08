import {useI18n} from "vue-i18n";
import {defineAsyncComponent} from "vue";

export function useAdminMenu() {
    const {t} = useI18n()

    return [
        {
            id: 'user',
            label: t('users.users'),
            href: route('admin.users.index'),
        },
        {
            id: 'example1',
            label: 'Ejemplo 1',
            children: [
                {
                    id: 'nivel2',
                    label: 'Nivel 2',
                    children: [
                        {
                            id: 'opcion1',
                            label: 'opcion1',
                            href: route('dashboard'),
                        },
                        {
                            id: 'opcion2',
                            label: 'opcion 2',
                            href: route('dashboard'),
                        },
                        {
                            id: 'opcion3',
                            label: 'opcion 3',
                            href: route('dashboard'),
                        },
                    ],
                },
                {
                    id: 'example2',
                    label: 'Ejemplo 2',
                    href: route('dashboard'),
                }
            ]
        }
    ]
}
