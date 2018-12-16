import index from './pages/index.vue';
import test_model_list from './pages/test_model_list.vue';
import test_model_form from './pages/test_model_form.vue';
import test_model_form_edit from './pages/test_model_form_edit.vue';

import test_form_sub_menu from './navigation/sub_menus/test_form_sub_menu.js';

//
// TO DO
//
// na kanw ayto to arxeio na enhmerwnetai me template opws ekana gia t workflow function k etsi otan ftiaxnetai 
// ena kainoyrgio crud
//

const routers = [
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/',
        title: 'otinanai',
        name: '/',
        component: index
    },
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/test_model_list',
        title: 'otinanai',
        name: 'test_model_list',
        component: test_model_list,
        meta: {
            submenu: test_form_sub_menu,
        }
    },
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/test_model_form',
        title: 'otinanai',
        name: 'test_model_create',
        component: test_model_form,
        meta: {
            submenu: [],
        }
    },
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/test_model_form_edit/:id',
        title: 'otinanai',
        name: 'test_model_update',
        component: test_model_form_edit,
        meta: {
            submenu: [],
        }
    },
];
export default routers;