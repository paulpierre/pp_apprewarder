


$(document).ready(function() {


    function saveFilter(o)
    {
        if(!o.offerFilterID) url = '/filter/add'; else url = '/filter/update';

        console.log(o);
        console.log('to url:' + url);

        $.ajax({url:url,
            type:'POST',
            data:o,
            success:function(data){
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

    function getAppStoreData(o,cb)
    {
        url = '/offer/appstoredata';

        $.ajax({url:url,
            type:'POST',
            data:o,
            success:function(data){
              if(typeof cb == 'function'){ cb(JSON.parse(data)); }
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



    function loadFilters(url,el,cb) {
        NProgress.start();
        $("#offer-filter-list").addClass('content-loading');
        $.ajax({url:url,success:function(data){
            $(el).removeClass('content-loading');
            $(el).html(data);
            $("[name='filter-enabled']").bootstrapSwitch();



            if(typeof cb == 'function') cb();
            NProgress.done();


        },
            error:function() {
                console.log('error loading the content');
                return;
            },
            cache:false
        });
    }

    function loadOffers(url,el,cb) {
        NProgress.start();
        $(el).addClass('content-loading');
         $.ajax({url:url,success:function(data){
             $(el).removeClass('content-loading');
             $(el).html(data);

             /**
              *  LETS TAG ALL ELEMENTS
              */
             var a = 0;
             $('a.network-offer').each(function(){
                a++;
                var offerName =  $(this).find('span.name').text().toLowerCase();
                var countries = $(this).attr('data-offer-countries').split(',');

                 for(index = 0; index < countries.length; ++index)
                 {
                    $(this).addClass(countries[index].toLowerCase());
                 }

                 if(
                     offerName.indexOf('ios') > 0
                         ||offerName.indexOf('iphone') > 0
                         ||offerName.indexOf('ipad') > 0
                         ||offerName.indexOf('ipod') > 0
                     )  {$(this).addClass('ios'); }
                 if(
                     offerName.indexOf('android') > 0

                     )  {$(this).addClass('android'); }
             });


             if(a>0) $('#network-offer-count').text(a);

             if(typeof cb == 'function') cb();
             NProgress.done();
             $container.isotope('updateSortData').isotope();


         },
             error:function() {
                 console.log('error loading the content');
                 return;
             },
             cache:false
         });
    }


    function updateOffer(o) {
        url = '/offer/update';

        $.ajax({url:url,
            type:'POST',
            data:o,
            success:function(data){
                var res = JSON.parse(data);
                modal({
                    modalType:res.type, //error
                    modalTitle:res.title,
                    modalMessage:res.message,
                    modalButtons:{
                        0:{text:'OK',callback:function(){
                            location.reload();
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

    function stopOffer(offerID) {
        url = '/offer/pause/' + offerID;
        var oID = offerID;
        $.ajax({url:url,
            success:function(data){
                var res = JSON.parse(data);
                modal({
                    modalType:res.type, //error
                    modalTitle:res.title,
                    modalMessage:res.message,
                    modalButtons:{
                        0:{text:'OK',callback:function(){
                            $('#offer-list tr.offer-edit-row[data-offer-id="' + oID +'"]').addClass('offer-disabled');
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

    function startOffer(offerID) {
        url = '/offer/start/' + offerID;
        var oID = offerID;
        $.ajax({url:url,
            success:function(data){
                var res = JSON.parse(data);
                modal({
                    modalType:res.type, //error
                    modalTitle:res.title,
                    modalMessage:res.message,
                    modalButtons:{
                        0:{text:'OK',callback:function(){
                            $('#offer-list tr.offer-edit-row[data-offer-id="' + oID +'"]').removeClass('offer-disabled');
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

    function deleteOffer(offerID) {
        url = '/offer/delete/' + offerID;
        var oID = offerID;
        $.ajax({url:url,
            success:function(data){
                var res = JSON.parse(data);
                modal({
                    modalType:res.type, //error
                    modalTitle:res.title,
                    modalMessage:res.message,
                    modalButtons:{
                        0:{text:'OK',callback:function(){
                            $('#offer-list tr.offer-edit-row[data-offer-id="' + oID +'"]').hide();
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

    function addOffer(o) {
        url = '/offer/add';

        $.ajax({url:url,
                type:'POST',
             data:o,
            success:function(data){
            var res = JSON.parse(data);
            modal({
                modalType:res.type, //error
                modalTitle:res.title,
                modalMessage:res.message
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
        $('#offer-tabs li').removeClass('active');
        $('#tab-' + tab).parent().addClass('active');
        $('div.offer-content').fadeOut('fast',function(){
            $('div#content-' + tab).fadeIn('fast');
        });

        switch(tab){
            case 'offer-add':
//if(_page == 'offer-add') {

                loadOffers('/offer/list','#network-offers',function(){
                    $container = $('div#network-offers');
                    $container.isotope({
                        filter: '*',
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false
                        },
                        getSortData: {
                            payout: '[data-payout] parseFloat',
                            name: '[data-offer-name]'
                        },
                        sortBy: 'payout',
                        sortAscending:false
                    });
                });
                //}
                break;
            case 'offer-current':
                break;
            case 'offer-statistics':
                break;
            case 'offer-filter':
                loadFilters('/filter/list','#offer-filter-list');
                break;
        }

    }



    $('#btn-refresh-network-offers').click(function(e){
        e.preventDefault();
        var el = this;
        loadOffers('/offer/list/r','#network-offers',function(){$(el).removeClass('disabled');});
    });


    $('input#text-filter').keyup(function(e){
        var data = $(this).val().trim().toLowerCase();
        if(data.length == 0){
             $container.isotope({filter:'*'});
            return;
        }
        $container.isotope({ filter: function(e){
            var offerName = $(this).attr('data-offer-name').trim().toLowerCase();
            if(offerName.indexOf(data) > -1) return true
            return false;
        } });

    });

    $('#filter-incent').click(function(e){
        e.preventDefault();

        $container.isotope({ filter: function(e){
            var offerName = $(this).attr('data-offer-name').trim().toLowerCase();
            var offerDescription = $(this).attr('data-offer-description').toLowerCase();
            var offerNetwork = $(this).attr('data-offer-network');

            switch(offerNetwork)
            {
                case 'adaction':
                    if(offerDescription.indexOf('incentivized traffic;') > -1) { return false;} else {return true;}
                    break;
                case 'ksix':
                    if(offerName.indexOf('no incent') > -1) { return false; } else { return true;}
                    break;
            }

        } });

    });

    $('#sort-payout-asc').click(function(e){
        e.preventDefault();
        $container.isotope({
            sortBy: 'payout',
            sortAscending:true
        });
    });


    $('#sort-payout-desc').click(function(e){
        e.preventDefault();
        $container.isotope({
            sortBy: 'payout',
            sortAscending:false
        });
    });

    $('#sort-name').click(function(e){
        e.preventDefault();
        $container.isotope({
            sortBy: 'name',
            sortAscending:true
        });
    });


    $('#filter-adaction').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.adaction',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    $('#filter-ksix').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.ksix',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    $('#filter-hasoffers').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.hasoffers',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    $('#filter-ios').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.ios',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    $('#filter-android').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.android',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });



    $('.filter-reset').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '*',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    /**
     *  FILTER COUNTRIES
     */
    $('#filter-us').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.us',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });


    $('#filter-ca').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.ca',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    $('#filter-northamerica').click(function(e){
        e.preventDefault();

        $container.isotope({
            filter: '.ca,.us',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
    });

    //DROP DOWN CONTROLS
    $('ul.dropdown-menu li a').click(function(e){
        e.preventDefault();
        $(this).parent().parent().parent().find('ul.dropdown-menu .glyphicon').removeClass('glyphicon-ok');
        var _data = $(this).attr('data');
        $(this).parent().parent().parent().find('ul.dropdown-menu').attr('data',_data);
        $(this).find('span.glyphicon').addClass('glyphicon-ok');
        //console.log(' a.data: ' + _data + ' ul.data:' + $(this).parent().parent().parent().find('ul.dropdown-menu').attr('data'));
    });

    var countryCodes = ['AF','UK','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BQ','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CG','CD','CK','CR','HR','CU','CW','CY','CZ','CI','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR','GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK','HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT','JM','JP','JE','JO','KZ','KE','KI','KP','KR','KW','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RO','RU','RW','RE','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST','SA','SN','RS','SC','SL','SG','SX','SK','SI','SB','SO','ZA','GS','SS','ES','LK','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG','UA','AE','GB','US','UM','UY','UZ','VU','VE','VN','VG','VI','WF','EH','YE','ZM','ZW','AX'];
    var countryList = [
        {label: 'North America', value: 'US,CA'},
        {label: 'Afghanistan', value: 'AF'},
        {label: 'Aland Islands', value: 'AX'},
        {label: 'Albania', value: 'AL'},
        {label: 'Algeria', value: 'DZ'},
        {label: 'American Samoa', value: 'AS'},
        {label: 'Andorra', value: 'AD'},
        {label: 'Angola', value: 'AO'},
        {label: 'Anguilla', value: 'AI'},
        {label: 'Antarctica', value: 'AQ'},
        {label: 'Antigua and Barbuda', value: 'AG'},
        {label: 'Argentina', value: 'AR'},
        {label: 'Armenia', value: 'AM'},
        {label: 'Aruba', value: 'AW'},
        {label: 'Australia', value: 'AU'},
        {label: 'Austria', value: 'AT'},
        {label: 'Azerbaijan', value: 'AZ'},
        {label: 'Bahamas', value: 'BS'},
        {label: 'Bahrain', value: 'BH'},
        {label: 'Bangladesh', value: 'BD'},
        {label: 'Barbados', value: 'BB'},
        {label: 'Belarus', value: 'BY'},
        {label: 'Belgium', value: 'BE'},
        {label: 'Belize', value: 'BZ'},
        {label: 'Benin', value: 'BJ'},
        {label: 'Bermuda', value: 'BM'},
        {label: 'Bhutan', value: 'BT'},
        {label: 'Bolivia', value: 'BO'},
        {label: 'Bosnia and Herzegovina', value: 'BA'},
        {label: 'Botswana', value: 'BW'},
        {label: 'Bouvet Island', value: 'BV'},
        {label: 'Brazil', value: 'BR'},
        {label: 'British Indian Ocean Territory', value: 'IO'},
        {label: 'Brunei Darussalam', value: 'BN'},
        {label: 'Bulgaria', value: 'BG'},
        {label: 'Burkina Faso', value: 'BF'},
        {label: 'Burundi', value: 'BI'},
        {label: 'Cambodia', value: 'KH'},
        {label: 'Cameroon', value: 'CM'},
        {label: 'Canada', value: 'CA'},
        {label: 'Cape Verde', value: 'CV'},
        {label: 'Cayman Islands', value: 'KY'},
        {label: 'Central African Republic', value: 'CF'},
        {label: 'Chad', value: 'TD'},
        {label: 'Chile', value: 'CL'},
        {label: 'China', value: 'CN'},
        {label: 'Christmas Island', value: 'CX'},
        {label: 'Cocos (Keeling) Islands', value: 'CC'},
        {label: 'Colombia', value: 'CO'},
        {label: 'Comoros', value: 'KM'},
        {label: 'Congo', value: 'CG'},
        {label: 'Congo, The Democratic Republic of the', value: 'CD'},
        {label: 'Cook Islands', value: 'CK'},
        {label: 'Costa Rica', value: 'CR'},
        {label: 'Cote D\'Ivoire', value: 'CI'},
        {label: 'Croatia', value: 'HR'},
        {label: 'Cuba', value: 'CU'},
        {label: 'Cyprus', value: 'CY'},
        {label: 'Czech Republic', value: 'CZ'},
        {label: 'Denmark', value: 'DK'},
        {label: 'Djibouti', value: 'DJ'},
        {label: 'Dominica', value: 'DM'},
        {label: 'Dominican Republic', value: 'DO'},
        {label: 'Ecuador', value: 'EC'},
        {label: 'Egypt', value: 'EG'},
        {label: 'El Salvador', value: 'SV'},
        {label: 'Equatorial Guinea', value: 'GQ'},
        {label: 'Eritrea', value: 'ER'},
        {label: 'Estonia', value: 'EE'},
        {label: 'Ethiopia', value: 'ET'},
        {label: 'Falkland Islands (Malvinas)', value: 'FK'},
        {label: 'Faroe Islands', value: 'FO'},
        {label: 'Fiji', value: 'FJ'},
        {label: 'Finland', value: 'FI'},
        {label: 'France', value: 'FR'},
        {label: 'French Guiana', value: 'GF'},
        {label: 'French Polynesia', value: 'PF'},
        {label: 'French Southern Territories', value: 'TF'},
        {label: 'Gabon', value: 'GA'},
        {label: 'Gambia', value: 'GM'},
        {label: 'Georgia', value: 'GE'},
        {label: 'Germany', value: 'DE'},
        {label: 'Ghana', value: 'GH'},
        {label: 'Gibraltar', value: 'GI'},
        {label: 'Greece', value: 'GR'},
        {label: 'Greenland', value: 'GL'},
        {label: 'Grenada', value: 'GD'},
        {label: 'Guadeloupe', value: 'GP'},
        {label: 'Guam', value: 'GU'},
        {label: 'Guatemala', value: 'GT'},
        {label: 'Guernsey', value: 'GG'},
        {label: 'Guinea', value: 'GN'},
        {label: 'Guinea-Bissau', value: 'GW'},
        {label: 'Guyana', value: 'GY'},
        {label: 'Haiti', value: 'HT'},
        {label: 'Heard Island and Mcdonald Islands', value: 'HM'},
        {label: 'Holy See (Vatican City State)', value: 'VA'},
        {label: 'Honduras', value: 'HN'},
        {label: 'Hong Kong', value: 'HK'},
        {label: 'Hungary', value: 'HU'},
        {label: 'Iceland', value: 'IS'},
        {label: 'India', value: 'IN'},
        {label: 'Indonesia', value: 'ID'},
        {label: 'Iran, Islamic Republic Of', value: 'IR'},
        {label: 'Iraq', value: 'IQ'},
        {label: 'Ireland', value: 'IE'},
        {label: 'Isle of Man', value: 'IM'},
        {label: 'Israel', value: 'IL'},
        {label: 'Italy', value: 'IT'},
        {label: 'Jamaica', value: 'JM'},
        {label: 'Japan', value: 'JP'},
        {label: 'Jersey', value: 'JE'},
        {label: 'Jordan', value: 'JO'},
        {label: 'Kazakhstan', value: 'KZ'},
        {label: 'Kenya', value: 'KE'},
        {label: 'Kiribati', value: 'KI'},
        {label: 'Korea, Democratic People\'S Republic of', value: 'KP'},
        {label: 'Korea, Republic of', value: 'KR'},
        {label: 'Kuwait', value: 'KW'},
        {label: 'Kyrgyzstan', value: 'KG'},
        {label: 'Lao People\'S Democratic Republic', value: 'LA'},
        {label: 'Latvia', value: 'LV'},
        {label: 'Lebanon', value: 'LB'},
        {label: 'Lesotho', value: 'LS'},
        {label: 'Liberia', value: 'LR'},
        {label: 'Libyan Arab Jamahiriya', value: 'LY'},
        {label: 'Liechtenstein', value: 'LI'},
        {label: 'Lithuania', value: 'LT'},
        {label: 'Luxembourg', value: 'LU'},
        {label: 'Macao', value: 'MO'},
        {label: 'Macedonia, The Former Yugoslav Republic of', value: 'MK'},
        {label: 'Madagascar', value: 'MG'},
        {label: 'Malawi', value: 'MW'},
        {label: 'Malaysia', value: 'MY'},
        {label: 'Maldives', value: 'MV'},
        {label: 'Mali', value: 'ML'},
        {label: 'Malta', value: 'MT'},
        {label: 'Marshall Islands', value: 'MH'},
        {label: 'Martinique', value: 'MQ'},
        {label: 'Mauritania', value: 'MR'},
        {label: 'Mauritius', value: 'MU'},
        {label: 'Mayotte', value: 'YT'},
        {label: 'Mexico', value: 'MX'},
        {label: 'Micronesia, Federated States of', value: 'FM'},
        {label: 'Moldova, Republic of', value: 'MD'},
        {label: 'Monaco', value: 'MC'},
        {label: 'Mongolia', value: 'MN'},
        {label: 'Montserrat', value: 'MS'},
        {label: 'Morocco', value: 'MA'},
        {label: 'Mozambique', value: 'MZ'},
        {label: 'Myanmar', value: 'MM'},
        {label: 'Namibia', value: 'NA'},
        {label: 'Nauru', value: 'NR'},
        {label: 'Nepal', value: 'NP'},
        {label: 'Netherlands', value: 'NL'},
        {label: 'Netherlands Antilles', value: 'AN'},
        {label: 'New Caledonia', value: 'NC'},
        {label: 'New Zealand', value: 'NZ'},
        {label: 'Nicaragua', value: 'NI'},
        {label: 'Niger', value: 'NE'},
        {label: 'Nigeria', value: 'NG'},
        {label: 'Niue', value: 'NU'},
        {label: 'Norfolk Island', value: 'NF'},
        {label: 'Northern Mariana Islands', value: 'MP'},
        {label: 'Norway', value: 'NO'},
        {label: 'Oman', value: 'OM'},
        {label: 'Pakistan', value: 'PK'},
        {label: 'Palau', value: 'PW'},
        {label: 'Palestinian Territory, Occupied', value: 'PS'},
        {label: 'Panama', value: 'PA'},
        {label: 'Papua New Guinea', value: 'PG'},
        {label: 'Paraguay', value: 'PY'},
        {label: 'Peru', value: 'PE'},
        {label: 'Philippines', value: 'PH'},
        {label: 'Pitcairn', value: 'PN'},
        {label: 'Poland', value: 'PL'},
        {label: 'Portugal', value: 'PT'},
        {label: 'Puerto Rico', value: 'PR'},
        {label: 'Qatar', value: 'QA'},
        {label: 'Reunion', value: 'RE'},
        {label: 'Romania', value: 'RO'},
        {label: 'Russian Federation', value: 'RU'},
        {label: 'RWANDA', value: 'RW'},
        {label: 'Saint Helena', value: 'SH'},
        {label: 'Saint Kitts and Nevis', value: 'KN'},
        {label: 'Saint Lucia', value: 'LC'},
        {label: 'Saint Pierre and Miquelon', value: 'PM'},
        {label: 'Saint Vincent and the Grenadines', value: 'VC'},
        {label: 'Samoa', value: 'WS'},
        {label: 'San Marino', value: 'SM'},
        {label: 'Sao Tome and Principe', value: 'ST'},
        {label: 'Saudi Arabia', value: 'SA'},
        {label: 'Senegal', value: 'SN'},
        {label: 'Serbia and Montenegro', value: 'CS'},
        {label: 'Seychelles', value: 'SC'},
        {label: 'Sierra Leone', value: 'SL'},
        {label: 'Singapore', value: 'SG'},
        {label: 'Slovakia', value: 'SK'},
        {label: 'Slovenia', value: 'SI'},
        {label: 'Solomon Islands', value: 'SB'},
        {label: 'Somalia', value: 'SO'},
        {label: 'South Africa', value: 'ZA'},
        {label: 'South Georgia and the South Sandwich Islands', value: 'GS'},
        {label: 'Spain', value: 'ES'},
        {label: 'Sri Lanka', value: 'LK'},
        {label: 'Sudan', value: 'SD'},
        {label: 'Suriname', value: 'SR'},
        {label: 'Svalbard and Jan Mayen', value: 'SJ'},
        {label: 'Swaziland', value: 'SZ'},
        {label: 'Sweden', value: 'SE'},
        {label: 'Switzerland', value: 'CH'},
        {label: 'Syrian Arab Republic', value: 'SY'},
        {label: 'Taiwan, Province of China', value: 'TW'},
        {label: 'Tajikistan', value: 'TJ'},
        {label: 'Tanzania, United Republic of', value: 'TZ'},
        {label: 'Thailand', value: 'TH'},
        {label: 'Timor-Leste', value: 'TL'},
        {label: 'Togo', value: 'TG'},
        {label: 'Tokelau', value: 'TK'},
        {label: 'Tonga', value: 'TO'},
        {label: 'Trinidad and Tobago', value: 'TT'},
        {label: 'Tunisia', value: 'TN'},
        {label: 'Turkey', value: 'TR'},
        {label: 'Turkmenistan', value: 'TM'},
        {label: 'Turks and Caicos Islands', value: 'TC'},
        {label: 'Tuvalu', value: 'TV'},
        {label: 'Uganda', value: 'UG'},
        {label: 'Ukraine', value: 'UA'},
        {label: 'United Arab Emirates', value: 'AE'},
        {label: 'English Folk',value:'GB,UK'},
        {label: 'Great Britain', value: 'GB'},
        {label: 'United Kingdom', value: 'UK'},
        {label: 'United States', value: 'US'},
        {label: 'United States Minor Outlying Islands', value: 'UM'},
        {label: 'Uruguay', value: 'UY'},
        {label: 'Uzbekistan', value: 'UZ'},
        {label: 'Vanuatu', value: 'VU'},
        {label: 'Venezuela', value: 'VE'},
        {label: 'Viet Nam', value: 'VN'},
        {label: 'Virgin Islands, British', value: 'VG'},
        {label: 'Virgin Islands, U.S.', value: 'VI'},
        {label: 'Wallis and Futuna', value: 'WF'},
        {label: 'Western Sahara', value: 'EH'},
        {label: 'Yemen', value: 'YE'},
        {label: 'Zambia', value: 'ZM'},
        {label: 'Zimbabwe', value: 'ZW'}
    ];

    var countryTagsEdit = $( "#offer-edit-countries" ).tagit({
        availableTags     : countryCodes,
        tagSource         : countryList,
        removeConfirmation: false,
        caseSensitive     : false,
        maxTags           : 30,//maximum tags allowed default almost unlimited
        allowSpaces: true,
        animate: true,
        singleField: true,
        singleFieldDelimiter: ',',
        afterTagAdded:function(event,ui) {
            console.log('Added country: '+ ui.tagTabel);
        },
        afterTagRemoved:function(event,ui) {
        },
        placeholderText: 'Type Country',
        preprocessTag:function(val) {
            if (!val) { return ''; }
            if(val.indexOf(',') > 0) {
                var a = val.split(',');
                for (index = 0; index < a.length; ++index) {
                    countryTagsEdit.tagit('createTag',a[index]);
                }
                return '';
            }
            if(jQuery.inArray(val,countryCodes) > 0) {return val[0].toUpperCase() + val.slice(1, val.length);
            } else { return '';}

        }
    });

    var countryTags = $( "#offer-countries" ).tagit({
        //itemName          : 'item',
        //fieldName         : 'tags',
        availableTags     : countryCodes,
        tagSource         : countryList,
        removeConfirmation: false,
        caseSensitive     : false,
        maxTags           : 30,//maximum tags allowed default almost unlimited
        //onlyAvailableTags : true,//boolean, allows tags that are in availableTags or not
        allowSpaces: true,
        animate: true,
        singleField: true,
        singleFieldDelimiter: ',',
        afterTagAdded:function(event,ui) {
            console.log('Added country: '+ ui.tagTabel);
        },
        afterTagRemoved:function(event,ui) {
            //alert(ui.item.value);

        },
        placeholderText: 'Type Country',
        preprocessTag:function(val) {
        if (!val) { return ''; }
        if(val.indexOf(',') > 0) {
            var a = val.split(',');
            for (index = 0; index < a.length; ++index) {
                countryTags.tagit('createTag',a[index]);
            }
            return '';
        }
        if(jQuery.inArray(val,countryCodes) > 0) {        return val[0].toUpperCase() + val.slice(1, val.length);
        } else { return '';}

    }
    });

    var countryFilterTags = $( "#offer-filter-countries" ).tagit({
        //itemName          : 'item',
        //fieldName         : 'tags',
        availableTags     : countryCodes,
        tagSource         : countryList,
        removeConfirmation: false,
        caseSensitive     : false,
        maxTags           : 30,//maximum tags allowed default almost unlimited
        //onlyAvailableTags : true,//boolean, allows tags that are in availableTags or not
        allowSpaces: true,
        animate: true,
        singleField: true,
        singleFieldDelimiter: ',',
        afterTagAdded:function(event,ui) {
            console.log('Added country: '+ ui.tagTabel);
        },
        afterTagRemoved:function(event,ui) {
            //alert(ui.item.value);

        },
        placeholderText: 'Type Country',
        preprocessTag:function(val) {
            if (!val) { return ''; }
            if(val.indexOf(',') > 0) {
                var a = val.split(',');
                for (index = 0; index < a.length; ++index) {
                    countryFilterTags.tagit('createTag',a[index]);
                }
                return '';
            }
            if(jQuery.inArray(val,countryCodes) > 0) {        return val[0].toUpperCase() + val.slice(1, val.length);
            } else { return '';}

        }
    });




    $('html').on('click','a.network-offer',function(e){
        e.preventDefault();
        var offerID = $(this).attr('data-offer-id');
        var offerName = $(this).attr('data-offer-name');
        var offerDescription = $(this).attr('data-offer-description');
        var offerIcon =  encodeURI(($(this).attr('data-offer-icon').length > 1)?$(this).attr('data-offer-icon'):'/view/img/image-no-icon.png');
        var offerCountries = $(this).attr('data-offer-countries');//$('#offer-countries').val();
        var offerNetworkPayout = $(this).attr('data-payout');
        var offerNetwork = $(this).attr('data-offer-network');
        var offerUserPayout = $(this).attr('data-offer-user-payout');
        var offerReferralPayout = $(this).attr('data-offer-referral-payout');
        var offerDestination = $(this).attr('data-offer-destination');

        //console.log(offerNetwork);

        $('#offer-info').attr('data-offer-user-payout',offerUserPayout);
        $('#offer-info').attr('data-offer-referral-payout',offerReferralPayout);
        $('#offer-info').attr('data-offer-network',offerNetwork);
        $('#offer-info').attr('data-offer-description',offerDescription);
        $('#offer-info').attr('data-offer-icon',offerIcon);
        $('#offer-info').attr('data-offer-id',offerID);
        $('#offer-info').attr('data-offer-name',offerName);
        $('#offer-info').attr('data-offer-countries',offerCountries);
        $('#offer-info').attr('data-payout',offerNetworkPayout);
        $('#offer-info').attr('data-offer-destination',offerDestination);


        /**
         *      <li><a href="#" class="offer-platform-none"><span class="glyphicon glyphicon-ok checkarea"></span> None</a></li>
         <li class="divider"></li>
         <li><a href="#" class="offer-platform-android"><span class="glyphicon checkarea"></span>Android</a></li>
         <li><a href="#" class="offer-platform-ios"><span class="glyphicon checkarea"></span>All iOS</a></li>
         <li><a href="#" class="offer-platform-iphone"><span class="glyphicon checkarea"></span>iPhone</a></li>
         <li><a href="#" class="offer-platform-ipad"><span class="glyphicon checkarea"></span>iPad</a></li>
         <li><a href="#" class="offer-platform-ipod"><span class="glyphicon checkarea"></span>iPod</a></li>
         */
        $('#offer-info').find('ul.offer-platform .glyphicon').removeClass('glyphicon-ok');

        if($(this).hasClass('ios')) {
            $('#offer-info').find('.offer-platform-ios span.glyphicon').addClass('glyphicon-ok');
        } else
        if($(this).hasClass('android')) {
            $('#offer-info').find('.offer-platform-android span.glyphicon').addClass('glyphicon-ok');
        } else {
            $('#offer-info').find('.offer-platform-none span.glyphicon').addClass('glyphicon-ok');

        }
        //if(offerName.indexOf('iphone') > -1)


        $('#offer-destination').val(offerDestination);

        $('#offer-info').find('.offer-network img').attr('src','/view/img/network_' + offerNetwork + '.png');

        $('#offer-info').find('.offer-user-payout').text(offerUserPayout);
        $('#offer-info').find('.offer-referral-payout').text(offerReferralPayout);

        var countries = offerCountries.split(',');
        var index,countriesHTML = '',currentTags;
        currentTags  = countryTags.tagit("assignedTags");
        if(currentTags.length > 0) countryTags.tagit('removeAll');
        for(index = 0; index < countries.length; ++index)
        {
            countriesHTML = countriesHTML + '<span class="flag flag-' + countries[index].toLowerCase() + '"></span>';
            countryTags.tagit('createTag',countries[index]);
        }
        $('#offer-info').find('.flag-container').html(countriesHTML);

        $('#offer-info').find('.icon').css({
            'background-image':'url(/view/img/offer-icon-overlay-white.png),url(' + offerIcon +')'
        });
        $('#offer-info').find('.offer-name').text(offerName);
        $('#offer-info').find('.offer-description').text(offerDescription);
        $('#offer-info').find('.offer-network-payout').text('$' + offerNetworkPayout);

        $('#offer-description').hide('fast');
        $('#offer-description-preview').show('fast');
    });

    $('#offer-description-preview').click(function(e){
        $('#offer-description').show('fast');
        $('#offer-description-preview').hide('fast');
    });



    $('#add-offer').click(function(e){
        e.preventDefault();

        var offerType,offerPlatform,offerCountries = $('#offer-countries').val();

        /*
         define('OFFERS_APPS',1);
         define('OFFERS_VIDEO',2);

         define('PLATFORM_IOS',1);
         define('PLATFORM_ANDROID',2);
         define('PLATFORM_IPHONE',11);
         define('PLATFORM_IPAD',12);
         define('PLATFORM_IPOD',13);
         */
         if($('#offer-info').find('.offer-platform-android span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 2;
        } else if($('#offer-info').find('.offer-platform-ios span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 1;
        } else if($('#offer-info').find('.offer-platform-iphone span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 11;
        } else if($('#offer-info').find('.offer-platform-ipad span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 12;
        } else if($('#offer-info').find('.offer-platform-ipod span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 13;
        } else {
             offerPlatform = 0;
        }


        if($('#offer-info').find('.offer-type-mobile span.glyphicon').hasClass('glyphicon-ok')) {
            offerType = 1;
        } else  if($('#offer-info').find('.offer-type-video span.glyphicon').hasClass('glyphicon-ok')) {
            offerType = 2;
        } else {
            offerType = 0;
        }

        addOffer({
            'offerUserPayout':$('#offer-info').attr('data-offer-user-payout'),
            'offerReferralPayout':$('#offer-info').attr('data-offer-referral-payout'),
            'offerDescription':$('textarea#offer-description').val(),
            'offerDestination':$('#offer-info').attr('data-offer-destination'),
            'offerIcon':$('#offer-info').attr('data-offer-icon'),
            'offerID':$('#offer-info').attr('data-offer-id'),
            'offerNetworkID':$('#offer-info').attr('data-offer-network-id'),
            'offerNetworkSource':$('#offer-info').attr('data-offer-network'),
            'offerName':$('#offer-info').attr('data-offer-name'),
            'offerCountries':offerCountries,
            'offerNetworkPayout':$('#offer-info').attr('data-payout'),
            'offerPlatform':offerPlatform,
            'offerType':offerType
        });
    });

    $('#offer-info span.offer-icon').click(function(){
        var offerIcon = $('#offer-info').attr('data-offer-icon');
        modal({
        modalType:1, //error
        modalTitle:'New icon URL',
        modalMessage:
            '<input type="text" id="input-offer-icon" class="form-control" value="' + offerIcon +'">',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){

                    var offerIcon = $('#input-offer-icon').val();
                    var currentIcon = $('#offer-info').attr('data-offer-icon');
                    console.log('attempting to replace: ' + currentIcon + ' with ' + offerIcon);
                    if(offerIcon.length < 1 || offerIcon == currentIcon)
                    {
                        $.magnificPopup.close();
                        return;
                    }

                    $('#offer-info').find('span.offer-icon').css({
                        'background-image':'url(/view/img/offer-icon-overlay-white.png),url(' + offerIcon +')'
                    });
                    $('#offer-info').attr('data-offer-icon',offerIcon);
                    $.magnificPopup.close();
                    return;
                }}
            }
        });
    });

    $('#offer-info span.offer-network-payout').click(function(){
        var offerNetworkPayout = $('#offer-info').attr('data-payout');
        modal({
            modalType:1, //error
            modalTitle:'Change Offer Payout',
            modalMessage:
                'This fucks with book-keeping. It is usually not a good idea to modify this.<br/><br/>Ad Network Payout: <div class="input-group input-group-lg"><span class="input-group-addon">$</span> <input type="text" id="input-offer-network-payout" class="form-control" value="' + offerNetworkPayout +'"></div>',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var offerNetworkPayout = parseFloat($('#input-offer-network-payout').val()).toFixed(2);
                    var currentOfferNetworkPayout = parseFloat($('#offer-info').attr('data-payout')).toFixed(2);
                    console.log(typeof offerNetworkPayout);
                    if(offerNetworkPayout < 0.01 || offerNetworkPayout == currentOfferNetworkPayout)
                    {
                        $.magnificPopup.close();
                        return;
                    }

                    $('#offer-info').find('span.offer-network-payout').text('$' + offerNetworkPayout);

                    $('#offer-info').attr('data-payout',offerNetworkPayout);
                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });

    $('#offer-info span.offer-name').click(function(){
        var offerName = $('#offer-info').attr('data-offer-name');
        modal({
            modalType:1, //error
            modalTitle:'Change Offer Payout',
            modalMessage:
                'Display name:<br/><input type="text" id="input-offer-name" class="form-control" value="' + offerName +'">',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var offerName = $('#input-offer-name').val().trim();
                    var currentOfferName = $('#offer-info').attr('data-offer-name');
                    if(offerName.length < 1 || offerName == currentOfferName)
                    {
                        $.magnificPopup.close();
                        return;
                    }
                    $('#offer-info').find('span.offer-name').text(offerName);
                    $('#offer-info').attr('data-offer-name',offerName);
                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });

    $('#offer-info div.offer-payout').click(function(){
        var offerUserPayout = $('#offer-info').attr('data-offer-user-payout');
        var offerReferralPayout = $('#offer-info').attr('data-offer-referral-payout');

        modal({
            modalType:1, //error
            modalTitle:'Change User Payouts',
            modalMessage:
                'User payout: <div class="input-group input-group-lg"><span class="input-group-addon"><span class="icon-coin"></span></span> <input type="text" id="input-offer-user-payout" class="form-control" value="' + offerUserPayout +'"></div><br/>Referral payout:<div class="input-group input-group-lg"><span class="input-group-addon"><span class="icon-coin"></span></span> <input type="text" id="input-offer-referral-payout" class="form-control" value="' + offerReferralPayout +'"></div>',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var offerUserPayout = parseInt($('#input-offer-user-payout').val());
                    var offerReferralPayout = parseInt($('#input-offer-referral-payout').val());

                    var currentUserPayout = parseInt($('#offer-info').attr('data-user-payout'));
                    var currentReferralPayout = parseInt($('#offer-info').attr('data-referral-payout'));

                    if(offerUserPayout < 1 || typeof offerUserPayout !== 'number' || typeof offerReferralPayout !== 'number')
                    {
                        $.magnificPopup.close();
                        return;
                    }

                    $('#offer-info').find('span.offer-user-payout').text(offerUserPayout);
                    $('#offer-info').find('span.offer-referral-payout').text(offerReferralPayout);

                    $('#offer-info').attr('data-offer-user-payout',offerUserPayout);
                    $('#offer-info').attr('data-offer-referral-payout',offerReferralPayout);

                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });
    var $container;

    var _page = window.location.hash.substring(1,window.location.hash.length);
    console.log(_page);
    if(typeof _page == 'string' && _page !== 'undefined' && _page !== '') { loadTab(_page) } else {loadTab('offer-add');}
    $(window).on('hashchange',function (e) {
        _page = window.location.hash.substring(1,window.location.hash.length);
        loadTab(_page);
    });

    $('#offer-list td.offer-geo').each(function(){
        var countries = $(this).parent().attr('data-offer-countries').split(',');
        if(countries.length > 0)
        {
            var index;
            for(index = 0; index < countries.length; ++index)
            {
                if(countries[index] == '' || typeof countries[index] == 'undefined') continue;
                $(this).append('<span class="flag flag-' + countries[index].toLowerCase().trim() +'"></span>');
            }
        }
    });



    $('.offer-edit-row').click(function(e){
        e.preventDefault();
        var offerCountries = $(this).attr('data-offer-countries'),
            offerName = $(this).attr('data-offer-name'),
            offerDescription = $(this).attr('data-offer-description'),
            //offerNetworkSource = $(this).attr('data-offer-source'),
            offerStatus = $(this).attr('data-offer-status'),
            offerID = $(this).attr('data-offer-id'),
            offerNetworkID = $(this).attr('data-offer-network-id'),
            offerType = $(this).attr('data-offer-type'),
            offerPlatform = $(this).attr('data-offer-platform'),
            offerNetworkPayout = $(this).attr('data-offer-network-payout'),
            offerIcon = $(this).attr('data-offer-icon'),
            offerUserPayout = $(this).attr('data-offer-user-payout'),
            offerReferralPayout = $(this).attr('data-offer-referral-payout');

        if(offerStatus == 0){

                modal({
                    modalType:3, //error
                    modalTitle:'Offer disabled',
                    modalMessage:'This offer has been disabled, would you like to unpause it?',
                    modalButtons:{
                        1:{text:'Start offer',class:'btn-success',callback:function(){


                            modal({
                                modalType:1, //error
                                modalTitle:'Stop Campaign',
                                modalMessage:
                                    'Are you sure you want to start campaign #' + offerID + ' "<strong>' + offerName +'</strong>"?',
                                modalButtons:{
                                    0:{text:'Cancel'},
                                    1:{text:'Yes, start it',class:'btn-success',callback:function(){
                                        startOffer(offerID);
                                        return;
                                    }}
                                }

                            });
                        }},
                        0:{text:'Cancel'}
                    }
                });
                return;

        }

        $('#offer-edit').attr('data-offer-user-payout',offerUserPayout);
        $('#offer-edit').attr('data-offer-referral-payout',offerReferralPayout);
        $('#offer-edit').attr('data-offer-type',offerType);
        $('#offer-edit').attr('data-offer-platform',offerPlatform);
        $('#offer-edit').attr('data-offer-description',offerDescription);
        $('#offer-edit').attr('data-offer-icon',offerIcon);
        $('#offer-edit').attr('data-offer-id',offerID);
        $('#offer-edit').attr('data-offer-name',offerName);
        $('#offer-edit').attr('data-offer-countries',offerCountries);
        $('#offer-edit').attr('data-payout',offerNetworkPayout);
        $('#offer-edit').attr('data-offer-network-id',offerNetworkID);
        //$('#offer-edit').attr('data-offer-destination',offerNetworkPayout);



        /*         define('OFFERS_APPS',1);
         define('OFFERS_VIDEO',2);

         define('PLATFORM_IOS',1);
         define('PLATFORM_ANDROID',2);
         define('PLATFORM_IPHONE',11);
         define('PLATFORM_IPAD',12);
         define('PLATFORM_IPOD',13);
         */
        $('#offer-edit').find('ul.offer-platform .glyphicon').removeClass('glyphicon-ok');
        console.log('offerPlatform: ' + offerPlatform + ' offerType:');
        switch(parseInt(offerPlatform))
        {
            case 1:
                $('#offer-edit').find('.offer-platform-ios span.glyphicon').addClass('glyphicon-ok');
                break;
            case 2:
                $('#offer-edit').find('.offer-platform-android span.glyphicon').addClass('glyphicon-ok');
                break;

            case 11:
                $('#offer-edit').find('.offer-platform-iphone span.glyphicon').addClass('glyphicon-ok');
                break;
            case 12:
                $('#offer-edit').find('.offer-platform-ipad span.glyphicon').addClass('glyphicon-ok');
                break;
            case 13:
                $('#offer-edit').find('.offer-platform-ipod span.glyphicon').addClass('glyphicon-ok');
                break;
        }

        switch(parseInt(offerType))
        {
            case 1:
                $('#offer-edit').find('.offer-type-mobile span.glyphicon').addClass('glyphicon-ok');
                break;
            case 2:
                $('#offer-edit').find('.offer-type-video span.glyphicon').addClass('glyphicon-ok');
                break;
        }

        //$('#offer-edit').find('.offer-network img').attr('src','/view/img/network_' + offerNetwork + '.png');

        $('#offer-edit').find('.offer-user-payout').text(offerUserPayout);
        $('#offer-edit').find('.offer-referral-payout').text(offerReferralPayout);

        var countries = offerCountries.split(',');
        var index,countriesHTML = '',currentTags;
        currentTags  = countryTagsEdit.tagit("assignedTags");
        if(currentTags.length > 0) countryTagsEdit.tagit('removeAll');
        for(index = 0; index < countries.length; ++index)
        {
            countriesHTML = countriesHTML + '<span class="flag flag-' + countries[index].toLowerCase() + '"></span>';
            countryTagsEdit.tagit('createTag',countries[index]);
        }
        $('#offer-edit').find('.flag-container').html(countriesHTML);

        $('#offer-edit').find('.offer-icon').css({
            'background-image':'url(/view/img/offer-icon-overlay-white.png),url(' + offerIcon +')'
        });
        $('#offer-edit').find('.offer-name').text(offerName);
        $('#offer-edit').find('.offer-description').text(offerDescription);
        $('#offer-edit').find('.offer-network-payout').text('$' + offerNetworkPayout);

        $('#offer-edit-description').hide('fast');
        $('#offer-edit-description-preview').show('fast');
    });

    $('#offer-edit div.offer-payout').click(function(){
        var offerUserPayout = $('#offer-edit').attr('data-offer-user-payout');
        var offerReferralPayout = $('#offer-edit').attr('data-offer-referral-payout');

        modal({
            modalType:1, //error
            modalTitle:'Change User Payouts',
            modalMessage:
                'User payout: <div class="input-group input-group-lg"><span class="input-group-addon"><span class="icon-coin"></span></span> <input type="text" id="input-offer-user-payout" class="form-control" value="' + offerUserPayout +'"></div><br/>Referral payout:<div class="input-group input-group-lg"><span class="input-group-addon"><span class="icon-coin"></span></span> <input type="text" id="input-offer-referral-payout" class="form-control" value="' + offerReferralPayout +'"></div>',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var offerUserPayout = parseInt($('#input-offer-user-payout').val());
                    var offerReferralPayout = parseInt($('#input-offer-referral-payout').val());

                    var currentUserPayout = parseInt($('#offer-edit').attr('data-user-payout'));
                    var currentReferralPayout = parseInt($('#offer-edit').attr('data-referral-payout'));

                    if(offerUserPayout < 1 || typeof offerUserPayout !== 'number' || typeof offerReferralPayout !== 'number')
                    {
                        $.magnificPopup.close();
                        return;
                    }

                    $('#offer-edit').find('span.offer-user-payout').text(offerUserPayout);
                    $('#offer-edit').find('span.offer-referral-payout').text(offerReferralPayout);

                    $('#offer-edit').attr('data-offer-user-payout',offerUserPayout);
                    $('#offer-edit').attr('data-offer-referral-payout',offerReferralPayout);

                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });

    $('#offer-edit-description-preview').click(function(e){
        $('#offer-edit-description').show('fast');
        $('#offer-edit-description-preview').hide('fast');
    });

    $('#save-offer').click(function(e){

        e.preventDefault();

        var offerType,offerPlatform,offerCountries = $('#offer-edit-countries').val();

        /*
         define('OFFERS_APPS',1);
         define('OFFERS_VIDEO',2);

         define('PLATFORM_IOS',1);
         define('PLATFORM_ANDROID',2);
         define('PLATFORM_IPHONE',11);
         define('PLATFORM_IPAD',12);
         define('PLATFORM_IPOD',13);
         */
        if($('#offer-edit').find('.offer-platform-android span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 2;
        } else if($('#offer-edit').find('.offer-platform-ios span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 1;
        } else if($('#offer-edit').find('.offer-platform-iphone span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 11;
        } else if($('#offer-edit').find('.offer-platform-ipad span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 12;
        } else if($('#offer-edit').find('.offer-platform-ipod span.glyphicon').hasClass('glyphicon-ok')) {
            offerPlatform = 13;
        } else {
            offerPlatform = 0;
        }


        if($('#offer-edit').find('.offer-type-mobile span.glyphicon').hasClass('glyphicon-ok')) {
            offerType = 1;
        } else  if($('#offer-edit').find('.offer-type-video span.glyphicon').hasClass('glyphicon-ok')) {
            offerType = 2;
        } else {
            offerType = 0;
        }




        updateOffer({
            'offerUserPayout':$('#offer-edit').attr('data-offer-user-payout'),
            'offerReferralPayout':$('#offer-edit').attr('data-offer-referral-payout'),
            'offerDescription':$('textarea#offer-edit-description').val(),
            'offerIcon':$('#offer-edit').attr('data-offer-icon'),
            'offerID':$('#offer-edit').attr('data-offer-id'),
            'offerName':$('#offer-edit').attr('data-offer-name'),
            'offerCountries':offerCountries,
            'offerNetworkPayout':$('#offer-edit').attr('data-payout'),
            'offerPlatform':offerPlatform,
            'offerType':offerType
        });
    });

    $('#offer-edit span.offer-icon').click(function(){
        var offerIcon = $('#offer-edit').attr('data-offer-icon');
        modal({
            modalType:1, //error
            modalTitle:'New icon URL',
            modalMessage:
                '<input type="text" id="input-offer-icon" class="form-control" value="' + offerIcon +'">',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){

                    var offerIcon = $('#input-offer-icon').val();
                    var currentIcon = $('#offer-edit').attr('data-offer-icon');
                    console.log('attempting to replace: ' + currentIcon + ' with ' + offerIcon);
                    if(offerIcon.length < 1 || offerIcon == currentIcon)
                    {
                        $.magnificPopup.close();
                        return;
                    }

                    $('#offer-edit').find('span.offer-icon').css({
                        'background-image':'url(/view/img/offer-icon-overlay-white.png),url(' + offerIcon +')'
                    });
                    $('#offer-edit').attr('data-offer-icon',offerIcon);
                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });

    $('#offer-edit span.offer-network-payout').click(function(){
        var offerNetworkPayout = $('#offer-edit').attr('data-payout');
        modal({
            modalType:1, //error
            modalTitle:'Change Offer Payout',
            modalMessage:
                'This fucks with book-keeping. It is usually not a good idea to modify this.<br/><br/>Ad Network Payout: <div class="input-group input-group-lg"><span class="input-group-addon">$</span> <input type="text" id="input-offer-network-payout" class="form-control" value="' + offerNetworkPayout +'"></div>',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var offerNetworkPayout = parseFloat($('#input-offer-network-payout').val()).toFixed(2);
                    var currentOfferNetworkPayout = parseFloat($('#offer-edit').attr('data-payout')).toFixed(2);
                    console.log(typeof offerNetworkPayout);
                    if(offerNetworkPayout < 0.01 || offerNetworkPayout == currentOfferNetworkPayout)
                    {
                        $.magnificPopup.close();
                        return;
                    }

                    $('#offer-edit').find('span.offer-network-payout').text('$' + offerNetworkPayout);

                    $('#offer-edit').attr('data-payout',offerNetworkPayout);
                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });

    $('#offer-edit span.offer-name').click(function(){
        var offerName = $('#offer-edit').attr('data-offer-name');
        modal({
            modalType:1, //error
            modalTitle:'Change Offer Payout',
            modalMessage:
                'Display name:<br/><input type="text" id="input-offer-name" class="form-control" value="' + offerName +'">',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Save',class:'btn-success',callback:function(){
                    var offerName = $('#input-offer-name').val().trim();
                    var currentOfferName = $('#offer-edit').attr('data-offer-name');
                    if(offerName.length < 1 || offerName == currentOfferName)
                    {
                        $.magnificPopup.close();
                        return;
                    }
                    $('#offer-edit').find('span.offer-name').text(offerName);
                    $('#offer-edit').attr('data-offer-name',offerName);
                    $.magnificPopup.close();
                    return;
                }}
            }

        });
    });

    $('#stop-offer').click(function(e){
        e.preventDefault();
        var offerName = $('#offer-edit').attr('data-offer-name');
        var offerID = $('#offer-edit').attr('data-offer-id');

        modal({
            modalType:1, //error
            modalTitle:'Stop Campaign',
            modalMessage:
                'Are you sure you want to stop campaign #' + offerID + ' "<strong>' + offerName +'</strong>"?',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Yes, stop it',class:'btn-danger',callback:function(){
                    stopOffer(offerID);
                    return;
                }}
            }

        });
    });

    $('#delete-offer').click(function(e){
        e.preventDefault();
        var offerName = $('#offer-edit').attr('data-offer-name');
        var offerID = $('#offer-edit').attr('data-offer-id');

        modal({
            modalType:1, //error
            modalTitle:'Delete Offer',
            modalMessage:
                'Are you sure you want to delete off #' + offerID + ' "<strong>' + offerName +'</strong>"?',
            modalButtons:{
                0:{text:'Cancel'},
                1:{text:'Yes, delete it',class:'btn-danger',callback:function(){
                    deleteOffer(offerID);
                    return;
                }}
            }

        });
    });


    /** ======================================
     *  OFFER FILTER FUNCTIONS
     *  ======================================
     */

    $('#save-filter').click(function(){
        saveFilter({
                offerFilterAction: parseInt($('div#offer-add-filter .offer-filter-action').attr('data')),
            offerFilterCondition  : parseInt($('div#offer-add-filter .offer-filter-condition').attr('data')),
            offerFilterNetwork : parseInt($('div#offer-add-filter .offer-filter-network').attr('data')),
            offerFilterPlatform : parseInt($('div#offer-add-filter .offer-filter-platform').attr('data')),
            offerFilterTarget : parseInt($('div#offer-add-filter .offer-filter-target').attr('data')),
            offerFilterText : $('div#offer-add-filter input.offer-filter-text').val().toLowerCase(),
            offerFilterName: $('div#offer-add-filter input.offer-filter-name').val(),
            offerFilterCountries:$('div#offer-add-filter input#offer-filter-countries').val(),
            offerFilterID:$('div#offer-add-filter').attr('data-filter-id')
        });
    });

    $('#offer-add-filter input.offer-filter-name').keyup(function(e){
        var _data = $(this).val();
        if (_data.length > 0) {
            $('#offer-add-filter span.offer-filter-name-display').text(_data);
            $(this).attr('data',_data);
        } else {
            $('#offer-add-filter span.offer-filter-name-display').text('Filter Name');
        }

        console.log(_data);
    });

    $('#delete-filter').click(function(){
        filterID = parseInt($('div#offer-add-filter').attr('data-filter-id'));
        if(filterID > 0)
        {
            modal({
                modalType:1, //error
                modalTitle:'Confirm',
                modalMessage:'Are you sure you want to delete filter ID: ' + filterID + '?',
                modalButtons:{
                    0:{text:'Cancel'},
                    1:{text:'Delete',callback:function(){
                        saveFilter({
                            offerFilterStatus:5,
                            offerFilterID:filterID
                        });
                        //location.reload();
                    }}}
            });
        } else {
            modal({
                modalType:3,
                modalTitle:'Oops',
                modalMessage:'You must first select a filter to delete it.'

            });
        }



    });



    $('#clear-filter').click(function(){
        $('div#offer-add-filter').find('.dropdown-menu li a span').removeClass('glyphicon-ok');
        $('div#offer-add-filter').find('.dropdown-menu').attr('data','');
        $('div#offer-add-filter .offer-filter-name').val('');
        $('div#offer-add-filter .offer-filter-text').val('');
        $('span.offer-filter-name-display').text('Create New Filter');

        $('div#offer-add-filter').attr('data-filter-id',0);
        countryFilterTags.tagit('removeAll');

    });

    $('html').on('click','td.filter-edit-row',function(e){
        e.preventDefault();
        var filterState = $(this).parent().find('td.filter-enabled input').data('bootstrap-switch').state();

        if(!filterState)
        {

            modal({
            modalType:3, //error
            modalTitle:'Filter Error',
            modalMessage:'You must enabled the filter before you can modify the settings.',
            modalButtons:{
                0:{text:'OK'}}
            });

            return;
        }


        //clear all check marks
        $('div#offer-add-filter').find('.dropdown-menu li a span').removeClass('glyphicon-ok');

        var filterAction = $(this).parent().attr('data-filter-action'); $('div#offer-add-filter .offer-filter-action').parent().attr('data',filterAction);
        var filterCondition = $(this).parent().attr('data-filter-condition'); $('div#offer-add-filter .offer-filter-condition').parent().attr('data',filterCondition);
        var filterNetwork = $(this).parent().attr('data-filter-network'); $('div#offer-add-filter .offer-filter-network').parent().attr('data',filterNetwork);
        var filterPlatform = $(this).parent().attr('data-filter-platform'); $('div#offer-add-filter .offer-filter-platform').parent().attr('data',filterPlatform);
        var filterTarget = $(this).parent().attr('data-filter-target');$('div#offer-add-filter .offer-filter-target').parent().attr('data',filterTarget);
        var filterName = $(this).parent().attr('data-filter-name');
        var filterText = $(this).parent().attr('data-filter-text');
        var filterCountries = $(this).parent().attr('data-filter-countries');
        var filterID = $(this).parent().attr('data-filter-id');
        console.log('This filter ID is: ' + filterID);
        $('div#offer-add-filter').attr('data-filter-id',filterID);
        $('span.offer-filter-name-display').text(filterName);

        $('div#offer-add-filter .offer-filter-action li a[data='+ filterAction +'] span').addClass('glyphicon-ok');
        $('div#offer-add-filter .offer-filter-condition li a[data='+ filterCondition +'] span').addClass('glyphicon-ok');
        $('div#offer-add-filter .offer-filter-network li a[data='+ filterNetwork +'] span').addClass('glyphicon-ok');
        $('div#offer-add-filter .offer-filter-platform li a[data='+ filterPlatform +'] span').addClass('glyphicon-ok');
        $('div#offer-add-filter .offer-filter-target li a[data='+ filterTarget +'] span').addClass('glyphicon-ok');

        $('div#offer-add-filter .offer-filter-name').val(filterName);
        $('div#offer-add-filter .offer-filter-text').val(filterText);


        var countries = filterCountries.split(',');
        var index,currentTags;
        //currentTags  = countryFilterTags.tagit("assignedTags");
        countryFilterTags.tagit('removeAll');
        if(filterCountries !== '' || filterCountries !== 'INT')
        {
            for(index = 0; index < countries.length; ++index)
            {
                countryFilterTags.tagit('createTag',countries[index]);
            }
        }
        scrollTo(0,0);



        /*

            offerFilterCondition  : parseInt($('div#offer-add-filter .offer-filter-condition').attr('data')),
            offerFilterNetwork : parseInt($('div#offer-add-filter .offer-filter-network').attr('data')),
            offerFilterPlatform : parseInt($('div#offer-add-filter .offer-filter-platform').attr('data')),
            offerFilterTarget : parseInt($('div#offer-add-filter .offer-filter-target').attr('data')),


            offerFilterText : $('div#offer-add-filter input.offer-filter-text').val().toLowerCase(),
            offerFilterName: $('div#offer-add-filter input.offer-filter-name').attr('data'),
            offerFilterCountries:$('div#offer-add-filter input#offer-filter-countries').val()

*/
    });

    $('html').on('switchChange.bootstrapSwitch','.filter-enabled',function(e,state)
    {
        e.preventDefault();


        var filterID = $(this).parent().attr('data-filter-id');

        if(state)
        {
            $(this).parent().find('td.content').removeClass('filter-disabled');
            saveFilter({
                offerFilterStatus:1,
                offerFilterID:filterID
            });
        } else {
            $(this).parent().find('td.content').addClass('filter-disabled');
            saveFilter({
                offerFilterStatus:0,
                offerFilterID:filterID
            });
        }

    });



    $('#get-appstore-data').click(function(e){
        e.preventDefault();
        $("#offer-info").addClass('content-loading');
       getAppStoreData({url:$('#offer-destination').val()},function(o){

           $("#offer-info").removeClass('content-loading');
            if(typeof o == 'object')
            {
                $('#offer-info').attr('data-offer-description', o.offerDescription);
                $('#offer-info').attr('data-offer-icon', o.offerIcon);
                $('#offer-info').attr('data-offer-name', o.offerName);

                $('#offer-info').find('.icon').css({
                    'background-image':'url(/view/img/offer-icon-overlay-white.png),url(' + o.offerIcon +')'
                });
                $('#offer-info').find('.offer-name').text(o.offerName);
                $('#offer-info').find('.offer-description').text(o.offerDescription);

            } else {
                modal({
                    modalType:3, //error
                    modalTitle:'Error fetching data',
                    modalMessage:'There was an error fetching the iTunes meta data for this offer. Please make sure this is an iTunes URL',
                    modalButtons:{
                        0:{text:'OK'}}
                });
            }


        });


    });


});


