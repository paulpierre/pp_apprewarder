<?php
$API_HOST = API_HOST;

print  <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><title>AppRewarder</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0" name="viewport">

<style type="text/css">
    @import url(http://fonts.googleapis.com/css?family=Titillium+Web:400,900,600,300,700);

    body {
        margin:0;
        padding:0;
        background-color:#f3f3f3;
        font-family:"Titillium Web";
    }

    #header {
        background:url(/view/img/img-logo-header.png) #ef0d31 no-repeat center 5px;
        background-size:130px 37px;
        width:100%;
        display:block;
        height:50px;
    }

    #content {
        border-radius:10px;
        padding:20px;
        width:90%;

        margin:0 auto;
        background-color:#fff;
        min-height:160px;
        font-size:18px;
    }
    a.btn {
        border-radius:7px;
        background:#98ca5f;
        color:#fff;
        font-weight:300;
        padding:15px;
        margin:0 auto;
        text-decoration:none;
        display:block;
        box-shadow:0 5px #72943a;
        text-align:center;

    }

    h1 {
        text-align:center;
        width:100%;
        font-size:50px;
        margin:0 0 10px 0;
    }

    h1.success {
        color:#98ca5f;
        text-shadow:0 1px #fff;

    }

    h1.error {
        color:#bf0927;
        text-shadow:0 1px #fff;
    }


    #character {
        background:url(/view/img/img-tooltip-char.png) no-repeat;
        background-size:75px 79px;
        float:left;
        display:inline-block;
        width:75px;
        height:79px;
        padding-right:25px;
    }

</style>
<body>
    <div id="header"></div>
    <h1 class="success">$MESSAGE_HEADER</h1>
    <div id="content">
    <span id="character"></span>
    $MESSAGE_BODY
    </div>
    <script>
    if(typeof Android == 'object') Android.didLoad();
    $JS_INJECT


    </script>
</body>
EOT;
?>