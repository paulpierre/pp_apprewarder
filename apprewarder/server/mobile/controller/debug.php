<?php
if (strtolower($controllerFunction) == 'xhr')
{
    switch($controllerID)
    {
        case 'ignoreoffers':

            $offerList = json_decode($_POST['offerList'],true);

            $offerCount = count($offerList);
            $sysOfferInstance = new Offer();

            $responseString = '';
            $successCount = 0;

            foreach($offerList as $offerItem)
            {
                $offerID = $offerItem;
                $result = $sysOfferInstance->ignore_offer($offerID);
                if(empty($result)) { $responseString .='Error ignoring: ' . $offerID .' '; } else { $successCount++;}
            }

            if($successCount > 0) { $responseString .= $successCount . ' offers ignored!'; }
            print $responseString;

            break;

        case 'disableoffers':

            $offerList = json_decode($_POST['offerList'],true);

            $offerCount = count($offerList);
            $sysOfferInstance = new Offer();

            $responseString = '';
            $successCount = 0;

            foreach($offerList as $offerItem)
            {
                //print_r($offerItem);
                $offerID = $offerItem;//$offerItem['offerID'];
                if($sysOfferInstance->offer_exists($offerID))
                {
                    $result = $sysOfferInstance->disable_offer($offerID);

                    if(empty($result)) { $responseString .='Error removing: ' . $offerID .' '; } else { $successCount++;}
                }
                else {      $responseString .='ID: ' . $offerID . ' does not exist in the system. ';}
            }

            if($successCount > 0) { $responseString .= $successCount . ' offers disabled!'; }
            print $responseString;

        break;

        case 'updatesysoffers':
            $offerList = json_decode($_POST['offerList'],true);
            $offerCount = count($offerList);
            $sysOfferInstance = new Offer();

            $responseString = '';
            $successCount = 0;

            foreach($offerList as $offerItem)
            {
                //print_r($offerItem);
                $offerID = $offerItem['offerID'];
                if($sysOfferInstance->offer_exists($offerID))
                {
                    $result = $sysOfferInstance->update_offer(array(
                        'offer_id'=>$offerID,
                        'offer_type'=>$offerItem['offerType'],
                        'offer_source_id'=>$offerItem['offerNetworkID'],
                        'offer_image_url'=>$offerItem['offerImageURL'],
                        'offer_filter'=>$offerItem['offerFilter'],
                        'offer_name'=>htmlspecialchars($offerItem['offerName']),
                        'offer_description'=>htmlspecialchars($offerItem['offerDescription']),
                        'offer_user_payout'=>$offerItem['offerUserPayout'],
                        'offer_referral_payout'=>$offerItem['offerReferralPayout'],
                        'offer_country'=>$offerItem['offerCountry'],
                        'offer_platform'=>$offerItem['offerPlatform']
                    ));

                    if(empty($result)) { $responseString .='Error inserting: ' . $offerID .' '; } else { $successCount++;}
                }
                else {      $responseString .='ID: ' . $offerID . ' does not exist in the system. ';}

                if($successCount > 0) { $responseString .= $successCount . ' offers updated!'; }

                print $responseString;
            }

        break;

        case 'geticons':
            $offerList = json_decode($_POST['offerList'],true);
            $offerInstance = new Offer();

            //print_r($offerInstance->get_adaction_icons($offerList));
            $offerCount = 0;
            $offerSet = $offerInstance->get_offer_list();

            //print_r($offerList);exit();
            foreach($offerList as $offerItem)
            {

                $offer = $offerSet[$offerItem['offerKey']];//$_SESSION['manualOffersData'][intval($offerItem['offerKey'])];
                //print_r($offer);
                if(!isset($offer['offer_destination']) && !strpos($offer['offer_destination'],'itunes.apple')) continue;

                $offerIcon = $offerInstance->get_itunes_icon($offer['offer_destination']);
                $offerInstance->set_offer_icon($offerItem['offerID'],$offerIcon);
                $offerCount++;


            }
            print $offerCount . ' offer icons updated. Currently only iOS apps are supported';
            break;


        case 'addsysoffers':


            $offerList = json_decode($_POST['offerList'],true);



            $offerCount = count($offerList);
            //print_r($offerList);

            if($offerInstance->manual_offers_did_expire())
            {
                print 'Oops. It looks like the manual offers have expired. Please refresh your browser!'; die();
            }

            $sysOfferInstance = new Offer();

            $responseString = '';
            $successCount = 0;
            foreach($offerList as $offerItem)
            {
                $offer = $_SESSION['manualOffersData'][intval($offerItem['offerKey'])];
                $offerID = $offerItem['offerID'];
                if(!$sysOfferInstance->offer_external_id_exists($offerID))
                {
                    $result = $sysOfferInstance->add_offer(array(
                        'offer_type'=>2,
                        'offer_external_id'=>$offerID,
                        'offer_external_cost'=>0,
                        'offer_source_id'=>$offer['offerNetworkID'],
                        'offer_user_payout'=>floatval($offer['offerUserPayout']),
                        'offer_referral_payout'=>floatval($offer['offerReferralPayout']),
                        'offer_network_payout'=>floatval($offer['offerNetworkPayout']),
                        'offer_image_url'=>'',
                        'offer_filter'=>'',
                        'offer_name'=>htmlspecialchars($offer['offerName']),
                        'offer_description'=>htmlspecialchars($offer['offerDescription']),
                        'offer_country'=>'',
                        'offer_platform'=>OFFER_UNKNOWN,
                        'offer_click_url'=>$offer['offerURL'],
                        'offer_destination'=>$offer['offerDestination']
                    ));

                    if(empty($result)) { $responseString .='Error inserting: ' . $offerID .' '; } else { $successCount++;}
                }
                else {      $responseString .='ID: ' . $offerID . ' already exists in the system. ';}
            }

            if($successCount > 0) { $responseString .= $successCount . ' new offers added!'; }
            print $responseString;
        break;
    }
    die();
}

