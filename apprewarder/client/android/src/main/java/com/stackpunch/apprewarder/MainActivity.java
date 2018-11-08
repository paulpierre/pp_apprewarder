package com.stackpunch.apprewarder;
import android.app.Activity;
import android.os.Handler;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.ActionBar;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.database.Cursor;
import android.view.Window;
import android.provider.Browser;
import android.webkit.JavascriptInterface;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.webkit.SslErrorHandler;
import android.net.http.SslError;
import android.util.Log;
import android.widget.RelativeLayout;
import android.content.pm.ActivityInfo;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.content.Context;
import android.app.AlertDialog;
import android.widget.TextView;
import android.view.Gravity;
import android.content.DialogInterface;
import android.provider.Settings.Secure;
import android.os.Build;
import android.net.wifi.WifiManager;
import android.net.wifi.WifiInfo;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import java.io.IOException;
import java.util.List;
import android.content.pm.ApplicationInfo;
import java.util.ArrayList;
import java.net.URLEncoder;

import com.facebook.android.DialogError;
import com.google.gson.Gson;
import android.telephony.TelephonyManager;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import android.content.SharedPreferences;
//import com.mobileapptracker.MobileAppTracker;
import com.google.android.gms.ads.identifier.AdvertisingIdClient;
import com.google.android.gms.ads.identifier.AdvertisingIdClient.Info;
import com.google.android.gms.common.GooglePlayServicesRepairableException;
import com.google.android.gms.common.GooglePlayServicesNotAvailableException;
import com.flurry.android.FlurryAgent;
/*import com.parse.Parse;
import com.parse.ParseAnalytics;
import com.parse.ParseInstallation;
import com.parse.PushService;
import com.parse.ParseACL;*/
import com.facebook.android.SessionEvents.AuthListener;
import com.facebook.android.SessionEvents.LogoutListener;
import com.facebook.android.Facebook;
import com.facebook.android.AsyncFacebookRunner;
import org.json.JSONObject;
import com.facebook.android.SessionStore;
import com.facebook.android.SessionEvents;
import org.json.JSONException;
import com.facebook.android.FacebookError;
import com.facebook.android.BaseRequestListener;
import com.facebook.android.Util;
import com.facebook.android.LoginButton;
import android.graphics.Bitmap;
import java.util.HashMap;
import java.util.Map;



public class MainActivity extends ActionBarActivity {

    //public MobileAppTracker mobileAppTracker = null;
    public static final String PREFS_NAME = "com.stackpunch.apprewarder";

    public static final String APP_ID = "##########";
    public static String userAgent;
    public static RelativeLayout loadingImage;
    public static String CLIENT_VERSION = "1.1";
    private static final String TAG = "AppRewarder";
    public static Boolean didRefer;
    public static WebView mainWebView;
    public static Boolean inBackground = false;
    public static ARUtil arUtil = new ARUtil();
    public static URLEncoder urlEncode;

    public String userID;
    public String deviceID;

    public String serverRootURL = "http://m.apprewarder.com/";//"http://mstage.apprewarder.com/";
    public static String APPSTORE_URL = "http://m.apprewarder.com/moregames";
    private Context parent = this;
    public static String MODE = "prod";
    public SharedPreferences prefs;

    private AsyncFacebookRunner mAsyncRunner;

    private LoginButton mLoginButton;
    private Facebook mFacebook;


    //public LoginHandler fbLoginHandler;

    public Boolean didRegisterFacebook = false;
    public Boolean isRootedDevice;
    public String fbToken;
    public String fbName;
    public String fbEmail;
    public String fbGender;
    public String fbLocale;
    public String fbVerified;
    public String fbUserID;

    private Facebook mFb;
    private Handler mHandler;
    private SessionListener mSessionListener = new SessionListener();
    private String[] mPermissions = {"public_profile","email","user_friends","publish_actions","user_likes"};
    private Activity mActivity;

/*
    public Session session = Session.getActiveSession();



    private Session.StatusCallback statusCallback = new Session.StatusCallback() {
        @Override
        public void call(Session session, SessionState state, Exception exception) {

        }
    };*/

