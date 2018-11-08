<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{$APP_META.APP_NAME} - {$APP_META.APP_DESCRIPTION}">
    <meta name="author" content="{$APP_META.APP_AUTHOR}">
    <link rel="shortcut icon" href="favicon.ico">
    <title>{$page_data.page_title}</title>
    <script src="{$RESOURCE_PATH.JS_PATH}jquery-1.10.2.min.js"></script>

    <link href="{$RESOURCE_PATH.CSS_PATH}bootstrap.min.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}bootstrap-theme.min.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}main.css" rel="stylesheet">
    <script src="{$RESOURCE_PATH.JS_PATH}bootstrap.min.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}html5shiv.js"></script><!--https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js  -->
    <script src="{$RESOURCE_PATH.JS_PATH}respond.min.js"></script><!--https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js-->

</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/home"></a>
        </div>

    </div>
</div>

<div class="container" style="width:900px;">


    <div class="page-header">
        <h1>AppRewarder Login</h1>
    </div>
        <div class="alert alert-warning">Your IP <strong>{$page_data.USER_IP}</strong> is being logged. Unauthorized access will be decimated by Lord Cthulu.</div>

        <div class="panel panel-info">
            <div class="panel-body">
                <form class="navbar-form navbar-left" action='http://{$API_HOST}/' method='post' xmlns="http://www.w3.org/1999/html">
                    <h3>Login</h3>

                    <div class="form-group">
                        <input type="text" name="username" class="form-control" style="width:300px; height:60px; font-size:24px;" placeholder="Who be you?">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password"  class="form-control"  style="width:300px; height:60px; font-size:24px;" placeholder="">
                    </div>
                    <button type="submit" class="btn btn-default"  style=" height:60px; font-size:24px;">Authenticate</button>
                </form>
            </div>
        </div>
</div>
