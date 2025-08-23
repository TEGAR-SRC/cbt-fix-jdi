package com.cbt.proctoring.security

import android.content.Context
import android.os.Build
import android.provider.Settings
import java.io.BufferedReader
import java.io.File
import java.io.InputStreamReader

/**
 * Security checker to detect rooted devices and emulators
 * Implements multiple detection methods for comprehensive security
 */
class SecurityChecker(private val context: Context) {

    enum class SecurityStatus {
        SAFE,
        ROOTED,
        EMULATOR
    }

    /**
     * Check if device is safe (not rooted and not emulator)
     */
    fun isDeviceSafe(): Boolean {
        return !isRooted() && !isEmulator()
    }

    /**
     * Check if device is rooted
     */
    fun isRooted(): Boolean {
        return checkRootBuildTags() ||
                checkRootFiles() ||
                checkSuBinary() ||
                checkRootApps()
    }

    /**
     * Check if device is an emulator
     */
    fun isEmulator(): Boolean {
        return checkEmulatorBuild() ||
                checkEmulatorFiles() ||
                checkEmulatorProperties() ||
                checkAndroidId()
    }

    /**
     * Get overall security status
     */
    fun getSecurityStatus(): SecurityStatus {
        return when {
            isRooted() -> SecurityStatus.ROOTED
            isEmulator() -> SecurityStatus.EMULATOR
            else -> SecurityStatus.SAFE
        }
    }

    // Root detection methods

    private fun checkRootBuildTags(): Boolean {
        val buildTags = Build.TAGS
        return buildTags != null && buildTags.contains("test-keys")
    }

    private fun checkRootFiles(): Boolean {
        val rootFiles = arrayOf(
            "/system/app/Superuser.apk",
            "/sbin/su",
            "/system/bin/su",
            "/system/xbin/su",
            "/data/local/xbin/su",
            "/data/local/bin/su",
            "/system/sd/xbin/su",
            "/system/bin/failsafe/su",
            "/data/local/su",
            "/su/bin/su",
            "/system/etc/init.d/99SuperSUDaemon",
            "/dev/com.koushikdutta.superuser.daemon/",
            "/system/xbin/daemonsu",
            "/system/etc/security/cacerts_google",
            "/sbin/supersu",
            "/system/bin/.ext/.su",
            "/system/usr/we-need-root/su-backup",
            "/system/xbin/mu"
        )

        return rootFiles.any { File(it).exists() }
    }

    private fun checkSuBinary(): Boolean {
        return try {
            val process = Runtime.getRuntime().exec(arrayOf("which", "su"))
            val reader = BufferedReader(InputStreamReader(process.inputStream))
            val line = reader.readLine()
            process.destroy()
            line != null
        } catch (e: Exception) {
            false
        }
    }

    private fun checkRootApps(): Boolean {
        val rootApps = arrayOf(
            "com.noshufou.android.su",
            "com.noshufou.android.su.elite",
            "eu.chainfire.supersu",
            "com.koushikdutta.superuser",
            "com.thirdparty.superuser",
            "com.yellowes.su",
            "com.koushikdutta.rommanager",
            "com.koushikdutta.rommanager.license",
            "com.dimonvideo.luckypatcher",
            "com.chelpus.lackypatch",
            "com.ramdroid.appquarantine",
            "com.ramdroid.appquarantinepro",
            "com.topjohnwu.magisk"
        )

        return rootApps.any { packageName ->
            try {
                context.packageManager.getPackageInfo(packageName, 0)
                true
            } catch (e: Exception) {
                false
            }
        }
    }

    // Emulator detection methods

    private fun checkEmulatorBuild(): Boolean {
        val fingerprint = Build.FINGERPRINT
        val model = Build.MODEL
        val manufacturer = Build.MANUFACTURER
        val brand = Build.BRAND
        val product = Build.PRODUCT
        val hardware = Build.HARDWARE

        return fingerprint.startsWith("generic") ||
                fingerprint.startsWith("unknown") ||
                model.contains("google_sdk") ||
                model.contains("Emulator") ||
                model.contains("Android SDK built for x86") ||
                manufacturer.contains("Genymotion") ||
                brand.startsWith("generic") && manufacturer.startsWith("generic") ||
                product.equals("google_sdk") ||
                hardware.contains("goldfish") ||
                hardware.contains("ranchu")
    }

