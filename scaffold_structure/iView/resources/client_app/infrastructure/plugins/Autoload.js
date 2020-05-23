import api_modules from '../autoload/ApiModules';
import mixin_modules from '../autoload/MixinModules';
import component_modules from '../autoload/ComponentModules';
import page_modules from '../autoload/PageModules';

var register_modules = {};
register_modules.install = function (Vue) 
{
    Object.values(component_modules).forEach(component => {
        if (typeof component.name === 'undefined') {
            throw "component.name in \""+component.__file+"\" does not exist."
        }
        
        if(Vue.options.components[component.name]) {
            delete Vue.options.components[component.name]
        }

        Vue.component(component.name, component);
    });

    Object.values(page_modules).forEach(page => {        
        if (page.use) {
            page.mixins = [];

            // the main case
            if (Array.isArray(page.use)) {
                let aliased_methods_in_mixin = {};
                let mixins = page.use;

                let return_methods = {};

                for (const index in mixins) {
                    if (mixins.hasOwnProperty(index)) {
                        const element = mixins[index];

                        if (typeof return_methods[element.alias] === 'undefined') {
                            return_methods[element.alias] = [];
                        }

                        // registration
                        if (mixin_modules[element.namespace]) {                                
                            return_methods[element.alias].push(mixin_modules[element.namespace].methods);
                        } else if (api_modules[element.namespace]) {
                            return_methods[element.alias].push(api_modules[element.namespace].methods);
                        } else {
                            throw "Namespace: \""+element.namespace+"\" does not exist."
                        }
                    }
                }

                for (const alias in return_methods) {
                    if (return_methods.hasOwnProperty(alias)) {
                        const element_ = return_methods[alias];
                        
                        aliased_methods_in_mixin[alias] = {
                            [alias]: Object.assign(...Object.values(element_)),
                        };
                    }
                }

                var final_aliased_methods_in_mixin = Object.assign(...Object.values(aliased_methods_in_mixin));

                page.mixins.push({
                    data: () => { return final_aliased_methods_in_mixin; }
                });
            }
        }
    });
};

export default register_modules;