<?php

$page_data = array(
    'page_title'=> 'Search FriendCode for Call Backs',
    'page_name' =>  $controller_name
);


switch($controller_function){

case 'submitfriendcode':

	$replacement = $_GET['friendcode'];
	$pattern = '/XXFRIENDCODEXX/';
	$sql=$SQL_DATA['SEARCH_USER_FRIENDCODE'];
	$sql= preg_replace($pattern,$replacement,$sql,-1);
	$results=$userInstance->db_query($sql);

	$columns=array();

	if($results){
	foreach($results[0] as $key => $value){ 
		array_push($columns,$key);
	};

	$smarty->assign('page_data',$page_data);
	$smarty->assign('results',$results);
	$smarty->assign('columns',$columns);

	}else{
		$results[0]='No Results';	
		$smarty->assign('results',$results);
	};
	break;
}


$smarty->display(HEADER_VIEW);
$smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
$smarty->display(FOOTER_VIEW);
