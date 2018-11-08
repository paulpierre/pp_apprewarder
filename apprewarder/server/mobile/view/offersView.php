<h3 class="full-width text-center ar-page-title">App Offers</h3>
<div id="banner-container"></div>

<div class="text-center full-width bg-light ar-offers">
    <span class="rsTmb tab-container">
        <a href="#" id="tab-1" class="text-center tab tab-highlight">
            <h3 class="tab-offers">
                <span class='icon-mobile tab-icon'></span>
                <span class="tab-text">Apps</span>
            </h3>
        </a>
    </span>
  <span class="rsTmb tab-container">
        <a href="#" id="tab-2" class="text-center tab">
            <h3 class="tab-offers">
                <span class='icon-clock-1 tab-icon'></span>
                <span class="tab-text">Pending</span>
            </h3>
        </a>
    </span>
</div>

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
                    <span class="ar-app-stats full-width"><b id="totalOffers"><? print count($_SESSION['offerData'][$_SESSION['currentOfferTypes']]); ?></b> apps and <span class="coin-balance-icon"></span><b id="totalOfferPayout"></b> coins available today</span>
                </div>
                <div class="btn-load-more button grey grey-shadow radius"><span class="icon-down-open"></span>Load More</div>
            </div>
        </div>

        <!-- TAB2: STAFF APPS -->
        <div id="ar-pending-offers" class="rsContent full-width">
            <div class="gallery-container three-column" id="ar-pending-offers-content">

            </div>
        </div>
    </div>
</div>

<script>


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

    $('#tab-1').click(function(e){
        e.preventDefault();
        contentSlider.goTo(0);
    });

    $('#tab-2').click(function(e){
        e.preventDefault();
        contentSlider.goTo(1);
    });

    var contentSlider =  $("#app-content").data('royalSlider');


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
        var newSize;

    });

    arLoadOffers('#ar-free-apps-content','a',1,function(){

        arLoadOffers('#ar-pending-offers-content','pending');
        arLoadPromo('#banner-container','offers',function(){
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

    });


    $('html').on('click','.app-details-download-button',function(e){
        e.preventDefault();
        arDownloadOffer({
            offerURL:$(this).attr('offerURL'),
            offerID:$(this).attr('offerID')
        });
        if($(this).attr('oid').toLowerCase() ==! 'locked') arAddPending();
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
            return true;
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

</script>


