<?php
/* ==============================================================
 * Generate the temporary OTA (over the air) provisioning profile
 * so the device can provide the UDID to us
 * ==============================================================
 */
    //if they are not using an iOS device, die out
    //if(!$detect_type->is('iOS')) { print 'You must be on an iOS device to continue.';die();}
$userNonce = $_GET['n'];
switch (MODE)
{
    case 'local':

        $_SESSION['deviceIMEI'] = '12938009150498';
        $_SESSION['deviceModel'] = 'iPhone4,1';
        $_SESSION['deviceID'] = 'f1cf769a80f96b4723c420a17b10fe7f161b956f';
        $_SESSION['deviceVersion'] = '10A523';

        //print 'location: http://' . LOCAL_IP . '/register/?deviceID=' . $_SESSION['deviceID'] .'&deviceModel='. $_SESSION['deviceModel'].'&deviceIMEI=' . $_SESSION['deviceIMEI']. '&deviceVersion=' . $_SESSION['deviceVersion'];
        //die();

        //if(!UtilityManager::nonce_is_valid($userNonce) || !isset($userNonce)) {header('Location: http://' . API_HOST . '/error/403/'); exit(); } TODO:add this back in potentially if we want to test and enable provisioning profiles for web profiles
        header('Location: http://' . API_HOST . '/register/?deviceID=' . $_SESSION['deviceID'] .'&deviceModel='. $_SESSION['deviceModel'].'&deviceIMEI=' . $_SESSION['deviceIMEI']. '&deviceVersion=' . $_SESSION['deviceVersion'] .'&n=' . $userNonce);
    break;

    case 'prod':
        header('Content-type: application/x-apple-aspen-config; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . urlencode(PROVISION_FILENAME));
        $data = file_get_contents(API_PATH . '/AppRewarder.mobileconfig');
        print $data;
        die();
    break;

    default:
    case 'stage':
        header('Content-type: application/x-apple-aspen-config; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . urlencode(PROVISION_FILENAME));
        $APP_DOMAIN = constant('API_HOST');

print <<<EOT
        <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
        <plist version="1.0">
            <dict>
                <key>PayloadContent</key>
                <dict>
                    <key>URL</key>
                    <string>http://$APP_DOMAIN/register/redir/?n=$userNonce</string>
                    <key>DeviceAttributes</key>
                    <array>
                        <string>UDID</string>
                        <string>IMEI</string>
                        <string>ICCID</string>
                        <string>VERSION</string>
                        <string>PRODUCT</string>

                    </array>
                </dict>

                <key>PayloadOrganization</key>
                <string>$APP_DOMAIN</string>
                <key>PayloadDisplayName</key>
                <string>$APP_NAME</string>
                <key>PayloadVersion</key>
                <integer>1</integer>
                <key>PayloadUUID</key>
                <string>##########</string>
                <key>PayloadIdentifier</key>
                <string>com.$APP_DOMAIN</string>
                <key>PayloadRemovalDisallowed</key>
                <false/>
                <key>PayloadDescription</key>
                <string>$APP_NAME requires your device ID to verify app launches. Press install to continue, no actual changes are made to your device.</string>
                <key>PayloadType</key>
                <string>Profile Service</string>
            </dict>
        </plist>
EOT;
        die();
        break;
}

?>