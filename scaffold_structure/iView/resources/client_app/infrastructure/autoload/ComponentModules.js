const componentModule = require.context("../../presentation/components", true, /\.vue$/);
let component_modules = {};

componentModule.keys().forEach(file_name => {
    const module_ = componentModule(file_name).default;    
    component_modules[module_.namespace] = module_;
});

export default component_modules;