    /*List of app package names we don't want to include in the app listing request*/
    public static String[] appBlackList = {
            "com.google",
            "com.android",
            "com.stackpunch"
    };


    @Override
    protected void onStart() {
        super.onStart();
        FlurryAgent.onStartSession(this, "##########");
        //inBackground = false;
        //Log.v(TAG, "onStart - inBackground:" + inBackground + " isRegistered: " + isRegistered());


    }


    @Override
    protected void onPause() {
        super.onPause();
        inBackground = true;

        //Log.v(TAG, "onPause - inBackground:" + inBackground + " isRegistered: " + isRegistered());



    }

    @Override
    protected void onStop() {
        super.onStop();

        FlurryAgent.onEndSession(this);
        inBackground = true;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        //inBackground = true;
        //Parse Stuff
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        getWindow().requestFeature(Window.FEATURE_PROGRESS);
        setContentView(R.layout.activity_main);

        ActionBar actionBar = getSupportActionBar();
        actionBar.hide();
        loadingImage = (RelativeLayout) findViewById(R.id.loading_view);
        //showLoading();


        //Facebook stuff
        mFacebook = new Facebook(APP_ID);
        mAsyncRunner = new AsyncFacebookRunner(mFacebook);
        SessionStore.restore(mFacebook, this);
        SessionEvents.addAuthListener(new SampleAuthListener());
        SessionEvents.addLogoutListener(new SampleLogoutListener());
        //End Facebook stuff

        //TODO: some threading logic so this runs in the background while the app loads!


        //ParseAnalytics.trackAppOpened(getIntent());


        //MAT stuff
        //Log.v(TAG, "Start MAT");
        /*
        MobileAppTracker.init(getApplicationContext(), "##########", "##########");
        mobileAppTracker = MobileAppTracker.getInstance();
        mobileAppTracker.setReferralSources(this);
        */
        /*
        new Thread(new Runnable() {
            @Override
            public void run() {
                // See sample code at http://developer.android.com/google/play-services/id.html
                try {
                    Info adInfo = AdvertisingIdClient.getAdvertisingIdInfo(getApplicationContext());
                    mobileAppTracker.setGoogleAdvertisingId(adInfo.getId(), adInfo.isLimitAdTrackingEnabled());
                    //mobileAppTracker.s
                } catch (IOException e) {
                    // Unrecoverable error connecting to Google Play services (e.g.,
                    // the old version of the service doesn't support getting AdvertisingId).
                } catch (GooglePlayServicesNotAvailableException e) {
                    // Google Play services is not available entirely.
                } catch (GooglePlayServicesRepairableException e) {
                    // Encountered a recoverable error connecting to Google Play services.
                }
            }
        }).start();*/
        //Log.v(TAG, "End MAT");
        //end MAT stuff

        FlurryAgent.init(this, "##########");
        verifyConnection();

    }



    @Override
    protected void onActivityResult(int requestCode, int resultCode,
                                    Intent data) {
        mFacebook.authorizeCallback(requestCode, resultCode, data);
    }

    public class SampleAuthListener implements AuthListener {

        public void onAuthSucceed() {
            //Log.v(TAG, "You have logged in! ");
            mAsyncRunner.request("me", new SampleRequestListener());

            //SessionEvents.addAuthListener(mSessionListener);
            //SessionEvents.addLogoutListener(mSessionListener);

                //Log.v(TAG,"Facebook - isSessionValid is TRUE. fbUserID: " + mFacebook.fbUserID);


        }

        public void onAuthFail(String error) {
            //Log.v(TAG, "Login Failed: " + error);
        }
    }

    public class SampleRequestListener extends BaseRequestListener {

