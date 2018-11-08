<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once(LIB_PATH . 'DeviceDetectManager.php');
include_once(LIB_PATH . 'Database.php');
include_once(LIB_PATH . 'UtilityManager.php');
include_once(MODEL_PATH . 'User.php');
include_once(MODEL_PATH . 'Offer.php');

$userInstance = new User();
$utilityInstance = new UtilityManager();
$sysOfferInstance = new Offer();
Header("content-type: application/x-javascript");

/*
$utilityInstance->log('userHasFacebook: ' . $userInstance->user_has_facebook()
.PHP_EOL.'isiOS: ' . (($userInstance->get_user_platform() == PLATFORM_IPAD || $userInstance->get_user_platform() == PLATFORM_IPHONE || $userInstance->get_user_platform() == PLATFORM_IPOD || $userInstance->get_user_platform() == PLATFORM_IOS)?'true':'false')
.PHP_EOL.'isAndroid: ' . (($userInstance->get_user_platform() == PLATFORM_ANDROID)?'true':'false')
.PHP_EOL.'userIsLoggedIn:' . (($userInstance->user_is_logged_in())?'true':'false')
.PHP_EOL.'userIsRegistered: ' . (($userInstance->didRegister?'true':'false')));
*/
?>
//<script>

function userHasFacebook() {return <? if($userInstance->user_has_facebook()) print 'true'; else print 'false';?>;}
function isiOS() { return <? if($userInstance->isiOS()) print 'true'; else print 'false' ?>;}
function isAndroid() { return <? if($userInstance->isAndroid()) print 'true'; else print 'false'; ?>; }
function userIsLoggedIn() { return <? if($userInstance->user_is_logged_in()) print 'true'; else print 'false'; ?>; }
function isRegistered() {return <?if(isset($_SESSION['userAccountStatus'])){$userInstance->userAccountStatus = $_SESSION['userAccountStatus'];$userInstance->process_user_account_status(); if($userInstance->didRegister) { print 'true';} else { print 'false';} } else {print 'false';} ?>;}
function getSupportEmail() {return '<? print SUPPORT_EMAIL; ?>';}
function getOfferPageMax() { return <? print OFFER_PAGE_MAX; ?>;}
function getUserID() {return <? print (isset($_SESSION['userID']))?'"'.$_SESSION['userID'] . '"':false;?>;}
function getFilterList() { return JSON.parse('<? print $sysOfferInstance->get_offer_filters_json(); ?>');}
function getUserLocale() { return '<? print $_SESSION['userLocale']; ?>';}
function didFacebookLike() { return <? print(($userInstance->user_did_like_facebook_page($_SESSION['userID'])))?'true':'false'; ?>;}
var touchSupport = false;
var eventClick = 'click';
var eventHover = 'mouseover mouseout';

(function(){
	if ('ontouchstart' in document.documentElement) {
		$('html').addClass('touch');
		touchSupport = true;
		eventClick = 'touchend';
		eventHover = 'touchstart touchend';
	} else {
		$('html').addClass('no-touch');
	}
})();

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

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


