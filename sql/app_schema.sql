-- =========================================
-- APPREWARDER  apprewarderwrite/4pp_r3w4rd3r!!
-- =========================================

-- ============
-- user_account
-- ============
-- table tracks registration of users

DROP TABLE IF EXISTS `user_account`;
CREATE TABLE `user_account` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_level` int(5) NOT NULL,
  `user_name` varchar(256) NOT NULL,
  `user_gender` int(1) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_hash` varchar(256) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `user_platform` int(2) NOT NULL, /* 1=iPhone 2=Android */
  `user_client_version` varchar(256) NOT NULL, /* the version of their client whether android or ios */
  `user_device_id` varchar(256) NOT NULL,
  `user_device_model` varchar(256) NOT NULL,
  `user_device_imei` varchar(256) NOT NULL,
  `user_device_mac` varchar(256) NOT NULL,
  `user_device_version` varchar(256) NOT NULL,
  `user_tcreate` int(11) NOT NULL,
  `user_tmodified` int(11) NOT NULL,
  `user_tlogin` date NOT NULL,
  `user_ip_register` varchar(20) NOT NULL, /* The IP address of where the user registered */
  `user_ip` varchar(20) NOT NULL, /* IP address of the user's last login */
  `user_account_status` int(2) NOT NULL,
  `user_referral_count` int(5) NOT NULL,
  `user_credits` int(20) NOT NULL, /* User's total credit balance */
  `user_credits_sum` int(20) NOT NULL, /* Life time accumulation of all credit types */
  `user_credits_referral` int(20) NOT NULL, /* total money earned through referrals */
  `user_credits_free` int(20) NOT NULL, /* Total credits given out for free e.g. leaderboards or contests, etc and NOT through offers */
  `user_cash` int(20) NOT NULL,
  `user_cash_sum` int(20) NOT NULL,
  `user_payout` int(20) NOT NULL, /* Total credits paid out to user from system */
  `user_facebook_like` int(3) NOT NULL, /* User did like our facebook page or not */
  `user_facebook_id` varchar(256) NOT NULL,
  `user_facebook_verified` int(1) NOT NULL,
  `user_facebook_token` varchar(256) NOT NULL,
  `user_locale_register` varchar(10) NOT NULL, /* GEO of where the user registered */
  `user_locale` varchar(10) NOT NULL, /* GEO of where the user last was */
  `user_friend_code` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE user_account AUTO_INCREMENT = 10000;

-- =============
-- user_referral
-- =============
-- table tracks all referrals and the status of the referrals

DROP TABLE IF EXISTS `user_referral`;
CREATE TABLE `user_referral` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,    /* user ID of the user who made the referral */
  `referral_referred_user_id` int(10) NOT NULL, /*user ID of the user being referred */
  `referral_tcreate` int(11) NOT NULL,
  `referral_tmodified` int(11) NOT NULL,
  `referral_status` int(3) NOT NULL, /*status on the referral  */
  `referral_source` int(3) NOT NULL, /* source of the referral (UA channel) like email, etc. */
  PRIMARY KEY (`id`),
  KEY (`referral_referred_user_id`),
  KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- =========
