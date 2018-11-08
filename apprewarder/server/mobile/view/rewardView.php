<h3 class="full-width text-center ar-page-title">Rewards</h3>
<?    if($userEmail && !$userInstance->didConfirmEmail)
{ ?>
<div data-alert class="alert-box">
    A confirmation email was sent to <strong><? print $userEmail; ?></strong>. To
    get rewards, you must click the link in the email.
    <a href="#" class="close">&times;</a>
</div>

<?}?>


<!-- TOP HOME BANNER -->
<div id="banner-container"></div>


<div id="ar-rewards-container">

    <div class="bg-awning panel grey ar-rewards-country full-width responsive text-center">
        <p>
        <div class="flag flag-<? print strtolower($_SESSION['userLocale']); ?>" style="display:inline-block;margin-right:5px;"></div>
        <strong><? print $countries[$_SESSION['userLocale']]; ?></strong>
        </p>
    </div>




</div>

<script>
    jQuery(document).ready(function($) {

        arLoadRewards('#ar-rewards-container');


        arLoadPromo('#banner-container','reward',function(){
            $('#ar-reward-banner-top').royalSlider({
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

</script>
