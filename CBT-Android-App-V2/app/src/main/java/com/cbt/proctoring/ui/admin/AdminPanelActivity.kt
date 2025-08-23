package com.cbt.proctoring.ui.admin

import android.Manifest
import android.animation.ObjectAnimator
import android.app.admin.DevicePolicyManager
import android.content.ComponentName
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.net.ConnectivityManager
import android.net.NetworkCapabilities
import android.os.Build
import android.os.Bundle
import android.util.Patterns
import android.view.View
import android.view.animation.AccelerateDecelerateInterpolator
import android.webkit.URLUtil
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.lifecycleScope
import com.cbt.proctoring.CBTApplication
import com.cbt.proctoring.R
import com.cbt.proctoring.databinding.ActivityAdminPanelBinding
import com.cbt.proctoring.receiver.DeviceAdminReceiver
import com.cbt.proctoring.ui.exam.ExamActivity
import com.google.android.material.dialog.MaterialAlertDialogBuilder
import com.google.android.material.snackbar.Snackbar
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch

/**
 * Admin Panel Activity
 * Provides admin interface for configuring CBT settings
 */
class AdminPanelActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivityAdminPanelBinding
    private lateinit var app: CBTApplication
    private lateinit var devicePolicyManager: DevicePolicyManager
    private lateinit var deviceAdminComponent: ComponentName
    
    companion object {
        private const val ANIMATION_DURATION = 300L
    }
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminPanelBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        app = CBTApplication.getInstance()
        devicePolicyManager = getSystemService(Context.DEVICE_POLICY_SERVICE) as DevicePolicyManager
        deviceAdminComponent = ComponentName(this, DeviceAdminReceiver::class.java)
        
        setupToolbar()
        setupUI()
        setupClickListeners()
        updateSystemStatus()
    }
    
    private fun setupToolbar() {
        setSupportActionBar(binding.toolbar)
        supportActionBar?.setDisplayHomeAsUpEnabled(false)
    }
    
    private fun setupUI() {
        // Load current settings
        binding.etCbtUrl.setText(app.getCbtUrl())
        
        // Setup URL validation
        binding.etCbtUrl.setOnFocusChangeListener { _, hasFocus ->
            if (!hasFocus) {
                validateUrl()
            }
        }
    }
    
    private fun setupClickListeners() {
        // Save URL Button
        binding.btnSaveUrl.setOnClickListener {
            saveUrlSettings()
        }
        
        // Change PIN Button
        binding.btnChangePin.setOnClickListener {
            changePinSettings()
        }
        
        // Start Exam Button
        binding.btnStartExam.setOnClickListener {
            startExam()
        }
        
        // Exit App Button
        binding.btnExitApp.setOnClickListener {
            showExitConfirmation()
        }
    }
    
    private fun validateUrl(): Boolean {
        val url = binding.etCbtUrl.text.toString().trim()
        
        if (url.isEmpty()) {
            binding.tilCbtUrl.error = "URL cannot be empty"
            return false
        }
        
        if (!URLUtil.isValidUrl(url) || !Patterns.WEB_URL.matcher(url).matches()) {
            binding.tilCbtUrl.error = "Please enter a valid URL"
            return false
        }
        
        binding.tilCbtUrl.error = null
        return true
    }
    
    private fun saveUrlSettings() {
        if (!validateUrl()) {
            return
        }
        
        val url = binding.etCbtUrl.text.toString().trim()
        app.setCbtUrl(url)
        
        showSuccessSnackbar(getString(R.string.admin_settings_saved))
        
        // Animate save confirmation
        animateButtonSuccess(binding.btnSaveUrl)
    }
    
    private fun changePinSettings() {
        val currentPin = binding.etCurrentPin.text.toString().trim()
        val newPin = binding.etNewPin.text.toString().trim()
        val confirmPin = binding.etConfirmPin.text.toString().trim()
        
        // Validate inputs
        if (currentPin.isEmpty() || newPin.isEmpty() || confirmPin.isEmpty()) {
            showErrorSnackbar("Please fill all PIN fields")
            return
        }
        
        if (currentPin != app.getAdminPin()) {
            binding.tilCurrentPin.error = "Current PIN is incorrect"
            return
        }
        
        if (newPin.length < 4) {
            binding.tilNewPin.error = "PIN must be at least 4 digits"
            return
        }
        
        if (newPin != confirmPin) {
            binding.tilConfirmPin.error = getString(R.string.admin_pin_mismatch)
            return
        }
        
        // Clear errors
        binding.tilCurrentPin.error = null
        binding.tilNewPin.error = null
        binding.tilConfirmPin.error = null
        
        // Save new PIN
        app.setAdminPin(newPin)
        
        // Clear fields
        binding.etCurrentPin.text?.clear()
        binding.etNewPin.text?.clear()
        binding.etConfirmPin.text?.clear()
        
        showSuccessSnackbar(getString(R.string.admin_pin_changed))
        animateButtonSuccess(binding.btnChangePin)
    }
    
    private fun startExam() {
        // Validate prerequisites
        if (!validateExamPrerequisites()) {
            return
        }
        
        showExamStartConfirmation()
    }
    
    private fun validateExamPrerequisites(): Boolean {
        var isValid = true
        val errors = mutableListOf<String>()
        
        // Check device admin
        if (!devicePolicyManager.isAdminActive(deviceAdminComponent)) {
            errors.add("Device admin is not enabled")
            isValid = false
        }
        
        // Check camera permission
        if (ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) 
            != PackageManager.PERMISSION_GRANTED) {
            errors.add("Camera permission is not granted")
            isValid = false
        }
        
        // Check network connectivity
        if (!isNetworkAvailable()) {
            errors.add("No internet connection")
            isValid = false
        }
        
        // Check CBT URL
        if (!validateUrl()) {
            errors.add("Invalid CBT URL")
            isValid = false
        }
        
        if (!isValid) {
            val errorMessage = "Cannot start exam:\n• " + errors.joinToString("\n• ")
            showErrorDialog("Prerequisites Not Met", errorMessage)
        }
        
        return isValid
    }
    
    private fun showExamStartConfirmation() {
        MaterialAlertDialogBuilder(this)
            .setTitle("Start Exam")
            .setMessage("Are you sure you want to start the exam? The app will enter kiosk mode and cannot be exited without admin PIN.")
            .setPositiveButton("Start Exam") { _, _ ->
                launchExamActivity()
            }
            .setNegativeButton("Cancel", null)
            .show()
    }
    
    private fun launchExamActivity() {
        // Clear any previous exam data
        app.clearExamData()
        
        // Navigate to exam activity
        val intent = Intent(this, ExamActivity::class.java)
        startActivity(intent)
        
        // Finish admin panel to prevent returning without PIN
        finish()
        
        overridePendingTransition(android.R.anim.slide_in_left, android.R.anim.slide_out_right)
    }
    
    private fun showExitConfirmation() {
        MaterialAlertDialogBuilder(this)
            .setTitle("Exit Application")
            .setMessage("Are you sure you want to exit the CBT Proctoring app?")
            .setPositiveButton("Exit") { _, _ ->
                finishAffinity()
            }
            .setNegativeButton("Cancel", null)
            .show()
    }
    
    private fun updateSystemStatus() {
        lifecycleScope.launch {
            while (true) {
                updateDeviceAdminStatus()
                updateCameraStatus()
                updateNetworkStatus()
                
                delay(2000) // Update every 2 seconds
            }
        }
    }
    
    private fun updateDeviceAdminStatus() {
        val isEnabled = devicePolicyManager.isAdminActive(deviceAdminComponent)
        
        if (isEnabled) {
            binding.tvDeviceAdminStatus.text = "Active"
            binding.tvDeviceAdminStatus.setTextColor(getColor(R.color.success))
            binding.indicatorDeviceAdmin.setBackgroundResource(R.drawable.bg_status_indicator)
            binding.indicatorDeviceAdmin.backgroundTintList = ContextCompat.getColorStateList(this, R.color.success)
        } else {
            binding.tvDeviceAdminStatus.text = "Inactive"
            binding.tvDeviceAdminStatus.setTextColor(getColor(R.color.error))
            binding.indicatorDeviceAdmin.backgroundTintList = ContextCompat.getColorStateList(this, R.color.error)
        }
    }
    
    private fun updateCameraStatus() {
        val isGranted = ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) == PackageManager.PERMISSION_GRANTED
        
        if (isGranted) {
            binding.tvCameraStatus.text = "Granted"
            binding.tvCameraStatus.setTextColor(getColor(R.color.success))
            binding.indicatorCamera.backgroundTintList = ContextCompat.getColorStateList(this, R.color.success)
        } else {
            binding.tvCameraStatus.text = "Denied"
            binding.tvCameraStatus.setTextColor(getColor(R.color.error))
            binding.indicatorCamera.backgroundTintList = ContextCompat.getColorStateList(this, R.color.error)
        }
    }
    
    private fun updateNetworkStatus() {
        val isConnected = isNetworkAvailable()
        
        if (isConnected) {
            binding.tvNetworkStatus.text = "Connected"
            binding.tvNetworkStatus.setTextColor(getColor(R.color.success))
            binding.indicatorNetwork.backgroundTintList = ContextCompat.getColorStateList(this, R.color.success)
        } else {
            binding.tvNetworkStatus.text = "Disconnected"
            binding.tvNetworkStatus.setTextColor(getColor(R.color.error))
            binding.indicatorNetwork.backgroundTintList = ContextCompat.getColorStateList(this, R.color.error)
        }
    }
    
    private fun isNetworkAvailable(): Boolean {
        val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        
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
    
    private fun animateButtonSuccess(button: View) {
        val originalColor = (button.background as? android.graphics.drawable.ColorDrawable)?.color ?: getColor(R.color.primary)
        
        // Change to success color temporarily
        button.backgroundTintList = ContextCompat.getColorStateList(this, R.color.success)
        
        // Scale animation
        val scaleX = ObjectAnimator.ofFloat(button, "scaleX", 1f, 1.1f, 1f)
        val scaleY = ObjectAnimator.ofFloat(button, "scaleY", 1f, 1.1f, 1f)
        
        scaleX.duration = ANIMATION_DURATION
        scaleY.duration = ANIMATION_DURATION
        scaleX.interpolator = AccelerateDecelerateInterpolator()
        scaleY.interpolator = AccelerateDecelerateInterpolator()
        
        scaleX.start()
        scaleY.start()
        
        // Restore original color after animation
        lifecycleScope.launch {
            delay(ANIMATION_DURATION)
            button.backgroundTintList = ContextCompat.getColorStateList(this@AdminPanelActivity, R.color.primary)
        }
    }
    
    private fun showSuccessSnackbar(message: String) {
        Snackbar.make(binding.root, message, Snackbar.LENGTH_SHORT)
            .setBackgroundTint(getColor(R.color.success))
            .setTextColor(getColor(R.color.white))
            .show()
    }
    
    private fun showErrorSnackbar(message: String) {
        Snackbar.make(binding.root, message, Snackbar.LENGTH_LONG)
            .setBackgroundTint(getColor(R.color.error))
            .setTextColor(getColor(R.color.white))
            .show()
    }
    
    private fun showErrorDialog(title: String, message: String) {
        MaterialAlertDialogBuilder(this)
            .setTitle(title)
            .setMessage(message)
            .setPositiveButton("OK", null)
            .show()
    }
    
    override fun onBackPressed() {
        // Prevent going back without proper navigation
        showExitConfirmation()
    }
}