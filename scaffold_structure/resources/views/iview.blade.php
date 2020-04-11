<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO -->
        <!-- <title>Mazisou.com - Online tools for free!</title>
        <meta name="description" content="http://www.mazisou.com is a website with various day-to-day online tools. From speed test, calculators to computer programming.">
        <link rel="canonical" href="http://www.mazisou.com">
        <meta name="robots" content="noindex,nofollow"> -->
        <!-- SEO end -->


    </head>
    <body>



        <div id="app">
            <app></app>
        </div>

        
        <link rel="stylesheet" href="https://unpkg.com/view-design@4.1.3/dist/styles/iview.css">
        
        <script src="{{ url('/') }}/js/app.js"></script>
        <link href="{{ url('/') }}/css/app.css" rel="stylesheet" type="text/css">
    </body>
</html>
