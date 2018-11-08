
<div class="content ">
        <div class="page-header">
            <h1>{$page_data.page_title}</h1>
        </div>

        <div class="alert alert-success alert-dismissable" style="display:none;">New version of dashboard, enjoy it bitch!</div>


        <div class="col-lg-9" id="report-global-stats">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-globe"></span> Global Lifetime Stats</strong></div>

                <!-- Table -->
                <table class="table">
                    <thead class="header">
                    <tr>
                        <td class="alert-success"><span class="glyphicon glyphicon-usd"></span><br/> Profit</td>
                        <td class="alert-success"><span class="glyphicon glyphicon-usd"></span><br/> Revenues</td>
                        <td class="alert-success"><span class="glyphicon glyphicon-usd"></span><span class="glyphicon glyphicon-user"></span><br/> ARPU</td>
                        <td class="alert-success"><span class="glyphicon glyphicon-stats"></span><br/> Margin</td>
                        <td><span class="glyphicon glyphicon-user"></span><br/> Users</td>
                        <td><span class="glyphicon glyphicon-copyright-mark"></span><br/> Lifetime</td>
                        <td><span class="glyphicon glyphicon-copyright-mark"></span><br/> Bonus</td>
                        <td><span class="glyphicon glyphicon-random"></span><br/> Referrals</td>
                        <td><span class="glyphicon glyphicon-copyright-mark"></span><br/> Referrals</td>

                    </tr>
                    </thead>
                    <tr>
                        <td class="alert-success"><strong>${($result.cb_revenue - ($result.credits_sum/600))|number_format:2:".":","}</strong></td>
                        <td  class="alert-success">${$result.cb_revenue|number_format:2:".":","}</td>
                        <td class="alert-success">${($result.cb_revenue/$result.user_count)|number_format:2:".":","}</td>
                        <td class="alert-success"><strong>{((1-(($result.credits_sum/600)/$result.cb_revenue))*100)|number_format:1:".":","}%</strong></td>
                        <td><strong>{$result.user_count}</strong></td>
                        <td>${($result.credits_sum/600)|number_format:2:".":","}</td>
                        <td>${($result.credits_free/600)|number_format:2:".":","}</td>
                        <td>{$result.referral_count}</td>
                        <td>${(($result.credits_referral/600))|number_format:2:".":","}</td>

                    </tr>

                </table>
            </div>
        </div>

        <div class="col-lg-3" id="report-user-stats">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-user"></span> Demographics</strong></div>

                <!-- Table -->
                <table class="table">
                    <tr>
                        <td class="header">
                            <img src="http://www.publicworkspartners.com/wp-content/uploads/2014/05/facebook-icon.png" width="20" height="20"/>
                        </td>
                        <td>{(($result.fb_count/$result.user_count)*100)|number_format:1:".":","}%</td>
                        <td class="header">
                            <span class="glyphicon glyphicon-envelope"></span>
                        </td>
                        <td>{(($result.fb_count/$result.email_count)*100)|number_format:1:".":","}%</td>
                    </tr>
                    <tr>
                        <td class="header">
                            <img src="http://vigesimalsoft.com/wp-content/uploads/2012/12/iPhone-Icon1.png" width="20" height="20"/>
                        </td>
                        <td>{(($result.ios_count/$result.user_count)*100)|number_format:1:".":","}%</td>
                        <td class="header">
                            <img src="http://www.ayowes.com/img/android-icon.png" width="20" height="20"/>
                        </td>
                        <td>{(($result.android_count/$result.user_count)*100)|number_format:1:".":","}%</td>
                    </tr>

                </table>
            </div>
        </div>



        <div class="col-lg-12 with-3d-shadow with-transitions"  id="report-monetization-stats">
            <div class="panel  panel-primary">
                <div class="panel-heading">
                    <div><span class="glyphicon glyphicon-stats"> </span><strong> System Monetization</strong></div>
                </div>
                <ul class="nav nav-tabs" id="system-monetization">
                    <li class="active"><a href="#"  id="system_monetization_total">Total</a></li>
                    <li><a href="#" id="system_monetization_network">By Network</a></li>
                    <li><a href="#" id="system_monetization_margin">Margins</a></li>
                </ul>
                <div>
                    <svg id="chart-home-monetization"></svg>
                </div>
            </div>
        </div>


        <div class="col-lg-4" id="report-network-revenue">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-time"></span> Ad Network API</div>
                <table class="table" style="text-align:left;">
                    <tr class="header">
                        <td>Network</td>
                        <td>Conversions</td>
                        <td>Revenue</td>
                    </tr>

                    <tr>
                        <td class="header"><img src="{$RESOURCE_PATH.IMG_PATH}/network_hasoffers.png"/></td>
                        <td>{$result.network.hasoffers.conversions}</td>
                        <td>${$result.network.hasoffers.payout|number_format:2:".":","}</td>
                    </tr>

                    <tr>
                        <td class="header"><img src="{$RESOURCE_PATH.IMG_PATH}/network_aarki.png"/></td>
                        <td>{$result.network.aarki.conversions}</td>
                        <td>${$result.network.aarki.payout|number_format:2:".":","}</td>
                    </tr>
                    <tr>
                        <td class="header"><img src="{$RESOURCE_PATH.IMG_PATH}/network_adscend.png"/></td>
                        <td>{$result.network.adscend.conversions}</td>
                        <td>${$result.network.adscend.payout|number_format:2:".":","}</td>

                    </tr>

                    <tr>
                        <td class="header"><img src="{$RESOURCE_PATH.IMG_PATH}/network_adaction.png"/></td>
                        <td>{$result.network.adaction.conversions}</td>
                        <td>${$result.network.adaction.payout|number_format:2:".":","}</td>
                    </tr>

                    <tr>
                        <td class="header"><img src="{$RESOURCE_PATH.IMG_PATH}/network_ksix.png"/></td>
                        <td>{$result.network.ksix.conversions}</td>
                        <td>${$result.network.ksix.payout|number_format:2:".":","}</td>
                    </tr>
                    <tr>
                        <td  class="alert-success header"><strong>Total Revenue</strong></td>
                        <td class="alert-success">{$result.network.adscend.conversions+$result.network.hasoffers.conversions+ $result.network.aarki.conversions+ $result.network.adaction.conversions+ $result.network.ksix.conversions}</td>
                        <td class="alert-success">${$result.network.adscend.payout+$result.network.hasoffers.payout+ $result.network.aarki.payout+ $result.network.adaction.payout+ $result.network.ksix.payout|number_format:2:".":","}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-lg-8" id="report-user-activity">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-stats"> Growth</span></div>
                <ul class="nav nav-tabs" id="system-growth">
                    <li class="active"><a href="#" id="system_user_activity_all">Users</a></li>
                    <li><a href="#" id="system_user_activity_dau_platform">DAU By Platform</a></li>
                    <li><a href="#" id="system_user_activity_new_users_platform">New Users By Platform</a></li>

                </ul>
                <div class="chart-container">
                    <svg id="chart-user-activity"></svg>
                </div>
            </div>
        </div>
    </div>






