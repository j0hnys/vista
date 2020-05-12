
// //vvv does not work in vanilla laravel
// require('fs').readdirSync(__dirname + '/').forEach(function(file) {
//     if (file.match(/\.vue$/) !== null) {
//         var name = file.replace('.vue', '');
//         exports[name] = require('./' + file);
//     }
// });
// //^^^

//pages
exports.index = require("../modules/presentation/store/pages/Index.js").default;
exports.page1 = require("../modules/presentation/store/pages/Page1.js").default;
