import index from './pages/index.vue';
import test_form from './pages/test_form.vue';
import test_list from './pages/test_list.vue';

import test_form_sub_menu from './navigation/sub_menus/test_form_sub_menu.js';

const routers = [
    {
        path: '/vista-framework/public/',
        meta: {
            title: 'otinanai'
        },
        component: index
    },
    {
        path: '/vista-framework/public/test_form',
        meta: {
            title: 'otinanai'
        },
        component: test_form,
        meta: {
            submenu: test_form_sub_menu,
        }
    },
    {
        path: '/vista-framework/public/test_list',
        meta: {
            title: 'otinanai'
        },
        component: test_list,
        meta: {
            submenu: [],
        }
    },
];
export default routers;