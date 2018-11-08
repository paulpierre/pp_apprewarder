<?
global $userEmail, $userFriendCode,$userCredits, $userInstance, $utilityInstance;


?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width" />
<title>AppRewarder - Take What's Yours</title>
<meta name="description" content="AppRewarder - Take What's Yours. Earn rewards you for using and playing with the apps you love.">
<meta name="keywords" content="AppRewarder AppRewarder iPhone iOS Android Nexus Play App Store Game Achievement Win Prizes Redeem">
<meta name="author" content="AppRewarder">
<link href="<? print CSS_RES; ?>/ar-slider.css" rel="stylesheet"/>
<link href="<? print CSS_RES; ?>/ar-animate.css" rel="stylesheet"/>
<link href="<? print CSS_RES; ?>/ar-slider-theme.css" rel="stylesheet"/>
<link href="<? print CSS_RES; ?>/flags.css" rel="stylesheet"/>


    <!-- Web App Data -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!-- Icons -->
<!-- non-retina iPhone pre iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon57.png" sizes="57x57">
<!-- non-retina iPad pre iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon72.png" sizes="72x72">
<!-- non-retina iPad iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon76.png" sizes="76x76">
<!-- retina iPhone pre iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon114.png" sizes="114x114">
<!-- retina iPhone iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon120.png" sizes="120x120">
<!-- retina iPad pre iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon144.png" sizes="144x144">
<!-- retina iPad iOS 7 -->
<link rel="apple-touch-icon-precomposed" href="img/webapp/icon152.png" sizes="152x152">


<!-- iPhone SPLASHSCREEN-->
<link href="img/webapp/apple-touch-startup-image-320x460.png" media="(device-width: 320px)" rel="apple-touch-startup-image">
<!-- iPhone (Retina) SPLASHSCREEN-->
<link href="img/webapp/apple-touch-startup-image-640x920.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<!-- iPhone SPLASHSCREEN 4" -->
<link href="img/webapp/apple-touch-startup-image-640x1096.png" rel="apple-touch-startup-image" media="(device-height: 568px)">
<!-- iPad (portrait) SPLASHSCREEN-->
<link href="img/webapp/apple-touch-startup-image-768x1004.png" media="(device-width: 768px) and (orientation: portrait)" rel="apple-touch-startup-image">
<!-- iPad (landscape) SPLASHSCREEN-->
<link href="img/webapp/apple-touch-startup-image-1024x748.png" media="(device-width: 768px) and (orientation: landscape)" rel="apple-touch-startup-image">
<!-- iPad (Retina, portrait) SPLASHSCREEN-->
<link href="img/webapp/apple-touch-startup-image-1536x2008.png" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<!-- iPad (Retina, landscape) SPLASHSCREEN-->
<link href="img/webapp/apple-touch-startup-image-2048x1496.png" media="(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)"media="(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">


<!-- Favicon -->
<link href="img/favicon.ico" rel="shortcut icon" type="image/x-icon">



<!-- Import Styles-->
<link rel='stylesheet' href='<? print CSS_RES; ?>/normalize.css'>
<link rel='stylesheet' href='<? print CSS_RES; ?>/fontello.css'>
<link rel='stylesheet' href='<? print CSS_RES; ?>/style.css'>
<link rel='stylesheet' href='<? print CSS_RES; ?>/modal.css'>

<!-- Custom Scripts -->
<!-- <script data-pace-options='{ "ajax": false,"elements":true }' src= '<? print JS_RES; ?>/load.js'></script> -->
<script src='<? print JS_RES; ?>/jq.js'></script>
<script src='<? print JS_RES; ?>/jqui.js'></script>
<script src='<? print JS_RES; ?>/jqe.js'></script>
<script src='<? print JS_RES; ?>/rs.js?v=9.3.6'></script>
<script src='<? print JS_RES; ?>/modal.js'></script>
<script src='<? print JS_RES; ?>/modernizr.js'></script>
<script src='<? print JS_RES; ?>/ar.php'></script>



<!-- Import GoogleWebFonts -->
<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,100,200,700,500' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>

</head>
<body class="touch-gesture">


<!-- Container -->
<div id="container">

    <!-- Main Content-->
