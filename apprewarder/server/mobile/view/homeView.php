<div id="banner-container"></div>
<div data-alert class="alert-box text-center">
    AppRewarder is still in <strong>BETA</strong>. <a href="#beta">Learn more</a>
</div>
<!-- TAB SLIDER NAV ELEMENTS -->
<div class="text-center full-width bg-light ar-home">
    <span class="rsTmb tab-container">
        <a href="#" id="tab-1" class="text-center tab tab-highlight">
            <h3>
                <span class='icon-mobile tab-icon'></span>
                <span class="tab-text">Apps</span>
            </h3>
        </a>
    </span>

    <span class="rsTmb tab-container">
        <a href="#" id="tab-2" class="text-center tab tab-rewards">
            <h3 class="animated bounce">
                <span class='icon-cart tab-icon'></span>
                <span class="tab-text">Rewards</span>
            </h3>
        </a>
    </span>
    <span class="rsTmb tab-container">
        <a href="#" id="tab-3" class="text-center tab">
            <h3>
                <span class='icon-trophy tab-icon'></span>
                <span class="tab-text">Staff Picks</span>
           </h3>
       </a>
    </span>

</div>
<!-- =TAB SLIDER NAV ELEMENTS -->



<!-- TAB SLIDER CONTAINER -->
<div class="content-slider-bg bg-dark full-width">
    <div id="app-content" class="royalSlider contentSlider">
        <!-- TAB1: FREE APPS -->
        <div id="ar-free-apps" class="rsContent full-width">
            <div class="gallery-container three-column" id="ar-free-apps-content">
            <div class="ar-offers-loading" style="display:none;">Hang tight, loading your offers...</div>
            <!-- TODO: need something to display if no offers show up .. what else can the user do? -->
            </div>


            <div class="text-center full-width">
                <div id="ar-app-stats-container">
                    <span class="ar-app-stats full-width"><b id="totalOffers"><? print (isset($_SESSION['offerData']) && isset($_SESSION['currentOfferTypes'])) ?(count($_SESSION['offerData'][intval($_SESSION['currentOfferTypes'])])):"0"; ?></b> apps and <span class="coin-balance-icon"></span><b id="totalOfferPayout"></b> coins available today</span>
                </div>
                <div class="btn-load-more button grey grey-shadow radius"><span class="icon-down-open"></span>Load More</div>
            </div>
        </div>

        <!-- TAB2: STAFF APPS -->


        <div id="ar-rewards" class="rsContent full-width">
            <div class="" id="ar-rewards-content">

            </div>
        </div>

        <!-- TAB3: STAFF APPS -->
        <div id="ar-staff-picks" class="rsContent full-width">
            <div class="gallery-container three-column" id="ar-staff-picks-content">

            </div>
        </div>

        <!-- TAB2: STAFF APPS
        <div id="ar-pending-offers" class="rsContent full-width" style="display:none;">
            <div class="gallery-container three-column" id="ar-pending-offers-content">

            </div>
        </div> -->
    </div>
</div>

<!-- =TAB SLIDER CONTENT -->





