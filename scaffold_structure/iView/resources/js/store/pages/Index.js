var state = {
    variable: '',
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        setVariable (state, data) 
        {
            state.variable = data;
        },
    },
    getters: {
        variable (state) 
        {
            return state.variable;
        }
    }
};
