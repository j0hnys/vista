//pages
import index from './pages/index.vue';

//sub menus
import demo_sub_menu from './navigation/sub_menus/demo_sub_menu.js';

const routers = [
    {
        path: process.env.MIX_BASE_RELATIVE_URL+'/',
        title: 'otinanai',
        name: '/',
        component: index,
        meta: {
            submenu: null,  //<--something like "demo_sub_menu" will go here
        }
    },
];
export default routers;