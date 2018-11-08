
GLOBAL_USER~select count(*) as user_count, sum(user_credits_sum) as credits_sum, sum(user_referral_count) as referral_count, sum(user_credits) as credits_float, sum(user_credits_referral) as credits_referral, sum(user_credits_free) as credits_free from user_account

GLOBAL_CALLBACK~select count(*) as cb_count, sum(offer_network_payout) as cb_revenue, sum(offer_payout) as cb_credits_sum  from user_offer_cb

USER_DEVICE_COUNT~select user_platform,count(user_platform) as device_count from user_account group by user_platform

USER_EMAIL_COUNT~select count(user_email) as email_count from user_account where user_email != ''

USER_FB_COUNT~select count(user_facebook_token) as fb_count from user_account where user_facebook_token != ''

MONETIZATION_TRANSACTIONS~select a.user_date as date, a.potentiallyOwe, b.PayOutToUser,b.revenue,c.BonusPayOut,format((b.revenue-a.potentiallyOwe),2) as profit,format((b.revenue-a.potentiallyOwe)/revenue,2) as netmargin,format((b.revenue-b.PayOutToUser)/revenue,2) as networkmargin,format((c.BonusPayOut + b.PayOutToUser-potentiallyOwe),2) as difference from (select format(sum(user_credits_sum)/600,2) as potentiallyOwe,date_format(from_unixtime(user_tcreate),'%Y-%m-%d') as user_date from user_account where from_unixtime(user_tcreate) >= 'AR_START_DATE' group by user_date) a,(select format(sum(offer_payout)/600,2) as PayOutToUser,format(sum(offer_network_payout),2) as revenue,count(*) as callback,date_format(from_unixtime(user_tcreate),'%Y-%m-%d') as user_date from(select * from user_account) a, (select * from user_offer_cb) b  where a.user_id=b.user_id and from_unixtime(user_tcreate) >= 'AR_START_DATE' group by user_date) b, (select format(((count(*) * 50)/600),2) as BonusPayOut,date_format(from_unixtime(referral_tcreate),'%Y-%m-%d') as referraldate from user_referral where from_unixtime(referral_tcreate) >= 'AR_START_DATE' group by referraldate ) c where referraldate=a.user_date and a.user_date=b.user_date

SYSTEM_MONETIZATION_TOTAL~select a.user_date as date, a.FloatingDebt, b.UserPayouts,b.Revenue,format((b.Revenue-a.FloatingDebt),2) as Profit,c.BonusPayouts from (select format(sum(user_credits_sum)/600,2) as FloatingDebt,date_format(from_unixtime(user_tcreate),'%Y-%m-%d') as user_date from user_account where from_unixtime(user_tcreate) >='AR_START_DATE' group by user_date) a,(select format(sum(offer_payout)/600,2) as UserPayouts,format(sum(offer_network_payout),2) as Revenue,count(*) as callback,date_format(from_unixtime(user_tcreate),'%Y-%m-%d') as user_date from(select * from user_account) a, (select * from user_offer_cb) b  where a.user_id=b.user_id and from_unixtime(user_tcreate) >= 'AR_START_DATE' group by user_date) b, (select format(((count(*) * 50)/600),2) as BonusPayouts,date_format(from_unixtime(referral_tcreate),'%Y-%m-%d') as referraldate from user_referral where from_unixtime(referral_tcreate) >='AR_START_DATE' group by referraldate ) c where referraldate=a.user_date and a.user_date=b.user_date;

SYSTEM_MONETIZATION_MARGIN~select a.user_date as date, format(((b.revenue-a.potentiallyOwe)/revenue)*100,2)  as ProfitMargin,format(((b.revenue-b.PayOutToUser)/revenue)*100,2) as TransactionalMargin,format((c.BonusPayOut + b.PayOutToUser-potentiallyOwe),2) as Delta from (select format(sum(user_credits_sum)/600,2) as potentiallyOwe,date_format(from_unixtime(user_tcreate),'%Y-%m-%d') as user_date from user_account where from_unixtime(user_tcreate) >= 'AR_START_DATE' group by user_date) a,(select format(sum(offer_payout)/600,2) as PayOutToUser,format(sum(offer_network_payout),2) as revenue,count(*) as callback,date_format(from_unixtime(user_tcreate),'%Y-%m-%d') as user_date from(select * from user_account) a, (select * from user_offer_cb) b  where a.user_id=b.user_id and from_unixtime(user_tcreate) >= 'AR_START_DATE' group by user_date) b, (select format(((count(*) * 50)/600),2) as BonusPayOut,date_format(from_unixtime(referral_tcreate),'%Y-%m-%d') as referraldate from user_referral where from_unixtime(referral_tcreate) >= 'AR_START_DATE' group by referraldate ) c where referraldate=a.user_date and a.user_date=b.user_date;

SYSTEM_MONETIZATION_NETWORK~select date_format(from_unixtime(offer_tcreate),'%Y-%m-%d') as date,offer_network,format(sum(offer_network_payout),2) as Payout from user_offer_cb  where from_unixtime(offer_tcreate) >= 'AR_START_DATE' group by date_format(from_unixtime(offer_tcreate),'Y%-%m-%d'), offer_network order by date asc;

GET_USER_ACCOUNT_DATA~select user_id,user_name, user_friend_code, case  when user_platform=2 then '2' when user_platform in (1,11,12,13) then '1' ELSE '0' end as user_platform, user_account_status, user_referral_count, user_credits, user_credits_sum, user_credits_referral,user_credits_free, user_cash, user_cash_sum, user_payout,user_locale_register, user_locale,user_tlogin, user_tcreate, user_tmodified, user_tlogin, user_facebook_id, user_gender, user_client_version,user_device_model,user_device_version,user_ip_register from user_account;

GET_SYS_REWARD~select reward_id,case reward_source_id when 1 then 'Amazon' when 3 then 'Paypal' when 4 then 'Bitcoin' when 0 then 'Promotional' else 'Unknown' end as reward_source,reward_source_id,reward_name, reward_description, reward_cost,reward_payout,reward_region,reward_expiration,concat(datediff(now(),from_unixtime(reward_tcreate)),' Days Ago') as created,concat(datediff(now(),from_unixtime(reward_tmodified)),' days Ago') as modified from sys_rewards;
