const requireModule = require.context("../../application/api/server", true, /\.js$/);
var api_modules = {};

requireModule.keys().forEach(file_name => {
    const module_ = requireModule(file_name).default;
    api_modules[module_.namespace] = module_;
});

export default api_modules;
