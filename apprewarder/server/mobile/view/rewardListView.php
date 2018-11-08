<? global $userCredits;

?>
<div id="ar-rewards-items" class="full-width bg-light">
    <?
    $rewardInstance = new Reward();
    $userInstance = new User();

    $rewardCount = 1;

    //print 'rewards: ' .count($rewardOffers);

    foreach($rewardOffers as $rewardItem)
    {
        $rewardName = $rewardItem['reward_name'];
        $rewardDescription = $rewardItem['reward_description'];
        $rewardCost = intval($rewardItem['reward_cost']);
        $rewardPayout = intval($rewardItem['reward_payout']);
        $rewardStatus = $rewardItem['reward_status'];
        $rewardProgress = intval((($userCredits < $rewardCost)?floor(($userCredits/$rewardCost) * 100):100));
        $rewardExpiration = $rewardItem['reward_expiration'];
        $rewardID = $rewardItem['reward_id'];
        $rewardDelta = intval($rewardCost - $userCredits);
        $rewardImage = $rewardItem['reward_img'];
        $rewardSource = intval($rewardItem['reward_source_id']);
        $rewardLimit = intval($rewardItem['reward_limit']);

        if($rewardCount == REWARD_PAGE_MAX + 1  && count($rewardOffers) > REWARD_PAGE_MAX) {
            ?><div id="ar-rewards-hidden-container"><?
        }

        if($rewardLimit > 0)
        {   //lets limit if there is a limit of # of times a user can claim a particular reward
            $rewards = $rewardInstance->get_claimed_reward($_SESSION['userID'],$rewardID);
            if(count($rewards) >= $rewardLimit) continue;
        }

        if(intval($rewardExpiration) !== 0 && $rewardExpiration <= time()) continue;

        if(($rewardSource == REWARD_SOURCE_IAP_ANDROID || $rewardSource == REWARD_SOURCE_PAID_ANDROID) && $userInstance->isiOS()) continue;
        if(($rewardSource == REWARD_SOURCE_IAP_IOS || $rewardSource == REWARD_SOURCE_PAID_IOS) && $userInstance->isAndroid()) continue;




        if($rewardSource == REWARD_SOURCE_IAP_IOS || $rewardSource ==  REWARD_SOURCE_IAP_ANDROID)
        {
            $rewardIAPSource = intval($rewardDescription);
            $rewardIAP = $rewardsInstance->get_iap_rewards($rewardSource,$rewardIAPSource);
            //print '<pre>' . print_r($rewardItem,true) .'</pre>';
            ?>

            <div class="ar-reward-iap rsNoDrag" style="background-image:url(<? print IMG_RES; ?>/rewards/<? print $rewardImage; ?>.png);">
                <span class="ar-reward-icon-spacer"></span>
                <?
                foreach($rewardIAP as $IAPItem)
                {
                    $rewardName = $IAPItem['reward_name'];
                    $rewardDescription = $IAPItem['reward_description'];
                    $rewardCost = intval($IAPItem['reward_cost']);
                    $rewardPayout = intval($IAPItem['reward_payout']);
                    $rewardStatus = $IAPItem['reward_status'];
                    $rewardProgress = intval(($userCredits < $rewardCost)?floor(($userCredits/$rewardCost) * 100):100);
                    $rewardExpiration = $IAPItem['reward_expiration'];
                    $rewardID = $IAPItem['reward_id'];
                    $rewardDelta = intval($rewardCost - $userCredits);
                    $rewardImage = $IAPItem['reward_img'];
                    $rewardSource = intval($IAPItem['reward_source_id']);
                    $rewardLimit = intval($IAPItem['reward_limit']);
                    $classImg = $rewardImage;

                    ?>
                    <a href="#" class="rsNoDrag ar-btn-reward" style="background:url(<? print IMG_RES; ?>/rewards/<? print $rewardImage; ?>.png) no-repeat top center;background-size:100px 133px;" rewardSrc="<? print $rewardSource;?>" rewardIcon="<? print $rewardImage;?>" rewardStatus="<? print $rewardStatus;?>" rewardID="<? print $rewardID;?>" rewardName="<? print $rewardName;?>" userBalance="<? print $userCredits;?>" rewardCost="<? print $rewardCost;?>" classImg="<? print $classImg;?>">
                        <div style="margin:0;max-width:60px; padding:5px 7px 5px 7px; position:relative;top:100px;" class="button red-shadow small radius">
                            <span class="coin-icon"></span><b><? print $rewardCost; ?></b>
                        </div>
                    </a>
                    <?
                }
                ?>
            </div>
            <?
        } else
        {

            switch($rewardSource)
            {
                case 0:
                    $classImg = $rewardImage;//'ar-reward-item'; //
                    break;
                case 1:
                    $classImg = 'ar-amazon-card';
                    break;
                case 2:
                    $classImg = 'ar-itunes-card';
                    break;
                case 3:
                    $classImg = 'ar-paypal-card';
                    break;
                case 4:
                    $classImg = 'ar-bitcoin-card';
                    break;

            }

            ?>

            <div class="ar-reward-card">
                        <span class="ar-reward-img-container">
                                <? if($rewardSource == 0) { ?>
                            <img src="<? print $IMG_RES . '/rewards/' . $rewardItem['reward_img'] .'.png' ?>" class="ar-reward-item"/>

                            <? } elseif($rewardSource == 5 ||$rewardSource == 6) { ?>
                            <div style="background-image:url(<? print $rewardImage; ?>);" class="app-thumb"><span></span></div>
                            <? } else {?>
                                   <span class="<? print $classImg; ?>">

                                        <span class="ar-reward-payout">$<? print $rewardPayout; ?></span>
                                   </span>
                            <? }?>

                        </span>
                        <span class="ar-reward-details">

                            <?
                            if ($rewardExpiration > 0 && $rewardSource == 0)
                            {
                                ?>
                                <div class="ar-reward-title"><? print $rewardName; ?></div>
                                <? print $rewardDescription; ?>
                                <div class="ar-expiration" data="<? print $rewardExpiration;?>"></div>
                                <?
                            }
                            elseif ($rewardStatus == 2)
                            {
                                ?>
                                <div class="ar-reward-title ar-reward-sold-out"><? print $rewardName; ?></div>
                                <div class="ar-progress full-width">
                                    <span class="ar-reward-sold-out-text">Sold Out</span>
                                </div>

                                <?
                            } elseif($rewardPayout > 5 && ($rewardProgress > 30) && $rewardDelta > 0)
                            {
                                ?>
                                <div class="ar-reward-title"><? print $rewardName ; ?></div>
                                <div class="ar-progress">
                                    Only <? print $rewardDelta; ?> more coins to go!
                                    <span class="progress"><span class="meter" style="width: <? print $rewardProgress; ?>%"></span></span>
                                </div>

                                <?
                            }
                            else
                            {

                            ?>
                                <div class="ar-reward-title"><? print $rewardName; ?></div>
                                <? print $rewardDescription; ?>


                            <? } ?>
                        </span>


                        <span class="ar-reward-payout <? if($rewardStatus == 2) print 'ar-reward-sold-out';?>">
                            <a href="#" class="ar-btn-reward button red-shadow small radius" rewardSrc="<? print $rewardSource;?>" rewardIcon="<? print $rewardImage;?>" rewardStatus="<? print $rewardStatus;?>" rewardID="<? print $rewardID;?>" rewardName="<? print $rewardName;?>" userBalance="<? print $userCredits;?>" rewardCost="<? print $rewardCost;?>" classImg="<? print $classImg;?>">
                                <span class="coin-icon"></span><b><? print $rewardCost; ?></b>
                            </a>
                        </span>
            </div>
            <?

        }
        $rewardCount++;
    }

    unset($rewardInstance);

    if(count($rewardOffers) > REWARD_PAGE_MAX)
    {
        ?>
        </div>

        <div class="text-center full-width">
            <a href="#" class="btn-load-more-rewards button grey grey-shadow radius"><span class="icon-down-open"></span>Load More</a>
        </div>
        <?
    }
    ?>
