
<div class="content ">
        <h1>{$page_data.page_title}</h1>
        <ul class="nav nav-tabs" id="offer-tabs">
            <li class="active"><a href="#offer-add"  id="tab-offer-add">Add Offers <span class="badge" id="network-offer-count"></span></a></li>
            <li><a href="#offer-current" id="tab-offer-current">Current Offers <span class="badge" id="local-offer-count">{$result|count}</span></a></li>
            <li><a href="#offer-statistics" id="tab-offer-statistics">Offer Statistics</a></li>
            <li><a href="#offer-filter" id="tab-offer-filter">Filter Duplicates</a></li>

        </ul>
    <br/>
    <div class="col-lg-12" id="offer-container">

        <!-- CURRENT OFFERS -->
        <div class="panel panel-default offer-content" id="content-offer-current" style="display:none;">
            <div class="panel-heading"><h4>Live AppRewarder Offers</h4></div>
            <div class="panel-body">
                <div id="offer-edit" data-offer-user-payout="" data-offer-status="" data-offer-referral-payout="" data-offer-description="" data-offer-icon="" data-offer-id="" data-offer-name=""  data-offer-countries=""  data-payout="0.00">
                    <div class="col-md-2" style="text-align: center;">
                        <span class="icon offer-icon" style="background-image:url(/view/img/offer-icon-overlay-white.png),url(/view/img/icon-unavailable.png);"></span>
                        <div class="offer-payout" style="padding-top:2px;">
                            <span class="icon-coin"></span>
                            <span class="offer-user-payout">0</span>
                            <br/>
                            <span class="glyphicon glyphicon-user"> </span>
                            <span class="offer-referral-payout">0</span>
                        </div>

                        <div class="flag-container">
                            <span class="flag flag-us"></span>
                            <span class="flag flag-ca"></span>
                            <span class="flag flag-it"></span>
                        </div>

                        <div class="offer-network">
                            <img src=""/>
                        </div>
                    </div>
                    <div class="information col-md-10">
                        <div class="name col-md-12">
                            <h4>
                                    <span class="label label-primary offer-network-payout">
                                        $0.00
                                    </span>
                                    <span class="offer-name">Select an offer from below<span>

                            </h4>
                        </div>

                        <div class="col-md-12 controls">
                            <div class="btn-group btn-group-sm">
                                <button id="save-offer" type="button" class="btn btn-success">
                                    <span class="glyphicon glyphicon-ok"></span> Save
                                </button>

                                <button id="stop-offer" type="button" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> Stop Campaign
                                </button>
                                <button id="delete-offer" type="button" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-trash"></span> Delete
                                </button>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        Platform <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-platform" role="menu">
                                        <li><a href="#" class="offer-platform-none"><span class="glyphicon glyphicon-ok checkarea"></span> None</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" class="offer-platform-android"><span class="glyphicon checkarea"></span> Android</a></li>
                                        <li><a href="#" class="offer-platform-ios"><span class="glyphicon checkarea"></span> All iOS</a></li>
                                        <li><a href="#" class="offer-platform-iphone"><span class="glyphicon checkarea"></span> iPhone</a></li>
                                        <li><a href="#" class="offer-platform-ipad"><span class="glyphicon checkarea"></span> iPad</a></li>
                                        <li><a href="#" class="offer-platform-ipod"><span class="glyphicon checkarea"></span> iPod</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        Offer Type <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-type" role="menu">
                                        <li><a href="#" class="offer-type-mobile"><span class="glyphicon glyphicon-ok checkarea"></span> Incent App</a></li>
                                        <li><a href="#" class="offer-type-video"><span class="glyphicon checkarea"></span> Video</a></li>
                                    </ul>
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <input type="text" class="form-control" id="offer-edit-countries" placeholder="Countries">
                                </div>
                            </div>




                        </div>
                        <textarea id="offer-edit-description" class="description offer-description col-md-12" style="display:none;">

                        </textarea>
                            <pre id="offer-edit-description-preview" class="description offer-description col-md-12">
                               No description.
                            </pre>

                    </div>
                </div>
            </div>
            <div id="offer-list">
                <table class="table">

                {foreach from=$result item=offer}

                    <tr class="offer-edit-row{if $offer.offer_status eq 0} offer-disabled{/if}" data-offer-type="{$offer.offer_type}" data-offer-status="{$offer.offer_status}" data-offer-user-payout="{$offer.offer_user_payout}" data-offer-referral-payout="{$offer.offer_referral_payout}" data-offer-countries="{$offer.offer_country}" data-offer-name="{$offer.offer_name}" data-offer-description="{$offer.offer_description|escape}" data-offer-network-id="{$offer.offer_external_id}" data-offer-network-source="{$offer.offer_source_id}" data-offer-id="{$offer.offer_id}" data-offer-platform="{$offer.offer_platform}" data-offer-network-payout="{$offer.offer_network_payout}" data-offer-icon="{$offer.offer_image_url}" >
                        <td class="offer-icon">
                            <span class="icon" style="background-image:url(/view/img/offer-icon-overlay-white.png),url({$offer.offer_image_url|replace:' ':'%20'}),url(/view/img/image-no-icon.png);"></span>
                        </td>
                        <td class="offer-network-id">
                            {$offer.offer_external_id}
                        </td>
                        <td class="offer-info">
                            <div class="offer-name">
                                {$offer.offer_name}
                            </div>
                            <div class="offer-description">
                                {$offer.offer_description}
                            </div>
                        </td>
                        <td class="offer-platform">
                            <span class="{if $offer.offer_platform eq 2}platform-android{else}platform-ios{/if}"></span>
                        </td>
                        <td class="offer-description">

                        </td>
                        <td class="offer-network">
                            <span class="{if $offer.offer_source_id eq 9}hasoffers{elseif $offer.offer_source_id eq 10}ksix{elseif $offer.offer_source_id eq 11}adaction{/if}"></span>
                        </td>
                        <td class="offer-network-payout">
                            ${$offer.offer_network_payout}
                        </td>
                        <td class="offer-payout">
                            <div class="offer-user-payout"><span class="icon-coin"></span>{$offer.offer_user_payout}</div>
                            <div class="offer-referral-payout"><span class="glyphicon glyphicon-user"></span>{$offer.offer_referral_payout}</div>
                        </td>
                        <td class="offer-geo">

                        </td>
                        <td class="offer-created">
                            <span class="glyphicon glyphicon-time"></span> {$offer.offer_tcreate|relative_date}
                        </td>
                        <td class="offer-modified">
                            <span class="glyphicon glyphicon-pencil"></span> {$offer.offer_tmodified|relative_date}
                        </td>


                    </tr>
                {/foreach}
                </table>
            </div>
        </div>


        <!-- ADD OFFERS -->
        <div class="panel panel-default offer-content" id="content-offer-add" style="display:none;">

            <div class="col-lg-12 navbar navbar-inverse" role="navigation">
                <div class="col-lg-3">
                    <form class="navbar-form navbar-left" role="search" style="width:300px;">
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" id="text-filter" placeholder="Filter offers">
                        </div>
                    </form>
                </div>


                <div class="btn-group col-lg-8 navbar-form">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Filter By <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" class="filter-reset">Reset</a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="filter-incent">Incent</a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="filter-adaction">AdAction</a></li>
                            <li><a href="#" id="filter-ksix">Ksix</a></li>
                            <li><a href="#" id="filter-hasoffers">HasOffers</a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="filter-ios">iOS</a></li>
                            <li><a href="#" id="filter-android">Android</a></li>
                        </ul>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Sort By <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" id="sort-payout-desc">Payout Desc</a></li>
                            <li><a href="#" id="sort-payout-asc">Payout Asc</a></li>
                            <li><a href="#" id="sort-name">Offer Name</a></li>
                        </ul>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Country <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" class="filter-reset">Reset</a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="filter-us">US</a></li>
                            <li><a href="#" id="filter-ca">CA</a></li>
                            <li><a href="#" id="filter-northamerica">North America</a></li>
                        </ul>
                    </div>
                </div>

                <div class="btn-group col-lg-1 btn-group-sm  navbar-form">
                    <button type="button" class="btn btn-primary" id="btn-refresh-network-offers"><span class="glyphicon glyphicon-refresh"></span> Refresh Offers</button>
                </div>
            </div>

           <div class="col-md-12">
                <div class="list-group" id="network-offers"  data-isotope-options='{ "layoutMode": "fitRows", "itemSelector": ".network-offer" }'>

                </div>
           </div>


            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong><span class="glyphicon glyphicon-info-sign"></span> Offer Information</strong></div>
                    <div id="offer-info" data-offer-destination="" data-offer-user-payout="" data-offer-referral-payout="" data-offer-network="" data-offer-description="" data-offer-icon="" data-offer-id="" data-offer-name=""  data-offer-countries=""  data-payout="0.00">
                       <div class="col-md-2" style="text-align: center;">
                           <span class="icon offer-icon" style="background-image:url(/view/img/offer-icon-overlay-white.png),url(/view/img/icon-unavailable.png);"></span>

                           <div class="offer-payout" style="padding-top:2px;">

                                   <span class="icon-coin"></span>
                                   <span class="offer-user-payout">0</span>
                                    <br/>
                               <span class="glyphicon glyphicon-user"> </span>

                                   <span class="offer-referral-payout">0</span>
                           </div>



                           <div class="flag-container">
                               <span class="flag flag-us"></span>
                               <span class="flag flag-ca"></span>
                               <span class="flag flag-it"></span>
                           </div>

                           <div class="offer-network">
                              <img src=""/>
                           </div>


                       </div>
                        <div class="information col-md-10">
                            <div class="name col-md-12">
                                <h4>
                                    <span class="label label-primary offer-network-payout">
                                        $0.00
                                    </span>
                                    <span class="offer-name">Select an offer from above to begin<span>

                               </h4>
                            </div>

                            <div class="col-md-12 controls">
                                <div class="btn-group btn-group-md">
                                    <button id="add-offer" type="button" class="btn btn-success">
                                        <span class="glyphicon glyphicon-ok"></span> Add Offer
                                    </button>

                                    <button type="button" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span> Block
                                    </button>
                                    <div class="btn-group btn-group-md">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Platform <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu offer-platform" role="menu">
                                            <li><a href="#" class="offer-platform-none"><span class="glyphicon glyphicon-ok checkarea"></span> None</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" class="offer-platform-android"><span class="glyphicon checkarea"></span> Android</a></li>
                                            <li><a href="#" class="offer-platform-ios"><span class="glyphicon checkarea"></span> All iOS</a></li>
                                            <li><a href="#" class="offer-platform-iphone"><span class="glyphicon checkarea"></span> iPhone</a></li>
                                            <li><a href="#" class="offer-platform-ipad"><span class="glyphicon checkarea"></span> iPad</a></li>
                                            <li><a href="#" class="offer-platform-ipod"><span class="glyphicon checkarea"></span> iPod</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group btn-group-md">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Offer Type <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu offer-type" role="menu">
                                            <li><a href="#" class="offer-type-mobile"><span class="glyphicon glyphicon-ok checkarea"></span> Incent App</a></li>
                                            <li><a href="#" class="offer-type-video"><span class="glyphicon checkarea"></span> Video</a></li>
                                        </ul>
                                    </div>

                                    <div class="btn-group btn-group-md">
                                            <input type="text" class="form-control input-sm" id="offer-countries" placeholder="Countries">
                                    </div>
                                </div>




                            </div>
                            <textarea id="offer-description" class="description offer-description col-md-12" style="display:none;">

                            </textarea>
                            <pre id="offer-description-preview" class="description offer-description col-md-12">
                               No description.
                            </pre>
                            <div class="btn-group btn-group-sm col-md-12">

                                <div class="btn-group btn-group-sm ">
                                    <input type="text" style="width:300px;" class="form-control input-sm" id="offer-destination"/>

                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button id="get-appstore-data" type="button" class="btn btn-sm btn-success ">
                                        Get App Store Data
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- OFFER STATISTICS -->


        <!-- OFFER FILTER -->
        <div class="panel panel-default offer-content" id="content-offer-filter" style="display:none;">
            <div class="panel-heading"><h4>Filter Builder</h4></div>
            <div class="panel-body">
                <div id="offer-add-filter"  data-filter-id="">
                    <div class="information col-md-12" style="margin-bottom:10px;">
                        <div class="name col-md-12">
                            <h4>

                                <span class="offer-filter-name-display">Create New Filter<span>

                            </h4>
                        </div>

                        <div class="col-md-12 controls"">
                            <div class="btn-group btn-group-sm col-md-3">
                                <input type="text" class="offer-filter-name form-control input-sm"  data="" placeholder="Name of the filter">
                            </div>
                            <div class="btn-group btn-group-sm col-md-9" style="padding-bottom:10px;">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        Filter an offer's <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-filter-target" data="1" role="menu">
                                        <li><a href="#" data="1"><span class="glyphicon glyphicon-ok checkarea"></span> Title</a></li>
                                        <li><a href="#" data="2"><span class="glyphicon checkarea"></span> Description</a></li>
                                    </ul>
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <input type="text" class="offer-filter-text form-control input-sm" placeholder="Contains text...(e.g. Game of War)" style="min-width:300px;">
                                </div>

                            </div>


                            <div class="btn-group btn-group-sm  col-md-12" style="padding:10px;">

                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        On platform <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-filter-platform" role="menu" data="0">
                                        <li><a href="#" data="0"><span class="glyphicon glyphicon-ok checkarea"></span> Any Platform</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" data="2"><span class="glyphicon checkarea"></span> Android</a></li>
                                        <li><a href="#" data="1"><span class="glyphicon checkarea"></span> All iOS</a></li>
                                        <li><a href="#" data="11"><span class="glyphicon checkarea"></span> iPhone</a></li>
                                        <li><a href="#" data="12"><span class="glyphicon checkarea"></span> iPad</a></li>
                                        <li><a href="#" data="13"><span class="glyphicon checkarea"></span> iPod</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        From Network <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-filter-network" role="menu" data="0">
                                        <li><a href="#" data="0"><span class="glyphicon glyphicon-ok checkarea"></span> All</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" data="7"><span class="glyphicon checkarea"></span> Adscend</a></li>
                                        <li><a href="#" data="2"><span class="glyphicon checkarea"></span> Aarki</a></li>
                                        <li><a href="#" data="11"><span class="glyphicon checkarea"></span> Adaction</a></li>
                                        <li><a href="#" data="10"><span class="glyphicon checkarea"></span> KSix</a></li>
                                        <li><a href="#" data="9"><span class="glyphicon checkarea"></span> HasOffers</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        And offer <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-filter-condition" role="menu" data="2">
                                        <li><a href="#" data="0"><span class="glyphicon checkarea"></span> None</a></li>
                                        <li><a href="#" data="1"><span class="glyphicon checkarea"></span> Is highest Payout</a></li>
                                        <li><a href="#" data="2"><span class="glyphicon glyphicon-ok checkarea"></span> Is lowest Payout </a></li>
                                        <li><a href="#" data="3"><span class="glyphicon checkarea"></span> Has icon </a></li>
                                        <li><a href="#" data="4"><span class="glyphicon checkarea"></span> Has NO icon </a></li>
                                    </ul>
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu offer-filter-action" role="menu" data="1">
                                        <li><a href="#" data="1" class="offer-type-mobile"><span class="glyphicon glyphicon-ok checkarea"></span> Hide offer</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <input type="text" class="form-control input-sm" id="offer-filter-countries" placeholder="In country...">
                                </div>
                            </div>

                        <div class="col-md-12">
                            <button id="save-filter" type="button" class="btn btn-sm btn-success">
                                <span class="glyphicon glyphicon-ok"></span> Save Filter
                            </button>
                            <button id="delete-filter" type="button" class="btn btn-sm btn-danger">
                                <span class="glyphicon glyphicon-remove"></span> Delete filter
                            </button>
                            <button id="clear-filter" type="button" class="btn btn-sm btn-primary">
                                Clear filter
                            </button>
                        </div>
                    </div>


                </div>
            </div>
            <div id="offer-filter-list">

            </div>
        </div>


    </div>

</div>







