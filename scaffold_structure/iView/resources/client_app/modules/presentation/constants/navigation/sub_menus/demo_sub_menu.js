const demo_sub_menu = [
    {
        name: '1',
        icon_type: 'ios-navigate',
        text: 'sub Option 1',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
    },
    {
        name: '2',
        icon_type: 'ios-navigate',
        text: 'sub Option 2',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
    },
    {
        name: '3',
        icon_type: 'ios-navigate',
        text: 'sub Option 3',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
        children: [
            {
                name: '3-1',
                icon_type: 'ios-navigate',
                text: 'sub Option group name 1',
                redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                children: [
                    {
                        name: '3-1-1',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 3-1',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '3-1-2',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 3-2',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '3-1-3',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 3-3',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                ]
            },
            {
                name: '3-2',
                icon_type: 'ios-navigate',
                text: 'sub Option group name 2',
                redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                children: [
                    {
                        name: '3-2-1',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 3-4',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '3-2-2',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 3-5',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '3-2-3',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 3-6',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                ]
            },
        ]
    },
];
export default demo_sub_menu;