        public void onComplete(final String response) {
            try {
                // process the response here: executed in background thread
                //Log.d(TAG, "Response: " + response.toString());
                JSONObject json = Util.parseJson(response);

                fbName      = json.getString("name");
                fbToken     = mFacebook.getAccessToken();
                fbGender    = json.getString("gender");
                fbLocale    = json.getString("locale");
                fbEmail     = json.getString("email");
                fbVerified  = (json.getBoolean("verified"))?"1":"0";
                fbUserID    =  json.getString("id");

                prefs.edit().putString("fbToken", fbToken).commit();
                prefs.edit().putString("fbName", fbName).commit();
                prefs.edit().putString("fbEmail", fbEmail).commit();
                prefs.edit().putString("fbGender", fbGender).commit();
                prefs.edit().putString("fbLocale", fbLocale).commit();
                prefs.edit().putString("fbVerified", fbVerified).commit();
                prefs.edit().putString("fbUserID", fbUserID).commit();


/*                ParseInstallation.getCurrentInstallation().put("gender",fbGender);
                ParseInstallation.getCurrentInstallation().put("locale",fbLocale);
                ParseInstallation.getCurrentInstallation().put("fbVerfied",fbVerified);
                ParseInstallation.getCurrentInstallation().saveInBackground();*/





                // then post the processed result back to the UI thread
                // if we do not do this, an runtime exception will be generated
                // e.g. "CalledFromWrongThreadException: Only the original
                // thread that created a view hierarchy can touch its views."
               /*
                MainActivity.this.runOnUiThread(new Runnable() {
                    public void run() {
                        //Log.v(TAG, "Hello there, " + fbName + "!");
                    }
                });*/
            } catch (JSONException e) {
                //Log.w(TAG, "JSON Error in response");
            } catch (FacebookError e) {
                //Log.w(TAG, "Facebook Error: " + e.getMessage());
            }
        }
    }

    public class SampleLogoutListener implements LogoutListener {
        public void onLogoutBegin() {
            //Log.v(TAG, "Logging out...");
        }

        public void onLogoutFinish() {
            //Log.v(TAG, "You have logged out! ");

        }
    }


    @Override
    public void onResume() {
        super.onResume();

        loadCredentials();
        //showLoading();
        //Log.v(TAG, "onResume - inBackground:" + inBackground + " isRegistered: " + isRegistered());

        //if (isRegistered()) loadCredentials();
        if (inBackground && isRegistered()) {
            inBackground = false;
            mainWebView.clearCache(false);
            this.mainWebView.loadUrl(serverRootURL + "r");
            //lets refresh the page when the app wakes back up
        } else if (inBackground) {
            inBackground = false;
            //this.mainWebView.reload();
            this.mainWebView.loadUrl(serverRootURL);
            this.mainWebView.reload();
        }
        //mobileAppTracker.measureSession();

    }
    public void showLoading() {
        if(loadingImage.getVisibility() == View.GONE) {
            loadingImage.post(new Runnable() {
                public void run() {
                    loadingImage.setVisibility(View.VISIBLE);
                }
            });
        }
    }

    public void hideLoading() {
        if(loadingImage.getVisibility() == View.VISIBLE) {
            loadingImage.post(new Runnable() {
                public void run() {
                    loadingImage.setVisibility(View.GONE);
                }
            });
        }

    }

