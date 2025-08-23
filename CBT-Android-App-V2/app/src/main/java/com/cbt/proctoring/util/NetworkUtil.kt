package com.cbt.proctoring.util

import android.content.Context
import android.net.ConnectivityManager
import android.net.Network
import android.net.NetworkCapabilities
import android.net.NetworkRequest
import android.os.Build
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow

/**
 * Network utility class for monitoring network connectivity
 * Provides real-time network status updates
 */
class NetworkUtil(private val context: Context) {

    private val connectivityManager = context.getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
    
    private val _isNetworkAvailable = MutableStateFlow(false)
    val isNetworkAvailable: StateFlow<Boolean> = _isNetworkAvailable.asStateFlow()
    
    private val _networkType = MutableStateFlow(NetworkType.NONE)
    val networkType: StateFlow<NetworkType> = _networkType.asStateFlow()
    
    private val _networkStrength = MutableStateFlow(NetworkStrength.WEAK)
    val networkStrength: StateFlow<NetworkStrength> = _networkStrength.asStateFlow()

    enum class NetworkType {
        WIFI,
        MOBILE,
        ETHERNET,
        NONE
    }

    enum class NetworkStrength {
        STRONG,
        MEDIUM,
        WEAK
    }

    private val networkCallback = object : ConnectivityManager.NetworkCallback() {
        override fun onAvailable(network: Network) {
            super.onAvailable(network)
            _isNetworkAvailable.value = true
            updateNetworkInfo()
        }

        override fun onLost(network: Network) {
            super.onLost(network)
            _isNetworkAvailable.value = false
            _networkType.value = NetworkType.NONE
            _networkStrength.value = NetworkStrength.WEAK
        }

        override fun onCapabilitiesChanged(network: Network, networkCapabilities: NetworkCapabilities) {
            super.onCapabilitiesChanged(network, networkCapabilities)
            updateNetworkInfo()
        }
    }

    init {
        registerNetworkCallback()
        updateNetworkInfo()
    }

    private fun registerNetworkCallback() {
        val networkRequest = NetworkRequest.Builder()
            .addCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET)
            .addCapability(NetworkCapabilities.NET_CAPABILITY_VALIDATED)
            .build()

        connectivityManager.registerNetworkCallback(networkRequest, networkCallback)
    }

    /**
     * Check if network is currently available
     */
    fun isNetworkAvailable(): Boolean {
        return if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            val network = connectivityManager.activeNetwork ?: return false
            val networkCapabilities = connectivityManager.getNetworkCapabilities(network) ?: return false
            
            when {
                networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_WIFI) -> true
                networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_CELLULAR) -> true
                networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_ETHERNET) -> true
                else -> false
            }
        } else {
            @Suppress("DEPRECATION")
            val networkInfo = connectivityManager.activeNetworkInfo
            networkInfo?.isConnected == true
        }
    }

    /**
     * Get current network type
     */
    fun getNetworkType(): NetworkType {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            val network = connectivityManager.activeNetwork ?: return NetworkType.NONE
            val networkCapabilities = connectivityManager.getNetworkCapabilities(network) ?: return NetworkType.NONE
            
            return when {
                networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_WIFI) -> NetworkType.WIFI
                networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_CELLULAR) -> NetworkType.MOBILE
                networkCapabilities.hasTransport(NetworkCapabilities.TRANSPORT_ETHERNET) -> NetworkType.ETHERNET
                else -> NetworkType.NONE
            }
        } else {
            @Suppress("DEPRECATION")
            val networkInfo = connectivityManager.activeNetworkInfo ?: return NetworkType.NONE
            
            return when (networkInfo.type) {
                ConnectivityManager.TYPE_WIFI -> NetworkType.WIFI
                ConnectivityManager.TYPE_MOBILE -> NetworkType.MOBILE
                ConnectivityManager.TYPE_ETHERNET -> NetworkType.ETHERNET
                else -> NetworkType.NONE
            }
        }
    }

    /**
     * Get network strength/quality
     */
    fun getNetworkStrength(): NetworkStrength {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            val network = connectivityManager.activeNetwork ?: return NetworkStrength.WEAK
            val networkCapabilities = connectivityManager.getNetworkCapabilities(network) ?: return NetworkStrength.WEAK
            
            // Check link downstream bandwidth
            val downstreamBandwidth = networkCapabilities.linkDownstreamBandwidthKbps
            
            return when {
                downstreamBandwidth >= 5000 -> NetworkStrength.STRONG // >= 5 Mbps
                downstreamBandwidth >= 1000 -> NetworkStrength.MEDIUM // >= 1 Mbps
                else -> NetworkStrength.WEAK
            }
        } else {
            // For older versions, assume medium strength if connected
            return if (isNetworkAvailable()) NetworkStrength.MEDIUM else NetworkStrength.WEAK
        }
    }

    /**
     * Check if network is metered (limited data)
     */
    fun isNetworkMetered(): Boolean {
        return if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            connectivityManager.isActiveNetworkMetered
        } else {
            // Assume mobile networks are metered
            getNetworkType() == NetworkType.MOBILE
        }
    }

    /**
     * Get network info as a map
     */
    fun getNetworkInfo(): Map<String, String> {
        val networkType = getNetworkType()
        val networkStrength = getNetworkStrength()
        val isAvailable = isNetworkAvailable()
        val isMetered = isNetworkMetered()
        
        return mapOf(
            "isAvailable" to isAvailable.toString(),
            "networkType" to networkType.name,
            "networkStrength" to networkStrength.name,
            "isMetered" to isMetered.toString(),
            "timestamp" to System.currentTimeMillis().toString()
        )
    }

    /**
     * Update network information
     */
    private fun updateNetworkInfo() {
        _isNetworkAvailable.value = isNetworkAvailable()
        _networkType.value = getNetworkType()
        _networkStrength.value = getNetworkStrength()
    }

    /**
     * Unregister network callback (call in onDestroy)
     */
    fun unregister() {
        try {
            connectivityManager.unregisterNetworkCallback(networkCallback)
        } catch (e: Exception) {
            // Callback might not be registered
        }
    }

    /**
     * Test network connectivity by attempting to reach a specific host
     */
    suspend fun testConnectivity(host: String = "8.8.8.8", timeout: Int = 3000): Boolean {
        return try {
            val process = Runtime.getRuntime().exec("ping -c 1 $host")
            process.waitFor() == 0
        } catch (e: Exception) {
            false
        }
    }

    /**
     * Get detailed network capabilities
     */
    fun getNetworkCapabilities(): NetworkCapabilities? {
        return if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            val network = connectivityManager.activeNetwork
            if (network != null) {
                connectivityManager.getNetworkCapabilities(network)
            } else null
        } else {
            null
        }
    }

    /**
     * Check if network has specific capability
     */
    fun hasNetworkCapability(capability: Int): Boolean {
        val capabilities = getNetworkCapabilities()
        return capabilities?.hasCapability(capability) == true
    }

    /**
     * Get bandwidth information
     */
    fun getBandwidthInfo(): Map<String, Int> {
        val capabilities = getNetworkCapabilities()
        return if (capabilities != null && Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            mapOf(
                "downstreamKbps" to capabilities.linkDownstreamBandwidthKbps,
                "upstreamKbps" to capabilities.linkUpstreamBandwidthKbps
            )
        } else {
            mapOf(
                "downstreamKbps" to -1,
                "upstreamKbps" to -1
            )
        }
    }
}