package com.cbt.proctoring.ui.admin

import android.animation.ObjectAnimator
import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.view.View
import android.view.animation.AccelerateDecelerateInterpolator
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.cbt.proctoring.CBTApplication
import com.cbt.proctoring.R
import com.cbt.proctoring.databinding.ActivityAdminLoginBinding
import com.google.android.material.snackbar.Snackbar
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch

/**
 * Admin Login Activity
 * Handles admin authentication using PIN
 */
class AdminLoginActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivityAdminLoginBinding
    private lateinit var app: CBTApplication
    
    companion object {
        private const val ANIMATION_DURATION = 300L
        private const val ERROR_DISPLAY_DURATION = 3000L
    }
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAdminLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        app = CBTApplication.getInstance()
        
        setupUI()
        setupClickListeners()
    }
    
    private fun setupUI() {
        // Setup PIN input
        binding.etPin.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                // Clear error when user types
                hideError()
            }
            
            override fun afterTextChanged(s: Editable?) {
                // Auto-attempt login when PIN length is 6
                if (s?.length == 6) {
                    attemptLogin()
                }
            }
        })
        
        // Focus on PIN input
        binding.etPin.requestFocus()
    }
    
    private fun setupClickListeners() {
        binding.btnLogin.setOnClickListener {
            attemptLogin()
        }
    }
    
    private fun attemptLogin() {
        val enteredPin = binding.etPin.text.toString().trim()
        
        if (enteredPin.isEmpty()) {
            showError(getString(R.string.admin_pin_empty))
            return
        }
        
        if (enteredPin.length < 4) {
            showError("PIN must be at least 4 digits")
            return
        }
        
        showLoading(true)
        
        // Simulate authentication delay for better UX
        lifecycleScope.launch {
            delay(500)
            
            val adminPin = app.getAdminPin()
            
            if (enteredPin == adminPin) {
                onLoginSuccess()
            } else {
                onLoginFailure()
            }
            
            showLoading(false)
        }
    }
    
    private fun onLoginSuccess() {
        showSuccessMessage()
        
        lifecycleScope.launch {
            delay(500)
            
            // Navigate to Admin Panel
            val intent = Intent(this@AdminLoginActivity, AdminPanelActivity::class.java)
            startActivity(intent)
            finish()
            
            // Add transition animation
            overridePendingTransition(android.R.anim.slide_in_left, android.R.anim.slide_out_right)
        }
    }
    
    private fun onLoginFailure() {
        showError(getString(R.string.admin_login_error))
        
        // Shake animation for error feedback
        val shakeAnimator = ObjectAnimator.ofFloat(binding.cardLoginForm, "translationX", 0f, 25f, -25f, 25f, -25f, 15f, -15f, 6f, -6f, 0f)
        shakeAnimator.duration = 600
        shakeAnimator.interpolator = AccelerateDecelerateInterpolator()
        shakeAnimator.start()
        
        // Clear PIN input
        binding.etPin.text?.clear()
        
        // Refocus on PIN input
        binding.etPin.requestFocus()
    }
    
    private fun showLoading(show: Boolean) {
        if (show) {
            binding.loadingOverlay.visibility = View.VISIBLE
            binding.loadingOverlay.alpha = 0f
            ObjectAnimator.ofFloat(binding.loadingOverlay, "alpha", 0f, 1f).apply {
                duration = ANIMATION_DURATION
                start()
            }
            
            // Disable inputs
            binding.etPin.isEnabled = false
            binding.btnLogin.isEnabled = false
        } else {
            ObjectAnimator.ofFloat(binding.loadingOverlay, "alpha", 1f, 0f).apply {
                duration = ANIMATION_DURATION
                addListener(object : android.animation.AnimatorListenerAdapter() {
                    override fun onAnimationEnd(animation: android.animation.Animator) {
                        binding.loadingOverlay.visibility = View.GONE
                    }
                })
                start()
            }
            
            // Enable inputs
            binding.etPin.isEnabled = true
            binding.btnLogin.isEnabled = true
        }
    }
    
    private fun showError(message: String) {
        binding.tvError.text = message
        binding.tvError.alpha = 0f
        binding.tvError.visibility = View.VISIBLE
        
        // Fade in error
        ObjectAnimator.ofFloat(binding.tvError, "alpha", 0f, 1f).apply {
            duration = ANIMATION_DURATION
            start()
        }
        
        // Auto-hide error after delay
        lifecycleScope.launch {
            delay(ERROR_DISPLAY_DURATION)
            hideError()
        }
        
        // Add error styling to input
        binding.tilPin.error = " " // Show error state without message (using TextView instead)
    }
    
    private fun hideError() {
        if (binding.tvError.visibility == View.VISIBLE) {
            ObjectAnimator.ofFloat(binding.tvError, "alpha", 1f, 0f).apply {
                duration = ANIMATION_DURATION
                addListener(object : android.animation.AnimatorListenerAdapter() {
                    override fun onAnimationEnd(animation: android.animation.Animator) {
                        binding.tvError.visibility = View.GONE
                    }
                })
                start()
            }
        }
        
        // Clear error styling
        binding.tilPin.error = null
    }
    
    private fun showSuccessMessage() {
        Snackbar.make(binding.root, getString(R.string.admin_login_success), Snackbar.LENGTH_SHORT)
            .setBackgroundTint(getColor(R.color.success))
            .setTextColor(getColor(R.color.white))
            .show()
    }
    
    override fun onBackPressed() {
        // Allow back press to exit app from admin login
        finishAffinity()
    }
    
    override fun onResume() {
        super.onResume()
        
        // Clear PIN when returning to this activity
        binding.etPin.text?.clear()
        hideError()
        
        // Focus on PIN input
        binding.etPin.requestFocus()
    }
}