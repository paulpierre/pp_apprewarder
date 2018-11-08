<h3 class="full-width text-center ar-page-title">History</h3>
<div id="ar-history-dashboard" class="full-width">
    <div class="rsContent">
	  	<span class="ar-history-kpi">Lifetime coins earned:
		<span class="coin-balance-icon"></span><b><? print (isset( $_SESSION['userCreditsSum'])? $_SESSION['userCreditsSum']:0);?></b>
	</span>

    </div>
    <div class="rsContent">
	  	<span class="ar-history-kpi"><span class="icon-clock"></span> Pending offers:
		<b><? print (isset($_SESSION['pendingOffersCount'])?$_SESSION['pendingOffersCount']:0);?></b>
	</span>

    </div>
    <div class="rsContent">
	   	<span class="ar-history-kpi"><span class="icon-cart"></span> Rewards Cashed Out:
			<span class="coin-balance-icon"></span><b><? print (isset($_SESSION['rewardsCount'])?$_SESSION['rewardsCount']:0);?></b>
		</span>
    </div>
    <div class="rsContent">
    	<span class="ar-history-kpi"><span class="icon-mobile-1"></span> Total downloads:
			<b><? print (isset($_SESSION['offersCount'])?$_SESSION['offersCount']:0);?></b>
		</span>
    </div>
    <div class="rsContent">
		<span class="ar-history-kpi"><span class="icon-users"></span> Total referral earnings:
			<span class="coin-balance-icon"></span><b><? print (isset($_SESSION['userCreditsReferral'])?$_SESSION['userCreditsReferral']:0);?></span></b>
		</span>
    </div>
    <div class="rsContent">
	 	<span class="ar-history-kpi"><span class="icon-users"></span> Total user referrals:
			<b><? print (isset($_SESSION['referralCount'])?$_SESSION['referralCount']:0);?></b>
		</span>
    </div>
    <div class="rsContent">
	 	<span class="ar-history-kpi"><span class="icon-clock-1"></span> You joined
			<b><? print UtilityManager::time_ago($_SESSION['userAccountCreated']) . ' ago';?></b>
		</span>
    </div>
</div>

<div id="ar-history-container">
	<!-- HISTORY ITEM -->

	<div id="ar-history-items" class="full-width bg-light">


