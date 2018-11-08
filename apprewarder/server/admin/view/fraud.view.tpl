
<div class="content ">
    <h1>{$page_data.page_title}</h1>
    <ul class="nav nav-tabs" id="fraud-tabs">
        <li class="active"><a href="#fraud-vpn"  id="tab-fraud-vpn">VPN Manager</a></li>
        <li><a href="#fraud-users" id="tab-fraud-users">Fraudulent Users</a></li>
        <li><a href="#fraud-statistics" id="tab-fraud-statistics">Fraud Statistics</a></li>
        <li><a href="#fraud-actions" id="tab-fraud-actions">Fraud Actions</a></li>

    </ul>
    <br/>
    <div class="fraud-content" id="content-fraud-vpn">
        <div id="isp-controls">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    Show Unresolved ISPs <span class="caret"></span>
                </button>
                <ul class="dropdown-menu vpn-list" role="menu">
                    <li><a href="#" class="vpn-list-unresolved"><span class="glyphicon glyphicon-ok checkarea"></span> Show Unresolved ISPs</a></li>
                    <li class="divider"></li>
                    <li><a href="#" class="vpn-list-all"><span class="glyphicon checkarea"></span> Show All ISPs</a></li>

                    <li><a href="#" class="vpn-list-denied"><span class="glyphicon checkarea"></span> Show Denied ISPs</a></li>
                    <li><a href="#" class="vpn-list-accepted"><span class="glyphicon checkarea"></span> Show Accepted ISPs</a></li>
                </ul>
            </div>
                <button id="fraud-deny-selected" type="button" class="btn btn-sm btn-danger">
                    <span class="glyphicon glyphicon-warning-sign"></span> Deny Selected</button>
                <button id="fraud-accept-selected" type="button" class="btn btn-sm btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Allow Selected</button>
            <button id="fraud-refresh" type="button" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>

            <button id="fraud-select-all" type="button" class="btn btn-sm btn-default"> Select all</button>

        </div>
        <table id="isp-list" class="display dataTable table table-striped table-bordered" cellspacing="0" width="100%">

        </table>


    </div>
</div>

