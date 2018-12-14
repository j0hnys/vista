import index from './pages/index.vue';
import test_form from './pages/test_form.vue';
import test_list from './pages/test_list.vue';

import test_form_sub_menu from './navigation/sub_menus/test_form_sub_menu.js';

const routers = [
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/',
        meta: {
            title: 'otinanai'
        },
        component: index
    },
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/test_form',
        meta: {
            title: 'otinanai'
        },
        component: test_form,
        meta: {
            submenu: test_form_sub_menu,
        }
    },
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/test_list',
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
