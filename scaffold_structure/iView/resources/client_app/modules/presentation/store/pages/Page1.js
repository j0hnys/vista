var state = {
    variable: '',
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        DEMO_MUTATION (state, data) 
        {
            state.variable = data;
        },
    },
    getters: {
        variable (state) 
        {
            return state.variable;
        }
    },
    actions: {
        setVariable({ commit }, data) 
        {
            commit('DEMO_MUTATION', data);
        },
    }
};
