<?php
class UtilityManager
{

    function send_email($user_email, $user_subject, $user_message)
    {
        $mail             = new PHPMailer();

        //$body            = file_get_contents('contents.html');
        $body 			  = $user_message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username   = SUPPORT_EMAIL;  // GMAIL username
        $mail->Password   = SUPPORT_EMAIL_PASSWORD;            // GMAIL password
        $mail->SetFrom(SUPPORT_EMAIL, SUPPORT_EMAIL_NAME);
        $mail->AddReplyTo(SUPPORT_EMAIL,SUPPORT_EMAIL_NAME);
        $mail->Subject    = $user_subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($user_email,'AppRewarder User');
        $address = $user_email;//"##########";
        //$mail->AddAddress($address, "AppRewarder User");

        //$mail->AddAttachment("images/phpmailer.gif");      // attachment
        //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

        if(!$mail->Send()) {
            return false;//echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return true; //echo "Message sent!";
        }
    }


    function get_xml_value($element_name, $xml, $content_only = true) {
        if ($xml == false) {
            return false;
        }
        $found = preg_match('#<'.$element_name.'(?:\s+[^>]+)?>(.*?)'.
            '</'.$element_name.'>#s', $xml, $matches);
        if ($found != false) {
            if ($content_only) {
                return $matches[1];  //ignore the enclosing tags
            } else {
                return $matches[0];  //return the full pattern match
            }
        }
        // No match found: return false.
        return false;
    }

    public static function log($data,$filename='apprewarder.log')
    {
        //return true; //lets disable login for now
        //if(MODE == 'prod') return true; //lets not log in prod
        $content = stripslashes($data) . "\n";
        $fp = fopen(LOG_PATH . $filename,"a+");
        $output = date(DATE_RFC822) . ': ' . $content;
        fputs($fp, $output . "\n");
        fclose($fp);
    }


    public function get_friend_code ($integer, $base = FRIENDCODE_CHARS)
    {
        $length = strlen($base);
        while($integer > $length - 1)
        {
            $out = $base[fmod($integer, $length)] . $out;
            $integer = floor( $integer / $length );
        }
        return $base[$integer] . $out;
    }

    public static function time_ago($ptime) {
        $etime = time() - $ptime;

        if ($etime < 1) {
            return 'just now';
        }

        $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '');
            }
        }
    }

    public static function aasort (&$array, $key, $sortType=SORT_DESC) {
        if(empty($array)) return true;
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        if($sortType == SORT_DESC)
        {$array=array_reverse($ret,true);}
    }

    public static function is_email( $email ){
        return filter_var( $email, FILTER_VALIDATE_EMAIL );
    }


    public static function nonce_create($nonce_ttl=NONCE_TTL)
    {
        if (is_string(NONCE_KEY) == false || strlen(NONCE_KEY) < 10) {
           die();
        }
        $salt = self::nonce_generate_hash();
        $time = time();
        $maxTime = $time +$nonce_ttl;
        $nonce = $salt . "," . $maxTime . "," . sha1( $salt . NONCE_KEY . $maxTime );
        return $nonce;
    }

    public static function nonce_is_valid($nonce='')
    {
        if (is_string($nonce) == false) {
            return false;
        }
        $a = explode(',', $nonce);
        if (count($a) != 3) {
            return false;
        }
        $salt = $a[0];
        $maxTime = intval($a[1]);
        $hash = $a[2];
        $back = sha1( $salt . NONCE_KEY . $maxTime );
        if ($back != $hash) {
            return false;
        }
        if (time() > $maxTime) {
            return false;
        }
        return true;

    }

    private static function nonce_generate_hash()
    {
        $length = 10;
        $chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $ll = strlen($chars)-1;
        $o = '';
        while (strlen($o) < $length) {
            $o .= $chars[ rand(0, $ll) ];
        }
        return $o;
    }

    function encrypt($str)
    {
        //$key = $this->hex2bin($key);
        $iv = AR_IV;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, AR_KEY, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    function decrypt($code)
    {
        //$key = $this->hex2bin($key);
        $code = hex2bin($code);
        $iv = AR_IV;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, AR_KEY, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

    function hex2bin($hexdata)
    {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

}
?>