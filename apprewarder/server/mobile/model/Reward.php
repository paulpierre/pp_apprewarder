<?php

class Reward extends Database {
    const TABLE_NAME = 'user_rewards';
    const REWARD_SYS_TABLE_NAME = 'sys_rewards';
    const SELECTOR_ALL =  '*';

    public function get_iap_rewards($rewardSourceID,$rewardIAPSourceID)
    {
        $q = 'SELECT reward_id, reward_weight, reward_limit, reward_cost,reward_source_id,reward_img,reward_name,reward_status,reward_description,reward_payout,reward_region,reward_tcreate,reward_tmodified,reward_expiration FROM sys_rewards WHERE reward_status > 0 AND reward_source_id=' . $rewardSourceID . ' AND reward_iap_source_id=' . $rewardIAPSourceID;
        $result = $this->db_query($q);
        return $result;
    }

    public function get_claimed_reward($user_id,$reward_id)
    {
        $db_columns = array(
            'reward_id',
            'reward_user_payout',
            'reward_user_cost'
        );

        $db_conditions = array(
            'reward_id'=>$reward_id,
            'user_id'=>$user_id
        );

        //allows us to use both user_id and user_device_id
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        return $result;
    }

    public function get_rewards($reward_region='INT')
    {
         /*
            $db_columns = array(
                'reward_id',
                'reward_cost',
                'reward_weight',
                'reward_source_id',
                'reward_img',
                'reward_name',
                'reward_status',
                'reward_description',
                'reward_payout',
                'reward_region',
                'reward_tcreate',
                'reward_tmodified',
                'reward_expiration'
            );
         */

          /*  $db_conditions = array(
                'reward_status'=>'1 OR reward_status=2'//,
                //'reward_region'=>strtoupper($reward_region)
            );*/

        $q = 'SELECT reward_id,reward_iap_source_id, reward_weight, reward_limit, reward_cost,reward_source_id,reward_img,reward_name,reward_status,reward_description,reward_payout,reward_region,reward_tcreate,reward_tmodified,reward_expiration FROM sys_rewards WHERE reward_status > 0 AND reward_iap_source_id=0';
        $result = $this->db_query($q);



        return $result;
    }

    public function get_reward($reward_id)
    {
        $db_columns = array(
            'reward_cost',
            'reward_payout',
            'reward_limit'
        );

        $db_conditions = array(
            'reward_id'=>$reward_id,
        );

        //allows us to use both user_id and user_device_id
        $result = $this->db_retrieve(self::REWARD_SYS_TABLE_NAME,$db_columns,$db_conditions,null,false);
        return $result[0];

    }



    public function add_reward($reward_cost,$reward_source_id,$reward_img,$reward_name,$reward_description,$reward_payout,$reward_region,$reward_status=1,$reward_expiration,$reward_weight=0,$reward_limit=0)
    {
        /*
         *  ADD A REWARD TO THE SYS REWARD TABLE
         */

        $db_columns = array(
            'reward_cost'=>intval($reward_cost),
            'reward_source_id'=>$reward_source_id,
            'reward_name'=>$reward_name,
            'reward_description'=>$reward_description,
            'reward_payout'=>floatval($reward_payout),
            'reward_region'=>$reward_region,
            'reward_status'=>$reward_status,
            'reward_img'=>$reward_img,
            'reward_weight'=>$reward_weight,
            'reward_limit'=>$reward_limit,
            'reward_expiration'=>$reward_expiration,
            'reward_tcreate'=>time(),
            'reward_tmodified'=>time()
        );
        $result = $this->db_create(self::REWARD_SYS_TABLE_NAME,$db_columns);
        return $result;
    }

    public function add_reward_claim($user_id,$reward_id)
    {

        $db_columns = array(
            'reward_cost',
            'reward_payout',
            'reward_source_id'
        );

        $db_conditions = array(
            'reward_id'=>$reward_id,
        );

        $result = $this->db_retrieve(self::REWARD_SYS_TABLE_NAME,$db_columns,$db_conditions,null,false);

        $reward_cost = $result[0]['reward_cost'];
        $reward_payout = $result[0]['reward_payout'];
        $reward_source_id = $result[0]['reward_source_id'];


        $db_columns = array(
            'user_id'=>$user_id,
            'reward_id'=>$reward_id,
            'reward_user_cost'=>intval($reward_cost),
            'reward_user_payout'=>intval($reward_payout),
            'reward_source_id'=>intval($reward_source_id),
            'reward_user_status'=>0,
            'reward_user_tcreate'=>time(),
            'reward_user_tmodified'=>time()
        );

        $result = $this->db_create(self::TABLE_NAME,$db_columns);
        if(empty($result)) return false;
        else return $result;
    }


    public function update_reward($reward_id,$reward_cost,$reward_source_id,$reward_name,$reward_description,$reward_payout,$reward_region,$reward_image,$reward_status=1,$reward_expiration,$reward_weight=0,$reward_limit=0)
    {
        $db_columns = array(
            'reward_cost'=>$reward_cost,
            'reward_source_id'=>$reward_source_id,
            'reward_name'=>$reward_name,
            'reward_weight'=>$reward_weight,
            'reward_limit'=>$reward_limit,
            'reward_description'=>$reward_description,
            'reward_img'=>$reward_image,
            'reward_payout'=>intval($reward_payout),
            'reward_region'=>$reward_region,
            'reward_status'=>$reward_status,
            'reward_expiration'=>$reward_expiration,
            'reward_tmodified'=>time()
        );
        $db_conditions = array('reward_id'=>$reward_id);
        $result = $this->db_update(self::REWARD_SYS_TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }

    public function remove_reward($reward_id)
    {
        $db_columns = array('reward_status'=>0);
        $db_conditions = array('reward_id'=>$reward_id);
        $result = $this->db_update(self::REWARD_SYS_TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }


/**
 * $rewardOffers = array(
array("name"=>"$2 Amazon Gift Card*","cost"=>2000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
array("name"=>"$5 Amazon Gift Card*","cost"=>5000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
array("name"=>"$10 Amazon Gift Card*","cost"=>10000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
array("name"=>"$25 Amazon Gift Card*","cost"=>25000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
array("name"=>"$50 Amazon Gift Card*","cost"=>50000,"description"=>"Never expires.","image"=>"icon_amazon.png"),
array("name"=>"$15 iTunes Gift Card","cost"=>15000,"description"=>"Never expires.","image"=>"icon_itunes.png")
);
 */



}
