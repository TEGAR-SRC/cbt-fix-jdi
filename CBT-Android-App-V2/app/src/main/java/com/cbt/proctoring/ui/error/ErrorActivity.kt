package com.cbt.proctoring.ui.error

import android.content.Intent
import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.cbt.proctoring.databinding.ActivityErrorBinding
import com.cbt.proctoring.ui.splash.SplashActivity

/**
 * Error Activity to display error messages and handle error scenarios
 */
class ErrorActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivityErrorBinding
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityErrorBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        setupErrorDisplay()
        setupClickListeners()
    }
    
    private fun setupErrorDisplay() {
        val errorTitle = intent.getStringExtra("error_title") ?: "Error"
        val errorMessage = intent.getStringExtra("error_message") ?: "An error occurred"
        val showRetry = intent.getBooleanExtra("show_retry", true)
        
        binding.tvErrorTitle.text = errorTitle
        binding.tvErrorMessage.text = errorMessage
        
        if (showRetry) {
            binding.btnRetry.visibility = View.VISIBLE
        } else {
            binding.btnRetry.visibility = View.GONE
        }
    }
    
    private fun setupClickListeners() {
        binding.btnRetry.setOnClickListener {
            // Restart the app
            val intent = Intent(this, SplashActivity::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            startActivity(intent)
            finish()
        }
        
        binding.btnExit.setOnClickListener {
            // Exit the app
            finishAffinity()
        }
    }
    
    override fun onBackPressed() {
        // Disable back button on error screen
        // Do nothing
    }
}