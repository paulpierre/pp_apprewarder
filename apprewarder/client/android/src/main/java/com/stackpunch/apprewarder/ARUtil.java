package com.stackpunch.apprewarder;
//import android.provider.DocumentsContract.Root;
import java.io.File;
import java.lang.Runtime;
import java.util.ArrayList;
import java.io.BufferedWriter;
import java.io.OutputStreamWriter;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import android.util.Log;


/**
 * Created by paulpierre on 5/15/14.
 */
public class ARUtil {
    private static String LOG_TAG = ARUtil.class.getName();

    public boolean isDeviceRooted() {
        //return false;
        return checkRootMethod1() || checkRootMethod2() || checkRootMethod3();
    }

    public boolean checkRootMethod1() {
        String buildTags = android.os.Build.TAGS;
        return buildTags != null && buildTags.contains("test-keys");
    }

    public boolean checkRootMethod2() {
        try {
            File file = new File("/system/app/Superuser.apk");
            return file.exists();
        } catch (Exception e) {return false;}
    }

    public boolean checkRootMethod3() {
        return new ARUtil().executeCommand(SHELL_CMD.check_su_binary)!=null;
    }



    public static enum SHELL_CMD {
        check_su_binary(new String[] { "/system/xbin/which", "su" });

        String[] command;

        SHELL_CMD(String[] command) {
            this.command = command;
        }
    }

    public ArrayList<String> executeCommand(SHELL_CMD shellCmd) {
        String line = null;
        ArrayList<String> fullResponse = new ArrayList<String>();
        Process localProcess = null;
        try {
            localProcess = Runtime.getRuntime().exec(shellCmd.command);
        } catch (Exception e) {
            return null;
        }
        BufferedWriter out = new BufferedWriter(new OutputStreamWriter(
                localProcess.getOutputStream()));
        BufferedReader in = new BufferedReader(new InputStreamReader(
                localProcess.getInputStream()));
        try {
            while ((line = in.readLine()) != null) {
                Log.d(LOG_TAG, "--> Line received: " + line);
                fullResponse.add(line);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        Log.d(LOG_TAG, "--> Full response was: " + fullResponse);
        return fullResponse;
    }

}
