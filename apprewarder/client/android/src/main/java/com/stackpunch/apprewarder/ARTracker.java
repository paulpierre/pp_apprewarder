package com.stackpunch.apprewarder;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
//import com.mobileapptracker.Tracker;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.net.Uri;
import com.flurry.android.FlurryAgent;
import android.util.Log;

public class ARTracker extends BroadcastReceiver {
    public static final String PREFS_NAME = "##########";
    @Override
    public void onReceive(Context context, Intent intent) {

        //for people who share their referral URL
        SharedPreferences prefs =  context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        Bundle localBundle = intent.getExtras();
        String referrer = localBundle.getString("referrer");
        if(referrer.length() > 0 && referrer.toLowerCase().contains("referrer")) {
            Uri arUri = Uri.parse("http://a?" + referrer);//intent.getData();
            String source = arUri.getQueryParameter("utm_source");
            String referral_code = arUri.getQueryParameter("referrer");
            if(referral_code != null) {
                SharedPreferences.Editor e = prefs.edit();
                e.putString("referrer", referral_code);
                e.putString("referrer_source", source);
                e.putBoolean("didRefer",false);
                e.apply();
            }

            FlurryAgent.logEvent("referral_" + source);
        }


/*        BroadcastReceiver hasOffersReceiver = new Tracker();
        hasOffersReceiver.onReceive(context,intent);*/
        FlurryAgent.logEvent("referral_HO");

    }
}