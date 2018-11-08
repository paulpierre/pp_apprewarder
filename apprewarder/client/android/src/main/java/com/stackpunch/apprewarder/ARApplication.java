package com.stackpunch.apprewarder;
import android.app.Application;
/*import com.parse.Parse;
import com.parse.ParseACL;
import com.parse.ParseInstallation;
import com.parse.PushService;*/

/**
 * Created by paulpierre on 6/12/14.
 */

public class ARApplication extends Application {
    @Override
    public void onCreate() {
        super.onCreate();
/*

        Parse.initialize(this, "##########", "##########");
        PushService.setDefaultPushCallback(this, MainActivity.class);

        ParseInstallation.getCurrentInstallation().getInstallationId();
        ParseInstallation.getCurrentInstallation().saveInBackground();
        ParseACL defaultACL = new ParseACL();
        defaultACL.setPublicReadAccess(true);
        ParseACL.setDefaultACL(defaultACL, true);

*/


    }
}
