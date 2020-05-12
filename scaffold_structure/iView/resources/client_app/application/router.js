//pages
var routes = require("./router.pages.js");

//sub menus
var sub_menus = require("./router.submenus.js");

const routers = [
    {
        path: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
        title: 'otinanai',
        name: '/',
        component: routes.index,
        meta: {
            submenu: null,  //<--something like "demo_sub_menu" will go here
        }
    },
    {
        path: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/page1',
        title: 'otinanai',
        name: '/page1',
        component: routes.page1,
        meta: {
            submenu: null,  //<--something like "demo_sub_menu" will go here
        }
    },
];
export default routers;