<? if($userInstance->user_is_logged_in()){ ?>
    <!-- Sidebar -->
    <aside id="sidemenu-container">
        <div id="sidemenu">
            <div id="author-profile">


                <? if($userInstance->user_has_facebook()) { ?>
                <div class="author-profile-photo">
                    <a href="#home"><img src="http://graph.facebook.com/<? print $_SESSION['userFacebookID'];?>/picture?type=square" alt="Profile"></a>
                </div>

                                <div class="author-profile-content">
                <p class="title" style="font-weight:bold;color:#FFF;"><? print $_SESSION['userName']; ?></p>

                <? } else { ?>
                <div class="author-profile-photo">
                    <a href="#home"><img src="<? print IMG_RES;?>/img-user-profile.png" alt="Profile"></a>
                </div>

                <div class="author-profile-content">
                    <a href="#" class='facebook-connect-button'></a>
                    <p class="title" style="display:none;">Tap to connect</p>
                    <? } ?>
                    <p class="subtitle" ><b>Referral Code:</b> <b><a href="#share" class="profile-friend-code"><? print $userFriendCode; ?></a></b></p>
                </div>
            </div>
            <nav id="nav-container">
                <ul class="nav">
                    <li class='current'><a class='ar-nav' href="#refresh"><span class='menu-icon icon-arrows-ccw'></span>Refresh</a></li>

                    <li class='current'><a class='ar-nav' href="#home"><span class='menu-icon icon-home'></span>Home</a></li>
                    <li><a href="#offers" class="ar-nav"><span class='menu-icon icon-grid'></span>Offers</a>
                    </li>
                    <li>
                        <a href="#reward" class="ar-nav"><span class='menu-icon icon-cart'></span>Rewards</a>
                    </li>
                    <li>
                        <a href="#share" class="ar-nav"><span class='menu-icon icon-share'></span>Share & Earn</a>
                    </li>
                    <li>
                        <a href="#history" class="ar-nav"><span class='menu-icon icon-clock'></span>History</a>
                    </li>
                   <!-- <li>
                        <a href="<? print SERVER_PROTOCOL . FORUM_HOST?>" class="ar-nav"><span class='menu-icon icon-info'></span>Forums</a>
                    </li> -->
                    <li>
                        <a href="#help" class="ar-nav"><span class='menu-icon icon-help'></span>Help</a>
                    </li>
                    <? if($userInstance->user_is_admin()){ ?><li><a href="#logout" ><span class='menu-icon icon-block'></span>Sign Out</a></li><? } ?>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- =Sidebar -->
<? } ?>

    <section id="content-container" class="dark">

        <!-- Header -->
        <header id="header">
            <div id="menu-trigger" class="header-button left icon-menu"></div>
            <h1><a href="#home"><span class='ar-logo'></span></a></h1>
            <div id="ar-notification-badge" class="ar-menu-badge">3</div>
            <a href="#history" id="coin-trigger" class="header-button right coin-balance-container"><span class="coin-balance-icon"></span><span class="coin-balance-value"><? print intval($userCredits); ?></span></a>
        </header>
        <!-- =Header -->



        <div id="content-wrapper">
            <div id="content">

            </div>
        </div>
<? if($userInstance->user_is_logged_in()){ ?>


        <!-- MENU -->
        <div id="ar-home-menu" class="wrapped-content bg-light bg-diag">



            <!-- =TAB SLIDER CONTENT -->
            <div class="text-center" id="ar-home-social">
                <!--
                <div class="button-social-text full-width text-center">
                    <b class="blue">Share on social media</b><br/>You <span class="icon-user"> +<span class="coin-icon"></span>1,000</span>
                        <span class="icon-right">Referral<span class="icon-user"></span>+<span class="coin-icon"></span>200
                </div>-->

                <div class="ar-social-referral full-width text-center">
                    <h3>Refer & Earn
                        <a href="#" class="icon-help ar-tip" title="What are Referral Codes?" text="Copy this referral code and share it with people online. When they sign up, you both receive coins for signing up and you get 50% bonus of all their LIFE TIME earnings!"></a>
                    </h3>
                    <p style="padding:0 5px 0 5px;margin:0;">
                        Refer this app to friends and earn 50% of their points!
                    </p>
                    <div class="ar-referralcode-container text-center">

                        <span class="button-social-text">Referral code:</span>
                        <input id="ar-friend-code" name="ar-friend-code" value="<? print $userFriendCode; ?>" type="text"/>

                        <br/>
                        <span class="button-social-text">Link:</span>
                        <input  id="ar-friend-code-url" name="ar-friend-code" value="<? print  USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_MANUAL; ?>" type="text"/>
                        <a href="#share" class="button radius full-width blue twitter-button-shadow ar-share-link">Start referring now!</span></a>
                    </div>
                </div>
            </div>
            <? if($userInstance->user_is_admin()){ ?><a href="/debug" class="button grey-shadow small radius full-width app-menu-button">Debug</a><? } ?>
            <a href="#offers" class="button grey-shadow small radius full-width app-menu-button"><span class="icon-grid"></span>Today's Offers</a>
            <a href="#reward" class="button grey-shadow small radius full-width app-menu-button"><span class="icon-cart"></span>Rewards</a>
            <a href="#share" class="button grey-shadow small radius app-menu-button full-width"><span class="icon-share"></span>Share & Earn</a>
            <a href="#history" class="button grey-shadow small radius app-menu-button full-width"><span class="icon-clock"></span>History</a>
            <a href="#help" class="button grey-shadow small radius app-menu-button full-width"><span class="icon-help"></span>Help</a>
        </div>


        <div class="ar-share-widgets full-width bg-dark">
            <p class="text-center share-widget full-width">
            <div class="full-width text-center social-buttons">
                <a href="<? print FB_FAN_PAGE; ?>" class="button radius small button-facebook facebook-button-shadow"><span class="icon-thumbs-up"></span></a>
                <a href="<? print FB_REFERRAL_WALL_POST_URL . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_FB . '&t='. urlencode(REFERRAL_FB_MESSAGE . ' My referral code is:' . $userFriendCode); ?>" class="button radius small button-facebook facebook-button-shadow"><span class="icon-facebook"></span></a>
                <a href="<? print TWITTER_REFERRAL_TWEET_URL . urlencode(REFERRAL_TWITTER_MESSAGE . ' CODE: ' . $userFriendCode) . '&url=' . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_TWITTER; ?>" class="button radius small button-twitter twitter-button-shadow"><span class="icon-twitter"></span></a>
                <a href="<? print GOOGLE_PLUS_URL . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_GOOGLEPLUS;?>" class="button radius small button-googleplus google-button-shadow"><span class="icon-google"></span></a>
            </div>
            </p>
        </div>

        <? } ?>
        <!-- =Container -->
    </section>
</div>



<a href="#" class="btn-top-fixed icon-up-open"></a>
<? if(MODE == 'prod') { ?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-46364927-3', 'apprewarder.com');
    ga('send', 'pageview');

</script>
<? } ?>
</body>
</html>