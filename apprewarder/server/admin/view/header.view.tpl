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
    <script src="{$RESOURCE_PATH.JS_PATH}jquery-ui-1.10.4.custom.min.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}jquery.validate.min.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}jquery.dataTables.min.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}dataTables.bootstrap.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}jquery.dataTables.editable.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}jquery.jeditable.mini.js"></script>


    <script src="{$RESOURCE_PATH.JS_PATH}isotope.js"></script>


    <link href="{$RESOURCE_PATH.CSS_PATH}jquery.tagit.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}tagit.ui-zendesk.css" rel="stylesheet">
    <script src="{$RESOURCE_PATH.JS_PATH}tag-it.js"></script>


    <script src="{$RESOURCE_PATH.JS_PATH}nprogress.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}lib/d3.v3.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}nv.d3.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}utils.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}models/axis.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}tooltip.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}interactiveLayer.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}models/legend.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}models/scatter.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}models/stackedArea.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}models/stackedAreaChart.js"></script>
    <link href="{$RESOURCE_PATH.CSS_PATH}nv.d3.css" rel="stylesheet" type="text/css">
    <link href="{$RESOURCE_PATH.CSS_PATH}datepicker.css" rel="stylesheet" type="text/css">
    <link href="{$RESOURCE_PATH.CSS_PATH}flags.css" rel="stylesheet" type="text/css">
    <link href="{$RESOURCE_PATH.CSS_PATH}nprogress.css" rel="stylesheet" type="text/css">

    <link href="{$RESOURCE_PATH.CSS_PATH}bootstrap-switch.css" rel="stylesheet" type="text/css">
    <script src="{$RESOURCE_PATH.JS_PATH}bootstrap-switch.js"></script>

    <script src="{$RESOURCE_PATH.JS_PATH}bootstrap-datepicker.js"></script>

    <link href="{$RESOURCE_PATH.CSS_PATH}modal.css" rel="stylesheet" type="text/css">
    <link href="{$RESOURCE_PATH.CSS_PATH}animate.css" rel="stylesheet" type="text/css">
    <script src="{$RESOURCE_PATH.JS_PATH}{$page_data.page_name}.js"></script>



    <script src="{$RESOURCE_PATH.JS_PATH}main.js"></script>

    <link href="{$RESOURCE_PATH.CSS_PATH}bootstrap.min.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}dataTables.bootstrap.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}bootstrap-theme.min.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <link href="{$RESOURCE_PATH.CSS_PATH}main.css" rel="stylesheet">
    <script src="{$RESOURCE_PATH.JS_PATH}bootstrap.min.js"></script>
    <script src="{$RESOURCE_PATH.JS_PATH}modal.js"></script>


    <script src="{$RESOURCE_PATH.JS_PATH}html5shiv.js"></script><!--https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js  -->
    <script src="{$RESOURCE_PATH.JS_PATH}respond.min.js"></script><!--https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js-->


    <link href="{$RESOURCE_PATH.CSS_PATH}nv.d3.css" rel="stylesheet" type="text/css">



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
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li{if $page_data.page_name == "home"} class="active"{/if}><a href="/home"><b class="glyphicon glyphicon-home"></b> Dashboard</a></li>
                <li{if $page_data.page_name == "offer"} class="active"{/if}><a href="/offer#offer-add"><b class="glyphicon glyphicon-th-large"></b> Offers</a></li>
                <li{if $page_data.page_name == "reward"} class="active"{/if}><a href="/reward"><b class="glyphicon glyphicon-gift"></b> Rewards</a></li>
                <li{if $page_data.page_name == "fraud"} class="active"{/if}><a href="/fraud"><b class="glyphicon glyphicon-warning-sign"></b> Fraud</a></li>
                <li{if $page_data.page_name == "user"} class="active"{/if}><a href="/user"><b class="glyphicon glyphicon-user"></b> Users</a></li>
                <li{if $page_data.page_name == "message"} class="active"{/if}><a href="/message"><b class="glyphicon glyphicon-envelope"></b> Messaging</a></li>
                <li{if $page_data.page_name == "config"} class="active"{/if}><a href="/config"><b class="glyphicon glyphicon-cog"></b> Settings</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container">


