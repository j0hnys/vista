const main_menu = [
    {
        name: '1',
        icon_type: 'ios-navigate',
        text: 'Option 1',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
    },
    {
        name: '2',
        icon_type: 'ios-navigate',
        text: 'Option 2',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
        children: [
            {
                name: '2-1',
                icon_type: 'ios-navigate',
                text: 'sub Option group name 1',
                redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                children: [
                    {
                        name: '2-1-1',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-1',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '2-1-2',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-2',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '2-1-3',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-3',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                ]
            },
            {
                name: '2-2',
                icon_type: 'ios-navigate',
                text: 'sub Option group name 2',
                redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                children: [
                    {
                        name: '2-2-1',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-4',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '2-2-2',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-5',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                    {
                        name: '2-2-3',
                        icon_type: 'ios-navigate',
                        text: 'sub Option 2-6',
                        redirect_url: process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/',
                    },
                ]
            },
        ]
    },
];
export default main_menu;