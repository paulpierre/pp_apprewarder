
$(document).ready(function() {

    function loadISP(url,el,cb) {
        NProgress.start();
        $(el).addClass('content-loading');
        $.ajax({url:url,success:function(data){
            $(el).removeClass('content-loading');
            $(el).html(data);

            $(el).html(data);
            if(typeof cb == 'function') cb();
            $(el).dataTable().fnDestroy();
            $(el).dataTable( {
                "bPaginate": true,
                "bStateSave": true,
                "bSotrt": true,
                "aLengthMenu": [[100, 200, 3000, -1], [100, 200, 300, "All"]],
                "iDisplayLength": 100
            } );

            /* Init DataTables */
            var oTable = $(el).dataTable();
            NProgress.done();


        },
            error:function() {
                console.log('error loading the content');
                return;
            },
            cache:false
        });
    }

    function ispLookup(o)
    {
        var url = 'https://www.maxmind.com/geoip/v2.0/city_isp_org/' + o.ip_address +'?demo=1';
        console.log(url);
        var isp_id = o.isp_id;
        var isp_status = o.isp_status;

        $.ajax({url:url,
            type:'GET',
            success:function(data){
                console.log(data);
                var res = data;
                var isp_country = res.country.iso_code;
                var isp_name = res.traits.isp;
                if(typeof isp_name == 'string' && isp_name.length > 1)
                {
                    modal({
                        modalType:2, //error
                        modalTitle:'ISP Found',
                        modalMessage:res.traits.ip_address + ' belongs to ' + isp_name + ' from ' + isp_country + ', save new information to database?',
                        modalButtons:{
                            0:{text:'Cancel'},
                            1:{text:'Yes',callback:function(){
                                ispUpdate({ispName:isp_name,ispID:parseInt(isp_id),ispCountry:isp_country.toUpperCase(),ispStatus:isp_status});
                                $('#isp-' + isp_id + ' td.isp-name').text(isp_name);
                                $('#isp-' + isp_id + ' td.isp-country').html('<span class="flag flag-' + isp_country.toLowerCase() +'"></span>');
                                //if(res.type == 2) location.reload();
                                return;
                            }}}
                    });
                } else {
                    modal({
                        modalType:3, //error
                        modalTitle:'Network error!',
                        modalMessage:'It looks like here was an making that request.',
                        modalButtons:{
                            0:{text:'OK'}}
                    });
                    return;
                }

            },
            error:function(e) {
                modal({
                    modalType:3, //error
                    modalTitle:'Network error!',
                    modalMessage:'It looks like here was an making that request.',
                    modalButtons:{
                        0:{text:'OK'}}
                });
                return;
            },
            cache:false
        });
    }

    function ispUpdate(o) {
        url = '/fraud/update';
        $.ajax({url:url,
            type:'POST',
            data:o,
            success:function(data){
                console.log(data);
                var res = JSON.parse(data);
                modal({
                    modalType:res.type, //error
                    modalTitle:res.title,
                    modalMessage:res.message,
                    modalButtons:{
                        0:{text:'OK',callback:function(){
                            if(res.type == 2) location.reload();
                            return;
                        }}}
                });
            },
            error:function(e) {
                modal({
                    modalType:3, //error
                    modalTitle:'Network error!',
                    modalMessage:'It looks like here was an error requesting that page.',
                    modalButtons:{
                        0:{text:'OK'}}
                });
                return;
            },
            cache:false
        });

    }

    function loadTab(tab) {
        console.log('loading tab: ' + tab);
        $('#fraud-tabs li').removeClass('active');
        $('#tab-' + tab).parent().addClass('active');
        $('div.fraud-content').fadeOut('fast',function(){
            $('div#content-' + tab).fadeIn('fast');
        });

        switch(tab){
            case 'fraud-vpn':
                break;
            case 'fraud-users':
                break;
            case 'fraud-statistics':
                break;
            case 'fraud-actions':
                loadFilters('/filter/list','#offer-filter-list');
                break;
        }

    }


    var _page = window.location.hash.substring(1,window.location.hash.length);
    console.log(_page);
    if(typeof _page == 'string' && _page !== 'undefined' && _page !== '') { loadTab(_page) } else { window.location="#fraud-vpn";}
    $(window).on('hashchange',function (e) {
        _page = window.location.hash.substring(1,window.location.hash.length);

        if(_page.length > 0) loadTab(_page);
        return;
    });

    //https://www.maxmind.com/geoip/v2.0/city_isp_org/104.11.64.208?demo=1
    $('html').on('click','button#fraud-ip-lookup',function(e){
        e.preventDefault();
        var isp_ip = $(this).parent().parent().attr('data-isp-ip');
        var isp_id = $(this).parent().parent().attr('data-isp-id');
        var isp_status = $(this).parent().parent().attr('data-isp-status');
        ispLookup({
            isp_id:isp_id,
            ip_address:isp_ip,
            isp_status:isp_status,
            cb:function(isp_name,country) {

            }
        });
    });






    $('html').on('click','#fraud-select-all',function(e){
        e.preventDefault();
        var state = (typeof this._isChecked == 'undefined' || !this._isChecked )?true:false;
        $(this).text(((state)?'Deselect all':'Select all'));
        if(state && $(this).is(':visible')) this._isChecked = true; else this._isChecked = false;
        $('#isp-list td.isp-enabled input.checkbox').each(function(){
            this.checked = state;
        });
    });

    $('html').on('click','td.isp-enabled,td.isp-date,td.isp-ip,td.isp-id,input.checkbox,td.isp-status',function(e){
       var checkbox =  $(this).parent().find('input.checkbox');
        if($(checkbox).is(':checked')) { $(checkbox).prop('checked',false); } else {$(checkbox).prop('checked',true); }
    });

    $('html').on('click','td.isp-name',function(){
        var ispName = $(this).parent().attr('data-isp-name');
        var ispID = $(this).parent().attr('data-isp-id');
        var ispCountry = $(this).parent().attr('data-isp-country');
        var ispStatus = $(this).parent().attr('data-isp-status');
        modal({
            modalType:1, //error
            modalTitle:'Change ISP Name / Country',
            modalMessage:
                'ISP Name: <div class="input-group input-group-lg"><input type="text" id="input-isp-name" class="form-control" value="' + ispName +'"></div>ISP Country: <div class="input-group input-group-lg"><input type="text" id="input-isp-country" class="form-control" value="' + ispCountry +'"></div>',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var ispNameEdit = $('input#input-isp-name').val();
                    var ispCountryEdit = $('input#input-isp-country').val();
                    if(ispName !== ispNameEdit || ispCountry !== ispCountryEdit){
                        ispUpdate({ispName:ispNameEdit,ispID:parseInt(ispID),ispCountry:ispCountryEdit.toUpperCase(),ispStatus:ispStatus});
                    }
                    else {
                        $.magnificPopup.close();
                        return;
                    }

                }},
                    2:{text:'Research',class:'btn-primary',callback:function(){
                    var url = 'https://www.google.com/#q=' + ispName.replace(' ','+');
                    window.open(url,'_blank');
                }

                }
            }

        });
    });

    $('html').on('click','#fraud-deny-selected',function(e){
        e.preventDefault();
        var ispArray = new Array();

        $('#isp-list td.isp-enabled input.checkbox:checked').each(function(){
            var ispID = $(this).parent().parent().attr('data-isp-id');
            var ispName = $(this).parent().parent().attr('data-isp-name');
            var ispCountry = $(this).parent().parent().attr('data-isp-country');
            var ispStatus = 5;
            ispArray.push({
                ispID:ispID,
                ispName:ispName,
                ispCountry:ispCountry,
                ispStatus:ispStatus
            });
        });
        if(ispArray.length > 0)
        {
            modal({
                modalType:1, //error
                modalTitle:'Flag ISPs as VPN',
                modalMessage:
                    'Are you sure you want to flag and deny all '+ ispArray.length + ' ISP\'s as VPN?',
                modalButtons:{
                    0:{text:'Cancel'},
                    1:{text:'Save',class:'btn-success',callback:function(){

                        if(ispArray.length > 0){
                            ispUpdate({ispList:ispArray});
                        }
                        else {
                            $.magnificPopup.close();
                            return;
                        }

                    }}
                }

            });
        } else {
            modal({
                modalType:3, //error
                modalTitle:'Error',
                modalMessage:
                    'You must select at least one ISP!',
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        }
    });

    $('html').on('click','#fraud-accept-selected',function(e){
        e.preventDefault();
        var ispArray = new Array();

        $('#isp-list td.isp-enabled input.checkbox:checked').each(function(){
            var ispID = $(this).parent().parent().attr('data-isp-id');
            var ispName = $(this).parent().parent().attr('data-isp-name');
            var ispCountry = $(this).parent().parent().attr('data-isp-country');
            var ispStatus = 1;
            ispArray.push({
                ispID:ispID,
                ispName:ispName,
                ispCountry:ispCountry,
                ispStatus:ispStatus
            });
        });
        if(ispArray.length > 0)
        {
            modal({
                modalType:1, //error
                modalTitle:'Flag ISPs as VPN',
                modalMessage:
                    'Are you sure you want flag accept all '+ ispArray.length + ' ISP\'s as VPN?',
                modalButtons:{
                    0:{text:'Cancel'},
                    1:{text:'Save',class:'btn-success',callback:function(){

                        if(ispArray.length > 0){
                            ispUpdate({ispList:ispArray});
                        }
                        else {
                            $.magnificPopup.close();
                            return;
                        }

                    }}
                }

            });
        } else {
            modal({
                modalType:3, //error
                modalTitle:'Error',
                modalMessage:
                    'You must select at least one ISP!',
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        }
    });


    $('html').on('click','ul.vpn-list li a',function(e){
        e.preventDefault();
        if($(this).hasClass('vpn-list-unresolved'))
        {
            loadISP('/fraud/isp/unresolved','#isp-list');
            $('#isp-controls button.dropdown-toggle').html('Show Unresolved ISP\'s <span class="caret"></span>');
        } else if($(this).hasClass('vpn-list-all'))
        {
            loadISP('/fraud/isp/all','#isp-list');
            $('#isp-controls button.dropdown-toggle').html('Show All ISP\'s <span class="caret"></span>');

        } else if($(this).hasClass('vpn-list-denied'))
        {
            loadISP('/fraud/isp/denied','#isp-list');
            $('#isp-controls button.dropdown-toggle').html('Show Denied ISP\'s <span class="caret"></span>');

        } else if($(this).hasClass('vpn-list-accepted'))
        {
            loadISP('/fraud/isp/accepted','#isp-list');
            $('#isp-controls button.dropdown-toggle').html('Show Accepted ISP\'s <span class="caret"></span>');

        }
    });

    $('html').on('click','#fraud-refresh',function(e){
        e.preventDefault();
        loadISP('/fraud/isp','#isp-list');
        $('#isp-controls button.dropdown-toggle').html('Show Unresolved ISP\'s <span class="caret"></span>');

    });

    loadISP('/fraud/isp','#isp-list');




});