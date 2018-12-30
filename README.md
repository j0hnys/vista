# vista
laravel UI generator using iview, vue

this package is alpha software at the moment

# Introduction

The purpose of this package is to generate a web UI for a laravel application using laravel's models, migrations and validations (FormRequests).

The package generates a Single Page Application (SPA) and generates CRUD functionallity through custom artisan commands which generate code. It uses vue js and iview UI component library as a foundation.

## Application architecture

### Preface

The main design principles behind the SPA architecture is to set a thin client structure which communicates with the server through restful API's. It wants to promote the notion that the front-end should be only for presentation, leaving the back-end to do the heavy lifting. This is the main reason for a more simplified routing and menu architecture. The code generation that is provided through this package's artisan commands imposes this simplicity. 

### Folder structure

The package on install creates among others the following folder structure

```
|* public_*
    |- css
    |- js
    |->.htaccess
    |->favicon.ico
    |->index.php
    |->robots.txt
|* resources_*
    |- js
        |- components
            |->BasicLayout.vue
        |- navigation
            |- main_menu
                |->main_menu.js
            |- sub_menus
                |->demo_sub_menus.js
        |- pages
            |->index.vuu
        |->app.js
        |->app.vue
        |->bootstrap.js
        |->router.js
        |->router.pages.js
        |->router.submenus.js
    |- sass
    |- views
        |->iview.blade.php
```
Where `|* public_*` and `|* resources_*` is the SPAs directories in the root folder of a laravel application.

This package supports multiple SPA's from one laravel instance. For example we could have a pair `|* public_front_end`, `|* resources_front_end` and `|* public_back_end`, `|* resources_back_end` that work in parallel with the same controllers, models, e.t.c. just by configuring webpack.mix.js accordingly. This is described in more details at the installation section below.

The general workflow for adding a new page follows the following pattern:

- creating a vue component in the `pages` folder
- updating the `router.js` with this new vue component
- updating the `main_menu.js` in order to have a visual route to the new page

The main layout is presented in the image below

![main_layout](images/main_menu_sub_menu_(edited).png)

There are four menu levels split in main menu and submenus. Main menu is at the left sidebar and is the same for every page, it can facilitate two levels. Sub menu's are defined in the router and presented above the content and can facilitate another two levels. There is also place for notifications and user account in the top right corner. 

As of now an automated CRUD functionallity is provided by code generation. It creates three pages (`*_create.vue`, `*_list_delete.vue`, `*_update.vue`) at the `pages` folder which correspond to viewing and deleting records to the database from a model and creating, updating accordingly. In the images below this functionallity is presented.

List/Delete:

![list_delete](images/list_delete.png)

Create/Update:

![create_update](images/create_update.png)



# installation

## to add to a laravel project as a package
add 
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/j0hnys/vista"
    }
],
```
and 
```json
"require-dev": {
    "j0hnys/vista": "dev-master"
},
```
to laravel's `composer.json` and then execute `composer update`

add
```json
"dependencies": {
    "iview": "^3.0.0",
    "iview-loader": "*",
    "vue": "^2.5.16",
    "vuex": "^3.0.1",
    "vue-router": "^2.8.1"
}
```
to laravel's `package.json` and then execute `npm install`


After publishing the configuration of this package (`php artisan vendor:publish`) the configuration can be found at `config/vista.php`. Below there is a snippet of that file

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Single Page Application Paths
    |--------------------------------------------------------------------------
    */

    'spas' => [
        [
            'resource_folder_name' => 'resources_front_test',
            'public_folder_name' => 'public_front_test',
            'mix_base_url_env_name' => 'MIX_BASE_URL_FRONT_TEST',
            'mix_base_relative_url_env_name' => 'MIX_BASE_RELATIVE_URL_FRONT_TEST',
            'mix_storage_url_env_name' => 'MIX_STORAGE_URL_FRONT_TEST',
        ],
    ],

];
```

As said in the application architecture section more than one SPA's can be created

After setting the configuration file execute:
```
php artisan vista:install resources_test_test
```

to install the `resources_test_test` SPA.

Lastly the `webpack.mix.js` has to be modified in order to support more than one SPA's. A sample `webpack.mix.js` is provided below:
```js
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath(('../vista-framework/'));

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.js('resources_front_test/js/app.js', 'public_front_test/js')
   .sass('resources_front_test/sass/app.scss', 'public_front_test/css');
```

# Available artisan commands

| Command | Description |
|---|---|
vista:export:model  | export a model's schema
vista:generate:crud | Create a Spa CRUD
vista:install       | Vista installer

# Basic usage

## CRUD

First we need to retrive the data from the model in order to create the CRUD pages. 

First we need to export the data to a json file using the following artisan command
```
php artisan vista:export:model test_model
```

where `test_model` is a laravel model.

and than execute:
```
php artisan vista:generate:crud test_tt 'app/Models/Schemas/Exports/test_model.json' resources_front_test
```

where the first parameter is the exported model and the second is the SPA that the pages are going to be created.

After completing this process the following changes have taken place:

- A new set of pages will be created at `pages` folder (`test_tt_list_delete.vue`, `test_tt_list_delete.vue`, `test_tt_update.vue`)  
- the `router.js` will be automatically updated with the routes for the new pages

Menu placement is left to the developer. It is done by modifying `main_menu.js` or sub_menus accordingly,