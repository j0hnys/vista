let component_modules = {};

const pagesModule = require.context("../../presentation/pages", true, /\.vue$/);

pagesModule.keys().forEach(file_name => {
    const module_ = pagesModule(file_name).default;
    component_modules[module_.namespace] = module_;
});

export default component_modules;
