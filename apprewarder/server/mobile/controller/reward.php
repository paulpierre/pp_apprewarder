<?php

$APP_NAME = constant('APP_NAME');
$USER_CURRENCY = (!isset($_SESSION['userCredits']))?0:$_SESSION['userCredits'];
$APP_BASE_URL = constant('API_HOST');
$IMG_RES = constant('IMG_RES');
$CSS_RES = constant('CSS_RES');
$JS_RES = constant('JS_RES');
$currentTime = intval(time());

$userCredits = intval($_SESSION['userCredits']);
$userFriendCode = $_SESSION['userFriendCode'];

//rewards offers

//lets refresh the account status
$userInstance->userAccountStatus = $_SESSION['userAccountStatus'];
$userInstance->process_user_account_status();
$rewardsInstance = new Reward();
$userLocale = isset($_SESSION['userLocale'])?$_SESSION['userLocale']:'INT';
$rewardOffers = $rewardsInstance->get_rewards($userLocale);

$userEmail = $_SESSION['userEmail'];
UtilityManager::aasort($rewardOffers,'reward_weight',SORT_DESC);
/*
$rewardOffers = array(
    array("name"=>"$2 Amazon Gift Card*","cost"=>2000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
    array("name"=>"$5 Amazon Gift Card*","cost"=>5000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
    array("name"=>"$10 Amazon Gift Card*","cost"=>10000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
    array("name"=>"$25 Amazon Gift Card*","cost"=>25000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
    array("name"=>"$50 Amazon Gift Card*","cost"=>50000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
    array("name"=>"$15 iTunes Gift Card","cost"=>15000,"description"=>"Never expires.","image"=>"icon_itunes.png")
);*/


switch($controllerFunction)
{
    case 'list':
        include(VIEW_PATH . 'rewardListView.php');
        exit();
        break;
    case 'claim':
        $rewardID = $controllerID;
        $userID = $_SESSION['userID'];
        $userCredits = $userInstance->get_user_credits($userID);
        $rewardData = $rewardsInstance->get_reward($rewardID);
        $rewardCost = intval($rewardData['reward_cost']);
        $rewardPayout = floatval($rewardData['reward_payout']);
        $rewardLimit = intval($rewardData['reward_limit']);
        $userClaims = $userInstance->get_user_claims_sum($userID);
        if($rewardLimit > 0) {
            $rewards = $rewardsInstance->get_claimed_reward($_SESSION['userID'],$rewardID);
            if(count($rewards) >= $rewardLimit) $userDidHitLimit = true;
        } else {
            $userDidHitLimit = false;
        }

        /*
        $dialogData = array(
            'status'=>1,
            'title'=>'Thank You :)',
            'body'=>'Awesome, thanks for liking our Facebook page, here is ' . PAYOUT_FACEBOOK_LIKE . ' coins! Hit OK to refresh.'
        );*/

        if(!isset($userID)) {
             $dialogData = array(
                 'status'=>0,
                 'title'=>'Yikes!',
                 'body'=>'You must be logged in to claim a reward. If the problem persists, please contact ' . SUPPORT_EMAIL .'.'
             );

        }elseif(!isset($rewardID)) {
            $dialogData = array(
                'status'=>0,
                'title'=>'Yikes!',
                'body'=>'No reward specified, please contact support at ' . SUPPORT_EMAIL . '.'
            );
        }elseif(empty($userEmail)) {
            $dialogData = array(
                'status'=>0,
                'title'=>'Yikes!',
                'body'=>'You must set and confirm your email address before you redeem a reward.'
            );
        }elseif($userCredits < $rewardCost) {
             $dialogData = array(
                 'status'=>2,
                 'title'=>'Yikes!',
                 'body'=>'You need <span class="inline-coin coin-balance-icon"></span>' . ($rewardCost - $userCredits) . ' more coins, download more apps to earn more.'
                 );
        }elseif(($userCredits-$userClaims) < $rewardCost) {
            $dialogData = array(
                'status'=>2,
                'title'=>'Yikes!',
                'body'=>'You have <span class="inline-coin coin-balance-icon"></span> ' . $userClaims . ' coins worth of pending rewards you have claimed. You will not have enough for this reward. Download more apps to earn more coins.'
            );
        }elseif(!$userInstance->didConfirmEmail){
            $dialogData = array(
                'status'=>0,
                'title'=>'Yikes!',
                'body'=>'You must first confirm the email we sent to your email address: ' . $userEmail .' or connect to Facebook.'
            );
        }elseif($userDidHitLimit){
            $dialogData = array(
                'status'=>0,
                'title'=>'We are so sorry',
                'body'=>'It looks like we have run out of the item you are trying to claim. We have limited availability of this particular item and we apologize, please check back soon!'
            );
        }elseif($rewardsInstance->add_reward_claim($userID,$rewardID)) {
            $dialogData = array(
                'status'=>1,
                'title'=>'Woot!',
                'body'=>'Avast! We will email your reward to ' . $userEmail .' and deduct ' . $rewardCost .' coins. Please allow 48 hrs for the reward to process.'
            );
        }

        exit(json_encode($dialogData));

    break;

    default:
        include(VIEW_PATH . 'rewardView.php');
    break;

}
?>

