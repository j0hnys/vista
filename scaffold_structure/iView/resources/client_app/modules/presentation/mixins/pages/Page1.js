export default {
    namespace: 'pages/page111',
    name: 'page1',
    methods: {
        testPageMixinFunction() {
            return 'aaaaaaaaaaaaaaa';
        },

        // calculateCollapsedBurialOptions({changed_burial_option, collapsed_burial_options})
        // {
        //     let new_collapsed_burial_options = Object.assign({}, collapsed_burial_options);

        //     if(new_collapsed_burial_options[changed_burial_option])
        //     {
        //         for(let burial_options_name in new_collapsed_burial_options)
        //         {
        //             if(burial_options_name === changed_burial_option)
        //             {
        //                 new_collapsed_burial_options[burial_options_name] = false;
        //             }
        //             else
        //             {
        //                 new_collapsed_burial_options[burial_options_name] = true;
        //             }
        //         }
        //     }
        //     else
        //     {
        //         new_collapsed_burial_options[changed_burial_option] = true;
        //     }

        //     return new_collapsed_burial_options;
        // },

        // calculateNextExpandedBurialOption({changed_burial_option, collapsed_burial_options, selected_burial_options})
        // {
        //     let new_collapsed_burial_options = Object.assign({}, collapsed_burial_options);

        //     let change_collapsed_step = 0;
        //     for(let burial_options_name in new_collapsed_burial_options)
        //     {
                
        //         if(change_collapsed_step === 1)
        //         {
        //             if(
        //                 (typeof selected_burial_options[burial_options_name].id === 'undefined' || selected_burial_options[burial_options_name].id === null)
        //                 && (typeof selected_burial_options[burial_options_name].name === 'undefined' || selected_burial_options[burial_options_name].name === null)
        //                 && (typeof selected_burial_options[burial_options_name].decide_later === 'undefined' || selected_burial_options[burial_options_name].decide_later === false)
        //             )
        //             {
        //                 new_collapsed_burial_options[burial_options_name] = false;
        //                 change_collapsed_step = 2;
        //             }
                    
        //         }

        //         if(burial_options_name === changed_burial_option)
        //         {
        //             new_collapsed_burial_options[changed_burial_option] = true;
        //             change_collapsed_step = 1;
        //         }
        //     }
            

        //     return new_collapsed_burial_options;
        // }
    }
}
