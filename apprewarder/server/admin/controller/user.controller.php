<?php

$page_data = array(
    'page_title'=> 'User Accounts',
    'page_name' =>  $controller_name
);


switch($controller_function){


case 'update':

$values['user_credits'] = $_POST['value'];
$condition['user_id'] =$_POST['user_id'];
$results=$userInstance->db_update('user_account',$values,$condition);

// Provide feedback to the entry field
if (!$results) { echo "<html><body><font color=red size=3>" . $_POST['value'] . "</font></body></html>"; }
else          { echo "<html><body><font color=green size=3>" . $_POST['value'] . "</font></body></html>"; }
	exit;
	break;
}


$result=$userInstance->db_query($SQL_DATA['GET_USER_ACCOUNT_DATA']);
/*
$columns=array();
foreach($results[0] as $key => $value){ 

array_push($columns,$key);

};*/

$smarty->assign('page_data',$page_data);
$smarty->assign('result',$result);
//$smarty->assign('columns',$columns);
$smarty->display(HEADER_VIEW);
$smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
$smarty->display(FOOTER_VIEW);
//print('<pre>' . print_r($result,true) . '</pre>');