-- sys_offers
-- =========
-- cost table of everything
DROP TABLE IF EXISTS `sys_offers`;
CREATE TABLE `sys_offers` (
  `offer_id` int(10) NOT NULL AUTO_INCREMENT,  /*record id     */
  `offer_type` int(3) NOT NULL,  /* 1 = app, 2 = invite, etc.         */
  `offer_external_id` varchar(255) NOT NULL,  /*id of the app within something like flurry      */
  `offer_external_cost` int(10) NOT NULL, /*cost the external dev charges for the offer like an app e.g. $0.99 or FREE    */
  `offer_source_id` int(10) NOT NULL, /* who the source is 0 = internal 1 = flurry, etc.         */
  `offer_user_payout` float(10) NOT NULL,  /* payout to the user for that particular offer              */
  `offer_referral_payout` float(10) NOT NULL, /* payout to referrals */
  `offer_network_payout` float(10) NOT NULL, /* payout the network to us */
  `offer_image_url` varchar(255) NOT NULL, /* URL pointing to the thumbnail of the offer */
  `offer_destination` varchar(255) NOT NULL,
  `offer_filter` varchar(255) NOT NULL, /* custom json filters for manual offer */
  `offer_name` varchar(255) NOT NULL, /* offer name */
  `offer_description` varchar(255) NOT NULL, /* description of the offer */
  `offer_country` varchar (255) NOT NULL,
  `offer_platform` int(2) NOT NULL, /* 1 = iOS 2 = Android */
  `offer_click_url` varchar(255) NOT NULL,
  `reward_expiration` int(10) NOT NULL,  /* when the offer was added the system         */
  `offer_tcreate` int(10) NOT NULL,  /* when the offer was added the system         */
  `offer_tmodified` int(10) NOT NULL,  /* when the offer was modified        */
  `offer_status` int(3) NOT NULL, /* 1 = enabled 0 = disabled 2 = blocked*/
  PRIMARY KEY (`offer_id`),
  KEY(`offer_source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ===========
-- user_offers
-- ===========
-- table tracks all offers redeemed within the app e.g. all apps downloaded

DROP TABLE IF EXISTS `user_offers`;
CREATE TABLE `user_offers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,  /* record id  */
  `offer_id` varchar(255) NOT NULL,    /*id of the offer as provided by the network    */
  `user_id` int(10) NOT NULL,   /*id of the player that executed the offer     */
  `user_ip` varchar(20) NOT NULL,
  `offer_payout` float(10) NOT NULL,   /*how many credits was paid out. track this separately because payout may fluctuate     */
  `offer_referral_payout` float(10) NOT NULL,
  `offer_referral_user_id` int(10) NOT NULL,
  `offer_url` varchar(255) NOT NULL,
  `offer_cost` int(10) NOT NULL, /* cost the external dev charges for the offer like an app, e.g. $0.99    */
  `offer_network_id` varchar(255) NOT NULL, /* id of the developer providing the offer    */
  `offer_network` varchar(255) NOT NULL, /*name of the developer  */
  `offer_network_payout` float(10) NOT NULL, /* the amount the network is paying AR */
  `offer_name` varchar(255) NOT NULL, /* name of the offer    */
  `offer_image_url` varchar(255) NOT NULL,
  `offer_platform` int(2) NOT NULL, /* 1 = iOS 2 = android */
  `offer_status` int(5) NOT NULL, /* whether the offer redeemed has been verified 0=clicked 1=redeemed 5=expired*/
  `offer_click_count` int(10) NOT NULL,
  `offer_tcreate` int(10) NOT NULL,
  `offer_tmodified` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`user_id`),
  KEY (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ===========
-- sys_rewards
-- ===========
-- the rewards for when a player wants to redeem stuff
DROP TABLE IF EXISTS `sys_rewards`;
CREATE TABLE `sys_rewards`(
  `reward_id` int(10) NOT NULL AUTO_INCREMENT,  /*record id         */
  `reward_weight` int(2) NOT NULL,
  `reward_limit` int()
  `reward_cost` int(10) NOT NULL, /* number of credits it costs to redeem         */
  `reward_source_id` int(10) NOT NULL, /* who the source is 0 = internal, 1 = amazon, etc.   */
  `reward_iap_source_id` int(3) NOT NULL,
  `reward_name` varchar(255) NOT NULL, /* name of the reward  */
  `reward_description` varchar (255) NOT NULL, /* user-facing description of the reward */
  `reward_payout` float(10) NOT NULL,  /* how much the $ payout is to the user   */
  `reward_region` varchar(255) NOT NULL, /* region the reward is displayed US/CN/VN etc. */
  `reward_status` int(3) NOT NULL, /* the status of the reward, 1 avail, 0 no avail, etc */
  `reward_tcreate` int(10) NOT NULL,  /* when the reward was created */
  `reward_tmodified` int(10) NOT NULL, /* when we modified the pricing, etc. */
  PRIMARY KEY (`reward_id`),
  KEY(`reward_source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =================
-- sys_offer_filters
-- =================
-- filter logic for offers
DROP TABLE IF EXISTS `sys_offer_filters`;
CREATE TABLE `sys_offer_filters`(
  `filter_id` int(10) NOT NULL AUTO_INCREMENT,
  `filter_name` varchar(255) NOT NULL,
  `filter_data` varchar(500) NOT NULL,
  `filter_status` int(1) NOT NULL,
  `filter_tcreate` int(10) NOT NULL,
  `filter_tmodified` int(10) NOT NULL,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =============
-- sys_isp
-- =============
-- track VPN traffic

DROP TABLE IF EXISTS `sys_isp`;
CREATE TABLE `sys_isp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(20) NOT NULL,    /* user ID of the user who made the referral */
  `isp_name` varchar(500) NOT NULL,
  `isp_tcreate` int(11) NOT NULL,
  `isp_tmodified` int(11) NOT NULL,
  `isp_country` varchar(5) NOT NULL, /*status on the referral  */
  `isp_status` int(3) NOT NULL, /*status on the referral  */
  UNIQUE (`user_ip`),
  PRIMARY KEY (`user_ip`),
  KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ============
-- user_rewards
-- ============
-- table tracks rewards requested and rewards redeemed

DROP TABLE IF EXISTS `user_rewards`;
CREATE TABLE `user_rewards` (
  `id` int(10) NOT NULL AUTO_INCREMENT, /* record ID of the redemption */
  `reward_id` int(10) NOT NULL, /* ID of the reward */
  `user_id` int(10) NOT NULL, /* ID of the user claiming the reward */
  `reward_source_id` int(10) NOT NULL, /* who the source is 0 = internal, 1 = amazon, etc.   */
  `reward_user_cost` int(10) NOT NULL, /* number of credits it costs to redeem */
  `reward_user_payout` float(10) NOT NULL, /* $  */
  `reward_user_status` int(3) NOT NULL, /* 0 disabled  1 enabled 2 sold out */
  `reward_user_tcreate` int(11) NOT NULL,
  `reward_img` varchar(255) NOT NULL,
  `reward_expiration` int(11) NOT NULL,
  `reward_user_tmodified` int(11) NOT NULL,
  PRIMARY KEY (`id`),
    KEY(`user_id`),
    KEY(`reward_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ===========
-- user_offer_cb
-- ===========
-- table tracks all offers redeemed from ad network call backs

DROP TABLE IF EXISTS `user_offer_cb`;
CREATE TABLE `user_offer_cb` (
  `id` int(10) NOT NULL AUTO_INCREMENT,  /* record id       */
  `network_cb_id` varchar(255) NOT NULL,    /*id of the offer, like an app        */
  `user_id` int(10) NOT NULL,   /*id of the player that executed the offer       */
  `offer_network` varchar(255) NOT NULL, /* name of the ad network */
  `offer_payout` float(5) NOT NULL,   /* how many credits redeemed. track this separately because payout may fluctuate    */
  `offer_network_payout` float(5) NOT NULL,
  `offer_referral_payout` float(10) NOT NULL,
  `offer_referral_user_id` int(10) NOT NULL,
  `offer_id` varchar(255) NOT NULL,
  `offer_tcreate` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`user_id`),
  KEY (`network_cb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- ===========
-- user_login
-- ===========
-- table tracks each day every single user logs in to help us track retention
DROP TABLE IF EXISTS `user_login`;
CREATE TABLE `user_login` (
  `user_id` int(10) unsigned NOT NULL,
  `tlogin` date NOT NULL,
  KEY (`user_id`,`tlogin`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;