<script>
jQuery(document).ready(function($) {

    $('#ar-home-banner-top').royalSlider({
        arrowsNav:false,

        imageScaleMode: 'fill',
        imageAlignCenter:true,

        transitionType: 'fade',

        fadeinLoadedSlide:true,
        autoPlay: {
            enabled: true
        }
    });

    $('#app-content').royalSlider({
        arrowsNav: false,
        fadeinLoadedSlide: false,
        navigateByClick:false,
        controlNavigationSpacing: 0,
        controlNavigation: 'none',
        imageAlignCenter:false,
        loop: false,
        autoHeight: true,
        autoScaleSlider:false,
        imageScaleMode:'none',
        updateSliderSize:true

    });
    var contentSlider =  $("#app-content").data('royalSlider');

    $('#tab-1').click(function(e){
        e.preventDefault();
        contentSlider.goTo(0);
    });

    $('#tab-2').click(function(e){
        e.preventDefault();
        contentSlider.goTo(1);
        var e = $('.ar-reward-iap');
        $(e).scrollLeft($(e).width());
        setTimeout(function(){

            $(e).animate({scrollLeft: 0}, 'slow');
        },1000);

    });

    $('#tab-3').click(function(e){
        e.preventDefault();
        contentSlider.goTo(2);
    });


    contentSlider.ev.on('rsAfterSlideChange',function(event){
        $('a.tab').animate({borderBottomColor:"#fff"},100);
        $('a.tab .tab-text').animate({color:"#c0c0c0"},100);
        $('#tab-' + parseInt(contentSlider.currSlideId +1 )).animate({
            borderBottomColor: "#ff0000"
        }, 500 );
        $('#tab-' + parseInt(contentSlider.currSlideId + 1) + ' .tab-text').animate({
            color: "#333"
        }, 500 );
        //alert(contentSlider.currSlideId);
        //var newSize;

    });

    var currentPage = 1;
    $("html").on('click', '.btn-load-more',function(){
        var _el = '#app-content .btn-load-more span';
        $(_el).addClass('ar-loading-indicator');
        $(_el).removeClass('icon-down-open');
        //$("#app-content").data('royalSlider').updateSliderSize(true);
        currentPage++;
        arLoadOffers('#ar-free-apps-content','a',currentPage,function(){
            var _el = '#app-content .btn-load-more span';
            $(_el).removeClass('ar-loading-indicator');
            $(_el).addClass('icon-down-open');
            window._offerPage++;
            $("#app-content").data('royalSlider').updateSliderSize();
        });



        // arLoadOffers(el,offerType,offerPage)
    });
    //load offers
    arLoadPromo('#banner-container','home',function(){
        $('#ar-home-banner-top').royalSlider({
            arrowsNav:false,
            controlNavigationSpacing: 0,
            controlNavigation: 'bullets',
            imageScaleMode: 'fill',
            imageAlignCenter:true,
            blockLoop: true,
            loop: true,
            numImagesToPreload: 6,
            transitionType: 'fade',
            keyboardNavEnabled: true,
            block: {
                delay: 400
            },
            fadeinLoadedSlide:true,
            autoPlay: {
                enabled: true
            }
        });
    });

    arLoadOffers('#ar-free-apps-content','a',1,function(){
        arLoadOffers('#ar-staff-picks-content','staff',1,function(){
            //arLoadOffers('#ar-pending-offers-content','pending');
            arLoadRewards('#ar-rewards-content');

        });

    });

    $('html').on('click','.app-details-download-button',function(e){
        e.preventDefault();
        arDownloadOffer({
            offerURL:$(this).attr('offerURL'),
            offerID:$(this).attr('offerID')
        });
        if($(this).attr('oid').toLowerCase() ==! 'locked') arAddPending();
    });



    $('html').on('click','#ar-btn-facebook-like',function(){
        arFacebookLike();
    });

    $('html').on('click','a.ar-btn-show-offer',function(){

        var offerSrc = $(this).attr('offerID');
        if(offerSrc.toLowerCase() == 'locked')
        {
            arModal({
                modalType:2,
                modalTitle:'Download Unlock App First!',
                modalMessage:'Yar! Download the unlock offer first to enjoy the rest of the TONS of offers we have.',
                modalButtons:{
                    1:{text:'Download',callback:function(){
                        $.magnificPopup.instance.open({
                            items: {
                                type:'inline',
                                src: '.ar-unlock-app'
                            },
                            removalDelay: 300,
                            mainClass: 'mfp-fade',
                            showCloseBtn:true
                        });
                    }},
                    0:{text:'OK'}//,callback:function(){alert('fart');}}
                }
            });
            return;
        }


        $.magnificPopup.instance.open({
            items: {
                type:'inline',
                src: '#' + offerSrc
            },
            removalDelay: 300,
            mainClass: 'mfp-fade',
            showCloseBtn:true

        });
        /*
        $('.ar-modal').magnificPopup({
            type:'inline',
            removalDelay: 300,
            mainClass: 'mfp-fade'
        });*/
    });

    $('html').on('click','.ar-share-earn',function() {
        window.location="#share";
        $.magnificPopup.close();
    });

    $('html').on('click','a.ar-btn-show-pending-offer',function(){

        var offerSrc = $(this).attr('offerID');

        $.magnificPopup.instance.open({
            items: {
                type:'inline',
                src: '#' + offerSrc
            },
            removalDelay: 300,
            mainClass: 'mfp-fade',
            showCloseBtn:true

        });
        /*
        $('.ar-modal').magnificPopup({
            type:'inline',
            removalDelay: 300,
            mainClass: 'mfp-fade'
        });*/
    });

});


    ///
    //window._offerPage = 1;

</script>