</div>



<? if(empty($userEmail) || !$userInstance->didConfirmEmail) { ?>
<div class="wrapped-content bg-light" id="ar-email-register">
    <form id="comments-form" class="full-width" action="">
        <p>
            <label for="userEmail">Register your email to cash out:</label>
            <a href="#" class="icon-help ar-tip" title="Register your email" text="We send rewards and verify users via email. Rewards come in the form of gift card codes you can redeem at any of our partner sites or in BitCoin."></a>

            <input type="email" class="h5-email" id="userEmail"/>
        </p>
        <p class="email-hide">
            <label for="userEmailVerify">Verify email:</label>
            <input type="email" class="h5-email" id="userEmailVerify"/>


        <p class="email-hide text-center">
            <a href="#" class="ar-btn-register-email button radius red-shadow ar-modal">Save email</a>
        </p>

        </p>
    </form>
</div>
<? } ?>
<script>
    jQuery(document).ready(function($) {

        $('.email-hide').hide();
        $('html').on('click','input#userEmail',function(){
            $('.email-hide').slideDown();

        });

        $('html').on('click', '.btn-load-more-rewards',function(e){
            e.preventDefault();
            $('#ar-rewards-hidden-container').slideDown('fast',function(){
                $("#app-content").data('royalSlider').updateSliderSize();

            });
            $(this).hide('fast');
        });


        $('.ar-expiration').each(function(index){
            $(this).css({fontWeight:700,color:'#ff0000',fontSize:'16px'})
            arTimer($(this), ' left');
        });

        $('html').on('click','.ar-btn-register-email',function(e){
            e.preventDefault();
            var _e1 = $('#userEmailVerify').attr('value').toLowerCase().trim();
            var _e2 = $('#userEmail').attr('value').toLowerCase().trim();

            if(_e1.length == 0 || _e2 == 0) {
                arModal({
                    modalType:2,
                    modalTitle:'Yarrr!',
                    modalMessage:'Both email address fields must NOT be left blank. Please try again.',
                    modalButtons:{
                        0:{text:'OK'}
                    }
                });
                return false;
            }

            if(!validateEmail(_e1) || !validateEmail(_e2)) {
                arModal({
                    modalType:2,
                    modalTitle:'Yarrr!',
                    modalMessage:'You must enter valid emails for both fields, please try again',
                    modalButtons:{
                        0:{text:'OK'}
                    }
                });
                return false;
            }
            if(_e1 !== _e2) {
                arModal({
                    modalType:2,
                    modalTitle:'Yarrr!',
                    modalMessage:'The email addresses did not match, please try again',
                    modalButtons:{
                        0:{text:'OK'}
                    }
                });
                return false;
            }

            arSetEmail(_e1);
            $('#ar-email-register').slideUp();

        });



        $('html').on('click','.ar-btn-reward',function(e){
            e.preventDefault();
            /*
            $('.ar-modal-popup').magnificPopup({
                delegate:'a',
                type:'inline',
                removalDelay: 300,
                mainClass: 'mfp-fade'
            }).magnificPopup('open');
            */

            //$('.ar-modal-popup').magnificPopup('open');
            var rewardIcon = $(this).attr('rewardIcon'),rewardID = $(this).attr('rewardID'),rewardStatus = $(this).attr('rewardStatus'), rewardCost = $(this).attr('rewardCost'), userCurrency = $(this).attr('userCurrency'), rewardName = $(this).attr('rewardName'),rewardImg = $(this).attr('classImg'), rewardSrc = $(this).attr('rewardSrc');

            if (rewardStatus == 2) return false;
            var rewardMessage;
            if(rewardSrc == 0) {
                rewardMessage = '<img class="ar-border-grey" src="<? print $IMG_RES ?>/rewards/' + rewardImg +'.png"/>'
                rewardMessage +='<br/>Are you sure you want to use <span class="coin-icon"></span><b>' + rewardCost + '</b> and get a ' + rewardName + '?'

            } else if(rewardSrc== 6 || rewardSrc==8){
                rewardMessage = '<div class="app-details-thumb" style="background-image:url(<? print $IMG_RES ?>/rewards/' + rewardIcon +'_sm.png);"><span></span></div>';
                rewardMessage +='<br/>Are you sure you want to use <span class="coin-icon"></span><b>' + rewardCost + '</b> and get ' + rewardName + '?'
            }
            else  if(rewardSrc == 5 || rewardSrc == 7) {
                rewardMessage = '<div class="app-details-thumb" style="background-image:url(' + rewardIcon +');"><span></span></div>';
                rewardMessage +='<br/>Are you sure you want to use <span class="coin-icon"></span><b>' + rewardCost + '</b> and get ' + rewardName + '?'

            } else {
                rewardMessage = '<div class="'+ rewardImg +' ar-reward-img"></div>';
                rewardMessage +='<br/>Are you sure you want to use <span class="coin-icon"></span><b>' + rewardCost + '</b> and get a ' + rewardName + ' gift card?'

            }
            arModal({
                modalType:0,
                modalTitle:'Avast! Redeem Reward?',
                modalMessage:rewardMessage,
                modalButtons:{
                    0:{text:'Cancel'},
                    1:{text:'Redeem',callback:function(){
                        //$.magnificPopup.close();
                        $('#ar-email-register').slideUp();
                        arClaimReward(rewardID);
                    }}
                }
            });
        });


        var e = $('.ar-reward-iap');
        $(e).scrollLeft($(e).width());
        setTimeout(function(){

            $(e).animate({scrollLeft: 0}, 'slow');
        },1000);

    });

</script>