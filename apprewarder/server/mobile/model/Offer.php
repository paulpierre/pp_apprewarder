<?php

class Offer extends Database {
    const TABLE_NAME = 'sys_offers';
    const OFFER_TABLE_NAME = 'user_offers';
    const OFFER_FILTER_NAME = 'sys_offer_filters';
    const SELECTOR_ALL =  '*';
    public $userID;


    public function update_offer_filter($filter_id,$o)
    {
        if(!isset($filter_id) || !is_numeric($filter_id)) return false;
        $db_columns = $o;
        $db_conditions = array('filter_id'=>$filter_id);
        $result = $this->db_update(self::OFFER_FILTER_NAME,$db_columns,$db_conditions,false);
        return $result;

    }

    public function add_offer_filter($offerFilterName,$o)
    {
        if(!isset($o)) return false;

        $db_columns = array(
            'filter_name'=>$offerFilterName,
            'filter_data'=>json_encode($o),
            'filter_status'=>1,
            'filter_tmodified'=>time(),
            'filter_tcreate'=>time()
        );

        $result = $this->db_create(self::OFFER_FILTER_NAME,$db_columns);
        return $result;
    }

    public function get_offer_filters($getDisabled=false)
    {

        if($getDisabled) $db_conditions = 'filter_status=0 or filter_status=1'; else $db_conditions = 'filter_status=1';
        $q = 'SELECT filter_id,filter_data,filter_status,filter_tmodified,filter_name,filter_tcreate FROM sys_offer_filters WHERE '.$db_conditions;
        return $this->db_query($q);
        //return $result = $this->db_retrieve(self::OFFER_FILTER_NAME,$db_columns,$db_conditions,null,false);
    }

    public function get_offer_filters_json()
    {
        $filters = $this->get_offer_filters();
        foreach($filters as $filter)
        {
            $filterArray[] =  json_decode($filter['filter_data']);
        }
        if(!empty($filterArray)) return json_encode($filterArray); else return false;
    }

    public function filter_exists($filter_id)
    {
        $db_columns = array('filter_id');
        $db_conditions = array('filter_id'=>$filter_id,'filter_status'=>1);
        $result = $this->db_retrieve(self::OFFER_FILTER_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return true;
    }

    public function offer_exists($offer_id)
    {
        $db_columns = array('offer_id');
        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return true;
    }


    public function offer_external_id_exists($offer_id)
    {
        $db_columns = array('offer_external_id');
        $db_conditions = array('offer_external_id'=>$offer_id);

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return true;//return $result[0]['offer_external_id'];
    }


    public function get_offer_id_by_external_id($external_id)
    {
        $db_columns = array('offer_id');
        $db_conditions = array('offer_external_id'=>$external_id);

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result[0]['offer_id'];
    }

    public function get_external_id_by_offer_id($offer_id)
    {
        $db_columns = array('offer_external_id');
        $db_conditions = array('offer_id'=>$offer_id);

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(!isset($result)) return false;
        return $result[0]['offer_external_id'];
    }

    public function ignore_offer($offer_external_id)
    {
        if(!isset($offer_external_id)) return false;

        $db_columns = array(
            'offer_tcreate'=>time(),
            'offer_external_id'=>$offer_external_id,
            'offer_status'=>0
        );

        $result = $this->db_create(self::TABLE_NAME,$db_columns);
        return $result;
    }

    public function add_offer($o)
    {
        $offerInstance = new OfferManager;
        $offer_type = (isset($o['offer_type']))?$o['offer_type']:2;
        $offer_external_id = (isset($o['offer_external_id']))?$o['offer_external_id']:0;
        $offer_external_cost = (isset($o['offer_external_cost']))?$o['offer_external_cost']:0;
        $offer_source_id = (isset($o['offer_source_id']))?$o['offer_source_id']:0;
        $offer_network_payout = (isset($o['offer_network_payout']))?floatval($o['offer_network_payout']):0;
        $offer_conversion = $offerInstance->offer_payout_conversion((isset($o['offer_user_payout']))?intval($o['offer_user_payout']):1,$offer_source_id);
        $offer_user_payout = $offer_conversion['userPayout'];
        $offer_referral_payout = $offer_conversion['userReferralPayout'];


        $offer_image_url = (isset($o['offer_image_url']))?$o['offer_image_url']:'';
        $offer_filter = (isset($o['offer_filter']))?$o['offer_filter']:mysql_real_escape_string(json_encode(array()));
        $offer_name = (isset($o['offer_name']))?$o['offer_name']:'';
        $offer_description = (isset($o['offer_description']))?$o['offer_description']:'';
        $offer_country = (isset($o['offer_country']))?$o['offer_country']:'';
        $offer_platform = (isset($o['offer_platform']))?$o['offer_platform']:OFFER_IOS;
        $offer_click_url = (isset($o['offer_click_url']))?$o['offer_click_url']:'';
        $offer_destination =  (isset($o['offer_destination']))?$o['offer_destination']:'';
        $offer_status = 1;
        $db_columns = array(
            'offer_tmodified'=>time(),
            'offer_tcreate'=>time(),
            'offer_type'=>$offer_type,
            'offer_external_id'=>$offer_external_id,
            'offer_external_cost'=>$offer_external_cost,
            'offer_source_id'=>$offer_source_id,
            'offer_network_payout'=>$offer_network_payout,
            'offer_user_payout'=>$offer_user_payout,
            'offer_referral_payout'=>$offer_referral_payout,
            'offer_image_url'=>$offer_image_url,
            'offer_filter'=>$offer_filter,
            'offer_name'=>$offer_name,
            'offer_description'=>$offer_description,
            'offer_country'=>$offer_country,
            'offer_platform'=>$offer_platform,
            'offer_click_url'=>$offer_click_url,
            'offer_status'=>$offer_status,
            'offer_destination'=>$offer_destination
        );

        $result = $this->db_create(self::TABLE_NAME,$db_columns);

        return $result;
    }



    public function set_offer_icon($offer_id,$offer_image_url)
    {
        $db_columns = array(
            'offer_tmodified'=>time(),
            'offer_image_url'=>$offer_image_url,
        );

        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }


    public function update_offer($o)
    {
        if(!isset($o['offer_id'])) return false;

        $offer_id = $o['offer_id'];
        $offer_type = (isset($o['offer_type']))?$o['offer_type']:1;
        $offer_image_url = (isset($o['offer_image_url']))?$o['offer_image_url']:'';
        $offer_name = (isset($o['offer_name']))?$o['offer_name']:'';
        $offer_user_payout = (isset($o['offer_user_payout']))?$o['offer_user_payout']:'';
        $offer_referral_payout = (isset($o['offer_referral_payout']))?$o['offer_referral_payout']:'';
        $offer_network_payout = (isset($o['offer_network_payout']))?$o['offer_network_payout']:'';
        $offer_description = (isset($o['offer_description']))?$o['offer_description']:'';
        $offer_country = (isset($o['offer_country']))?$o['offer_country']:'';
        $offer_platform = (isset($o['offer_platform']))?$o['offer_platform']:OFFER_IOS;

        $db_columns = array(
            'offer_tmodified'=>time(),
            'offer_type'=>$offer_type,
            'offer_image_url'=>$offer_image_url,
            'offer_user_payout'=>$offer_user_payout,
            'offer_referral_payout'=>$offer_referral_payout,
            'offer_network_payout'=>$offer_network_payout,
            'offer_name'=>$offer_name,
            'offer_description'=>$offer_description,
            'offer_country'=>$offer_country,
            'offer_platform'=>$offer_platform,
        );

        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);


        return $result;

    }

