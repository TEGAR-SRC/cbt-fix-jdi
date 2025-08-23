package com.cbt.proctoring.receiver

import android.app.admin.DeviceAdminReceiver
import android.content.Context
import android.content.Intent
import android.util.Log
import com.cbt.proctoring.CBTApplication

/**
 * Device Admin Receiver for managing device administration policies
 * Required for kiosk mode and advanced security features
 */
class DeviceAdminReceiver : DeviceAdminReceiver() {

    companion object {
        private const val TAG = "DeviceAdminReceiver"
    }

    override fun onEnabled(context: Context, intent: Intent) {
        super.onEnabled(context, intent)
        Log.d(TAG, "Device admin enabled")
        
        // Update preferences
        CBTApplication.getInstance().getAppPreferences().edit()
            .putBoolean(CBTApplication.PREF_DEVICE_ADMIN_ENABLED, true)
            .apply()
    }

    override fun onDisabled(context: Context, intent: Intent) {
        super.onDisabled(context, intent)
        Log.d(TAG, "Device admin disabled")
        
        // Update preferences
        CBTApplication.getInstance().getAppPreferences().edit()
            .putBoolean(CBTApplication.PREF_DEVICE_ADMIN_ENABLED, false)
            .apply()
    }

    override fun onPasswordChanged(context: Context, intent: Intent, user: android.os.UserHandle) {
        super.onPasswordChanged(context, intent, user)
        Log.d(TAG, "Password changed")
    }

    override fun onPasswordFailed(context: Context, intent: Intent, user: android.os.UserHandle) {
        super.onPasswordFailed(context, intent, user)
        Log.d(TAG, "Password failed")
    }

    override fun onPasswordSucceeded(context: Context, intent: Intent, user: android.os.UserHandle) {
        super.onPasswordSucceeded(context, intent, user)
        Log.d(TAG, "Password succeeded")
    }

    override fun onPasswordExpiring(context: Context, intent: Intent, user: android.os.UserHandle) {
        super.onPasswordExpiring(context, intent, user)
        Log.d(TAG, "Password expiring")
    }

    override fun onLockTaskModeEntering(context: Context, intent: Intent, pkg: String) {
        super.onLockTaskModeEntering(context, intent, pkg)
        Log.d(TAG, "Lock task mode entering: $pkg")
    }

    override fun onLockTaskModeExiting(context: Context, intent: Intent) {
        super.onLockTaskModeExiting(context, intent)
        Log.d(TAG, "Lock task mode exiting")
    }
}