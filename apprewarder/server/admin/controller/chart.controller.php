<?php

$showArea = false;

function transpose($array) {
    array_unshift($array, null);
    return call_user_func_array('array_map', $array);
}



if($controller_id == 'raw') $debug = true && print '<pre>'; else $debug = false;

    switch($controller_function)
{
        case 'system_monetization_total':
            $showArea = true;
            $query = $SQL_DATA['SYSTEM_MONETIZATION_TOTAL'];
            $r = $userInstance->db_query($query);

            break;

        case 'system_monetization_network':
            $showArea = true;
            $query = $SQL_DATA['SYSTEM_MONETIZATION_NETWORK'];
            $r = $userInstance->db_query($query);
            $last_date = '';$i = -1;
            foreach($r as $item)
            {
                if($last_date == $item['date'])
                {
                    $d[$i][$item['offer_network']]=$item['Payout'];
                } else {

                    $d[$i+1] = array(
                        'date'=>$item['date'],
                        'ksix'=>0.00,
                        'hasoffers'=>0.00,
                        'aarki'=>0.00,
                        'adscend'=>0.00,
                        'adaction'=>0.00,
                        'fyber'=>0.00
                    );
                    $i++;

                    $d[$i][$item['offer_network']] = $item['Payout'];
                    $last_date = $item['date'];

                }
            }
            $r = $d;
            break;

        case 'system_monetization_margin':
            $showArea = true;
            $query = $SQL_DATA['SYSTEM_MONETIZATION_MARGIN'];
            $r = $userInstance->db_query($query);

            break;

        case 'system_user_activity_dau_platform':
            //unset($_SESSION);
            $showArea = true;
            $analyticsInstance = new Analytics();
            $analyticsInstance->startDate = date('Y-m-d',time() - 86400 * 30); //last 30 days

            $dau_ios = $analyticsInstance->DAUiOS();
            $dau_android = $analyticsInstance->DAUAndroid();

            //exit('<pre> ios: ' . print_r($dau_ios,true) . PHP_EOL . ' android:' . print_r($dau_android,true) .'</pre>');

            foreach($dau_ios as $key=>$item){$d[$key]['iOS'] = $item;}
            foreach($dau_android as $key=>$item){$d[$key]['Android'] = $item;}
            ksort($d);
            foreach($d as $item=>$key)
            {
                $r[] = array(
                    'date'=> $item,
                    'iOS'=> $key['iOS'],
                    'Android'=> $key['Android']
                );
            }
            //exit('<pre> r: ' . print_r($r,true));
            break;

        case 'system_user_activity_new_users_platform':
            //unset($_SESSION);
            $showArea = true;
            $analyticsInstance = new Analytics();
            $analyticsInstance->startDate = date('Y-m-d',time() - 86400 * 30); //last 30 days

            $dau_ios = $analyticsInstance->newUsersiOS();
            $dau_android = $analyticsInstance->newUsersAndroid();

            //exit('<pre> ios: ' . print_r($dau_ios,true) . PHP_EOL . ' android:' . print_r($dau_android,true) .'</pre>');

            foreach($dau_ios as $key=>$item){$d[$key]['iOS'] = $item;}
            foreach($dau_android as $key=>$item){$d[$key]['Android'] = $item;}
            ksort($d);
            foreach($d as $item=>$key)
            {
                $r[] = array(
                    'date'=> $item,
                    'iOS'=> $key['iOS'],
                    'Android'=> $key['Android']
                );
            }
            //exit('<pre> r: ' . print_r($r,true));
            break;

        case 'system_user_activity_all':
            //unset($_SESSION);

            $analyticsInstance = new Analytics();
            $analyticsInstance->startDate = date('Y-m-d',time() - 86400 * 30); //last 30 days

            $flurryDAU = $analyticsInstance->DAU();
            $flurryNewUsers = $analyticsInstance->newUsers();

            //exit('<pre> flurryDAU: ' . print_r($flurryDAU,true) . PHP_EOL . ' flurryNewUsers:' . print_r($flurryNewUsers,true) .'</pre>');

            foreach($flurryDAU as $key=>$item){$d[$key]['DAU'] = $item;}
            foreach($flurryNewUsers as $key=>$item){$d[$key]['NewUser'] = $item;}
            foreach($d as $item=>$key)
            {
                $r[] = array(
                    'date'=> $item,
                    'DAU'=>  isset($key['DAU'])?$key['DAU']:0,
                    'NewUser'=> isset($key['NewUser'])?$key['NewUser']:0
                );
            }
            //exit('<pre> r: ' . print_r($r,true));
            break;

        case 'system_user_activity_device_os':
            break;


        default:
            $result = 'Unfortunately that report does not exist';
        break;
    }


/*
 *  RUN THE QUERY
 */
if(isset($query) || isset($r))
{
    $d = transpose($r);
    $k = array_keys($r[0]);
    $dates =$d[0];
    unset($d[0],$k[0]);
    $keys = array_values($k);
    $data = array_values($d);

    $result ='[';

    for($a=0;$a<(count($keys));$a++)
    {
        $result .='{'.(($showArea)?'"area":true,':'').'"key":"' . $keys[$a] .'","values":[';
        for($b=0;$b<(count($dates));$b++)
        {
            $result .= '[' . strtotime(($dates[$b])) . '000,' . (isset($data[$a][$b])?$data[$a][$b]:0) . ']' .(($b<(count($data[0])-1))?',':'');
            if($debug) print "a:$a,b:$b val:" . $data[$a][$b] . PHP_EOL;
        }
        $result .= ']}' .( ($a<count($keys)-1)?',':'');
    }
    $result .=']';
}
if($debug) print '<h1>Query:</h1>' .$query . PHP_EOL .'<h1>Keys</h1>' . print_r($keys,true) . PHP_EOL .'<h1>Dates</h1>'. print_r($dates,true) .'<h1>Database</h1>' . print_r($r,true) . PHP_EOL . '<h1>JSON:</h1>';
exit($result);

?>