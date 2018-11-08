<?php

$page_data = array(
    'page_title'=> 'SYS REWARDS',
    'page_name' =>  $controller_name
);



switch($controller_function){


case 'addreward':

if($_POST['reward_cost'] < 1 or $_POST['reward_payout'] < 1){ exit;};


$data['reward_cost'] = $_POST['reward_cost'];
$data['reward_source_id'] =$_POST['reward_source_id'];
$data['reward_name'] =$_POST['reward_name'];
$data['reward_description'] =$_POST['reward_description'];
$data['reward_payout'] =$_POST['reward_payout'];
$data['reward_region'] =$_POST['reward_region'];
$data['reward_status'] =$_POST['reward_status'];
$data['reward_tcreate'] =time();
$data['reward_tmodified'] =time();
$data['reward_img'] =$_POST['reward_img'];
$data['reward_expiration'] =$_POST['reward_expiration'];

$results=$userInstance->db_create('sys_rewards',$data);

// Provide feedback to the entry field
if (!$results) { echo "<html><body><font color=red size=3>" . 'Insert Failed' . "</font></body></html>"; }
else          { echo "<html><body><font color=green size=3>" . 'Insert Success' . "</font></body></html>"; }
        break;
case 'updatereward':
	exit;
	break; 
}











$results=$userInstance->db_query($SQL_DATA['GET_SYS_REWARD']);

$columns=array();
foreach($results[0] as $key => $value){ 

array_push($columns,$key);

};
$smarty->assign('page_data',$page_data);
$smarty->assign('results',$results);
$smarty->assign('columns',$columns);
$smarty->display(HEADER_VIEW);
$smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
$smarty->display(FOOTER_VIEW);
