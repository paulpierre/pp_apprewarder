{foreach from=$result item=offer}
<a href="#" class="network-offer {$offer.offerSrc}" data-offer-destination="{$offer.offerDestination}" data-offer-user-payout="{$offer.offerUserPayout}" data-offer-referral-payout="{$offer.offerReferralPayout}" data-offer-network="{$offer.offerSrc}" data-offer-description="{$offer.offerDescription|escape}" data-offer-icon="{$icons[$offer.offerNetworkID][$offer.offerID]}" data-offer-id="{$offer.offerID}" data-offer-name="{$offer.offerName}"  data-offer-countries="{$countries[$offer.offerNetworkID][$offer.offerID]}"  data-payout="{$offer.offerNetworkPayout}">
    <span class="icon" style="background-image:url(/view/img/offer-icon-overlay-white.png),url({$icons[$offer.offerNetworkID][$offer.offerID]|replace:' ':'%20'}),url(/view/img/image-no-icon.png);"></span>
    <span class="name">{$offer.offerName}</span>
    <span class="countries" style="display:none;"></span>
    <span class="payout"><strong>${$offer.offerNetworkPayout}</strong> <span class="icon-coin"> </span>{$offer.offerUserPayout}</span>
    <span class="network {$offer.offerSrc}"></span>
</a>
{/foreach}