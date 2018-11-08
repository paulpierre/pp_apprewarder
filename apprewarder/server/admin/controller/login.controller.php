<?php

$page_data = array(
    'page_title'=> 'Login',
    'page_name' =>  $controller_name,
    'USER_IP'=> $_SERVER['REMOTE_ADDR']
);

$smarty->assign('page_data',$page_data);
$smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
$smarty->display(FOOTER_VIEW);

?>