    public function get_offer($offer_id)
    {
        $db_columns = array(
            'offer_id',
            'offer_tmodified',
            'offer_tcreate',
            'offer_type',
            'offer_external_id',
            'offer_external_cost',
            'offer_source_id',
            'offer_network_payout',
            'offer_user_payout',
            'offer_referral_payout',
            'offer_image_url',
            'offer_filter',
            'offer_name',
            'offer_description',
            'offer_country',
            'offer_platform',
            'offer_click_url',
            //'offer_status'
        );
        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result[0];
    }

    public function get_external_ids($get_disabled = false)
    {
        $db_columns = array(
            'offer_external_id'
        );


        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,'',null,false);
        if(empty($result)) return false;
        return $result;
    }

    public function get_offer_list($get_disabled = false) //get a list of available enabled offers
    {/*
        $db_columns = array(
            'offer_id',
            'offer_tmodified',
            'offer_tcreate',
            'offer_type',
            'offer_external_id',
            'offer_external_cost',
            'offer_source_id',
            'offer_network_payout',
            'offer_user_payout',
            'offer_referral_payout',
            'offer_image_url',
            'offer_filter',
            'offer_name',
            'offer_destination',
            'offer_description',
            'offer_country',
            'offer_platform',
            'offer_click_url',
            'offer_status'
        ); */
        $db_conditions = 'offer_status=1';
        if($get_disabled) $db_conditions = 'offer_status=0 or offer_status=1';
        $q = 'SELECT offer_id,offer_tmodified,offer_tcreate,offer_type,offer_external_id,offer_external_cost,offer_source_id,offer_network_payout,offer_user_payout,offer_referral_payout,offer_image_url,offer_filter,offer_name,offer_destination,offer_description,offer_country,offer_platform,offer_click_url,offer_status FROM ' . self::TABLE_NAME .' WHERE '.$db_conditions;
        return $this->db_query($q);
/*
        $db_conditions = ($get_disabled)?'':array('offer_status'=>1);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result;
*/

    }

    public function disable_offer($offer_id)
    {
        if( !is_numeric($offer_id) || !$this->offer_exists($offer_id)) return false;
        $db_columns = array('offer_status'=>0);
        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }


    public function delete_offer($offer_id)
    {
        if( !is_numeric($offer_id) || !$this->offer_exists($offer_id)) return false;
        $db_columns = array('offer_status'=>5);
        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }

    public function enable_offer($offer_id)
    {
        if( !is_numeric($offer_id) || !$this->offer_exists($offer_id)) return false;
        $db_columns = array('offer_status'=>1);
        $db_conditions = array('offer_id'=>$offer_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }



    public function raw_dump_offers()
    {
        $db_columns = array(
            'offer_id',
            'offer_tmodified',
            'offer_tcreate',
            'offer_type',
            'offer_external_id',
            'offer_external_cost',
            'offer_source_id',
            'offer_network_payout',
            'offer_user_payout',
            'offer_referral_payout',
            'offer_image_url',
            'offer_filter',
            'offer_name',
            'offer_description',
            'offer_country',
            'offer_platform',
            'offer_click_url',
            'offer_status'
        );

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,'',null,false);
        return $result;
    }

}
?>