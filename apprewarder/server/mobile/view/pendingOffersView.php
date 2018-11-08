<?php
if(!empty($offersData))
{

    foreach($offersData as $offer)
    {

        //if(empty($offer)) continue; //skip

        $offerName = $offer['offer_name'];
        $offerImage = (empty($offer['offer_image_url']))?IMG_RES . '/icon-unavailable.png':$offer['offer_image_url'];
        $offerPayout = $offer['offer_payout'];
        $offerReferralPayout = $offer['offer_referral_payout'];
        $offerCost = (isset($offer['offer_cost']) && is_numeric($offer['offer_cost']) && floatval($offer['offer_cost']) > 0)?'$'. floatval($offer['offer_cost']):'FREE';
        $offerID = $offer['offer_id'];
        $offerURL = $offer['offer_url'];


        //$totalOfferPayout += $offerPayout;

        ?>
        <a offerID="pending-<? print $offerID; ?>" class="app-offer-container ar-btn-show-pending-offer"  target="_blank">
            <div class="app-offer">
                <div class="app-title"><? print $offerName; ?> </div>

                <div class="app-thumb ar-half-alpha" style="background-image:url(<? print $offerImage; ?>);">
                    <span></span>
                </div>

                <div class="app-payout">
                  <div class="app-user-referral" style="text-align:center;width:100%;"><span class="app-payout-value" style="width:100%;"><? print UtilityManager::time_ago($offer['offer_tcreate']) . ' ago'; ?></span></div>
                </div>
            </div>

            <div id="pending-<? print $offerID; ?>" class="mfp-hide app-details-popup white-popup bg-dark <? print ($isUnlockOffer)?'ar-unlock-app':''; ?>">
                <div class="app-details-top-container">
                    <div class="app-details-header">
                        <div class="app-details-thumb" style="background-image:url(<? print $offerImage; ?>);"><span></span></div>
                    </div>
                    <div class="app-details-title"> <? print $offerName; ?> </div>

                    <div class="app-details-description">
                        Coins can take up to 24 hours to process. Remember: you need to run apps for 30 seconds up to 1 minute and complete any tutorials or registrations.
                        <br><br>Unfortunately if you have downloaded this app in the past on this device, you will not get coins for them.
                        All pending apps get cleared within 3 days of download.
                    </div>


                    <div offerID="<? print $i; ?>" oid="<? print $offerID; ?>" offerURL="<? print (!$isUnlockOffer)?$offerURL:'locked'; ?>"class="app-details-download-button large button full-width rounded red-shadow bounce animated">
                        <? if($isUnlockOffer){ ?>
                        Download and unlock!
                        <? } else { ?> Download again for <div class="app-details-payout"><span></span>+<? print $offerPayout; ?></div> <? } ?>
                    </div>
                </div>
            </div>
        </a>
        <?
    }


    ?>
    <!--
<div class="text-center full-width">
    <div id="ar-app-stats-container">
        <span class="ar-app-stats full-width"><b><? print count($offersData); ?></b> pending apps total worth <span class="coin-balance-icon"></span><b><? print $totalOfferPayout;?></b> coins available today</span>
    </div>
</div>-->
<?

}
else
{

    //TOOD: put in some HTML here for no offers shown at all ...
    ?>
    <div class="full-width">
        <h1 class="ar-empty-page">No pending offers!
            <b>Hmm.. Why don't you start downloading!</b>
        </h1>
    </div>

<?
}
?>


