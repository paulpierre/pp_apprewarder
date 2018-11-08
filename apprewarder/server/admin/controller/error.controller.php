<?php

$page_data = array(
    'page_title'        =>  'Oops! Could not find what you were looking for.',
    'error_message'     =>  'Sorry, the page "' . $controller_name .'" you are looking for does not exist',
    'page_name'         =>  $controller_name
);

$smarty->assign('page_data',$page_data);
$smarty->assign('error_message',$error_message);
$smarty->display(HEADER_VIEW);
$smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
$smarty->display(FOOTER_VIEW);
