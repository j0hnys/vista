const requireModule = require.context("../../presentation/mixins", true, /\.js$/);
var mixin_modules = {};

requireModule.keys().forEach(file_name => {
    const module_ = requireModule(file_name).default;
    mixin_modules[module_.namespace] = module_;
});

export default mixin_modules;
