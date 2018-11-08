<!-- Table -->
<thead>
<tr>

    <th>#</th>
    <th></th>

    <th>IP Address</th>
    <th>ISP Name</th>
    <th>Country</th>
    <th>Created</th>
    <th>Modified</th>
    <th>Flag</th>

</tr>
</thead>
{foreach from=$result item=isp}
<tr id="isp-{$isp.id}" data-isp-name="{if $isp.isp_name neq "?"}{$isp.isp_name}{/if}" data-isp-ip="{$isp.user_ip}" data-isp-id="{$isp.id}" data-isp-country="{$isp.isp_country}" data-isp-status="{$isp.isp_status}">


    <td class="isp-id" data-sort="{$isp.id}">{$isp.id}</td>
    {if $isp.isp_status eq 5}
        <td class="isp-status isp-denied" data-sort="{$isp.isp_status}">{$isp.isp_status}<span class="glyphicon glyphicon-remove"></span></td>
        {elseif $isp.isp_status eq 1}
        <td class="isp-status isp-accepted" data-sort="{$isp.isp_status}">{$isp.isp_status}<span class="glyphicon glyphicon-ok"></span></td>
        {else}
        <td class="isp-status" data-sort="{$isp.isp_status}">{$isp.isp_status}</td>
    {/if}
    <td class="isp-ip" data-sort="$isp.user_ip">{$isp.user_ip}</td>

    <td class="isp-name" data-sort="{$isp.isp_name}">{if $isp.isp_name neq "?"}{$isp.isp_name}{else}<button id="fraud-ip-lookup" type="button" class="btn btn-sm btn-default">
        <span class="glyphicon glyphicon-search"></span> Lookup
    </button>{/if}</td>
    <td class="isp-country" data-sort="{$isp.isp_country}"><span class="flag flag-{$isp.isp_country|lower}"></span></td>
    <td class="isp-date" data-sort="{$isp.isp_tcreate}">
        <span class="glyphicon glyphicon-time"></span> {$isp.isp_tcreate|relative_date}
    </td>
    <td class="isp-date" data-sort="{$isp.isp_tmodified}">
        <span class="glyphicon glyphicon-time"></span> {$isp.isp_tmodified|relative_date}
    </td>
    <td class="isp-enabled" data-sort="{$isp.isp_status}"><input type="checkbox" class="checkbox"{if $isp.isp_status eq 1} checked>{/if}</td>

</tr>
{/foreach}