function arModal(o)
{
    var btn = '',btnClose='',charClass;

    switch(o.modalType)
    {
        default:case 'undefined':case '0': charClass='hide'; break;
        case 1:charClass = 'ar-char-normal';break;
        case 2:charClass = 'ar-char-error';break;
    }

    for(var k in o.modalButtons)
    {
        if(k == 0) { btnClose ='<a id="ar-btn-'+k+'" class="modal-btn ar-btn-close button radius grey grey-shadow">' + o.modalButtons[k].text +'</a>';}
        else {
            btn+='<a id="ar-btn-' + k +'" class="modal-btn button radius red-shadow">' + o.modalButtons[k].text + '</a>';
        }
    }

    var modalSrc = '<div class="ar-modal-container full-width white-popup bg-dark"><div class="ar-modal-header"><span class="ar-modal-character '+charClass+'"></span><b>'+ o.modalTitle+'</b></div><div class="ar-modal-message">'+ o.modalMessage+'</div><div class="ar-btn-container">'+btn+btnClose+'</div></div>';
    //console.log('ITEMS: \n COUNT: ' + $.magnificPopup.instance.items);//.length + $.magnificPopup.instance.items[1]);
    //$.magnificPopup.instance.items[1];

    var itemData = {
        src: modalSrc,
        type: 'inline'
    };

    var modalData = {
        items:itemData,
        removalDelay: 300,
        mainClass: 'mfp-fade',

        callbacks: {

            beforeOpen: function() {

            },
            beforeClose:function(){
                $('.modal-btn').each(function(i,v){
                    $('#' + $(this).attr('id')).unbind();

                });
            },
            beforeOpen: function() {

            },
            open:function()
            {
                for(var k in o.modalButtons)
                {
                    var btnInstance = $('#ar-btn-' + k);
                    if(k == 0) {
                        $('.mfp-close').addClass('hide');

                        if(typeof o.modalButtons[k].callback == 'function') {var f = o.modalButtons[k].callback;btnInstance.data('f',f);}
                        btnInstance.click(function(){
                            if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                            $.magnificPopup.instance.close();
                        });
                    } else {
                        if(typeof o.modalButtons[k].callback == 'function'){var f = o.modalButtons[k].callback;btnInstance.data('f',f);}

                        btnInstance.click(function(){
                            if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                        });
                    }
                }
            },
            change:function()
            {

            }
        }
    };


    if(!$.magnificPopup.instance.isOpen)
    {

        $.magnificPopup.instance.open(modalData);

    } else if($.magnificPopup.instance.isOpen ) {

        $('.modal-btn').each(function(i,v){
            $('#' + $(this).attr('id')).unbind();

        });
        $.magnificPopup.instance.items[0] = itemData;

        $.magnificPopup.instance.updateItemHTML();

        for(var k in o.modalButtons)
        {
            var btnInstance = $('#ar-btn-' + k);
            if(k == 0) {
                $('.mfp-close').addClass('hide');

                if(typeof o.modalButtons[k].callback == 'function') {var f = o.modalButtons[k].callback;btnInstance.data('f',f);}
                btnInstance.click(function(){
                    if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                    $.magnificPopup.instance.close();
                });
            } else {
                if(typeof o.modalButtons[k].callback == 'function'){var f = o.modalButtons[k].callback;btnInstance.data('f',f);}

                btnInstance.click(function(){
                    if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                });
            }
        }

    }
    else {

    }
}


function arShowLoading()
{
    $('body,html').css({'overflow-x': 'hidden','overflow-y': 'hidden'});
    $('body').append('<div class="ar-loading-full"></div><div class="ar-loading-img">Loading</div>');
    window._i = $('.ar-loading-img');
    window._f = $('.ar-loading-full');
    window._f.css('top',$(window).scrollTop());
    window._i.css("top", ($(window).scrollTop() + ($(window).height()/2)) - (window._i.outerHeight() / 2));
    window._i.css("left", ($(window).width() / 2) - (window._i.outerWidth() / 2));

}

function arHideLoading()
{
    window._f.fadeOut('slow',function(){
        $(this).remove();
    });

    window._i.fadeOut('slow',function(){
        $(this).remove();
        $('body,html').css({'overflow': 'auto','height': 'auto'});
    });
}


function arLoadPage (url,el)
{
    window.location.hash = '';


    arShowLoading();

	$.ajax({url:url,success:function(data){

        arHideLoading();
        if(data.length == 0)
        {
            arModal({
                modalType:2,
                modalTitle:'Yar, oops!',
                modalMessage:'My apologies. It looks like there was an error requesting that page. If problems continue, please contact us at:' + getSupportEmail(),
                modalButtons:{
                    0:{text:'OK',callback:function(){
                        scroll(0,0);
                        window.location.hash = '#home';
                    }}
                }
            });

            return;
        }
        scroll(0,0);

        var _o = $('#content');
		_o.attr('id','#content-old');
		_o.css({zIndex:998,position:'absolute',top:'48px'});
		$('#content-wrapper').prepend('<div id="content">' + data + '</div>');
		_o.animate({opacity:0},1000,function(){$(this).remove();});

	},
        error:function(data) {
            return;
        },
	cache:false
	});

	return;
}

