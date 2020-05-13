import axios from 'axios';

const apiClient = {
    getHome: function()
    {
        return axios.get(process.env.MIX_BASE_RELATIVE_URL_BACKEND + '/home');
    }
};

export default apiClient;