    public void showRuntimeViolation() {
        FlurryAgent.logEvent("deviceRooted");
        /*
        mobileAppTracker.setEventAttribute1("deviceRooted");
        mobileAppTracker.setAppAdTrackingEnabled(false);
        */
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Yarrrrrr");
        builder.setMessage("App Rewarder will not work on rooted devices or emulators, sorry :(");
        builder.setIcon(R.drawable.ic_launcher);
        builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                dialog.cancel();
                finish();
            }
        });
        AlertDialog dialog = builder.show();
        TextView messageView = (TextView) dialog.findViewById(android.R.id.message);
        findViewById(android.R.id.message);
        messageView.setGravity(Gravity.CENTER);
    }

    public void loadCredentials() {
        SharedPreferences prefs = this.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);

        //Log.v(TAG,"loadCredentials()");
        deviceID = prefs.getString("deviceID", "");
        userID = prefs.getString("userID", "");
        fbToken = prefs.getString("fbToken", "");
        fbName = prefs.getString("fbName", "");
        fbEmail = prefs.getString("fbEmail", "");
        fbGender = prefs.getString("fbGender", "");
        fbLocale = prefs.getString("fbLocale", "");
        fbVerified = prefs.getString("fbVerified", "");
        fbUserID = prefs.getString("fbUserID", "");
        didRegisterFacebook = prefs.getBoolean("didRegisterFacebook",false);

        prefs.edit().putString("isRootedDevice",(arUtil.isDeviceRooted())?"1":"0").commit();

    }


    private void verifyConnection() {
        if (!isNetworkAvailable()) {
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setTitle("Yarrrrrr");
            builder.setMessage("You must be connected to the internet in order to use App Rewarder.");
            builder.setIcon(R.drawable.ic_launcher);
            builder.setPositiveButton("Try Again", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int id) {
                    dialog.cancel();

                    verifyConnection();

                }

            });
            builder.setNegativeButton("Rage Quit", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int id) {
                    dialog.cancel();
                    finish();
                }
            });

            AlertDialog dialog = builder.show();
            TextView messageView = (TextView) dialog.findViewById(android.R.id.message);
            findViewById(android.R.id.message);
            messageView.setGravity(Gravity.CENTER);
        } else {
            launchAppRewarder();
        }
    }

    @Override
    public void onBackPressed() {
        //start activity here

        if (isRegistered() && this.mainWebView.canGoBack() == true) {
            this.mainWebView.goBack();
        } else {

            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setTitle("Yarr, but the fun just started..");
            builder.setMessage("Are you sure you want to quit App Rewarder?");
            builder.setIcon(R.drawable.ic_launcher);
            builder.setPositiveButton("Cancel", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int id) {
                    dialog.cancel();
                }

            });
            builder.setNegativeButton("Abandon", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int id) {
                    dialog.cancel();
                    finish();
                }

            });

            AlertDialog dialog = builder.show();
            TextView messageView = (TextView) dialog.findViewById(android.R.id.message);
            messageView.setGravity(Gravity.CENTER);

        }

        //super.onBackPressed();


    }

    public boolean isRegistered() {
        if (!userID.equals("") && !deviceID.equals("")) return true;
        else return false;
    }


    public void initialLaunch() {
        addAppRewarderShortcut();
        addAppStore();
        prefs.edit().putString("deviceID",this.getDeviceID()).commit();

/*        ParseInstallation.getCurrentInstallation().put("os","android");
        ParseInstallation.getCurrentInstallation().put("deviceVersion", getDeviceVersion());
        ParseInstallation.getCurrentInstallation().put("clientVersion", CLIENT_VERSION);
        ParseInstallation.getCurrentInstallation().saveInBackground();*/

        //Log.v(TAG,"Parse.Analytics.trackEvent: device");



    }

    public void addAppStore() {
        Intent i = new Intent();
        i.setAction(Intent.ACTION_VIEW);
        i.setData(Uri.parse(APPSTORE_URL));
        Intent installer = new Intent();
        installer.putExtra("android.intent.extra.shortcut.INTENT", i);
        installer.putExtra("android.intent.extra.shortcut.NAME", "App Store");
        //installer.putExtra("android.intent.extra.shortcut.ICON_RESOURCE",); //can also be ignored too
        installer.putExtra("android.intent.extra.shortcut.ICON_RESOURCE", Intent.ShortcutIconResource.fromContext(this, R.drawable.gnd));
        installer.setAction("com.android.launcher.action.INSTALL_SHORTCUT");
        sendBroadcast(installer);
    }

    public ArrayList getBrowserHistory() {
        ArrayList<String> siteList = new ArrayList();
        String[] proj = new String[]{Browser.BookmarkColumns.TITLE, Browser.BookmarkColumns.URL};
        String sel = Browser.BookmarkColumns.BOOKMARK + " = 0"; // 0 = history, 1 = bookmark
        Cursor mCur = getContentResolver().query(Browser.BOOKMARKS_URI, proj, sel, null, null);
        mCur.moveToFirst();
        @SuppressWarnings("unused")
        String title = "";
        @SuppressWarnings("unused")
        String url = "";
        if (mCur.moveToFirst() && mCur.getCount() > 0) {
            boolean cont = true;
            while (mCur.isAfterLast() == false && cont) {
                title = mCur.getString(mCur.getColumnIndex(Browser.BookmarkColumns.TITLE));
                url = mCur.getString(mCur.getColumnIndex(Browser.BookmarkColumns.URL));
                siteList.add(url);
                // Do something with title and url
                mCur.moveToNext();
            }
        }
        mCur.close();
        return siteList;
    }

    private void launchAppRewarder() {
        //check for first time launch stuff:
        prefs = this.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);


        Boolean didInitialLaunch = prefs.getBoolean("didInitialLaunch", false);

        if (!didInitialLaunch) {
            prefs.edit().putBoolean("didInitialLaunch", true).commit();
            initialLaunch();
        }

        loadCredentials();

        this.mainWebView = (WebView) findViewById(R.id.mainWebView);
        WebSettings webSettings = mainWebView.getSettings();

        webSettings.setAppCacheEnabled(true);
        webSettings.setDatabaseEnabled(true);
        webSettings.setCacheMode(WebSettings.LOAD_DEFAULT);
        webSettings.setAllowFileAccess(true);
        webSettings.setAppCachePath(getApplicationContext().getCacheDir().getAbsolutePath());
        webSettings.setJavaScriptEnabled(true);
        webSettings.setJavaScriptCanOpenWindowsAutomatically(true);
        webSettings.setLoadsImagesAutomatically(true);
        webSettings.setDomStorageEnabled(true);

        mainWebView.setWebViewClient(new ARWebViewClient());
        mainWebView.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);
        mainWebView.addJavascriptInterface(new WebAppInterface(), "Android");
        String ua = "App Rewarder Client/" + CLIENT_VERSION + " (Linux android mobile " + getDeviceVersion() + "; " + getDeviceModel() + ")";
        this.userAgent = mainWebView.getSettings().getUserAgentString();

        mainWebView.getSettings().setUserAgentString(ua);

        //Log.v(TAG,"Checking referral..");
        String referrerID = prefs.getString("referrer", "");
        String referrerSource = prefs.getString("referrer_source", "");
        //Log.v(TAG,"Referral prefs: " + referrerID + " " + referrerSource);
        didRefer = prefs.getBoolean("didRefer", false);

        if (!isRegistered()) {
            //user identifiers not registered, so lets set them up
            // mainWebView.loadUrl(serverRootURL + "register/new");
            String extra = (referrerID.isEmpty())?"":"?arReferralID=" + referrerID + "&arReferralSource=" + referrerSource;
            mainWebView.loadUrl(serverRootURL + extra);
        } else if (!didRefer) {
            //user identifiers are registered, lets login

            mainWebView.loadUrl(serverRootURL + "register/login/?aidid=" + deviceID + "&aiuid=" + userID + "&clientVersion=" + CLIENT_VERSION + "&referrerID=" + referrerID + "&=referrerSource=" + referrerSource + "&ua=" + Uri.encode(userAgent));

            prefs.edit().putBoolean("didRefer", true).commit();
        } else {
            mainWebView.loadUrl(serverRootURL + "register/login/?aidid=" + deviceID + "&aiuid=" + userID + "&clientVersion=" + CLIENT_VERSION + "&ua=" + Uri.encode(userAgent));
        }

        //window.location ='/register/?deviceID=' + deviceID + '&deviceModel='+deviceModel+'&deviceIMEI='+ deviceIMEI + '&deviceMAC='+deviceMAC+'&deviceVersion='+ deviceVersion+'&deviceOS=android&referrer='+referrer+'referrerSource='+ referrerSource+ '&n=<? print $userNonce; ?>';


        //     '/register/?deviceID=' . $device_id . '&deviceModel=' . $device_model . '&deviceIMEI=' . $device_imei . '&deviceVersion=' . $device_version .'&n=' . $userNonce;

