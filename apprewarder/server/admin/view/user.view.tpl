
<div class="content ">
    <h1>{$page_data.page_title}</h1>
    <ul class="nav nav-tabs" id="offer-tabs">
        <li class="active"><a href="#offer-add"  id="tab-offer-add">User List  <span class="badge" id="user-list-count">{$result|count}</span><span class="badge" id="network-offer-count"></span></a></li>
        <li><a href="#offer-current" id="tab-offer-current">User Statistics</a></li>
        <li><a href="#offer-statistics" id="tab-offer-statistics">Referral Statistics</a></li>
        <li><a href="#offer-filter" id="tab-offer-filter">User Details</a></li>

    </ul>
    <br/>
    <div class="" id="offer-container">

        <!-- Table -->
        <table id="user-list" class="display dataTable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Pic</th>
                <th>Name</th>
                <th>OS</th>
                <th>Credits</th>
                <th>Ref</th>
                <th>Payout</th>
                <th>Reg</th>
                <th>Loc</th>
                <th>Reg</th>
                <th>Login</th>
                <th>Ver</th>
            </tr>
            </thead>

    {foreach from=$result item=user}
        <tr>
            <td class="user-id">{$user.user_id}</td>

            <td class="user-friend-code" data-sort="{$user.user_friend_code}">{$user.user_friend_code}</td>
            <td class="user-image" {if $user.user_facebook_id} data-sort="{$user.user_facebook_id}"{/if} >{if $user.user_facebook_id}<img src="http://graph.facebook.com/{$user.user_facebook_id}/picture?type=square"/>{/if}</td>
            <td class="user-name" data-sort="{$user.user_name}">{$user.user_name}</td>
            <td class="user-platform" data-sort="{$user.user_platform}">
                <span class="{if $user.user_platform eq 2}platform-android{else}platform-ios{/if}"></span>
            </td>
            <td class="user-credits" data-sort="user.user_credits">
            {$user.user_credits}
            </td>
            <td class="user-referrral-count" data-sort="{$user.user_referral_count}">{$user.user_referral_count}</td>
            <td class="user-payout" data-sort="{$user.user_payout}">${$user.user_payout}</td>
            <td class="user-register-locale {if $user.user_locale neq $user.user_locale_register}warning{/if}" data-sort="{$user.user_locale}"><span class="flag flag-{$user.user_locale_register|lower}"></span></td>
            <td class="user-locale {if $user.user_locale neq $user.user_locale_register}warning{/if}" data-sort="{$user.user_locale}"><span class="flag flag-{$user.user_locale|lower}"></span></td>
            <td class="user-register" data-sort="{$user.user_tcreate}">
                <span class="glyphicon glyphicon-time"></span> {$user.user_tcreate|relative_date}
            </td>
            <td class="user-login" data-sort="{$user.user_tlogin}">
                <span class="glyphicon glyphicon-time"></span> {$user.user_tlogin|relative_date}
            </td>
            <td class="user-version" data-sort="{$user.user_client_version}">{$user.user_client_version}</td>


        </tr>
    {/foreach}
        </table>
    </div>
</div>
