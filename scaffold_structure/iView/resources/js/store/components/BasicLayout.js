var state = {
    breadcrumbs: [],
};

export default {
    namespaced: true,
    state: state,
    mutations: {    //must be synchronous!! ta "actions" einai workflows praktika!!
        set_breadcrumbs (state, data) {
            state.breadcrumbs = data;
        },
    },
    getters: {
        breadcrumbs: (state) => {
            // console.log(data);
            return state.breadcrumbs;
        }
    },
};