function arFacebookLike()
{

    var url = '/srv/like';
    $.ajax(url,{ success:function(data){
        console.log('data:' + data);
        var o = JSON.parse(data);


        if(o.status == 1)
        {
            arModal({
                modalType:0,
                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        } else if(o.status==3){
            arModal({
                modalType:0,
                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'Later'},
                    1:{text:'Like Our Page',callback:function(){

                        setTimeout(function(){
                            window.location='http://facebook.com/<? FACEBOOK_FAN_PAGE_ID ?>';
                            $.magnificPopup.instance.close();
                        },1500);
                        window.location = 'fb://profile/<? print FACEBOOK_FAN_PAGE_ID ?>';


                        return;
                    }}
                }
            });
        } else if(o.status==4) {
            arModal({
                modalType:0,
                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{

                    1:{text:'Yes, I love rewards',callback:function(){
                        //window.location = 'http://www.facebook.com/AppRewarder/';
                        if(isiOS() && typeof window.bridge == 'object')window.bridge.send(JSON.stringify({"f":"fb_login"}));
                        else if(isAndroid() && typeof Android !== "undefined") Android.fbLogin();
                        return;
                    }}
                }
            });
        }
        else
        {
            arModal({
                modalType:2,
                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'OK',callback:function(){
                        location.reload();
                    }}
                }
            });
        }

    },
        error:function(data) {
            arModal({
                modalType:2,
                modalTitle:'Yar, oops!',
                modalMessage:'My apologies. It looks like there was an error updating your email address. Please try again soon. If you continue to have problems please email us at: ' + getSupportEmail(),
                modalButtons:{
                    0:{text:'OK'}
                }
            });
            return;
        },
        cache:false
    });
}

function arSetEmail(email)
{
    var url = '/srv/update_email';

    $.ajax({url:url,data:{'userEmail':email},success:function(data){
        arModal({
            modalTitle:'',
            modalMessage:data,
            modalButtons:{
                0:{text:'OK'}
            }
        });
    },
        error:function(data) {
            arModal({
                modalType:2,
                modalTitle:'Yar, oops!',
                modalMessage:'My apologies. It looks like there was an error updating your email address. Please try again soon. If you continue to have problems please email us at: ' + getSupportEmail(),
                modalButtons:{
                    0:{text:'OK'}
                }
            });
            return;
        },
        cache:false
    });

}