$adminPass = md5(DEBUG_ADMIN_PASSWORD);
if(!isset($_COOKIE['admin']))
{
    if(isset($_POST['password']) && md5($_POST['password']) == $adminPass){

        //$_SESSION['admin'] = $adminPass;
        setcookie('admin',$adminPass,time()+3600*24*30);

        header('location: http://' . API_HOST . '/debug');
        exit();
    }

    if(isset($_POST['password'])) print '<div style="color:#FF0000">invalid password</div><br>';
    ?>
    <form action='http://<? echo API_HOST; ?>/debug' method='post' xmlns="http://www.w3.org/1999/html">
        console: <input id='pw' name='password' type='password'/>
        <input type='submit' value='login'>
    </form>
    <?

    die();

}

?>
<body xmlns="http://www.w3.org/1999/html">
<head>
    <script src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
    <script src="http://tablesorter.com/__jquery.tablesorter.min.js"></script>
    <title>debug admin</title>
    <script type="text/javascript">

        $(function () {
            $('#checkall,#checkall2').click(function () {
                $(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
            });

            $(document).ready(function()
                    {

                        $('#userRewards').tablesorter();
                        $('#localOffers').tablesorter();
                        $('#hasOffers').tablesorter();
                        $('#btnSelectAllOffers').click(function(e){
                            e.preventDefault();
                            $('.offerSelect').each(function(index){
                                $(this).attr('checked','checked');
                            });
                        });
                        $('#btnDeselectAllOffers').click(function(e){
                            e.preventDefault();
                            $('.offerSelect').each(function(index){
                                $(this).removeAttr('checked');
                            });
                        });

                        $('#btnSelectAllSysOffers').click(function(e){
                            e.preventDefault();
                            $('.sysOfferSelect').each(function(index){
                                $(this).attr('checked','checked');
                            });
                        });

                        $('#btnDeselectAllSysOffers').click(function(e){
                            e.preventDefault();
                            $('.sysOfferSelect').each(function(index){
                                $(this).removeAttr('checked');
                            });
                        });

                        $('.checkRow').click(function(e){
                            //e.preventDefault();
                            var checkBox = $(this).children().find('.offerSelect');
                            if(checkBox.is(':checked')) { checkBox.removeAttr('checked');}
                            else {checkBox.attr('checked','checked'); }
                        });




                        $('.canEdit').click(function(){
                            //when clicking assume we want it selected
                            $(this).parents('tr').find('.sysOfferSelect').attr('checked','checked');
                            $(this).children('span').hide();
                            $(this).children('.editField').show();
                            if($(this).children('.editField').is('input,textarea'))
                            {
                                $(this).children('.editField').select();
                            }

                        });


                        $('#btnGetIcons').click(function(e){
                            e.preventDefault();
                            var offerList = new Array();


                            $('.sysOfferSelect').each(function(index){
                               // alert(index);
                                if($(this).is(':checked')) offerList.push({'offerKey':index,'offerID':$(this).attr('offerID')});
                            });
                           if(offerList.length < 1) { alert('Please Select an offer first'); return;}



                            var xhr = $.post( "/debug/xhr/getIcons/",{"offerList":JSON.stringify(offerList)}, function(result) {
                            })
                            .done(function(result) {
                                alert(result );
                            })
                            .fail(function(result) {
                                alert( "There was an error processing the request." );
                            })


                        });

                        $('#btnIgnoreOffers').click(function(){
                                if (confirm('You will never see these offers again, are you sure?')) {
                                    var offerList = new Array();
                                    $('.offerSelect').each(function(index){


                                        $('.offerSelect:checked').each(function(index){
                                            offerList.push($(this).attr('offerID'));
                                            $(this).parents('tr').remove();

                                        });

                                    });
                                    var xhr = $.post( "/debug/xhr/IgnoreOffers/",{"offerList":JSON.stringify(offerList)}, function(result) {

                                    })
                                            .done(function(result) {
                                                alert(result );
                                            })
                                            .fail(function(result) {
                                                alert( "There was an error processing the request." );
                                            })

                                }
                        });

                        $('#btnSaveChanges').click(function(e){
                            e.preventDefault();

                            var offerList = new Array();
                            $('.sysOfferSelect:checked').each(function(index){

                                offerList.push({
                                    'offerID':$(this).attr('offerID'),
                                    'offerName':$(this).parents('tr').find('.offerName').attr('value'),
                                    'offerImageURL':$(this).parents('tr').find('.offerImageURL').attr('value'),
                                    'offerPlatform':$(this).parents('tr').find('.offerPlatform :selected').attr('value'),
                                    'offerDescription':$(this).parents('tr').find('.offerDescription').val(),
                                    'offerType':$(this).parents('tr').find('.offerType').val(),
                                    'offerCountry':$(this).parents('tr').find('.offerCountry').attr('value'),
                                    'offerUserPayout':$(this).parents('tr').find('.offerUserPayout').attr('value'),
                                    'offerReferralPayout':$(this).parents('tr').find('.offerReferralPayout').attr('value'),
                                    'offerFilter':$(this).parents('tr').find('.offerFilter').attr('value')

                                });
                            });

                            var xhr = $.post( "/debug/xhr/UpdateSysOffers/",{"offerList":JSON.stringify(offerList)}, function(result) {

                            })
                                    .done(function(result) {
                                        alert(result );
                                    })
                                    .fail(function(result) {
                                        alert( "There was an error processing the request." );
                                    })
                        });

                        $('#btnDisableOffers').click(function(e){
                            e.preventDefault();
                            var offerList = new Array();
                            $('.sysOfferSelect:checked').each(function(index){
                                offerList.push($(this).attr('offerID'));
                                $(this).parents('tr').remove();

                            });

                            var xhr = $.post( "/debug/xhr/DisableOffers/",{"offerList":JSON.stringify(offerList)}, function(result) {

                            })
                                    .done(function(result) {
                                        alert(result );
                                    })
                                    .fail(function(result) {
                                        alert( "There was an error processing the request." );
                                    })
                        });

                        $('#btnAddOffers').click(function(){
                            var offerList = new Array();
                            $('.offerSelect:checked').each(function(index){
                                offerList.push({'offerKey':parseInt($(this).attr('keyID')),'offerID':$(this).attr('offerID')});
                                //build array
                                $(this).parents('tr').remove();

                            });


                            var xhr = $.post( "/debug/xhr/addSysOffers/",{"offerList":JSON.stringify(offerList)}, function(result) {

                            })
                                    .done(function(result) {
                                        alert(result );
                                    })
                                    .fail(function(result) {
                                        alert( "There was an error processing the request." );
                                    })

                        });


                        $("#filter").keyup(function(){
                            //hide all the rows
                            $("#offerBody").find("tr").hide();

                            //split the current value of searchInput
                            var data = this.value.split(" ");
                            //create a jquery object of the rows
                            var jo = $("#offerBody").find("tr");

                            //Recusively filter the jquery object to get results.
                            $.each(data, function(i, v){
                                jo = jo.filter("*:contains('"+v+"')");
                            });
                            //show the rows that match.
                            jo.show();
                            //Removes the placeholder text

                        }).focus(function(){
                                    this.value="";
                                    $(this).css({"color":"black"});
                                    $(this).unbind('focus');
                                }).css({"color":"#C0C0C0"});



                    }
            );

            if(typeof Android == 'object') Android.didLoad();
        });
    </script>
    <style type="text/css">
        body {
            font-family: Arial;font-size:8px;
        }

        table,td {
            font-family: Arial;
            font-size:14px;
            border: #c0c0c0 solid 1px;


        }
        table tr:hover {
            background:#F0F0F0;
        }
        .button {
            background:#5f9cc5;
            border-radius:5;
            padding:10px;
            text-decoration:none;
            font-family:'Arial';
            font-size:20px !important;
            color:#FFF;

            margin-right:10px;
        }

        #tblOffersButtons {
            padding:20px;
            border:1px solid #c0c0c0;
        }
        .bgRed {
            background-color:#931b0c;
        }

        .bgGreen {
            background-color:#81a921;
        }

        .boxScroll {
            height:300px;
            overflow-y:scroll;

        }

        h1 {
            font-family:Arial;
            font-size:20px;
        }

        .canEdit:hover {
            background-color:#fff5ba;
        }

        .editField {
            display:none;
            width:100%;

        }

        text-area {
            min-width:300px;
        }

    </style>
