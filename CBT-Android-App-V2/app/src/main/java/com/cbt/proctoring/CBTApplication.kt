package com.cbt.proctoring

import android.app.Application
import android.app.NotificationChannel
import android.app.NotificationManager
import android.content.Context
import android.os.Build
import androidx.appcompat.app.AppCompatDelegate

/**
 * Main Application class for CBT Proctoring App
 * Handles app-wide initialization and configuration
 */
class CBTApplication : Application() {

    companion object {
        // Notification channels
        const val CHANNEL_PROCTORING = "proctoring_channel"
        const val CHANNEL_NETWORK = "network_channel"
        const val CHANNEL_SECURITY = "security_channel"
        
        // App preferences
        const val PREFS_NAME = "cbt_proctoring_prefs"
        const val PREF_ADMIN_PIN = "admin_pin"
        const val PREF_CBT_URL = "cbt_url"
        const val PREF_FIRST_LAUNCH = "first_launch"
        const val PREF_DEVICE_ADMIN_ENABLED = "device_admin_enabled"
        const val PREF_CAMERA_PERMISSION_GRANTED = "camera_permission_granted"
        const val PREF_EXAM_SESSION_ID = "exam_session_id"
        const val PREF_EXAM_START_TIME = "exam_start_time"
        const val PREF_CHEAT_ATTEMPTS = "cheat_attempts"
        const val PREF_LAST_PHOTO_TIMESTAMP = "last_photo_timestamp"
        const val PREF_NETWORK_STATUS = "network_status"
        
        // Default values
        const val DEFAULT_ADMIN_PIN = "123456"
        const val DEFAULT_CBT_URL = "http://192.168.1.100:8000/student/exam"
        const val MAX_CHEAT_ATTEMPTS = 3
        const val PHOTO_CAPTURE_INTERVAL = 30000L // 30 seconds
        const val NETWORK_CHECK_INTERVAL = 5000L // 5 seconds
        
        private lateinit var instance: CBTApplication
        
        fun getInstance(): CBTApplication = instance
    }

    override fun onCreate() {
        super.onCreate()
        instance = this
        
        // Initialize app
        initializeApp()
        
        // Create notification channels
        createNotificationChannels()
        
        // Set default night mode
        AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO)
        
        // Initialize default preferences
        initializeDefaultPreferences()
    }

    private fun initializeApp() {
        // Any app-wide initialization can be done here
        // For example: crash reporting, analytics, etc.
    }

    private fun createNotificationChannels() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val notificationManager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
            
            // Proctoring channel
            val proctoringChannel = NotificationChannel(
                CHANNEL_PROCTORING,
                getString(R.string.notification_channel_proctoring),
                NotificationManager.IMPORTANCE_LOW
            ).apply {
                description = getString(R.string.notification_proctoring_text)
                setShowBadge(false)
                enableLights(false)
                enableVibration(false)
            }
            
            // Network channel
            val networkChannel = NotificationChannel(
                CHANNEL_NETWORK,
                getString(R.string.notification_channel_network),
                NotificationManager.IMPORTANCE_HIGH
            ).apply {
                description = getString(R.string.notification_network_title)
                setShowBadge(true)
                enableLights(true)
                enableVibration(true)
            }
            
            // Security channel
            val securityChannel = NotificationChannel(
                CHANNEL_SECURITY,
                getString(R.string.notification_channel_security),
                NotificationManager.IMPORTANCE_HIGH
            ).apply {
                description = getString(R.string.notification_security_title)
                setShowBadge(true)
                enableLights(true)
                enableVibration(true)
            }
            
            // Create channels
            notificationManager.createNotificationChannels(listOf(
                proctoringChannel,
                networkChannel,
                securityChannel
            ))
        }
    }

    private fun initializeDefaultPreferences() {
        val prefs = getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        
        if (prefs.getBoolean(PREF_FIRST_LAUNCH, true)) {
            // First launch - set default values
            prefs.edit().apply {
                putString(PREF_ADMIN_PIN, DEFAULT_ADMIN_PIN)
                putString(PREF_CBT_URL, DEFAULT_CBT_URL)
                putBoolean(PREF_FIRST_LAUNCH, false)
                putBoolean(PREF_DEVICE_ADMIN_ENABLED, false)
                putBoolean(PREF_CAMERA_PERMISSION_GRANTED, false)
                putInt(PREF_CHEAT_ATTEMPTS, 0)
                putLong(PREF_LAST_PHOTO_TIMESTAMP, 0L)
                putBoolean(PREF_NETWORK_STATUS, false)
                apply()
            }
        }
    }

    /**
     * Get shared preferences instance
     */
    fun getAppPreferences() = getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    /**
     * Clear exam-related data
     */
    fun clearExamData() {
        getAppPreferences().edit().apply {
            remove(PREF_EXAM_SESSION_ID)
            remove(PREF_EXAM_START_TIME)
            putInt(PREF_CHEAT_ATTEMPTS, 0)
            putLong(PREF_LAST_PHOTO_TIMESTAMP, 0L)
            apply()
        }
    }

    /**
     * Check if device admin is enabled
     */
    fun isDeviceAdminEnabled(): Boolean {
        return getAppPreferences().getBoolean(PREF_DEVICE_ADMIN_ENABLED, false)
    }

    /**
     * Check if camera permission is granted
     */
    fun isCameraPermissionGranted(): Boolean {
        return getAppPreferences().getBoolean(PREF_CAMERA_PERMISSION_GRANTED, false)
    }

    /**
     * Get admin PIN
     */
    fun getAdminPin(): String {
        return getAppPreferences().getString(PREF_ADMIN_PIN, DEFAULT_ADMIN_PIN) ?: DEFAULT_ADMIN_PIN
    }

    /**
     * Set admin PIN
     */
    fun setAdminPin(pin: String) {
        getAppPreferences().edit().putString(PREF_ADMIN_PIN, pin).apply()
    }

    /**
     * Get CBT URL
     */
    fun getCbtUrl(): String {
        return getAppPreferences().getString(PREF_CBT_URL, DEFAULT_CBT_URL) ?: DEFAULT_CBT_URL
    }

    /**
     * Set CBT URL
     */
    fun setCbtUrl(url: String) {
        getAppPreferences().edit().putString(PREF_CBT_URL, url).apply()
    }

    /**
     * Get cheat attempts count
     */
    fun getCheatAttempts(): Int {
        return getAppPreferences().getInt(PREF_CHEAT_ATTEMPTS, 0)
    }

    /**
     * Increment cheat attempts
     */
    fun incrementCheatAttempts(): Int {
        val currentAttempts = getCheatAttempts()
        val newAttempts = currentAttempts + 1
        getAppPreferences().edit().putInt(PREF_CHEAT_ATTEMPTS, newAttempts).apply()
        return newAttempts
    }

    /**
     * Check if max cheat attempts reached
     */
    fun isMaxCheatAttemptsReached(): Boolean {
        return getCheatAttempts() >= MAX_CHEAT_ATTEMPTS
    }
}