    private fun checkEmulatorFiles(): Boolean {
        val emulatorFiles = arrayOf(
            "/dev/socket/qemud",
            "/dev/qemu_pipe",
            "/system/lib/libc_malloc_debug_qemu.so",
            "/sys/qemu_trace",
            "/system/bin/qemu-props",
            "/dev/socket/genyd",
            "/dev/socket/baseband_genyd"
        )

        return emulatorFiles.any { File(it).exists() }
    }

    private fun checkEmulatorProperties(): Boolean {
        return try {
            val process = Runtime.getRuntime().exec("getprop ro.kernel.qemu")
            val reader = BufferedReader(InputStreamReader(process.inputStream))
            val line = reader.readLine()
            process.destroy()
            line != null && line.contains("1")
        } catch (e: Exception) {
            false
        }
    }

    private fun checkAndroidId(): Boolean {
        val androidId = Settings.Secure.getString(
            context.contentResolver,
            Settings.Secure.ANDROID_ID
        )
        
        // Known emulator Android IDs
        val emulatorIds = arrayOf(
            "9774d56d682e549c",
            "0000000000000000",
            null
        )
        
        return emulatorIds.contains(androidId)
    }

    // Additional security checks

    /**
     * Check if USB debugging is enabled
     */
    fun isUsbDebuggingEnabled(): Boolean {
        return Settings.Global.getInt(
            context.contentResolver,
            Settings.Global.ADB_ENABLED,
            0
        ) == 1
    }

    /**
     * Check if developer options are enabled
     */
    fun isDeveloperOptionsEnabled(): Boolean {
        return Settings.Global.getInt(
            context.contentResolver,
            Settings.Global.DEVELOPMENT_SETTINGS_ENABLED,
            0
        ) == 1
    }

    /**
     * Check if device is in debug mode
     */
    fun isDebugMode(): Boolean {
        return (context.applicationInfo.flags and android.content.pm.ApplicationInfo.FLAG_DEBUGGABLE) != 0
    }

    /**
     * Get device security score (0-100, higher is more secure)
     */
    fun getSecurityScore(): Int {
        var score = 100
        
        if (isRooted()) score -= 50
        if (isEmulator()) score -= 40
        if (isUsbDebuggingEnabled()) score -= 5
        if (isDeveloperOptionsEnabled()) score -= 3
        if (isDebugMode()) score -= 2
        
        return maxOf(0, score)
    }

    /**
     * Get detailed security report
     */
    fun getSecurityReport(): SecurityReport {
        return SecurityReport(
            isDeviceSafe = isDeviceSafe(),
            isRooted = isRooted(),
            isEmulator = isEmulator(),
            isUsbDebuggingEnabled = isUsbDebuggingEnabled(),
            isDeveloperOptionsEnabled = isDeveloperOptionsEnabled(),
            isDebugMode = isDebugMode(),
            securityScore = getSecurityScore(),
            deviceInfo = getDeviceInfo()
        )
    }

    private fun getDeviceInfo(): Map<String, String> {
        return mapOf(
            "manufacturer" to Build.MANUFACTURER,
            "model" to Build.MODEL,
            "brand" to Build.BRAND,
            "product" to Build.PRODUCT,
            "hardware" to Build.HARDWARE,
            "fingerprint" to Build.FINGERPRINT,
            "androidVersion" to Build.VERSION.RELEASE,
            "apiLevel" to Build.VERSION.SDK_INT.toString(),
            "buildType" to Build.TYPE,
            "tags" to Build.TAGS
        )
    }

    data class SecurityReport(
        val isDeviceSafe: Boolean,
        val isRooted: Boolean,
        val isEmulator: Boolean,
        val isUsbDebuggingEnabled: Boolean,
        val isDeveloperOptionsEnabled: Boolean,
        val isDebugMode: Boolean,
        val securityScore: Int,
        val deviceInfo: Map<String, String>
    )
}