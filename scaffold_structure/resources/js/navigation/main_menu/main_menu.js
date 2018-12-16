const main_menu = [
    {
        name: '1',
        icon_type: 'ios-navigate',
        text: 'Home',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
    },
    {
        name: '2',
        icon_type: 'ios-navigate',
        text: 'Menu with children',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
        children: [
            {
                name: '2-1',
                icon_type: 'ios-navigate',
                text: 'sub Option group name 1',
                redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
                children: [
                    {
                        name: '2-1-1',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-1',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
                    },
                ]
            },
            {
                name: '2-2',
                icon_type: 'ios-navigate',
                text: 'sub Option group name 2',
                redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
                children: [
                    {
                        name: '2-2-1',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-2',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
                    },
                ]
            },
        ]
    },
];
export default main_menu;