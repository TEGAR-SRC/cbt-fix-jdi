package com.cbt.proctoring.ui.splash

import android.Manifest
import android.animation.Animator
import android.animation.AnimatorSet
import android.animation.ObjectAnimator
import android.app.admin.DevicePolicyManager
import android.content.ComponentName
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.os.Build
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.view.View
import android.view.animation.AccelerateDecelerateInterpolator
import android.widget.Toast
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.lifecycleScope
import com.cbt.proctoring.CBTApplication
import com.cbt.proctoring.R
import com.cbt.proctoring.databinding.ActivitySplashBinding
import com.cbt.proctoring.receiver.DeviceAdminReceiver
import com.cbt.proctoring.security.SecurityChecker
import com.cbt.proctoring.ui.admin.AdminLoginActivity
import com.cbt.proctoring.ui.error.ErrorActivity
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch

/**
 * Splash Activity - Entry point of the application
 * Handles initial security checks, permissions, and navigation
 */
class SplashActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivitySplashBinding
    private lateinit var securityChecker: SecurityChecker
    private lateinit var devicePolicyManager: DevicePolicyManager
    private lateinit var deviceAdminComponent: ComponentName
    private lateinit var app: CBTApplication
    
    private var permissionsGranted = false
    private var deviceAdminEnabled = false
    
    companion object {
        private const val SPLASH_DELAY = 3000L
        private const val ANIMATION_DURATION = 1000L
    }
    
    // Permission launcher
    private val permissionLauncher = registerForActivityResult(
        ActivityResultContracts.RequestMultiplePermissions()
    ) { permissions ->
        val cameraGranted = permissions[Manifest.permission.CAMERA] ?: false
        val internetGranted = permissions[Manifest.permission.INTERNET] ?: true // Usually granted by default
        val networkStateGranted = permissions[Manifest.permission.ACCESS_NETWORK_STATE] ?: true
        
        permissionsGranted = cameraGranted && internetGranted && networkStateGranted
        
        if (permissionsGranted) {
            app.getAppPreferences().edit()
                .putBoolean(CBTApplication.PREF_CAMERA_PERMISSION_GRANTED, true)
                .apply()
            
            checkDeviceAdmin()
        } else {
            showPermissionError()
        }
    }
    
    // Device admin launcher
    private val deviceAdminLauncher = registerForActivityResult(
        ActivityResultContracts.StartActivityForResult()
    ) { result ->
        deviceAdminEnabled = devicePolicyManager.isAdminActive(deviceAdminComponent)
        
        if (deviceAdminEnabled) {
            app.getAppPreferences().edit()
                .putBoolean(CBTApplication.PREF_DEVICE_ADMIN_ENABLED, true)
                .apply()
            
            proceedToMainActivity()
        } else {
            showDeviceAdminError()
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivitySplashBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        initializeComponents()
        startAnimations()
        checkSecurityAndPermissions()
    }

    private fun initializeComponents() {
        app = CBTApplication.getInstance()
        securityChecker = SecurityChecker(this)
        devicePolicyManager = getSystemService(Context.DEVICE_POLICY_SERVICE) as DevicePolicyManager
        deviceAdminComponent = ComponentName(this, DeviceAdminReceiver::class.java)
    }

    private fun startAnimations() {
        // Initially hide all views
        binding.cardLogo.alpha = 0f
        binding.tvTitle.alpha = 0f
        binding.tvSubtitle.alpha = 0f
        binding.progressBar.alpha = 0f
        binding.tvLoading.alpha = 0f
        
        // Scale down logo initially
        binding.cardLogo.scaleX = 0.5f
        binding.cardLogo.scaleY = 0.5f
        
        lifecycleScope.launch {
            delay(500) // Wait a bit before starting animations
            
            // Animate logo
            val logoAnimator = AnimatorSet().apply {
                playTogether(
                    ObjectAnimator.ofFloat(binding.cardLogo, "alpha", 0f, 1f),
                    ObjectAnimator.ofFloat(binding.cardLogo, "scaleX", 0.5f, 1f),
                    ObjectAnimator.ofFloat(binding.cardLogo, "scaleY", 0.5f, 1f)
                )
                duration = ANIMATION_DURATION
                interpolator = AccelerateDecelerateInterpolator()
            }
            
            // Animate title
            val titleAnimator = ObjectAnimator.ofFloat(binding.tvTitle, "alpha", 0f, 1f).apply {
                duration = ANIMATION_DURATION
                startDelay = 300
                interpolator = AccelerateDecelerateInterpolator()
            }
            
            // Animate subtitle
            val subtitleAnimator = ObjectAnimator.ofFloat(binding.tvSubtitle, "alpha", 0f, 1f).apply {
                duration = ANIMATION_DURATION
                startDelay = 600
                interpolator = AccelerateDecelerateInterpolator()
            }
            
            // Animate progress bar
            val progressAnimator = ObjectAnimator.ofFloat(binding.progressBar, "alpha", 0f, 1f).apply {
                duration = ANIMATION_DURATION
                startDelay = 900
                interpolator = AccelerateDecelerateInterpolator()
            }
            
            // Animate loading text
            val loadingAnimator = ObjectAnimator.ofFloat(binding.tvLoading, "alpha", 0f, 1f).apply {
                duration = ANIMATION_DURATION
                startDelay = 1200
                interpolator = AccelerateDecelerateInterpolator()
            }
            
            // Start all animations
            val mainAnimator = AnimatorSet().apply {
                playTogether(logoAnimator, titleAnimator, subtitleAnimator, progressAnimator, loadingAnimator)
            }
            
            mainAnimator.start()
            
            // Animate background circles
            animateBackgroundElements()
        }
    }

    private fun animateBackgroundElements() {
        val circle1 = ObjectAnimator.ofFloat(binding.bgCircle1, "rotation", 0f, 360f).apply {
            duration = 20000L
            repeatCount = ObjectAnimator.INFINITE
        }
        
        val circle2 = ObjectAnimator.ofFloat(binding.bgCircle2, "rotation", 360f, 0f).apply {
            duration = 15000L
            repeatCount = ObjectAnimator.INFINITE
        }
        
        val circle3 = ObjectAnimator.ofFloat(binding.bgCircle3, "rotation", 0f, 360f).apply {
            duration = 25000L
            repeatCount = ObjectAnimator.INFINITE
        }
        
        circle1.start()
        circle2.start()
        circle3.start()
    }

    private fun checkSecurityAndPermissions() {
        lifecycleScope.launch {
            // Update loading text
            binding.tvLoading.text = getString(R.string.splash_initializing)
            
            delay(1000) // Show splash for a minimum time
            
            // Check device security
            if (!securityChecker.isDeviceSafe()) {
                showSecurityError()
                return@launch
            }
            
            // Check permissions
            checkPermissions()
        }
    }

    private fun checkPermissions() {
        val requiredPermissions = mutableListOf<String>()
        
        // Check camera permission
        if (ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) 
            != PackageManager.PERMISSION_GRANTED) {
            requiredPermissions.add(Manifest.permission.CAMERA)
        }
        
        // Check network permissions (usually granted by default)
        if (ContextCompat.checkSelfPermission(this, Manifest.permission.INTERNET)
            != PackageManager.PERMISSION_GRANTED) {
            requiredPermissions.add(Manifest.permission.INTERNET)
        }
        
        if (ContextCompat.checkSelfPermission(this, Manifest.permission.ACCESS_NETWORK_STATE)
            != PackageManager.PERMISSION_GRANTED) {
            requiredPermissions.add(Manifest.permission.ACCESS_NETWORK_STATE)
        }
        
        if (requiredPermissions.isNotEmpty()) {
            // Request permissions
            binding.tvLoading.text = "Requesting permissions..."
            permissionLauncher.launch(requiredPermissions.toTypedArray())
        } else {
            permissionsGranted = true
            checkDeviceAdmin()
        }
    }

    private fun checkDeviceAdmin() {
        binding.tvLoading.text = "Checking device admin..."
        
        deviceAdminEnabled = devicePolicyManager.isAdminActive(deviceAdminComponent)
        
        if (!deviceAdminEnabled) {
            // Request device admin permission
            val intent = Intent(DevicePolicyManager.ACTION_ADD_DEVICE_ADMIN).apply {
                putExtra(DevicePolicyManager.EXTRA_DEVICE_ADMIN, deviceAdminComponent)
                putExtra(DevicePolicyManager.EXTRA_ADD_EXPLANATION, 
                    getString(R.string.device_admin_description))
            }
            
            binding.tvLoading.text = "Requesting device admin..."
            deviceAdminLauncher.launch(intent)
        } else {
            proceedToMainActivity()
        }
    }

    private fun proceedToMainActivity() {
        binding.tvLoading.text = "Starting application..."
        
        Handler(Looper.getMainLooper()).postDelayed({
            // Navigate to admin login
            val intent = Intent(this, AdminLoginActivity::class.java)
            startActivity(intent)
            finish()
            
            // Add transition animation
            overridePendingTransition(android.R.anim.fade_in, android.R.anim.fade_out)
        }, 1000)
    }

    private fun showSecurityError() {
        val securityStatus = securityChecker.getSecurityStatus()
        val errorMessage = when (securityStatus) {
            SecurityChecker.SecurityStatus.ROOTED -> getString(R.string.security_root_detected)
            SecurityChecker.SecurityStatus.EMULATOR -> getString(R.string.security_emulator_detected)
            else -> getString(R.string.security_device_unsafe)
        }
        
        val intent = Intent(this, ErrorActivity::class.java).apply {
            putExtra("error_title", getString(R.string.error_security_title))
            putExtra("error_message", errorMessage)
            putExtra("show_retry", false)
        }
        startActivity(intent)
        finish()
    }

    private fun showPermissionError() {
        Toast.makeText(this, getString(R.string.permission_camera_denied), Toast.LENGTH_LONG).show()
        
        val intent = Intent(this, ErrorActivity::class.java).apply {
            putExtra("error_title", "Permission Required")
            putExtra("error_message", getString(R.string.permission_camera_message))
            putExtra("show_retry", true)
        }
        startActivity(intent)
        finish()
    }

    private fun showDeviceAdminError() {
        Toast.makeText(this, getString(R.string.device_admin_required), Toast.LENGTH_LONG).show()
        
        val intent = Intent(this, ErrorActivity::class.java).apply {
            putExtra("error_title", "Device Admin Required")
            putExtra("error_message", getString(R.string.device_admin_description))
            putExtra("show_retry", true)
        }
        startActivity(intent)
        finish()
    }

    override fun onBackPressed() {
        // Disable back button on splash screen
        // Do nothing
    }
}