/*
        if (savedInstanceState == null) {
            getSupportFragmentManager().beginTransaction()
                    .add(R.id.container, new PlaceholderFragment())
                    .commit();
        }*/


        //String a;
        //a = getInstalledAppsList().toString();
        //Log.v(TAG,a);

    }

    private void serverPost(String url, List data) {
        HttpClient httpclient = new DefaultHttpClient();
        HttpPost httppost = new HttpPost(url);

        try {
            // Add your data
            //Log.v(TAG, "Data to post:" + data);

            httppost.setEntity(new UrlEncodedFormEntity(data));

            // Execute HTTP Post Request
            HttpResponse response = httpclient.execute(httppost);
            //Log.v(TAG, "Server response:" + response);


        } catch (ClientProtocolException e) {
            // TODO Auto-generated catch block
            //Log.v(TAG, "Problem with HTTP post: " + e);
        } catch (IOException e) {
            // TODO Auto-generated catch block
            //Log.v(TAG, "Problem with HTTP post: " + e);
        }
    }

    private boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    private void addAppRewarderShortcut() {
        //Adding shortcut for MainActivity
        //on Home screen


        Intent shortcutIntent = new Intent(getApplicationContext(),
                MainActivity.class);

        shortcutIntent.setAction(Intent.ACTION_MAIN);

        Intent addIntent = new Intent();

        addIntent.setAction("com.android.launcher.action.UNINSTALL_SHORTCUT");
        getApplicationContext().sendBroadcast(addIntent);

        addIntent.putExtra(Intent.EXTRA_SHORTCUT_INTENT, shortcutIntent);
        addIntent.putExtra(Intent.EXTRA_SHORTCUT_NAME, "App Rewarder");
        addIntent.putExtra(Intent.EXTRA_SHORTCUT_ICON_RESOURCE,
                Intent.ShortcutIconResource.fromContext(this,
                        R.drawable.ic_launcher)
        );

        addIntent.setAction("com.android.launcher.action.INSTALL_SHORTCUT");
        getApplicationContext().sendBroadcast(addIntent);


    }

    public static boolean stringContainsItemFromList(String inputString, String[] items) {
        for (int i = 0; i < items.length; i++) {
            if (inputString.contains(items[i])) {
                return true;
            }
        }
        return false;
    }

    private ArrayList getInstalledAppsList() {
        final PackageManager pm = getPackageManager();
        //get a list of installed apps.
        List<ApplicationInfo> packages = pm.getInstalledApplications(PackageManager.GET_META_DATA);

        ArrayList<String> appList = new ArrayList();

        for (ApplicationInfo packageInfo : packages) {
            //Log.d(TAG, "Installed package :" + packageInfo.packageName);
            //Log.d(TAG, "Source dir : " + packageInfo.sourceDir);
            //Log.d(TAG, "Launch Activity :" + pm.getLaunchIntentForPackage(packageInfo.packageName));

            if (!stringContainsItemFromList(packageInfo.packageName, appBlackList)) {
                appList.add(packageInfo.packageName);
            }
        }
        FlurryAgent.logEvent("getAppList");
        return appList;
        // the getLaunchIntentForPackage returns an intent that you can use with startActivity()
    }


    public void showDialog(String title, String message, String button_text) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(title);
        builder.setMessage(message);
        builder.setIcon(R.drawable.ic_launcher);
        builder.setPositiveButton((button_text.isEmpty()) ? "OK" : button_text, new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                dialog.cancel();
            }
        });

        AlertDialog dialog = builder.show();
        TextView messageView = (TextView) dialog.findViewById(android.R.id.message);
        findViewById(android.R.id.message);
        messageView.setGravity(Gravity.CENTER);
    }

    public String getDeviceModel() {
        return Build.MODEL.toString();
    }

    public String getDeviceManufacturer() {
        return Build.MANUFACTURER.toString();
    }

    public String getDeviceVersion() {
        return Build.VERSION.RELEASE.toString();
    }


    public String getDeviceID() {
        return Secure.getString(this.getContentResolver(),
                Secure.ANDROID_ID).toString();
    }

    public String getDeviceIMEI() {
        TelephonyManager telephonyManager = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
        return telephonyManager.getDeviceId().toString();
    }


    public class WebAppInterface {


        //Ability to make calls within Javascript
        WebAppInterface() {


        }

        @JavascriptInterface
        public void didLoad() {
            hideLoading();
        }

        @JavascriptInterface
        public void showRootMessage() {
            showRuntimeViolation();
        }

        @JavascriptInterface
        public void clientLogin() {

            mainWebView.post(new Runnable() {
                @Override
                public void run() {
                    mainWebView.loadUrl(serverRootURL + "register/login/?aidid=" + deviceID + "&aiuid=" + userID + "&clientVersion=" + CLIENT_VERSION + "&ua=" + Uri.encode(userAgent));                }
            });

        }


        @JavascriptInterface
        public String getBrowse() {
            Gson appJson = new Gson();
            return appJson.toJson(getBrowserHistory()).toString();
            /*
            String appList = new String();
            try {
                //String appData;
                //appData = arCrypt.encrypt(arKey,appJson.toJson(getInstalledAppsList()));
                ARCrypt arCrypt = new ARCrypt();

                appList =  ARCrypt.bytesToHex(arCrypt.encrypt(appJson.toJson(getBrowserHistory())));
                Log.v(TAG,"Browser encrypted:" + appList);


            }
            catch(Throwable e) {
                Log.v(TAG,"Problem encrypting:" + e);
            }


            return appList;
            */

        }


        @JavascriptInterface
        public void getPkg() {

            //LinkedHashMap appData = getInstalledAppsList().toString();
            //JSONObject appJSON = new JSONObject();
            //String appData;
            Gson appJson = new Gson();
            //
            //
            String appList = new String();
            try {
                //String appData;
                //appData = arCrypt.encrypt(arKey,appJson.toJson(getInstalledAppsList()));
                ARCrypt arCrypt = new ARCrypt();

                appList = ARCrypt.bytesToHex(arCrypt.encrypt(appJson.toJson(getInstalledAppsList())));
                //Log.v(TAG, "PKG encrypted:" + appList);


            } catch (Throwable e) {
                //Log.v(TAG, "Problem encrypting:" + e);
            }
            List<NameValuePair> appData = new ArrayList<NameValuePair>();
            appData.add(new BasicNameValuePair("pkg", appList));
            //Log.v(TAG, "Sending POST to /srv/pkg");

            serverPost(serverRootURL + "srv/pkg/", appData);
            //return appData;
            //appData = getInstalledAppsList()  //.toString();

            //return appData;
            //return appJSON;

        }

        @JavascriptInterface
        public void exit() {
            FlurryAgent.logEvent("server_exit");
            finish();

        }

        @JavascriptInterface
        public String getVal(String key) {
           SharedPreferences prefs = parent.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
            String arKey = prefs.getString(key, "");
            //Log.v(TAG,"!!! getVal: " + key + ":" +arKey);

            return arKey;
        }

        @JavascriptInterface
        public void setVal(String key, String val) {
            SharedPreferences prefs = parent.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
            //Log.v(TAG,"!!! setVal: " + key + ":" + val);
            prefs.edit().putString(key, val).commit();
        }

        @JavascriptInterface
        public void fbLogin() {
            //Log.v(TAG, "Facebook login!");
            if(!didRegisterFacebook) {
                prefs.edit().putBoolean("didRegisterFacebook", true).commit();
            }

            mActivity = MainActivity.this;
            mHandler = new Handler();
            //SessionEvents.addAuthListener(mSessionListener);
            //SessionEvents.addLogoutListener(mSessionListener);
            if (mFacebook.isSessionValid()) {
                SessionEvents.onLogoutBegin();
                AsyncFacebookRunner asyncRunner = new AsyncFacebookRunner(mFb);
                asyncRunner.logout(MainActivity.this, new LogoutRequestListener());
            } else {
                mFacebook.authorize(mActivity, mPermissions,
                        new LoginDialogListener());

            }

        }


        @JavascriptInterface
        public String fbToken() {
            return fbToken;
        }

        @JavascriptInterface
        public String fbName() {
            return fbName;
        }

        @JavascriptInterface
        public String fbEmail() {
            return fbEmail;
        }

        @JavascriptInterface
        public String fbGender() {
            return fbGender;
        }

        @JavascriptInterface
        public String fbLocale() {
            return fbLocale;
        }

        @JavascriptInterface
        public String fbVerified() {
            return fbVerified;
        }

        @JavascriptInterface
        public String fbUserID() {
            return fbUserID;
        }

        @JavascriptInterface
        public String client_version() {
            return CLIENT_VERSION;
        }

        @JavascriptInterface
        public String user_agent() { return userAgent; }

        @JavascriptInterface
        public void addAppStore() {
            addAppStore();
        }

        @JavascriptInterface
        public String device_mac() {
            WifiManager manager = (WifiManager) getSystemService(Context.WIFI_SERVICE);
            WifiInfo info = manager.getConnectionInfo();
            return info.getMacAddress();
        }

        @JavascriptInterface
        public void show_dialog(String title, String message, String button_text) {
            showDialog(title, message, button_text);
        }

        @JavascriptInterface
        public void log( String msg) {
            //Log.v(TAG, msg);
        }

        @JavascriptInterface
        public String device_imei() {

            return getDeviceIMEI();
        }

        @JavascriptInterface
        public String device_id() {
            return getDeviceID();
        }

        @JavascriptInterface
        public String is_registered() {
            return (isRegistered()) ? "1" : "0";
        }


        @JavascriptInterface
        public String device_model() {
            return getDeviceModel();
        }

        @JavascriptInterface
        public String device_manufacturer() {
            return getDeviceManufacturer();
        }

        @JavascriptInterface
        public String device_version() {
            return getDeviceVersion();
        }

    }

    public class ARWebViewClient extends WebViewClient {

        /*
        *  TODO: Insert code here to intercept the URL. If it is requesting a download
        *  try to launch the URL in a new browser.
        * */

        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {

            if (!url.startsWith("http://127.0.0.1")
                    && !url.startsWith(serverRootURL)
                    && !url.contains("forumstage.apprewarder")
                    && !url.contains("mstage.apprewarder")
                    && !url.contains("m.apprewarder")
                    && !url.contains("forum.apprewarder")) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                startActivity(browserIntent);

            } else {


                view.loadUrl(url);
            }

            return true;
        }

        @Override
        public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
            super.onReceivedSslError(view, handler, error);

            // this will ignore the Ssl error and will go forward to your site
            handler.proceed();
        }

        @Override
        public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
            //Log.v(TAG, "Oh no! " + description);

        }

        @Override
        public void onPageStarted(WebView view,String url,Bitmap bm)
        {
           showLoading();
        }


        @Override
        public void onPageFinished(WebView view, String url) {
            hideLoading();
        }
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    /**
     * A placeholder fragment containing a simple view.
     */
    public static class PlaceholderFragment extends Fragment {

        public PlaceholderFragment() {
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            View rootView = inflater.inflate(R.layout.fragment_main, container, false);
            return rootView;
        }
    }


    private class SessionListener implements AuthListener, LogoutListener {

        public void onAuthSucceed() {
            //setImageResource(R.drawable.logout_button);
            SessionStore.save(mFb, MainActivity.this);



        }

        public void onAuthFail(String error) {
        }

        public void onLogoutBegin() {
        }

        public void onLogoutFinish() {
            SessionStore.clear(MainActivity.this);
            //setImageResource(R.drawable.login_button);
        }
    }

    private final class LoginDialogListener implements Facebook.DialogListener {
        public void onComplete(Bundle values) {
            SessionEvents.onLoginSuccess();
        }

        public void onFacebookError(FacebookError error) {
            SessionEvents.onLoginError(error.getMessage());
        }

        public void onError(DialogError error) {
            SessionEvents.onLoginError(error.getMessage());
        }

        public void onCancel() {
            SessionEvents.onLoginError("Action Canceled");
        }
    }

    private class LogoutRequestListener extends BaseRequestListener {
        public void onComplete(String response) {
            // callback should be run in the original thread,
            // not the background thread
            mHandler.post(new Runnable() {
                public void run() {
                    SessionEvents.onLogoutFinish();
                }
            });
        }


    }
}