package com.cbt.proctoring.service

import android.app.Notification
import android.app.NotificationManager
import android.app.PendingIntent
import android.app.Service
import android.content.Intent
import android.os.Build
import android.os.IBinder
import android.util.Log
import androidx.core.app.NotificationCompat
import androidx.lifecycle.lifecycleScope
import com.cbt.proctoring.CBTApplication
import com.cbt.proctoring.R
import com.cbt.proctoring.api.ProctoringApiService
import com.cbt.proctoring.model.NetworkEvent
import com.cbt.proctoring.ui.exam.ExamActivity
import com.cbt.proctoring.util.NetworkUtil
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.Job
import kotlinx.coroutines.delay
import kotlinx.coroutines.flow.collect
import kotlinx.coroutines.launch

/**
 * Network Monitoring Service
 * Monitors network connectivity during exam and reports changes to backend
 */
class NetworkMonitoringService : Service() {

    private lateinit var networkUtil: NetworkUtil
    private lateinit var app: CBTApplication
    private lateinit var apiService: ProctoringApiService
    private lateinit var notificationManager: NotificationManager
    
    private val serviceScope = CoroutineScope(Dispatchers.IO + Job())
    private var monitoringJob: Job? = null
    
    private var lastNetworkStatus = false
    private var disconnectionStartTime = 0L
    private var wasNotificationShown = false

    companion object {
        private const val TAG = "NetworkMonitoringService"
        private const val NOTIFICATION_ID = 1002
        private const val NETWORK_CHECK_INTERVAL = 5000L // 5 seconds
        private const val NOTIFICATION_DISCONNECT_ID = 1003
    }

    override fun onCreate() {
        super.onCreate()
        Log.d(TAG, "NetworkMonitoringService created")
        
        app = CBTApplication.getInstance()
        networkUtil = NetworkUtil(this)
        apiService = ProctoringApiService.create()
        notificationManager = getSystemService(NotificationManager::class.java)
        
        createNotificationChannel()
        startForeground(NOTIFICATION_ID, createNotification())
        
        startNetworkMonitoring()
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        Log.d(TAG, "NetworkMonitoringService started")
        return START_STICKY // Restart if killed
    }

