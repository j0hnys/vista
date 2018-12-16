const main_menu = [
    {
        name: '1-1',
        icon_type: 'ios-navigate',
        text: 'test_model_list',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/test_model_list',
    },
    {
        name: '1-2',
        icon_type: 'ios-navigate',
        text: 'test_model_form',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/test_model_form',
    },
    {
        name: '1-3',
        icon_type: 'ios-navigate',
        text: 'Option 3',
        redirect_url: process.env.MIX_BASE_RELATIVE_URL+'/',
    },
];
export default main_menu;