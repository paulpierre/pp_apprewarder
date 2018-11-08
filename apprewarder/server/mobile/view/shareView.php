<h3 class="full-width text-center ar-page-title">Share</h3>
<div id="banner-container"></div>
<div  class="wrapped-content text-center bg-dark">

    <div id="ar-share-container">
        <div  class="bg-dark full-width">
            <h3>Share your Referral Code</h3>
            <div>
                Why share? Anyone that signs up with your referral code or link gets <b><? print REFERRAL_SIGNUP_BONUS; ?></b> <span class="coin-balance-icon"></span><b>coins</b> and
                you get 50% of whatever they earn! Friends will need to reach a minimum of <b><? print REFERRAL_PAYOUT_MIN_BONUS; ?></b> coins.

            </div>
            <a href="<? print FB_FAN_PAGE; ?>" class="button radius small button-facebook facebook-button-shadow"><span class="icon-thumbs-up"></span></a>


            <a href="<? print FB_REFERRAL_WALL_POST_URL . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_FB . '&t='. urlencode(REFERRAL_FB_MESSAGE . ' My referral code is:' . $userFriendCode); ?>" class="button radius small button-facebook facebook-button-shadow"><span class="icon-facebook"></span></a>


            <a href="<? print TWITTER_REFERRAL_TWEET_URL . urlencode(REFERRAL_TWITTER_MESSAGE . ' CODE: ' . $userFriendCode) . '&url=' . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_TWITTER; ?>" class="button radius small button-twitter twitter-button-shadow"><span class="icon-twitter" style="position:relative;right:5px;"></span></a>


            <a href="<? print GOOGLE_PLUS_URL . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_GOOGLEPLUS;?>" class="button radius small button-googleplus google-button-shadow"><span class="icon-google" style="position:relative;right:5px;"></span></a>

            <a href="<? print SMS_REFERRAL_URL . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_SMS; ?>" class="button radius small button-sms sms-button-shadow"><span class="icon-chat-1"></span></a>

            <a href="<? print MAIL_REFERRAL_URL . USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_EMAIL; ?>" class="button radius small button-email green-shadow"><span class="icon-email" style="position:relative;right:5px;"></span></a>
        </div>



        <div class="wrapped-content">
            <script src='<? print JS_RES; ?>/qr.js'></script>
            <script>
                <!--
                var qrcode = new QRCode(document.getElementById("qr-code"), {
                    width : 200,
                    height : 200,
                        colorDark : "#bf0927",
                        colorLight : "#f4f4f4",
                });
                qrcode.makeCode("<? print USER_REFERRAL_URL_BASE . $userFriendCode . '/' . REFERRAL_SOURCE_QR; ?>");
                -->
            </script>

            <div id="qr-code" style="height:150px;-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;"> <strong>Your QR Code</strong></div>
        </div>

    </div>


</div>
<div class="wrapped-content">
    Not making enough coins and want to earn a crazy amount of coins through referrals? Learn the top tips and tricks
    at AppRewarder University!
    <a href="#university" class="ar-btn-university button red-shadow radius full-width"><span class="icon-book"></span>AR University</a>
</div>




<script>
    arLoadPromo('#banner-container','share',function(){
        $('#ar-share-banner-top').royalSlider({
            arrowsNav:false,
            controlNavigationSpacing: 0,
            controlNavigation: 'bullets',
            imageScaleMode: 'fill',
            imageAlignCenter:true,
            blockLoop: true,
            loop: true,
            numImagesToPreload: 6,
            transitionType: 'fade',
            keyboardNavEnabled: true,
            block: {
                delay: 400
            },
            fadeinLoadedSlide:true,
            autoPlay: {
                enabled: true
            }
        });
    });
</script>
