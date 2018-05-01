<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <!--<formhub heading="Select table and country" geturl="api/tablehub" posturl="api/tablehub"></formhub>-->
            <!--<formhub heading="Select the way the columns should be edited" geturl="api/formlines/componenttype/formhubs" posturl="api/formlines"></formhub>-->
            <!--formhub heading="Create the label for each field" geturl="api/formlines/labels/formhubs" posturl="api/formlines/labels/formhubs"></formhub-->
            <!--selectedit language="no" formid="7"></selectedit-->
            <!--uform geturl="api/form/formhubs/no"></uform-->
            <router-link to='/form/api%2Fformhub/api%2Fformhub/Test'>Testing</router-link>
        </div>
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
