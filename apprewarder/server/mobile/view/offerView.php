<?php
global $lockedOffers;
global $userDidUnlock;
if(count($lockedOffers) < 1) $userDidUnlock = true;
$offerArray = $offerData['offerArray'];
$offerIndexStart = $offerData['offerIndexStart'];
$offerIndexFinish = $offerData['offerIndexFinish'];
$totalOfferPayout = 0;
if(!empty($_SESSION['offerData'][$_SESSION['currentOfferTypes']]))
{

    for($i=$offerIndexStart; $i <= $offerIndexFinish; ++$i)
    {
       //if(empty($offerArray[$i])) continue; //skip if particular network returned no results

        $offer = $offerArray[$i];//$_SESSION['offerData'][$_SESSION['currentOfferTypes']][$i];
        $offerName = $offer['offerName'];
        $offerImage = (empty($offer['offerImage']))?IMG_RES . '/icon-unavailable.png':$offer['offerImage'];
        $offerPayout = $offer['offerUserPayout'];
        $offerReferralPayout = $offer['offerReferralPayout'];
        $offerCost = (isset($offer['offerUserCost']) && is_numeric($offer['offerUserCost']) && floatval($offer['offerUserCost']) > 0)?'$'. floatval($offer['offerUserCost']):'FREE';
        $offerURL = $offer['offerURL'];
        $offerDescription = (isset($offer['offerDescription']) && $offer['offerDescription'] !== null)?$offer['offerDescription']:'Download app, run for 30 seconds, complete all tutorials.';
        $offerID = $offer['offerID'];
        $offerNetworkID = $offer['offerNetworkID'];
        $offerHasImage = (empty($offer['offerImage']))?0:1;
        $isUnlockOffer = (count($lockedOffers) > 0 && !$userDidUnlock && in_array($offerID,$lockedOffers))?true:false;
        if($userDidUnlock && in_array($offerID,$lockedOffers)) continue;
        //if(!$isUnlockOffer) $totalOfferPayout += $offerPayout;

        ?>

        <a offerID="<? print ((!$userDidUnlock && $isUnlockOffer) || $userDidUnlock)?$i:'locked'; ?>" oimg="<? print $offerHasImage; ?>" network="<? print $offerNetworkID; ?>" rank="<? print $offerPayout; ?>" oid="<? print $offerID; ?>" class="ar-btn-show-offer  app-offer-container<? if(!$userDidUnlock) print (!$isUnlockOffer)?' ar-half-alpha':' ar-unlock'; ?>">
            <div class="app-offer" payout="<? print ($isUnlockOffer)?0:$offerPayout; ?>">
                <div class="app-title"> <? print $offerName; ?> </div>

                <div class="app-thumb" style="background-image:url(<? print $offerImage; ?>);">
                    <span></span>
                </div>

                <div class="app-payout">
                    <div class="app-user-payout"><? if(!$isUnlockOffer){?><span class="coin-payout-icon"></span><span class="app-payout-value"> <? print $offerPayout; ?> </span><? } else {
                        ?>

                        <span style="float:left;" class="app-lock"></span><span class="app-payout-value"> Unlock</span>
                        <? }
                        ?></div>
                    <? if($offerReferralPayout > 0 && !$isUnlockOffer) { ?><div class="app-user-referral"><span class="icon-users"></span><span class="app-payout-value">+<? print $offerReferralPayout; ?></span></div> <? } ?>
                </div>
                <div id="<? print $i; ?>" class="mfp-hide app-details-popup white-popup bg-dark <? print ($isUnlockOffer)?'ar-unlock-app':''; ?>">
                    <div class="app-details-top-container">
                        <div class="app-details-header">
                            <div class="app-details-thumb" style="background-image:url(<? print $offerImage; ?>);"><span></span></div>
                        </div>
                        <div class="app-details-title"> <? print $offerName; ?> </div>

                        <div class="app-details-description">
                            <? print $offerDescription; ?>
                            <? if(!$isUnlockOffer) {?> <div class="highlight yellow full-width text-center" style="padding-top:5px;">Install, play for 30 seconds.</div><? } ?>
                        </div>


                        <div offerID="<? print $i; ?>" oid="<? print $offerID; ?>" offerURL="<? print (!$isUnlockOffer)?$offerURL:'locked'; ?>"class="app-details-download-button large button full-width rounded red-shadow bounce animated">
                          <? if($isUnlockOffer){ ?>
                            Download and unlock!
                            <? } else { ?> Run it and earn <div class="app-details-payout"><span></span>+<? print $offerPayout; ?></div> <? } ?>
                        </div>

                    </div>

                    <? if(!$isUnlockOffer) { ?>
                    <div class="app-details-bottom-container full-width">
                        <? if($offerReferralPayout > 0) { ?>
                            <div class="app-user-share">
                                <div class="text">When any friend downloads this app, you get</div>
                                <div class="share-animation">
                                    <span class="icon-users"></span>
                                    <span class="app-payout-value">+<? print $offerReferralPayout; ?></span>

                                    <span class="scrolling-line"><span class="coin-icon-animated"></span></span>
                                    <span class="icon-user"></span>You
                                </div>
                            </div>
                        <? } ?>


                        <div class="ar-share-earn button blue radius blue-shadow">Share & Earn More</div>
                    </div>
                    <? } ?>
                </div>
            </div>
        </a>
    <?
    }
    //print '<pre>i: ' . $i . ' OfferIndexFinish' . $offerIndexFinish . ' offerNetwork-currentIndex: ' . $_SESSION['offerNetwork']['currentIndex'] . ' networkCount:'. count($_SESSION['offerNetwork']) . '</pre>' ;
    if(empty($offerArray[$i +1]) && (intval($_SESSION['offerNetwork']['currentIndex']) >= (count($_SESSION['offerNetwork'])-1))) {
        print '<span id="next_page_' .$_SESSION['offerCurrentPage'] .'" data="0"/>';
    } else {
        print '<span id="next_page_' .$_SESSION['offerCurrentPage'] .'" data="1"/>';
    }
}
else
{

    //TOOD: put in some HTML here for no offers shown at all ...
    ?>
    <div class="full-width">
            <h1 class="ar-empty-page">Yar! No offers.
                <b>Try again in a few hours and maybe some will appear. Or email us if you have any issues: <? print SUPPORT_EMAIL;?></b>
            </h1>
    </div>

<?
}
?>


