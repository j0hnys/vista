
// //vvv does not work in vanilla laravel
// require('fs').readdirSync(__dirname + '/').forEach(function(file) {
//     if (file.match(/\.js$/) !== null) {
//         var name = file.replace('.vue', '');
//         exports[name] = require('./' + file);
//     }
// });
// //^^^

//pages
exports.demo_sub_menu = require("./navigation/sub_menus/demo_sub_menu.js").default;