    override fun onBind(intent: Intent?): IBinder? = null

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            // This channel should already be created in CBTApplication
            // Just ensure it exists
        }
    }

    private fun createNotification(): Notification {
        val intent = Intent(this, ExamActivity::class.java)
        val pendingIntent = PendingIntent.getActivity(
            this, 0, intent, 
            PendingIntent.FLAG_UPDATE_CURRENT or PendingIntent.FLAG_IMMUTABLE
        )

        return NotificationCompat.Builder(this, CBTApplication.CHANNEL_NETWORK)
            .setContentTitle(getString(R.string.notification_network_title))
            .setContentText("Monitoring network connectivity")
            .setSmallIcon(R.drawable.ic_network_connected)
            .setContentIntent(pendingIntent)
            .setOngoing(true)
            .setSilent(true)
            .setPriority(NotificationCompat.PRIORITY_LOW)
            .setCategory(NotificationCompat.CATEGORY_SERVICE)
            .build()
    }

    private fun startNetworkMonitoring() {
        // Initialize current network status
        lastNetworkStatus = networkUtil.isNetworkAvailable()
        
        monitoringJob = serviceScope.launch {
            networkUtil.isNetworkAvailable.collect { isConnected ->
                handleNetworkStatusChange(isConnected)
            }
        }
        
        // Also do periodic checks as backup
        serviceScope.launch {
            while (true) {
                try {
                    val currentStatus = networkUtil.isNetworkAvailable()
                    if (currentStatus != lastNetworkStatus) {
                        handleNetworkStatusChange(currentStatus)
                    }
                    delay(NETWORK_CHECK_INTERVAL)
                } catch (e: Exception) {
                    Log.e(TAG, "Error in network monitoring loop", e)
                    delay(5000) // Wait before retrying
                }
            }
        }
    }

    private fun handleNetworkStatusChange(isConnected: Boolean) {
        val currentTime = System.currentTimeMillis()
        
        if (isConnected != lastNetworkStatus) {
            Log.d(TAG, "Network status changed: $isConnected")
            
            if (isConnected) {
                // Network reconnected
                handleNetworkReconnected(currentTime)
            } else {
                // Network disconnected
                handleNetworkDisconnected(currentTime)
            }
            
            lastNetworkStatus = isConnected
            
            // Update app preferences
            app.getAppPreferences().edit()
                .putBoolean(CBTApplication.PREF_NETWORK_STATUS, isConnected)
                .apply()
        }
    }

    private fun handleNetworkDisconnected(timestamp: Long) {
        disconnectionStartTime = timestamp
        
        // Show notification
        showDisconnectionNotification()
        
        // Log to API (this will fail, but we'll queue it for when connection returns)
        logNetworkEvent(
            eventType = "disconnected",
            timestamp = timestamp,
            networkInfo = networkUtil.getNetworkInfo()
        )
    }

    private fun handleNetworkReconnected(timestamp: Long) {
        val disconnectionDuration = if (disconnectionStartTime > 0) {
            timestamp - disconnectionStartTime
        } else {
            0L
        }
        
        // Hide disconnection notification
        hideDisconnectionNotification()
        
        // Log reconnection event
        logNetworkEvent(
            eventType = "reconnected",
            timestamp = timestamp,
            networkInfo = networkUtil.getNetworkInfo(),
            duration = disconnectionDuration
        )
        
        // Reset disconnection start time
        disconnectionStartTime = 0L
    }

    private fun logNetworkEvent(
        eventType: String,
        timestamp: Long,
        networkInfo: Map<String, String>,
        duration: Long? = null
    ) {
        serviceScope.launch {
            try {
                val sessionId = app.getAppPreferences().getString(CBTApplication.PREF_EXAM_SESSION_ID, "unknown") ?: "unknown"
                val studentId = "student_${System.currentTimeMillis()}" // Replace with actual student ID
                
                val networkEvent = NetworkEvent(
                    sessionId = sessionId,
                    studentId = studentId,
                    eventType = eventType,
                    networkInfo = networkInfo,
                    timestamp = timestamp,
                    duration = duration
                )

                val response = apiService.logNetworkEvent(networkEvent)
                
                if (response.isSuccessful) {
                    Log.d(TAG, "Network event logged successfully: $eventType")
                } else {
                    Log.e(TAG, "Failed to log network event: ${response.code()}")
                }
                
            } catch (e: Exception) {
                Log.e(TAG, "Error logging network event", e)
                // Could implement local storage for offline events here
            }
        }
    }

    private fun showDisconnectionNotification() {
        if (wasNotificationShown) return
        
        val notification = NotificationCompat.Builder(this, CBTApplication.CHANNEL_NETWORK)
            .setContentTitle("Network Disconnected")
            .setContentText("Exam paused. Please check your internet connection.")
            .setSmallIcon(R.drawable.ic_network_disconnected)
            .setPriority(NotificationCompat.PRIORITY_HIGH)
            .setCategory(NotificationCompat.CATEGORY_ALARM)
            .setAutoCancel(false)
            .setOngoing(true)
            .setColor(getColor(R.color.error))
            .build()

        notificationManager.notify(NOTIFICATION_DISCONNECT_ID, notification)
        wasNotificationShown = true
    }

    private fun hideDisconnectionNotification() {
        if (!wasNotificationShown) return
        
        notificationManager.cancel(NOTIFICATION_DISCONNECT_ID)
        wasNotificationShown = false
        
        // Show reconnection notification briefly
        val reconnectionNotification = NotificationCompat.Builder(this, CBTApplication.CHANNEL_NETWORK)
            .setContentTitle("Network Reconnected")
            .setContentText("Internet connection restored. Exam resumed.")
            .setSmallIcon(R.drawable.ic_network_connected)
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)
            .setAutoCancel(true)
            .setTimeoutAfter(5000) // Auto-dismiss after 5 seconds
            .setColor(getColor(R.color.success))
            .build()

        notificationManager.notify(NOTIFICATION_DISCONNECT_ID + 1, reconnectionNotification)
    }

    /**
     * Check network quality and report if poor
     */
    private fun checkNetworkQuality() {
        serviceScope.launch {
            try {
                val networkStrength = networkUtil.getNetworkStrength()
                val bandwidthInfo = networkUtil.getBandwidthInfo()
                
                if (networkStrength == NetworkUtil.NetworkStrength.WEAK) {
                    val networkInfo = networkUtil.getNetworkInfo().toMutableMap()
                    networkInfo.putAll(bandwidthInfo.mapValues { it.value.toString() })
                    
                    logNetworkEvent(
                        eventType = "poor_quality",
                        timestamp = System.currentTimeMillis(),
                        networkInfo = networkInfo
                    )
                }
            } catch (e: Exception) {
                Log.e(TAG, "Error checking network quality", e)
            }
        }
    }

    /**
     * Test connectivity to exam server
     */
    private fun testExamServerConnectivity() {
        serviceScope.launch {
            try {
                val response = apiService.ping()
                val isServerReachable = response.isSuccessful
                
                if (!isServerReachable && networkUtil.isNetworkAvailable()) {
                    // Network is available but server is not reachable
                    val networkInfo = networkUtil.getNetworkInfo().toMutableMap()
                    networkInfo["server_reachable"] = "false"
                    networkInfo["server_response_code"] = response.code().toString()
                    
                    logNetworkEvent(
                        eventType = "server_unreachable",
                        timestamp = System.currentTimeMillis(),
                        networkInfo = networkInfo
                    )
                }
                
            } catch (e: Exception) {
                Log.e(TAG, "Error testing server connectivity", e)
            }
        }
    }

    /**
     * Get current network status for external queries
     */
    fun getCurrentNetworkStatus(): Map<String, Any> {
        return mapOf(
            "isConnected" to lastNetworkStatus,
            "networkType" to networkUtil.getNetworkType().name,
            "networkStrength" to networkUtil.getNetworkStrength().name,
            "isMetered" to networkUtil.isNetworkMetered(),
            "disconnectionStartTime" to disconnectionStartTime,
            "lastStatusCheck" to System.currentTimeMillis()
        )
    }

    override fun onDestroy() {
        super.onDestroy()
        Log.d(TAG, "NetworkMonitoringService destroyed")
        
        // Cancel monitoring job
        monitoringJob?.cancel()
        
        // Clean up network utility
        networkUtil.unregister()
        
        // Hide any notifications
        hideDisconnectionNotification()
    }
}