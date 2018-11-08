<table class="table">
<!--
$filterConst = array(
    'OFFER_FILTER_TARGET_TITLE'=>OFFER_FILTER_TARGET_TITLE,
    'OFFER_FILTER_TARGET_DESCRIPTION'=>OFFER_FILTER_TARGET_DESCRIPTION,
    'OFFER_FILTER_ACTION_HIDE'=>OFFER_FILTER_ACTION_HIDE,
    'OFFER_FILTER_CONDITION_NONE'=>OFFER_FILTER_CONDITION_NONE,
    'OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT'=>OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT,
    'OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT'=>OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT,
    'OFFER_FILTER_CONDITION_HAS_ICON'=>OFFER_FILTER_CONDITION_HAS_ICON,
    'OFFER_FILTER_CONDITION_HAS_NO_ICON'=>OFFER_FILTER_CONDITION_HAS_NO_ICON


$filterAdNetworks = array(
    'KSIX_API_PROVIDER_ID'=>KSIX_API_PROVIDER_ID,
    'HASOFFERS_API_PROVIDER_ID'=>HASOFFERS_API_PROVIDER_ID,
    'AARKI_API_PROVIDER_ID'=>AARKI_API_PROVIDER_ID,
    'ADSCEND_API_PROVIDER_ID'=>ADSCEND_API_PROVIDER_ID,
    'ADACTION_API_PROVIDER_ID'=>ADACTION_API_PROVIDER_ID
);

);
-->
{foreach from=$result item=filter}
    {assign var=filterData value=$filter.filter_data|json_decode:true}
    {assign var=filterCountries value=","|explode:$filterData.offerFilterCountries}
    <tr data-filter-id="{$filter.filter_id}" data-filter-action="{$filterData.offerFilterAction}" data-filter-condition="{$filterData.offerFilterCondition}" data-filter-network="{$filterData.offerFilterNetwork}" data-filter-platform="{$filterData.offerFilterPlatform}" data-filter-target="{$filterData.offerFilterTarget}" data-filter-text="{$filterData.offerFilterText}" data-filter-name="{$filter.filter_name}" data-filter-countries="{$filterData.offerFilterCountries}">
        <td class="id">{$filter.filter_id}</td>
        <td class="filter-enabled"><input type="checkbox" data-on-color="success" name="filter-enabled" data-size="small"{if $filter.filter_status eq 1} checked>{/if}</td>

        <td class="content {if $filter.filter_status eq 0}filter-disabled{/if} filter-edit-row">
            <div class="name">{$filter.filter_name} </div>

                                <span class="action">
                                    {if $filterData.offerFilterAction eq $filterConst.OFFER_FILTER_ACTION_HIDE} Hide{/if}
                                    <span class="condition">
                                    {if $filterData.offerFilterCondition eq $filterConst.OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT}
                                        <span class="high"><span class="glyphicon glyphicon-arrow-up"></span> highest paying</span>
                                    {elseif $filterData.offerFilterCondition eq $filterConst.OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT}
                                        <span class="low"><span class="glyphicon glyphicon-arrow-down"></span> lowest paying</span>
                                    {elseif $filterData.offerFilterCondition eq $filterConst.OFFER_FILTER_CONDITION_HAS_ICON}
                                        <span class="low"><span class="glyphicon glyphicon-ok"></span> has icon</span>
                                    {elseif $filterData.offerFilterCondition eq $filterConst.OFFER_FILTER_CONDITION_HAS_NO_ICON}
                                        <span class="low"><span class="glyphicon glyphicon-remove"></span> has no icon</span>
                                    {/if}
                                    </span>

                                offers</span>
            from <span class="adnetwork">
            {if $filterData.offerFilterNetwork eq 0}
            All ad networks
                {elseif $filterData.offerFilterNetwork eq $filterAdNetworks.KSIX_API_PROVIDER_ID}
            Ksix
                {elseif $filterData.offerFilterNetwork eq $filterAdNetworks.HASOFFERS_API_PROVIDER_ID}
            HasOffers
                {elseif $filterData.offerFilterNetwork eq $filterAdNetworks.AARKI_API_PROVIDER_ID}
            Aarki
                {elseif $filterData.offerFilterNetwork eq $filterAdNetworks.ADSCEND_API_PROVIDER_ID}
            Adscend
                {elseif $filterData.offerFilterNetwork eq $filterAdNetworks.ADACTION_API_PROVIDER_ID}
            AdAction
            {/if}
            </span>
            containing

            <span class="text">"{$filterData.offerFilterText}"</span>

            in the offer

            <span class="target">
            {if $filterData.offerFilterTarget eq $filterConst.OFFER_FILTER_TARGET_TITLE}
                Title
                {elseif $filterData.offerFilterTarget eq $filterConst.OFFER_FILTER_TARGET_DESCRIPTION}
                Description
            {/if}
            </span>

             from
            <span class="country">
                {if $filterData.offerFilterCountries eq "INT"} All Countries {else}
                    {foreach from=$filterCountries item=country}

                        <span class="flag flag-{$country|lower}"></span>
                    {/foreach}
                {/if}
           </span>
        </td>


        <td class="filter-created">
            <span class="glyphicon glyphicon-time"></span> {$filter.filter_tcreate|relative_date}
        </td>
        <td class="filter-modified">
            <span class="glyphicon glyphicon-pencil"></span> {$filter.filter_tmodified|relative_date}
        </td>


    </tr>
{/foreach}

</table>