
// //vvv does not work in vanilla laravel
// require('fs').readdirSync(__dirname + '/').forEach(function(file) {
//     if (file.match(/\.vue$/) !== null) {
//         var name = file.replace('.vue', '');
//         exports[name] = require('./' + file);
//     }
// });
// //^^^

//pages
exports.index = require("./store/pages/Index.js").default;
