<?

$userPlatform = $userInstance->get_user_platform();
if(    $userPlatform == PLATFORM_IOS
    || $userPlatform == PLATFORM_IPOD
    || $userPlatform == PLATFORM_IPHONE
    || $userPlatform == PLATFORM_IPAD) { $isiOS = true; } else $isiOS = false;
switch($controllerFunction)
{
    case 'home':
        $userInstance->process_user_account_status();
        if(!$userInstance->didRegister) exit(); //dont show banners if they arent registered
        ?>
            <div id="ar-home-banner-top" class="royalSlider rsMinW">
                <div class="rsContent">
                    <div class="full-width text-center">
                        <a href="#reward">
                            <img class="rsImg" src="<? print IMG_RES ?>/banners/banner_09.png" data-rsw="700" data-rsh="260" />
                        </a>

                    </div>
                </div>
                <div class="rsContent">

                <div class="full-width text-center">
                        <a href="#"reward>
                            <img class="rsImg" src="<? print IMG_RES ?>/banners/banner_08.png" data-rsw="700" data-rsh="260" />
                        </a>
                </div>
                </div>
                <div class="rsContent">

                <div class="full-width text-center">
                        <a href="#" id="ar-btn-facebook-like">
                            <img class="rsImg" src="<? print IMG_RES ?>/banners/banner_07.png" data-rsw="700" data-rsh="260" />
                        </a>
                    </div>
                </div>
                </div>
                <!--
                <div class="rsContent t01" >
                    <div class="bContainer">
                        <div class="text rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true">AppRewarder Pays You To Play Apps You Love</div>
                        <div class="icons rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></div>
                    </div>
                </div>

                <div class="rsContent t02">
                    <div class="bContainer">
                        <span class="bg01 rsABlock" data-move-effect="left" data-fade-effect="true"></span>
                        <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="false"></span>
                    </div>
                </div>

                <div class="rsContent t02">
                    <div class="bContainer">
                        <span class="bg02 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
                        <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></span>
                    </div>
                </div>

                <div class="rsContent t02">
                    <div class="bContainer">
                        <span class="bg03 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
                    </div>
                </div>

                <div class="rsContent t03">
                    <div class="bContainer">
                        <span class="bg01 rsABlock"  data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>

                        <span class="gifts rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                        <span class="cards rsABlock" data-move-effect="bottom"  data-delay="500" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                    </div>
                </div>

                <div class="rsContent t04">
                    <div class="bContainer">
                        <span class="bg01 rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>
                        <span class="network rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                    </div>
                </div>
             -->
            </div>
        <?
        break;

    case 'offers':
        exit();
        ?>
    <div id="ar-home-banner-top" class="royalSlider rsMinW">
        <div class="rsContent t01" >
            <div class="bContainer">
                <div class="text rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true">AppRewarder Pays You To Play Apps You Love</div>
                <div class="icons rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></div>
            </div>
        </div>

        <div class="rsContent t02">
            <div class="bContainer">
                <span class="bg01 rsABlock" data-move-effect="left" data-fade-effect="true"></span>
                <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="false"></span>
            </div>
        </div>

        <div class="rsContent t02">
            <div class="bContainer">
                <span class="bg02 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
                <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></span>
            </div>
        </div>

        <div class="rsContent t02">
            <div class="bContainer">
                <span class="bg03 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
            </div>
        </div>

        <div class="rsContent t03">
            <div class="bContainer">
                <span class="bg01 rsABlock"  data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>

                <span class="gifts rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                <span class="cards rsABlock" data-move-effect="bottom"  data-delay="500" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
            </div>
        </div>

        <div class="rsContent t04">
            <div class="bContainer">
                <span class="bg01 rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>
                <span class="network rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
            </div>
        </div>

    </div>
    <?
        break;
    case 'staff':

        $promoBanners = unserialize(PROMO_BANNERS);
        $promoOffers = unserialize(PROMO_OFFERS);
        foreach($promoBanners as $promo) {
            if(($isiOS && $promo['os'] == PLATFORM_ANDROID) || (!$isiOS && $promo['os'] == PLATFORM_IOS)) continue;
            print '<div class="full-width text-center"><img class="rsImg" src="' . $promo['img']. '" data-rsw="700" data-rsh="260" onClick="javascript:window.location=\'' . $promo['url'] . '\';"/></div>';
        }
 ;
        foreach($promoOffers as $promo)
        {
            if(($isiOS && $promo['os'] == PLATFORM_ANDROID) || (!$isiOS && $promo['os'] == PLATFORM_IOS)) continue;
            $url = $promo['url'];
            $icon = $promo['icon'];
            $name = $promo['name'];
print <<<EOL
                <a href="$url" class="app-offer-container ar-modal">
                    <div class="app-offer">
                        <div class="app-title">$name</div>
                        <div class="app-thumb" style="background-image:url($icon);">
                            <span></span>
                        </div>
                        <div class="app-payout">
                            <div class="app-user-payout"><span class="coin-payout-icon"></span><span class="app-payout-value">0</span></div>
                        </div>
                    </div>
                </a>
EOL;
        }
       ?>
        <?


        break;
    case 'reward':
        exit();
        ?>
            <div id="ar-reward-banner-top" class="royalSlider rsMinW">
                <div class="rsContent t01" >
                    <div class="bContainer">
                        <div class="text rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true">AppRewarder Pays You To Play Apps You Love</div>
                        <div class="icons rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></div>
                    </div>
                </div>

                <div class="rsContent t02">
                    <div class="bContainer">
                        <span class="bg01 rsABlock" data-move-effect="left" data-fade-effect="true"></span>
                        <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="false"></span>
                    </div>
                </div>

                <div class="rsContent t02">
                    <div class="bContainer">
                        <span class="bg02 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
                        <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></span>
                    </div>
                </div>

                <div class="rsContent t02">
                    <div class="bContainer">
                        <span class="bg03 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
                    </div>
                </div>

                <div class="rsContent t03">
                    <div class="bContainer">
                        <span class="bg01 rsABlock"  data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>

                        <span class="gifts rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                        <span class="cards rsABlock" data-move-effect="bottom"  data-delay="500" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                    </div>
                </div>

                <div class="rsContent t04">
                    <div class="bContainer">
                        <span class="bg01 rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>
                        <span class="network rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                    </div>
                </div>

            </div>
        <?
        break;

    case 'share':
        ?>
    <div id="ar-share-banner-top" class="royalSlider rsMinW">
        <div class="rsContent t01" >
            <div class="bContainer">
                <div class="text rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true">AppRewarder Pays You To Play Apps You Love</div>
                <div class="icons rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></div>
            </div>
        </div>

        <div class="rsContent t02">
            <div class="bContainer">
                <span class="bg01 rsABlock" data-move-effect="left" data-fade-effect="true"></span>
                <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="false"></span>
            </div>
        </div>

        <div class="rsContent t02">
            <div class="bContainer">
                <span class="bg02 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
                <span class="finger rsABlock" data-move-effect="bottom"  data-delay="1200" data-move-offset="100" data-fade-effect="true"></span>
            </div>
        </div>

        <div class="rsContent t02">
            <div class="bContainer">
                <span class="bg03 rsABlock" data-fade-effect="true" data-move-effect="none" data-delay="0"></span>
            </div>
        </div>

        <div class="rsContent t03">
            <div class="bContainer">
                <span class="bg01 rsABlock"  data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>

                <span class="gifts rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
                <span class="cards rsABlock" data-move-effect="bottom"  data-delay="500" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
            </div>
        </div>

        <div class="rsContent t04">
            <div class="bContainer">
                <span class="bg01 rsABlock" data-move-effect="top"  data-delay="0" data-move-offset="100" data-fade-effect="true"></span>
                <span class="network rsABlock" data-move-effect="right"  data-delay="1000" data-move-offset="100" data-fade-effect="true" data-easing="easeOutBack"></span>
            </div>
        </div>

    </div>
    <script>
        <?
        break;
}
?>