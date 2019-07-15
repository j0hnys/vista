const modules = {};

if (process.env.NODE_ENV == 'development') {
    const requireModule = require.context("./schemas", true, /\.js$/); //extract js files inside modules folder
    requireModule.keys().forEach(fileName => {
        var module_ = requireModule(fileName).default;
        modules[module_.namespace] = module_;
    });
}

var checkNamespace = function (namespace, data) {    
    if (process.env.NODE_ENV != 'development') {
        return true;
    } else if (!modules[namespace]) {
        throw new Error('namespace: "'+namespace+'" is not defined"');
    }
    if (modules[namespace]) {
        modules[namespace].assert(data);
    }
};

var check = function (hello_type, data) {    
    if (process.env.NODE_ENV != 'development') {
        return true;
    }

    hello_type.assert(data);
};


export default {check, checkNamespace};