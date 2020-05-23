import TypeChecker from '../type_checker/TypeChecker.js';

var TypedComponentData = {};
TypedComponentData.install = function (Vue, options) {    
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
    
    Vue.prototype.$typeChecker = {
        check(namespace, data) {
            return TypeChecker.checkNamespace(namespace, data);
        },
    }
}

export default TypedComponentData;