function arClaimReward(rewardID)
{
    var url = '/reward/claim/' + rewardID;
    $.ajax({url:url,success:function(data){
        var o = JSON.parse(data);
        if(o.status == 1)
        {
            arModal({

                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        }else if(o.status==0)
        {
            arModal({
                modalType:2,
                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        } else if(o.status==2)
        {
            arModal({
                modalType:2,
                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'Get more apps',callback:function(){
                        location.hash='#offers';
                    }}
                }
            });

        }else
        {
            arModal({

                modalTitle:o.title,
                modalMessage:o.body,
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        }


    },
        error:function(data) {
            arModal({
                modalType:2,
                modalTitle:'Yar, oops!',
                modalMessage:'My apologies. It looks like there was an error requesting your reward. Please try again soon. If you continue to have problems please email us at: ' + getSupportEmail(),
                modalButtons:{
                    0:{text:'OK'}
                }
            });
        },
        cache:false
    });

}

function arDownloadOffer(o)
{
    var offerURL = o.offerURL, offerID = o.offerID;
    if(
       typeof offerURL == 'undefined' ||
       offerURL == '' ||
       typeof offerID == 'undefined' ||
       offerID == ''
       ) {
        arModal({
            modalType:2, //error
            modalTitle:'Yar, oops!',
            modalMessage:'It looks like there was a problem with the download. Please try again shortly and if the problem persists, please email us at: ' + getSupportEmail(),
            modalButtons:{
                0:{text:'OK'}
            }
        });
        return;
    }
    var url = '/offer/get/' + o.offerID;

    $.ajax({url:url,success:function(data){

        arModal({
            modalType:1,
            modalTitle:'',
            modalMessage:data,
            modalButtons:{
                0:{text:'OK',callback:function(){
                    var el = $('a.app-offer-container[offerID="' + offerID + '"]');
                    el.addClass('animated flash');
                    var a = setTimeout(function(){el.slideUp();
                        window.open(offerURL,'_blank');
                    },700);
                }}
            }
        });

    },
        error:function(data) {
            arModal({
                modalType:2,
                modalTitle:'Yar, oops!',
                modalMessage:'My apologies. It looks like there was an error downloading. Please try again soon. If you continue to have problems please email us at:' + getSupportEmail(),
                modalButtons:{
                    0:{text:'OK'}
                }
            });
            return;

        },
        cache:false
    });
}

function arFilter(el)
{
    jQuery.expr[':'].contains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
                .indexOf(m[3].toUpperCase()) >= 0;
    };

    var filterList = getFilterList();
    var locale = getUserLocale();
    var target,condition,didFilter = false;
    var payload = function(action,el){
        switch(parseInt(action))
        {
            case 1:el.parent().parent().remove();break;
        }
        didFilter = true;
    };
    for(k in filterList)
    {
        switch(parseInt(filterList[k].offerFilterTarget)) {
            case 1: target='app-title';break;
            case 2: target='app-details-description'; break;
        }
        var q = $(el + ' .' + target +':contains("'+ (filterList[k].offerFilterText.toLowerCase()) +'")');
        $(q).each(function(key,val){
            condition = parseInt(filterList[k].offerFilterCondition);
            switch(condition)
            {
                case 2:
                    if(q.length > 1 && key !==0)payload(filterList[k].offerFilterAction,$(this));
                    break;
                case 3:if(q.length > 1 && key !==q.length-1) payload(filterList[k].offerFilterAction,$(this));break;
                case 4:if($(this).attr('oimg') == '1') payload(filterList[k].offerFilterAction,$(this));break;
                case 5:if($(this).attr('oimg') == '0') payload(filterList[k].offerFilterAction,$(this));break;
            }
        });
    }
    return didFilter;

}

function arLoadRewards(el)
{
    $('.ar-offers-loading').show();

    var url = '/reward/list';
    $.ajax({url:url,success:function(data){

        $(el).append(data);


        $('.ar-offers-loading').hide('fast',function(){
            $(this).remove();
            $("#app-content").data('royalSlider').updateSliderSize();
        });


        if(typeof callback == 'function') {
            callback();

        }
    },
        error:function(data) {console.log('error loading the content');return;},
        cache:false
    });
}

function arLoadOffers(el,offerType,offerPage,callback)
{

    $('.ar-offers-loading').show();

    var url = '/offer/' + offerType + '/' + ((typeof offerPage == 'number')?offerPage:'');
    $.ajax({url:url,success:function(data){


        if(data.length == 0) {
            $('.btn-load-more').hide();
            return;
        }


        $(el).append(data);


        $(el).find('a.app-offer-container').sort(function (a, b) {
            return (parseInt($(a).attr('rank')) - parseInt($(b).attr('rank')))* -1;
        }).appendTo(el);

        arFilter(el);

        $('.ar-offers-loading').hide('fast',function(){
            $(this).remove();
            $("#app-content").data('royalSlider').updateSliderSize();
        });

        if(offerType == 'pending') return;

        var pageSize = getOfferPageMax;
        var nextPage = ($('#next_page_' + offerPage).attr('data') == '1')?true:false;


        var offerSum = 0;
        var offerCount = 0;
        $('.app-offer:[payout]').each(function(){
            var payout = parseInt($(this).attr('payout'));
            offerSum += payout;
            offerCount++;
        });

        if(!nextPage) {$('.btn-load-more').hide();}
        $('#totalOffers').html(offerCount);
        $('#totalOfferPayout').html(offerSum);
        $('.offer-payout').addClass('app-offer');
        arHidePending();

        if(typeof callback == 'function') {
            callback();

        }
    },
        error:function(data) {console.log('error loading the content');return;},
        cache:false
    });

}

function arTimer(el,txtAppend)
{
    var _time = parseInt($(el).attr('data')) - Math.round(new Date().getTime() / 1000),_txt, _this = $(el), _d, _h, _m,_s;
    _this._timer =  setInterval(function(){
        _time--;
        _d = parseInt(_time / 86400);
        _n = _time % 86400;
        _h = parseInt(_n / 3600);
        _n = _n % 3600;
        _m = parseInt(_n / 60);
        _s = parseInt(_n % 60);

        _txt = ((_d>0)?_d+'d ':'') + ((_d<2 && _h > 0)?_h+'h ':'') + ((_h<24 && _m>0)?_m+'m ':'') + ((_h<24 && _s>0)?_s+'s':'');
        if(_time < 0) { _this.html('Expired'); clearInterval(_this._timer); return;}
        else {
            if(typeof txtAppend == 'undefined') var txtAppend = '';
            _this.html(_txt + txtAppend);
        }
    },1000);
}


function arLoadPromo(el,promoName,callback)
{
    var url = '/promo/' + promoName
    $.ajax({url:url,success:function(data){
        $(el).append(data);
        if(typeof callback == 'function')callback();else return;
    },
        error:function(data) {console.log('error loading the content');return;},
        cache:false
    });
}


function arAddPending(oid) {
    if(typeof oid == 'undefined' || typeof oid == null) return;
    var c = cookieGet('ar_apps_p');
    var _ar_apps_p = (c !== null)?c+','+oid:oid;
    cookieSet('ar_apps_p',_ar_apps_p);
    return;
}

function arHidePending(){
    var _ar_apps_p = cookieGet('ar_apps_p');
    if(_ar_apps_p == null) return;
    _ar_apps_p.split(/,/);
    for(i=0;i<=_ar_apps_p.length;i++){$('.app-offer-container[oid=' + _ar_apps_p[i]+ ']').addClass('hide');}
}


jQuery(document).ready(function($) {
    var opened = true;
    var _currentHash = window.location.hash.substring(1,window.location.hash.length);
    var page = _currentHash;

    if(isiOS() && typeof window.bridge == "undefined") {
        document.addEventListener('WebViewJavascriptBridgeReady', function(event) {
            window.bridge = event.bridge;
            var u=getUserID();
            if(u) {window.bridge.send(JSON.stringify({"f":"set_uid","uid":u}), function responseCallback(responseData){

            });}
            window.bridge.init(function(message, responseCallback) { });
        }, false);
    } else if(isAndroid() && typeof Android !== "undefined"){
        if(getUserID()) {
            var _u = Android.getVal("userID");
            if(typeof _u  !== "string" || _u.length <= 0)  Android.setVal('userID',getUserID());
        }

        Android.didLoad();
    }

	$('a.ar-nav').bind(eventClick, function(e) {

        $('#content-container').toggleClass('active');
        $('#sidemenu').toggleClass('active');
        if(opened){
            opened = false;
            setTimeout(function() {
                $('#sidemenu-container').removeClass('active');
            }, 500);
        } else {
            $('#sidemenu-container').addClass('active');
            opened = true;
        }

        e.preventDefault();
        window.location = $(this).attr('href');
	});


	$('#menu-trigger').bind(eventClick, function(e) {
        e.preventDefault();

        if(userIsLoggedIn())
        {
            $('#content-container').toggleClass('active');
            $('#sidemenu').toggleClass('active');
            if(opened){
                opened = false;
                setTimeout(function() {
                    $('#sidemenu-container').removeClass('active');
                }, 500);
            } else {
                $('#sidemenu-container').addClass('active');
                opened = true;
            }
        }
	});

    $('html').on('click','.facebook-connect-button',function(e){
        e.preventDefault();
        if(isiOS() && typeof window.bridge == 'object')window.bridge.send(JSON.stringify({"f":"fb_login"}));
        else if(isAndroid() && typeof Android !== "undefined") Android.fbLogin();
    });

    if(userIsLoggedIn() && !userHasFacebook()){
        if(isiOS()) {
            setTimeout(function(){  window.bridge.send(JSON.stringify({"f":"fb_data"}), function responseCallback(responseData){
                var res = JSON.parse(responseData);
                var _d = {
                    'fbEmail':res.fbEmail,
                    'fbName':res.fbName,
                    'fbUserID':res.fbUserID,
                    'fbGender':res.fbGender,
                    'fbLocale':res.fbLocale,
                    'fbVerified':res.fbVerified,
                    'fbToken':res.fbToken
                };
                $.post('/srv/fb',_d,function(data) {
                    if(data !== '0') {
                        arModal({modalType:1,modalTitle:'',modalMessage:data,modalButtons:{0:{text:'OK'}}});

                        arModal({
                            modalType:1,
                            modalTitle:'',
                            modalMessage:data,
                            modalButtons:{
                                0:{text:'OK',callback:function(){
                                    window.location = '#refresh';
                                    return;

                                }}
                            }
                        });
                    }

                });
            });},2000);
        } else if(isAndroid() && !userHasFacebook()) {

            var fbEmail = Android.fbEmail()
                    ,fbName = Android.fbName()
                    ,fbUserID = Android.fbUserID()
                    ,fbGender = Android.fbGender()
                    ,fbLocale = Android.fbLocale()
                    ,fbVerified = Android.fbVerified()
                    ,fbToken = Android.fbToken();

            if(fbToken.length > 5) {
                _d = {
                    'fbEmail':fbEmail,
                    'fbName':fbName,
                    'fbUserID':fbUserID,
                    'fbGender':fbGender,
                    'fbLocale':fbLocale,
                    'fbVerified':fbVerified,
                    'fbToken':fbToken
                };
                $.post('/srv/fb',_d,function(data) {
                    if(data !== '0') {
                        arModal({modalType:1,modalTitle:'',modalMessage:data,modalButtons:{0:{text:'OK'}}});
                        window.location = '#refresh';
                    }

                });
            }

        }
    }



	jQuery.rsCSS3Easing.easeOutBack = 'cubic-bezier(0.175, 0.885, 0.320, 1.275)';

    $('a.btn-top-fixed').click(function(e){
        e.preventDefault();
        $("html, body").animate({scrollTop:0}, '500', 'swing');
    });
    $('.btn-top-fixed').css({bottom:5,right:5});

    $('html').on('click','input[name=ar-friend-code]',function() {
        this.focus();
        this.setSelectionRange(0,999);
    });

    $('html').on('click','a.ar-tip',function(){
        arModal({
            modalType:1,
            modalTitle:(typeof $(this).attr('title') !== 'undefined')?$(this).attr('title'):'Tip:',
            modalMessage:$(this).attr('text'),
            modalButtons:{
                0:{text:'OK'}
            }
        });
    });

 	var $alertBoxes = $('html').on(eventClick,'.alert-box .close',function(e){
        e.preventDefault();
        var $parent = $(this).parent('.alert-box');
        $parent.fadeOut(500);
        setTimeout(function() { $parent.hide(0); }, 500);
    });

    if(window.location.hash == '') {
        if(userIsLoggedIn()) {
            arLoadPage('/home','#content');
        } else
        {
            arLoadPage('/register/new','#content');
            return;
        }
    }
    $(window).hashchange(function(e){
        var _page = window.location.hash.substring(1,window.location.hash.length);
        if(_currentHash == _page || _page == '')
        {
            e.preventDefault();
            return;
        }

        if(_page=='share') $('.ar-share-link').hide(); else $('.ar-share-link').show();

        switch(_page)
        {
            case 'share':
            case 'reward':
            case 'vault':
            case 'history':
            case 'home':
            case 'offers':
            case 'faq':
            case 'help':
            case 'beta':
            case 'university':
                if(userIsLoggedIn()) {
                    _currentHash = _page;
                    arLoadPage('/' + _page ,'#content');
                } else {
                    arLoadPage('/register/new','#content');
                }
                break;
            case 'logout':
                window.location = '/logout';
                break;
            case 'refresh':
                window.location = '/r/';
                _currentHash = '';
                break;
            case 'back':
                e.preventDefault();
                window.history.back();
                break;
            default:
                break;
        }
        return;
    });


    if(!didFacebookLike())
    {
        var fbLikeViews = (window.localStorage.getItem('fbLikeViews') !==null)?window.localStorage.getItem('fbLikeViews'):0;
        //console.log('fbLikeViews:' + fbLikeViews);
        if(parseInt(fbLikeViews) < 1 && userHasFacebook()) {
            fbLikeViews++;
            window.localStorage.setItem('fbLikeViews',fbLikeViews);
            arFacebookLike();
        } else if(!userHasFacebook())
        {
            arFacebookLike();
        }
    }


});
//</script>