<?php
$userInstance = new User();
$userHistoryData = $userInstance->get_user_history($_SESSION['userID']);
$utilityInstance->aasort($userHistoryData,'tmodified',SORT_DESC);
if($userHistoryData && count($userHistoryData) > 0) {
    $itemCount = 1;
 foreach($userHistoryData as $userHistory)
 {

        if($itemCount == HISTORY_PAGE_MAX + 1 && count($userHistoryData) > HISTORY_PAGE_MAX) {
            print '<div id="ar-history-hidden-container">';
        }

        if(isset($userHistory['referral_referred_user_id']))
        {
            $referralImage = IMG_RES . '/icon_user.png';
            $referralPayout = PAYOUT_REFERRAL_REFERRER;
            $isPending = (intval($userHistory['referral_status']) == 0)?true:false;
            $referralDate = (!$isPending)?$utilityInstance->time_ago($userHistory['tmodified']) . ' ago':'Referral pending';
            $referralSource = $userHistory['referral_source'];

            ?>
            <div class="ar-history-item full-width">
                <div class="ar-history-icon">
				<span class="icon-users">
				</span>
                </div>
                <div class="ar-history-details">
                    <div class="ar-history-name"><span class="icon-share"></span><?
                        switch($referralSource)
                        {
                            case REFERRAL_SOURCE_EMAIL: print 'Email'; break;
                            case REFERRAL_SOURCE_FB: print 'Facebook'; break;
                            case REFERRAL_SOURCE_TWITTER: print 'Twitter'; break;
                            case REFERRAL_SOURCE_LINK: print 'Web URL'; break;
                            case REFERRAL_SOURCE_MANUAL: print 'Manual input'; break;
                            case REFERRAL_SOURCE_UNKNOWN: default: print 'Unknown'; break;
                        }
                        ?> Referral</div>
                    <div class="ar-history-time"><span class="icon-clock"></span><? print $referralDate; ?></div>
                </div>
                <div class="ar-history-payout<? print ($isPending)?' ar-half-alpha':''; ?>">
                    <span class="coin-balance-icon"></span><b><? print $referralPayout; ?></b>
                </div>
            </div>

            <?

        }
        elseif(isset($userHistory['offer_name']))
        {
            $offerID = $userHistory['offer_id'];
            $offerName = $userHistory['offer_name'];
            $offerImage = (empty($userHistory['offer_image_url']))?IMG_RES . '/icon-unavailable.png':$userHistory['offer_image_url'];
            $offerPayout = (isset($userHistory['offer_payout']) && is_numeric($userHistory['offer_payout']) && intval($userHistory['offer_payout']) !== 0)?$userHistory['offer_payout']:'N/A';
            $isPending = (intval($userHistory['offer_status']) == 0)?true:false;
            $isExpired = (intval($userHistory['offer_status']) == 5)?true:false;
            //$userDidUnlock = (intval($_SESSION['userAccountStatus']) == 1)?true:false;
            $isUnlockOffer = (in_array($offerID,$lockedOffers))?true:false;
            if($isPending)
            {
                $offerDate = 'Verification pending';
            } elseif ($isExpired)
            {
                $offerDate = 'Expired ' . $utilityInstance->time_ago($userHistory['tmodified']) . ' ago';
            } else {
                $offerDate = $utilityInstance->time_ago($userHistory['tmodified']) . ' ago';

            }
            ?>
            <div class="ar-history-item full-width <? print ($isPending)?'ar-history-pending':''; ?>">
                <div class="ar-history-icon">
                    <div class="app-thumb" style="background-image:url(<? print $offerImage; ?>);">
                        <span></span>
                    </div>
                </div>
                <div class="ar-history-details">
                    <div class="ar-history-name"><span class="icon-mobile-1"></span><? print $offerName; ?></div>
                    <div class="ar-history-time"><span class="icon-clock"></span><? print $offerDate; ?></div>
                </div>
                <div class="ar-history-payout">
                    <? if($isUnlockOffer){?> <span style="float:left;" class="app-lock-white"><?  } else {?><span class="coin-balance-icon"></span><b><? print $offerPayout; ?></b><? } ?>
                </div>
            </div>
            <?

        }
        elseif(isset($userHistory['reward_name']))
        {
            $rewardName = $userHistory['reward_name'];
            $rewardImage = IMG_RES . '/rewards/' . $rewardSources[$userHistory['reward_source_id']]['image'];
            $rewardPayout = (isset($userHistory['reward_payout']) && is_numeric(intval($userHistory['reward_payout'])) && intval($userHistory['reward_payout']) !== 0)?$userHistory['reward_payout']:'N/A';
            $rewardCost = (isset($userHistory['reward_cost']) && is_numeric(intval($userHistory['reward_cost'])) && intval($userHistory['reward_cost']) !== 0)?$userHistory['reward_cost']:0;
            $rewardDate = (isset($userHistory['tmodified']))? $utilityInstance->time_ago($userHistory['tmodified']) . ' ago':'N/A';
            $rewardSourceID = $userHistory['reward_source_id'];
            $rewardCSS = $rewardSources[$userHistory['reward_source_id']]['css'];
            $rewardStatus = intval($userHistory['reward_user_status']);
            if($rewardStatus == 0) $rewardDate = ' Payout pending';

            ?>
            <div class="ar-history-item full-width">
                <div class="ar-history-icon">
				<span class="ar-reward-img-container <? print ($rewardStatus == 0)?'ar-half-alpha':''?>">
                    <? if($rewardCSS !== '') {?>
					<span class="<? print $rewardCSS; ?>">
						<span class="ar-reward-payout">$<? print $rewardPayout; ?></span>
					</span>
                    <? } else {  ?>
                        <img src="<? print $rewardImage; ?>" class="ar-reward-item"/>
                    <? } ?>
				</span>
                </div>
                <div class="ar-history-details">
                    <div class="ar-history-name"><? print $rewardName; ?></div>
                    <div class="ar-history-time"><span class="icon-clock"></span><? print $rewardDate; ?></div>
                </div>
                <div class="ar-history-payout">
                    <span class="coin-balance-icon"></span><b><? print $rewardCost; ?></b>
                </div>
            </div>
            <?
        }

        $itemCount++;
    }

 }

else
{
    ?>
     <h1 class="ar-empty-page">Yar! No history activity.
        <b>Hmm.. Why don't you start downloading or claiming rewards?</b>
    </h1>
    <?
}

    if(count($userHistoryData) > HISTORY_PAGE_MAX)
    {
        ?>
                </div> </div>
                <div class="text-center full-width">
                    <a href="#" class="btn-load-more-history button grey grey-shadow radius"><span class="icon-down-open"></span>Load More</a>
                </div>
    <?
    }
?>
<script>
    $('html').on('click', '.btn-load-more-history',function(e){
        e.preventDefault();
        $('#ar-history-hidden-container').slideDown('fast');
        $(this).hide('fast');
    });
    $('#ar-history-dashboard').royalSlider({
        arrowsNav:false,
        fadeinLoadedSlide: false,
        controlNavigationSpacing: 0,
        controlNavigation: false,
        imageScaleMode: 'none',
        imageAlignCenter:true,
        blockLoop: false,
        loop: true,
        transitionType: 'move',
        keyboardNavEnabled: false,
        block: {
            delay: 400
        },

        autoPlay: {
            enabled: true
        }
    });


</script>