</head>
<?

//$userInstance = new User();
//$utilityInstance = new UtilityManager();
$sysOfferInstance = new Offer();

switch($controllerFunction)
{
    case 'sessionclear':
        print 'session removed. <a href="/debug">Go back to debug</a><br><br><a href="/">Go back to offers</a>';
        session_destroy();
    break;

    case 'sessionshow':
        $currentTime = time();
        $offerTTL = OFFER_TTL;
        $offerNonAppsTTL = (intval($_SESSION['offerTime'][OFFERS_VIDEO]) + $offerTTL) - $currentTime;
        $offerAppsTTL = (intval($_SESSION['offerTime'][OFFERS_APPS]) + $offerTTL) - $currentTime;
        $offerAllTTL = (intval($_SESSION['offerTime'][OFFERS_APPS]) + $offerTTL) - $currentTime;


        print '<a href="/debug/">Go back</a><br><pre>';
        print 'current time: ' . time() . '<br>';
        print 'global offer TTL: ' . $offerTTL . ' seconds, ' . round($offerTTL / 60,2) . ' minutes or ' . round($offerTTL / 60 / 60,2) . ' hours.<br>'  ;
        print 'OFFERS_APPS cache expires in: ' . round($offerAppsTTL / 60,2) . ' minutes or ' . round($offerAppsTTL / 60 / 60,2) . ' hours.<br>';
        print 'OFFERS_VIDEO cache expires in: ' . round($offerNonAppsTTL / 60,2) . ' minutes or ' . round($offerNonAppsTTL / 60 / 60,2) . ' hours.<br><br>';
        print 'OFFERS_ALL cache expires in: ' . round($offerAllTTL / 60,2) . ' minutes or ' . round($offerNonAppsTTL / 60 / 60,2) . ' hours.<br><br>';

        print_r($_SESSION);
        print '<br><a href="/debug/">Go back</a>';
    break;

    case 'offersraw':
        //dump the live raw logs
        print '<a href="/debug/">Go back</a><br><pre>';
        $offerInstance->raw_dump_offers();

    break;

    case 'showuseroffers':
        print '<pre>';
        $offers = $userInstance->raw_dump_user_offers();
        //print_r($users);
        print '<h1>User offers</h1><table border="1">';
        print '<tr><td>id</td><td>offer_id</td><td>user_id</td><td>offer_payout</td><td>offer_cost</td><td>offer_network_id</td><td>offer_network</td><td>offer_name</td><td>icon</td><td>offer_status</td><td>offer_click_count</td><td>offer_tcreate</td><td>offer_tmodified</td></tr>';
        foreach($offers as $offer)
        {
            print '<tr><td>'.$offer['id']. '</td><td>'.$offer['offer_id']. '</td><td>'.$offer['user_id'].'</td><td>'.floatval($offer['offer_payout']).'</td><td>'.$offer['offer_cost'].'</td><td>'.$offer['offer_network_id'].'</td><td>'.$offer['offer_network'].'</td><td>'.$offer['offer_name'].'</td><td><img style="width:75px;height:75px;" src="'. $offer['offer_image_url'] .'"/></td><td>'. $offer['offer_status'] .'</td><td>'. $offer['offer_click_count'] .'</td><td>'.$utilityInstance->time_ago(intval($offer['offer_tcreate'])).' ago</td><td>'.$utilityInstance->time_ago(intval($offer['offer_tcreate'])).' ago</td></tr>';
        }
        print '</table>';
    break;

    case 'showuserscallbacks':
        print '<pre>';
        $callbacks = $userInstance->raw_dump_user_callbacks();
        //print_r($users);
        print '<h1>User callbacks</h1><table border="1">';
        print '<tr><td>id</td><td>network_cb_id</td><td>user_id</td><td>offer_network</td><td>offer_payout</td><td>offer_id</td><td>offer_tcreate</td></tr>';
        foreach($callbacks as $callback)
        {
            print '<tr><td>'.$callback['id']. '</td><td>'.$callback['network_cb_id']. '</td><td>'.$callback['user_id'].'</td><td>'.$callback['offer_network'].'</td><td>'.floatval($callback['offer_payout']).'</td><td>'.$callback['offer_id'].'</td><td>'.$utilityInstance->time_ago(intval($callback['offer_tcreate'])).' ago</td></tr>';
        }
        print '</table>';


    break;

    case 'showusers':

        if($controllerID=='update')
        {
            $userID = $_POST['userID'];
            $userCredits = $_POST['userCredits'];
            if(isset($userID)&& isset($userCredits) && $userInstance->update_user_credits($userID,$userCredits)) print 'UserID:' . $userID . ' successfully updated to ' . $userCredits . ' credits'; else print 'Fuck, major error. Contact Paul!';
            die();
        }
        print '<pre>';
        $users = $userInstance->raw_dump_users();
        //print_r($users);
        print '<h1>User accounts</h1><table border="1">';
        print '<tr><td>user_id</td><td>user_friend_code</td><td>user_name</td><td>user_credits</td><td>user_referral_count</td><td>user_device_id</td><td>user_device_model</td><td>user_device_version</td><td>user_tcreate</td><td>user_tlogin</td><td>user_ip</td><td>user_locale</td><td>user_email</td></tr>';
        foreach($users as $user)
        {
            print '<tr><td>'.$user['user_id']. '</td><td>'.$user['user_friend_code']. '</td><td>'.$user['user_name'].'</td><td><input id="' . $user['user_id']. 'input" oldvalue="'.$user['user_credits'].'" value="'.$user['user_credits'].'"/><a href="#" class="submitCredits" id="'.$user['user_id'].'">Submit</a></td><td>'.$user['user_referral_count'].'</td><td>'.$user['user_device_id'].'</td><td>'.$user['user_device_model'].'</td><td>'.$user['user_device_version'].'</td><td>'.$utilityInstance->time_ago(intval($user['user_tcreate'])).' ago</td><td>'.$utilityInstance->time_ago(intval($user['user_tlogin'])).' ago</td><td>'.$user['user_ip'].'</td><td>'.$user['user_locale'].'</td><td>' . $user['user_email']. '</td></tr>';


        }
        print '</table>';
        ?>
    <script>
        $(document).ready(function() {
            $(".submitCredits").click(function(){
                var userID = $(this).attr('id');
                var oldCredits = $("#" + userID + "input").attr('oldvalue');
                var userCredits = $("#" + userID + "input").val();

                if(confirm('Are you sure you want to change user credits from ' + oldCredits + ' credits to ' + userCredits + "?"))
                {
                    $.post("/debug/showUsers/update", { userID: userID, userCredits:userCredits },
                            function(data) {
                                alert(data);
                                location.reload();
                            });
                }
                else return false;
            });
        });
    </script>
    <?
        print $debugFooter;

    break;

    case 'showsysoffers':
        if($controllerID == 'r') {

            $offerInstance->purge_manual_offers_cache();
            $_SESSION['manualOffersData'] = '';
            $_SESSION['manualOffersTime'] = '';
            $_SESSION['manualOffersTime'] = time();

            $hasOffers = $offerInstance->get_hasoffers_offers(OFFERS_APPS);
            $ksixOffers = $offerInstance->get_ksix_offers(OFFERS_APPS);
            $adActionOffers = $offerInstance->get_adaction_offers(OFFERS_APPS);
            $_SESSION['manualOffersData'] = array_merge((array)$hasOffers , (array)$ksixOffers,(array)$adActionOffers);
            print '<script>window.location="/debug/showsysoffers/";</script></body></html>';
            exit();
        } else {
            if(!empty($_SESSION['manualOffersData']) && $offerInstance->manual_offers_did_expire() == false) {
                $offers = $_SESSION['manualOffersData'];
            } else {
                print '<script>window.location="/debug/showsysoffers/r";</script></body></html>';
                exit();
            }
        }
        ?>
        <h1>System offers</h1>
        <h1><img src="http://aboutyourstartup.com/wp-content/uploads/2012/04/HasOffers_Logo_Large1-250x87.png" width="200" height="70"/>
        <img src="https://media.go2app.org/user_content/brand/logos/bmg/logo_1324327400.png" width="230" height="50"/>
        <img src="https://media.go2app.org/user_content/brand/logos/adactioninteractive/logo_1394617565.png" width="50" height="50"/>
        </h1><br>
        <div id="tblOffersButtons">
            <a href="#" class="button" id="btnSelectAllOffers">Select All</a>
            <a href="#" class="button" id="btnDeselectAllOffers">Deselect All</a>
            <a href="#" class="button bgGreen" id="btnAddOffers">Add selected offers</a>
            <a href="#" class="button bgRed" id="btnIgnoreOffers">Ignore selected offers</a>
            <a href="/debug/showsysoffers/r" class="button" id="btnRefreshOffers">Refresh Offers</a>
            <h1 style="display:inline;">Filter</h1><input id="filter" name="filter" width="300"/>
        </div>
        <div class='boxScroll'>
        <table id="hasOffers" class="tablesorter">
        <thead>
        <tr>
        <th>Name</th>
        <th>Offer ID</th>
        <!--<th>Icon</th>-->
        <th>Description</th>
        <th>Type</th>
        <th>Source ID</th>
        <th>Network Payout</th>
        <th>User Payout</th>
        <th>Referral Payout</th>
        <th>Click URL</th>
        <th>Select</th>

        </tr>
        </thead>
        <tbody id="offerBody">

        <?

        foreach($offers as $offerKey=>$offer)
        {
            /*
            if(!empty($offer['offerDestination']) && $a)
            {
                $txt = $offer['offerDestination'];
                $start = strpos($txt,'/id') + 3;
                $end = strpos($txt,'?');
                $id = substr($txt,$start,$end-$start); //print '<pre>original:' . $txt. PHP_EOL. 'start:' . $start. ' ' . substr($txt,$start,1) . PHP_EOL . 'end:' . $end . ' '. substr($txt,$end,1) . PHP_EOL  . 'final:' . $id;
            }*/

            //$offerIcon = isset($offerIcons[$offer['offerID']])?'<img src="'. $offerIcons[$offer['offerID']] . '"/>':'';
            print '<tr class="checkRow">';
            print '<td>'. $offer['offerName'] .'</td>';
            //print '<td><a href="javascript:$(\'#btnImg' . $offer['offerID'] .'\').remove();$(\'#imgURL' . $offer['offerID']. '\').show();" id="btnImg' . $offer['offerID']. '" class="showInput">Add</a><input class="imgInput" id="imgURL' . $offer['offerID'] . '" name="imgURL' . $offer['offerID'] . '" style="display:none;"/></td>';
            //print '<td><select name="platform"' . $offer['offerID'] .'><option value="0">None</option><option value="1">iOS</option><option value="2">Android</option></select></td>';
            print '<td>' . $offer['offerID'] .'</td>';
            //print '<td>' . $offerIcon . '</td>';
            print '<td>' . $offer['offerDescription'] .'</td>';
            print '<td>' . $offer['offerType'] .'</td>';
            print '<td>' . $offer['offerNetworkID'] .'</td>';
            print '<td>' . floatval($offer['offerNetworkPayout']) .'</td>';
            print '<td>' . $offer['offerUserPayout'] . '</td>';
            print '<td>' . $offer['offerReferralPayout'] . '</td>';
            print '<td><a href="'.$offer['offerURL'].'" target="_new">' . $offer['offerURL'] .'</a></td>';
            print '<td><input type="checkbox" class="offerSelect" offerID="'.$offer['offerID'].'" keyID="'. $offerKey .'" value="0"/></td>';
            print '</tr>';
        }

        ?>
        </tbody>
        </table>
        </div>



        <h1>AppRewarder Local Offers </h1><br><span style="font-size:15px;font-family:Arial">Click on a field below to edit</span><br>
        <div id="tblOffersButtons">

            <a href="#" class="button bgGreen" id="btnSaveChanges">Save changes</a>
            <a href="#" class="button bgRed" id="btnDisableOffers">Disable Offer</a>
            <a href="#" class="button" id="btnSelectAllSysOffers">Select All</a>
            <a href="#" class="button" id="btnDeselectAllSysOffers">Deselect All</a>
            <a href="/debug/showsysoffers/" class="button" id="btnRefreshSysOffers">Refresh Offers</a>
            <a href="#" class="button" id="btnGetIcons">Get Icons</a>


        </div>

            <table id="localOffers" class="tablesorter">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Icon URL</th>
                <th>Platform</th>
                <th>Offer External ID</th>
                <th>Description</th>
                <th>Type</th>
                <th>Source</th>
                <th>Network Payout</th>
                <th>User Payout</th>
                <th>Referral Payout</th>
                <th>Click URL</th>
                <th>Country</th>
                <th>Filter</th>
                <th>Created</th>
                <th>Modified</th>
                <th>Select</th>
            </tr></thead>
            <tbody>
            <tr>
             <?

                $offerList = $sysOfferInstance->get_offer_list();


                foreach($offerList as $offer)
                {
                    switch(intval($offer['offer_platform']))
                    {
                        case 0:$platform_html = 'N/A';break;
                        case 1:$platform_html = '<img src="http://www.sandiegopchelp.com/images/stories/tech_images/silver-apple-logo-small.jpg" width="50" height="50"/>';break;
                        case 11:$platform_html = '<img src="https://cdn1.iconfinder.com/data/icons/iPhone_4G-png/512/iphone%204G%20shadow.png" alt="iPhone" width="50" height="50"/>';break;
                        case 12:$platform_html = '<img src="http://www.wuhsd.org//cms/lib/CA01000258/Centricity/Domain/17/iPad_Logo.png" alt="iPad" width="50" height="50"/>';break;
                        case 2:$platform_html = '<img src="http://www.creativefreedom.co.uk/icon-designers-blog/wp-content/uploads/2013/03/00-android-4-0_icons.png" width="50" height="50"/>';break;
                    }

                    switch(intval($offer['offer_type']))
                    {
                        case OFFERS_ALL: $offer_type_text = "ALL";break;
                        case OFFERS_APPS: $offer_type_text = "MOBILE APPS";break;
                        case OFFERS_VIDEO: $offer_type_text = "VIDEO";break;
                    }

                    switch(intval($offer['offer_source_id']))
                    {
                        case 0: $offer_source_text = "APPREWARDER";break;
                        case 1: $offer_source_text = "FLURRY";break;
                        case 2: $offer_source_text = "AARKI";break;
                        case 4: $offer_source_text = "SPONSORPAY";break;
                        case 5: $offer_source_text = "NATIVEX";break;
                        case 6: $offer_source_text = "SUPERSONICADS";break;
                        case 7: $offer_source_text = "ADSCEND";break;
                        case 8: $offer_source_text = "W4";break;
                        case 9: $offer_source_text = "HASOFFERS";break;
                        case 10: $offer_source_text = "KSIX";break;
                        case 11: $offer_source_text = "ADACTION";break;
                    }

                    if($offer['offer_image_url'] !== '') $offer_image_html = '<img src="' . $offer['offer_image_url'] .'" width="50" height="50"/>'; else $offer_image_html = 'N/A';

                    print '<tr>';
                    print '<td>' . $offer['offer_id']. '</td>';
                    print '<td class="canEdit"><span>' . $offer['offer_name']. '</span><input name="offer_name" class="editField offerName" value="' . $offer['offer_name']. '" /></td>';
                    print '<td class="canEdit"><span>' . $offer_image_html . '</span><input name="offer_image_url" class="editField offerImageURL" value="' . $offer['offer_image_url']. '" /></td>';
                    print '<td class="canEdit"><span>'. $platform_html.'</span><select name="offer_platform" class="editField offerPlatform" value="' . $offer['offer_platform'] .'" ><option value="0"' . ((intval($offer['offer_platform']) == 0)?' selected="selected"':'') .'>N/A</option><option value="1"' . ((intval($offer['offer_platform']) == 1)?' selected="selected"':'') .'>iOS Universal</option><option value="11"' . ((intval($offer['offer_platform']) == 11)?' selected="selected"':'') .'>iPhone</option><option value="12"' . ((intval($offer['offer_platform']) == 12)?' selected="selected"':'') .'>iPad</option><option value="2"' . ((intval($offer['offer_platform']) == 2)?' selected="selected"':'') .'>Android</option></select></td>';
                    print '<td>' . $offer['offer_external_id']. '</td>';
                    print '<td class="canEdit"><span>' . $offer['offer_description']. '</span><textarea name="offer_description" class="editField offerDescription">' . $offer['offer_description']. '</textarea></td>';
                    print '<td class="canEdit"><span>' . $offer_type_text. '</span><select name="offer_type" class="editField offerType" value="' . $offer['offer_type'].'" ><option value="0"' . ((intval($offer['offer_type']) == 0)?' selected="selected"':'') .'>ALL</option><option value="1"' . ((intval($offer['offer_type']) == 1)?' selected="selected"':'') .'>MOBILE APP</option><option value="2"' . ((intval($offer['offer_type']) == 2)?' selected="selected"':'') .'>VIDEO</option></select></td>';
                    print '<td>' . $offer_source_text. '</td>';
                    print '<td>$' . number_format(floatval($offer['offer_network_payout']),2). '</td>';
                    print '<td class="canEdit"><span>' . floatval($offer['offer_user_payout']). '</span><input name="offer_user_payout" class="editField offerUserPayout" value="' . $offer['offer_user_payout'].'"/></td>';
                    print '<td class="canEdit"><span>' . floatval($offer['offer_referral_payout']). '</span><input name="offer_referral_payout" class="editField offerReferralPayout" value="' . $offer['offer_referral_payout'].'"/></td>';
                    print '<td><a href="' . $offer['offer_click_url']. '" target="_new">' . $offer['offer_click_url']. '</a></td>';
                    print '<td class="canEdit"><span>' . $offer['offer_country']. '</span><input name="offer_country" class="editField offerCountry" value="' . $offer['offer_country']. '"/></td>';
                    print '<td class="canEdit"><span>' . $offer['offer_filter']. '</span><input name="offer_filter" class="editField offerFilter" value="' . $offer['offer_filter']. '" /></td>';
                    print '<td>' . $utilityInstance->time_ago($offer['offer_tcreate']). ' ago</td>';
                    print '<td>' . $utilityInstance->time_ago($offer['offer_tmodified']). ' ago</td>';
                    print '<td><input type="checkbox" class="sysOfferSelect" offerID="'.$offer['offer_id'].'" keyID="'. $offerKey .'" value="0"/></td>';
                    print '</tr>';
                }


            ?>
            </tr>
            </tbody>
            </table>


        <?

    break;


    case 'rewards':
        switch($controllerID)
        {

            case 'upload_confirm':
                if(!isset($_SESSION['_csv'])) { print 'Error accessing CSV data..call Paul!';die(); }
                print 'Updating balances...<br><br>';
                $rows = $_SESSION['_csv'];
                foreach($rows as $row)
                {
                    $cols = explode(",",$row);
                    $friend_code = trim($cols[1]);
                    $user_id = $userInstance->get_user_id_by_user_friend_code($friend_code);
                    $user_credits_deduct = intval($cols[0]);
                    $user_credits_result = $userInstance->user_deduct_credits($user_id,$user_credits_deduct);
                    print 'updating user ' . $user_id . ' balance to ' . $user_credits_result .'..<br>';
                }
                print '<br><br>balances updated, ' . count($rows) . ' user rows updated.';
                unset($_SESSION['_csv']);
                print '<br><br><a href="/debug/rewards/upload">Go back</a>';
                die();
            break;
            case 'upload':

                    $rewardsFunction = $_POST['function'];
                    switch($rewardsFunction)
                    {

                        case "upload":

                            ?>
                            <h1>Confirm you want to modify credits:</h1>
                            <TABLE border="1">
                            <TR><TD>user_id</TD><TD>friend_code</TD><TD>balance</TD><TD>deduct amount</TD><TD>new balance</TD></TR>
                            <?
                            $target_path = TMP_PATH;
                            /* Add the original filename to our target path.
                            Result is "uploads/filename.extension" */
                            $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

                            if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

                                print '<span style="color:red;">There was an error uploading the file, please try again!</span>';
                            }

                            print 'The file '.  basename( $_FILES['uploadedfile']['name']) .' has been uploaded<br><br><pre>';


                            $file_contents = file_get_contents($target_path);
                            $rows = explode("\n",$file_contents);
                            $_SESSION['_csv'] = $rows;
                            print '<pre>'.print_r($rows,true) . '</pre>';
                            foreach($rows as $row)
                            {

                                $cols = explode(",",$row);
                                $friend_code = trim($cols[1]);
                                $user_id = $userInstance->get_user_id_by_user_friend_code($friend_code);
                                $user_credits = $userInstance->get_user_credits($user_id);
                                $user_credits_deduct = $cols[0];
                                $user_credits_result = intval($user_credits) - intval($user_credits_deduct);

                                print '<tr>';
                                print '<td>' . $user_id .'</td>';
                                print '<td>'. $friend_code .'</td>';
                                print '<td>' . $user_credits .'</td>';
                                print '<td style="color:red;">'. $user_credits_deduct .'</td>';
                                print '<td style="color:green;">' .$user_credits_result . '</td>';
                                print '</tr>';
                            }

                            ?>

                            </TABLE>
                            <a href="/debug/rewards/upload_confirm">Yes, confirm</a>
                            <?
                           // print_r($data);

                        break;
                    }
                ?>
                    <h1>Upload CSV</h1>
                    <p><b>INSTRUCTIONS</b>: Upload a Microsoft formatted CSV (not MS-DOS) with only two columns. Column 1: contains credits to deduct. Column 2: contains referral code. Export to CSV and upload.</p>
                    <form enctype="multipart/form-data" action="/debug/rewards/upload" method="POST">
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                        Choose a file to upload: <input name="uploadedfile" type="file" /><br />
                        <input type="hidden" name="function" value="upload"/>
                        <input type="submit" value="Upload File" />
                    </form>
                    <br><br><a href="/debug">Go back</a>
                <?
            break;

            case 'user':
                //$userInstance = new User();
                //$utilityInstance = new UtilityManager();

                $userRewards = $userInstance ->raw_dump_user_rewards();
                ?>
                <h1>User reward</h1><table id="userRewards" class="tablesorter">
                <form>
                    <thead>
                    <tr><td><input type="checkbox" name="check_all" value="0" id="checkall"></td>
                    <td>id</td>
                    <td>user_id</td>
                    <td>user_email</td>
                    <td>reward</td>
                    <td>value</td>
                    <td>user_friend_code</td>
                    <td>credits_to_deduct</td>
                    <td>credits_balance</td>
                    <td>total_credits_earned</td>
                    <td>total_credits_charged</td>
                    <td>total_credits_pending_charge</td>
                    <td>reward_user_status</td>
                    <td>submitted</td
                    </tr>
                    </thead>
                <?
                foreach($userRewards as $userReward)
                {
                    $userID = $userReward['user_id'];
                    if(!isset($userArray[$userID])) $userArray[$userID] = array();
                    $userCredits = $userInstance->get_user_credits($userID);
                    $userCreditsSum = (isset($userArray[$userID]['userCreditSum']))?$userArray[$userID]['userCreditSum']:$userInstance->get_user_credits_history_sum($userID);
                    $userSpendSum = (isset($userArray[$userID]['userSpendSum']))?$userArray[$userID]['userSpendSum']:$userInstance->get_user_spend_history_sum($userID);
                    $userClaimSum = (isset($userArray[$userID]['userClaimSum']))?$userArray[$userID]['userClaimSum']:$userInstance->get_user_claims_sum($userID);
                    switch(intval($userReward['reward_source_id']))
                    {
                        case 5:
                            $rewardImage = $userReward['reward_img'];
                            break;
                        default:
                            $rewardImage =  IMG_RES .  '/' . $rewardSources[intval($userReward['reward_source_id'])]['image'];
                            break;

                    }
                    ?>
                    <tr><td><input type="checkbox"  name="<? print $userReward['id']; ?>" value="0" id="reward<? print $userReward['id']; ?>"></td>
                    <td><? print $userReward['id'];?></td>
                    <td><? print $userReward['user_id'];?></td>
                    <td><? print $userReward['user_email'];?></td>
                    <td><img alt="<? print $userReward['reward_source_id']; ?>" src="<? print $rewardImage;?>" style="width:50px;height:50px;"/></td>
                    <td><? print $userReward['reward_user_payout'];?></td>
                    <td><? print $userReward['user_friend_code'];?></td>
                    <td style="background-color:rgba(255,242,164,0.47)"><? print $userReward['reward_user_cost'];?></td>
                    <td style="background-color:rgba(0,163,0,0.19)"><? print $userCredits;?></td>
                    <td><? print $userCreditsSum;?></td>
                    <td><? print $userSpendSum;?></td>
                    <td><? print $userClaimSum;?></td>
                    <td><? print (intval($userReward['reward_user_status'])==0)?'<span style="color:red;">pending</span>':'<span style="color:green">complete</span>';?></td>
                    <td><? print $utilityInstance->time_ago(intval($userReward['reward_user_tcreate']));?> ago</td>
                    </tr>
                 <?
                }
                print '</table></form><a href="submit">UPDATE CREDIT BALANCE!</a> ';
                die();
            break;

            case 'system':

                $rewardInstance = new Reward();
                $rewardList = $rewardInstance->get_rewards('US');
                //print '<pre>'.print_r($rewardList,true) . '</pre>';

                $rewardsFunction = $_POST['function'];
                switch($rewardsFunction)
                {
                    case 'create':
                        //print '<h1>post:</h1><pre>'.print_r($_POST,true) . '</pre>';
                        $reward_cost = $_POST['reward_cost'];
                        $reward_source_id = $_POST['reward_source_id'];
                        $reward_name = $_POST['reward_name'];
                        $reward_description = $_POST['reward_description'];
                        $reward_payout = $_POST['reward_payout'];
                        $reward_region = $_POST['reward_region'];
                        $reward_status = $_POST['reward_status'];
                        $reward_img = $_POST['reward_img'];
                        $reward_expiration = $_POST['reward_expiration'];
                        if(
                            !isset($reward_cost) ||
                            !isset($reward_source_id) ||
                            !isset($reward_name) ||
                            !isset($reward_description) ||
                            !isset($reward_payout) ||
                            !isset($reward_region) ||
                            !isset($reward_status)

                        ) {

                            print '<p style="color:red;">Sorry, you must fill out all fields when adding a new reward source. <a href="/debug/rewards/system/"> Go back</a></p><br>';
                            die();
                        }

                        $result = $rewardInstance->add_reward($reward_cost,$reward_source_id,$reward_img,$reward_name,$reward_description,$reward_payout,$reward_region,$reward_status=1,$reward_expiration);
                        print '<span style="color#:green;">Reward added successfully added!</span><br>';
                    break;
                }


                ?>

                <h1>Add a reward:</h1>
                <p>
                <form action='/debug/rewards/system' method='post'>
                Reward type:
                        <select name="reward_source_id">
                            <? //$rewardSources is set in the config file
                                $i=0;
                                for($i=0;$i<count($rewardSources);$i++)
                                {
                                    print '<option value="' . $i. '">' . $rewardSources[$i]['name'] . '</option>';
                                }
                            ?>
                        </select>
                    <br>
                    Reward name:<input name="reward_name" /><br>
                    Reward description:<input name="reward_description" /><br>
                    Reward status:<select name="reward_status"><option value='1'>enabled</option><option value='0'>disabled</option><option value='2'>sold out</option></select><br>
                    Reward payout (in real currency):<input name="reward_payout" /><br>
                    Reward region:<input name="reward_region" value="US,INT"/><br>
                    Reward image:<input name="reward_img" /><br>
                    Reward cost (in credits):<input name="reward_cost" /><br>
                    Reward Expiration(in INT(11) TIMESTAMP):<input name="reward_expiration" /><br>

                <input type="hidden" name="function" value="create"/>
                    <input type='submit' value='Create new reward'/>

                </p>
                </form>

                <TABLE border="1">
                    <TH colspan=11>Current rewards:</TH>
                    <TR><TD>reward_id</TD>
                        <TD>img</TD>
                        <TD>reward_name</TD>
                        <TD>reward_description</TD>
                        <TD>reward_cost</TD>
                        <TD>reward_payout</TD>
                        <TD>reward_source_id</TD>
                        <TD>reward_region</TD>
                        <TD>reward_expiration</TD>
                        <TD>created</TD>
                        <TD>modified</TD>
                    </TR>
                    <?
                    if(empty($rewardList)) { print '<TR><TD colspan="10" style="color:red;">No rewards inserted into DB! Please add some</TD></TR></TABLE>';

                    } else {

                        foreach($rewardList as $reward)
                          {

                            print '<TR>';
                            print '<TD>'. $reward['reward_id'].'</TD>';
                            if($reward['reward_source_id'] == 0) {
                                print '<TD><img src="'. IMG_RES . '/rewards/' . $reward['reward_img'] .'.png" style="width:50px;height:50px;"/></TD>';

                            } else {
                                print '<TD><img src="'. IMG_RES . "/" . $rewardSources[$reward['reward_source_id']]['image'] .'" style="width:50px;height:50px;"/></TD>';
                            }
                            print '<TD>'. $reward['reward_name'] .'</TD>';
                            print '<TD>'. $reward['reward_description'] .'</TD>';
                            print '<TD>'. $reward['reward_cost'] .'</TD>';
                            print '<TD>'. $reward['reward_payout'] .'</TD>';
                            print '<TD>'. $rewardSources[$reward['reward_source_id']]['name'] .'</TD>';
                            print '<TD>'. $reward['reward_region'] .'</TD>';
                            $timeDelta = time()-(intval($reward['reward_expiration']) - time());
                            $rewardExpiry = (intval($reward['reward_expiration']) !== 0)?'Expires in '.$utilityInstance->time_ago($timeDelta):'N/A';
                            print '<TD> '. $rewardExpiry .' VAL: ' .($timeDelta) .'</TD>';
                            print '<TD>'. $utilityInstance->time_ago(intval($reward['reward_tcreate'])) .' ago</TD>';
                            print '<TD>'. $utilityInstance->time_ago(intval($reward['reward_tmodified'])) .' ago</TD>';
                            print '</TR>';
                          }
                    }

                    ?>

                </TABLE>


            <?
            break;

        }
    break;

    default:
        ?>

            <script type="text/javascript">

                function cookieGet(key) {
                    if (!document.cookie) { return null; }
                    var regex = new RegExp(key+"=(.+?)(;|$)");
                    var matches = document.cookie.match(regex);
                    if (!matches) { return null; }
                    return unescape(matches[1]);
                }

                function cookieSet(key, value, d) {
                    if (!d) {
                        d = new Date();
                        d.setTime(d.getTime() + 30 * 24 * 60 * 60 * 1000);
                    }
                    var parts = [key+"="+escape(value)];
                    parts.push("expires="+d.toGMTString());
                    parts.push("path=/");
                    document.cookie = parts.join(";");
                    return value;
                }
                function cookieDelete(key) {
                    document.cookie = encodeURIComponent(key) + "=deleted; expires=" + new Date(0).toUTCString();
                }
            </script>



        <?
        print '<h1>Debug</h1>';
        print '<p style="font-size:20px;line-height:30px;">';
        print '<a href="/debug/rewards/upload">Upload CSV for redemption</a><br>';
        print '<a href="/debug/rewards/system">Manage system rewards</a><br>';
        print '<a href="/debug/rewards/user">Manage User rewards</a><br>';
        print '<a href="/debug/sessionClear">Clear Session Data</a><br>';
        print '<a href="/debug/sessionShow">Show Session Data</a><br>';
        print '<a href="/debug/offersRaw">Show Raw Ad Network Offers</a><br>';
        print '<a href="/debug/showUsers">Show all users</a><br>';
        print '<a href="/debug/showUserOffers">Show all initiated offers</a><br>';
        print '<a href="/debug/showUsersCallbacks">Show all completed offers (callbacks)</a><br>';
        print '<a href="/debug/showSysOffers">Show system offers</a><br>';
        print '<a href="javascript:alert(\'aidid:\' + cookieGet(\'aidid\') + \' aiuid:\' + cookieGet(\'aiuid\'))">Show Cookies</a><br>';
        print '<a href="javascript:cookieDelete(\'aidid\');cookieDelete(\'aiuid\');alert(\'Storage cleared!\');">Clear Cookies</a><br>';
        print '</p>';
        print '<br><a href="/">Go Back</a><br><br>';
        ?>


        <?
        break;

}
?>
</body>