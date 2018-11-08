<div id="ar-vault-container" class="ar-vault-bg-top full-width">
    <div  class="full-width bg-metal"></div>

    <div id="ar-vault-timer" class="full-width" data="1395173691">
        1day 2hrs 3min 2sec
    </div>

    <div id="ar-vault-menu-container" class="two-column full-width">

        <div id="ar-vault-balance-text">
            <a href="#" class="icon-help ar-tip" title="Coins & Cash" text="You earn <span class=coin-balance-icon style=float:none;display:inline-block;display:relative;top:3px;left:2px;></span> coins for downloading and playing apps. Each month you get to convert that into <span style=position:relative;left:2px; class=cash-icon-inline></span> bucks which allows you to redeem awesome stuff like Amazon Gift Cards or Bitcoins. Tap the Exchange button to turn your coins into bucks."></a>
            Balance:
        </div>


        <div id="ar-vault-menu">
            <div id="ar-vault-prompt">Until the vault unlocks</div>
            <div id="ar-vault-button-container">
                <a href="#ar-btn-vault-cashout" id="ar-btn-vault-cashout" class="button radius green-shadow green  ar-half-alpha">Cash Out</a>
                <a href="#ar-btn-vault-transfer" id="ar-btn-vault-transfer" class="button radius grey-shadow grey "><span class="icon-shuffle"></span>Exchange</a>

            </div>
            <div id="ar-vault-balance-value">
                <span class="ar-icon-cash-large"></span>
                10000
            </div>
        </div>
    </div>
    <div id="ar-vault-balance-container" class="full-width">


    </div>
</div>


<div id="ar-vault-history-container">
    <!-- HISTORY ITEM -->

    <div id="ar-history-items" class="full-width bg-light">
        <div class="ar-history-item full-width">

            <div class="ar-history-payout">
                <span class="ar-icon-cash-large ar-icon-add"></span> 10 bucks exchanged
            </div>
            <div class="ar-history-details">
                <div class="ar-history-time"><span class="icon-clock"></span> 10 days ago</div>
            </div>
        </div>

        <div class="ar-history-item full-width">

            <div class="ar-history-payout">
                <span class="ar-icon-cash-large ar-icon-subtract"></span> 100 in bucks spent
            </div>
            <div class="ar-history-details">
                <div class="ar-history-time"><span class="icon-clock"></span> 10 days ago</div>
            </div>
        </div>

    </div>




</div>




<? if(!isset($userEmail)) { ?>
<div class="wrapped-content bg-light" id="ar-email-register">
    <form id="comments-form" class="full-width" action="">
        <p>
            <label for="userEmail">Register your email to cash out:</label>
            <a href="#" class="icon-help ar-tip" title="Register your email" text="We send rewards and verify users via email. Rewards come in the form of gift card codes you can redeem at any of our partner sites or in BitCoin."></a>

            <input type="email" class="h5-email" name="userEmail" id="userEmail"/>
        </p>
        <p class="email-hide">
            <label for="userEmailVerify" class="email-hide">Verify email:</label>
            <input type="email" class="h5-email email-hide" name="userEmailVerify" id="userEmailVerify"/>


        <p class="email-hide text-center">
            <a href="#ar-register-email-popup" class="button radius red-shadow ar-modal">Register</a>
        </p>

        </p>
    </form>
</div>
<? } ?>






<script>
    arTimer('#ar-vault-timer');
</script>