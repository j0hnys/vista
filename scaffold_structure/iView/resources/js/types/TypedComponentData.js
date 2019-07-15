import HelloType, { Dict, Enum, Tuple, List, Type, Rule, Self, IfExists } from 'hello-type'
import TypeChecker from '../types/TypeChecker.js';

var TypedComponentData = {};
TypedComponentData.install = function (Vue, options) {
    
    // console.log({
    //     Vue: Vue,
    // });

    // 1. add global method or property
    Vue.myGlobalMethod = function () {
        // some logic ...
    }
  
    // 2. add a global asset
    Vue.directive('my-directive', {
        bind (el, binding, vnode, oldVnode) {
            // some logic ...
        }      
    })
  
    // 3. inject some component options
    Vue.mixin({
        created() {
            const options = this.$options;
            const types = options.types;
            
            if (types) {
                if (types.namespace) {
                    TypeChecker.checkNamespace(types.namespace, this._data);
                } else if (types.schema) {
                    TypeChecker.check(types.schema(), this._data);
                }
            }
        },
    })
  
    // 4. add an instance method
    Vue.prototype.$myMethod = function (methodOptions) {
        // some logic ...
    }
}

export default TypedComponentData;