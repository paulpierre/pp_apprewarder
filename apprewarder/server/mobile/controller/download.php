<?php
$APP_DOMAIN = API_HOST;//constant('API_HOST');
$IPA_FILE = (MODE == 'prod')?'apprewarder.ipa':'apprewarder_stage.ipa';
if($detect_type->isiOS())
{
    switch($controllerFunction)
    {
        case 'apprewarder_stage.ipa':
        case 'apprewarder.ipa':
            $file = $controllerFunction;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit();
        break;

        case 'appicon_large.png':
        case 'appicon_small.png':
            header('Content-type: image/png');
            $file = file_get_contents($controllerFunction, true);
            print $file;
            fclose($file);
            exit();
        break;

        case 'apprewarder.plist':
            header('Content-type: text/xml');
print <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>items</key>
	<array>
		<dict>
			<key>assets</key>
			<array>
				<dict>
					<key>kind</key>
					<string>software-package</string>
					<key>url</key>
					<string>http://$APP_DOMAIN/download/$IPA_FILE</string>
				</dict>
				<dict>
					<key>kind</key>
					<string>full-size-image</string>
					<key>needs-shine</key>
					<false/>
					<key>url</key>
					<string>http://$APP_DOMAIN/download/appicon_large.png</string>
				</dict>
				<dict>
					<key>kind</key>
					<string>display-image</string>
					<key>needs-shine</key>
					<false/>
					<key>url</key>
					<string>http://$APP_DOMAIN/download/appicon_small.png</string>
				</dict>
			</array>
			<key>metadata</key>
			<dict>
				<key>bundle-identifier</key>
				<string>##########</string>
				<key>bundle-version</key>
				<string>1.0</string>
				<key>kind</key>
				<string>software</string>
				<key>subtitle</key>
				<string>for iOS</string>
				<key>title</key>
				<string>AppRewarder</string>
			</dict>
		</dict>
	</array>
</dict>
</plist>
EOT;

            exit();
        break;

        default:
            header('Location: itms-services://?action=download-manifest&url=https://'.$APP_DOMAIN.'/download/apprewarder.plist');
            exit();
        break;
    }

}

?>