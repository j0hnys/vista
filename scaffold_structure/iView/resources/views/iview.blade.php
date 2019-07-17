<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO -->
        <!-- <title>example.com - example!</title>
        <meta name="description" content="http://www.example.com is an example website.">
        <link rel="canonical" href="http://www.example.com">
        <meta name="robots" content="noindex,nofollow"> -->
        <!-- SEO end -->

    </head>
    <body>



        <div id="app">
            <app></app>
        </div>

        
        <link rel="stylesheet" href="https://unpkg.com/iview@3.0.1/dist/styles/iview.css">
        
        <script src="{{ url('/') }}/js/app.js"></script>
        <link href="{{ url('/') }}/css/app.css" rel="stylesheet" type="text/css">
    </body>
</html>
