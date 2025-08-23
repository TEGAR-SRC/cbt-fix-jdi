package com.cbt.proctoring.ui.exam

import android.annotation.SuppressLint
import android.app.admin.DevicePolicyManager
import android.content.ComponentName
import android.content.Context
import android.content.Intent
import android.graphics.Bitmap
import android.os.Build
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.view.KeyEvent
import android.view.View
import android.view.WindowManager
import android.webkit.ConsoleMessage
import android.webkit.WebChromeClient
import android.webkit.WebResourceError
import android.webkit.WebResourceRequest
import android.webkit.WebSettings
import android.webkit.WebView
import android.webkit.WebViewClient
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.cbt.proctoring.CBTApplication
import com.cbt.proctoring.R
import com.cbt.proctoring.databinding.ActivityExamBinding
import com.cbt.proctoring.receiver.DeviceAdminReceiver
import com.cbt.proctoring.service.ProctoringService
import com.cbt.proctoring.service.NetworkMonitoringService
import com.cbt.proctoring.ui.admin.AdminLoginActivity
import com.cbt.proctoring.util.NetworkUtil
import com.google.android.material.dialog.MaterialAlertDialogBuilder
import kotlinx.coroutines.Job
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

/**
 * Exam Activity - Core CBT browser with security and proctoring
 * Implements kiosk mode, anti-cheat detection, and proctoring features
 */
class ExamActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivityExamBinding
    private lateinit var app: CBTApplication
    private lateinit var devicePolicyManager: DevicePolicyManager
    private lateinit var deviceAdminComponent: ComponentName
    private lateinit var networkUtil: NetworkUtil
    
    private var examStartTime = System.currentTimeMillis()
    private var cheatAttempts = 0
    private var isExamLoaded = false
    private var isWarningShown = false
    
    private var timerJob: Job? = null
    private var proctoringJob: Job? = null
    private var networkMonitorJob: Job? = null
    
    companion object {
        private const val MAX_CHEAT_ATTEMPTS = 3
        private const val PHOTO_CAPTURE_INTERVAL = 30000L // 30 seconds
        private const val NETWORK_CHECK_INTERVAL = 5000L // 5 seconds
        private const val VIOLATION_WARNING_DURATION = 5000L // 5 seconds
    }
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityExamBinding.inflate(layoutInflater)
        setContentView(binding.root)
        
        initializeComponents()
        setupSecurity()
        setupWebView()
        setupClickListeners()
        startProctoring()
        loadExamPage()
    }
    
    private fun initializeComponents() {
        app = CBTApplication.getInstance()
        devicePolicyManager = getSystemService(Context.DEVICE_POLICY_SERVICE) as DevicePolicyManager
        deviceAdminComponent = ComponentName(this, DeviceAdminReceiver::class.java)
        networkUtil = NetworkUtil(this)
        
        examStartTime = System.currentTimeMillis()
        cheatAttempts = app.getCheatAttempts()
    }
    
    @SuppressLint("WrongConstant")
    private fun setupSecurity() {
        // Enable FLAG_SECURE to prevent screenshots and screen recording
        window.setFlags(
            WindowManager.LayoutParams.FLAG_SECURE,
            WindowManager.LayoutParams.FLAG_SECURE
        )
        
        // Enable kiosk mode (Lock Task Mode)
        if (devicePolicyManager.isAdminActive(deviceAdminComponent)) {
            try {
                startLockTask()
            } catch (e: Exception) {
                // Handle lock task mode failure
                Toast.makeText(this, "Could not enable kiosk mode", Toast.LENGTH_SHORT).show()
            }
        }
        
        // Disable recent apps and home button
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            window.addFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN)
        }
    }
    
    @SuppressLint("SetJavaScriptEnabled")
    private fun setupWebView() {
        val webSettings = binding.webview.settings
        
        // Enable JavaScript and DOM storage
        webSettings.javaScriptEnabled = true
        webSettings.domStorageEnabled = true
        webSettings.databaseEnabled = true
        webSettings.allowFileAccess = false
        webSettings.allowContentAccess = false
        webSettings.allowFileAccessFromFileURLs = false
        webSettings.allowUniversalAccessFromFileURLs = false
        
        // Disable zoom and other interactions
        webSettings.setSupportZoom(false)
        webSettings.builtInZoomControls = false
        webSettings.displayZoomControls = false
        
        // Disable text selection and context menu
        binding.webview.setOnLongClickListener { true }
        binding.webview.isLongClickable = false
        binding.webview.isHapticFeedbackEnabled = false
        
        // Set cache and loading settings
        webSettings.cacheMode = WebSettings.LOAD_NO_CACHE
        webSettings.setAppCacheEnabled(false)
        webSettings.loadsImagesAutomatically = true
        webSettings.blockNetworkImage = false
        webSettings.blockNetworkLoads = false
        
        // Set user agent
        webSettings.userAgentString = webSettings.userAgentString + " CBTProctoring/1.0"
        
        // Disable multiple windows
        webSettings.setSupportMultipleWindows(false)
        
        // Set WebView client
        binding.webview.webViewClient = object : WebViewClient() {
            override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
                super.onPageStarted(view, url, favicon)
                showLoading(true)
            }
            
            override fun onPageFinished(view: WebView?, url: String?) {
                super.onPageFinished(view, url)
                showLoading(false)
                isExamLoaded = true
                
                // Inject security JavaScript
                injectSecurityJavaScript()
            }
            
            override fun onReceivedError(view: WebView?, request: WebResourceRequest?, error: WebResourceError?) {
                super.onReceivedError(view, request, error)
                showError(true)
            }
        }
        
        // Set WebChrome client
        binding.webview.webChromeClient = object : WebChromeClient() {
            override fun onConsoleMessage(consoleMessage: ConsoleMessage?): Boolean {
                // Log console messages for debugging (in development)
                return true
            }
            
            override fun onProgressChanged(view: WebView?, newProgress: Int) {
                super.onProgressChanged(view, newProgress)
                // You can show progress here if needed
            }
        }
    }
    
    private fun injectSecurityJavaScript() {
        val securityScript = """
            javascript:(function() {
                // Disable right-click context menu
                document.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });
                
                // Disable text selection
                document.addEventListener('selectstart', function(e) {
                    e.preventDefault();
                    return false;
                });
                
                // Disable drag and drop
                document.addEventListener('dragstart', function(e) {
                    e.preventDefault();
                    return false;
                });
                
                // Disable copy, cut, paste
                document.addEventListener('keydown', function(e) {
                    if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 88)) {
                        e.preventDefault();
                        return false;
                    }
                    if (e.keyCode === 123) { // F12
                        e.preventDefault();
                        return false;
                    }
                    if (e.ctrlKey && e.shiftKey && e.keyCode === 73) { // Ctrl+Shift+I
                        e.preventDefault();
                        return false;
                    }
                    if (e.ctrlKey && e.shiftKey && e.keyCode === 74) { // Ctrl+Shift+J
                        e.preventDefault();
                        return false;
                    }
                    if (e.ctrlKey && e.keyCode === 85) { // Ctrl+U
                        e.preventDefault();
                        return false;
                    }
                });
                
                // Disable print
                window.print = function() {
                    alert('Printing is disabled during exam');
                };
                
                // Override console to prevent debugging
                console.log = console.info = console.warn = console.error = function() {};
                
                // Disable selection styling
                var style = document.createElement('style');
                style.innerHTML = '* { -webkit-user-select: none !important; -moz-user-select: none !important; -ms-user-select: none !important; user-select: none !important; }';
                document.head.appendChild(style);
                
                // Add CBT proctoring indicator
                var indicator = document.createElement('div');
                indicator.innerHTML = '🔒 Proctored Exam';
                indicator.style.cssText = 'position: fixed; top: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; z-index: 9999; pointer-events: none;';
                document.body.appendChild(indicator);
            })();
        """.trimIndent()
        
        binding.webview.evaluateJavascript(securityScript, null)
    }
    
    private fun setupClickListeners() {
        binding.btnRetry.setOnClickListener {
            loadExamPage()
        }
        
        binding.btnExitExam.setOnClickListener {
            handleExitAttempt()
        }
        
        binding.btnContinueExam.setOnClickListener {
            hideWarning()
        }
        
        binding.btnForceExit.setOnClickListener {
            showAdminPinDialog()
        }
    }
    
    private fun loadExamPage() {
        showError(false)
        showLoading(true)
        
        val cbtUrl = app.getCbtUrl()
        binding.webview.loadUrl(cbtUrl)
    }
    
    private fun startProctoring() {
        // Start proctoring service
        val proctoringIntent = Intent(this, ProctoringService::class.java)
        startForegroundService(proctoringIntent)
        
        // Start network monitoring service
        val networkIntent = Intent(this, NetworkMonitoringService::class.java)
        startForegroundService(networkIntent)
        
        // Start timer
        startExamTimer()
        
        // Start periodic monitoring
        startPeriodicMonitoring()
    }
    
    private fun startExamTimer() {
        timerJob = lifecycleScope.launch {
            while (true) {
                val elapsedTime = System.currentTimeMillis() - examStartTime
                val hours = (elapsedTime / 3600000).toInt()
                val minutes = ((elapsedTime % 3600000) / 60000).toInt()
                val seconds = ((elapsedTime % 60000) / 1000).toInt()
                
                val timeFormat = String.format(Locale.getDefault(), "%02d:%02d:%02d", hours, minutes, seconds)
                binding.tvTimer.text = timeFormat
                
                delay(1000)
            }
        }
    }
    
    private fun startPeriodicMonitoring() {
        // Network monitoring
        networkMonitorJob = lifecycleScope.launch {
            networkUtil.isNetworkAvailable.collect { isConnected ->
                updateNetworkStatus(isConnected)
            }
        }
        
        // Update violation counter
        updateViolationCounter()
    }
    
    private fun updateNetworkStatus(isConnected: Boolean) {
        if (isConnected) {
            binding.ivNetworkStatus.setImageResource(R.drawable.ic_network_connected)
            binding.ivNetworkStatus.setColorFilter(getColor(R.color.network_connected))
            binding.tvNetworkStatus.text = getString(R.string.exam_network_status)
            binding.tvNetworkStatus.setTextColor(getColor(R.color.exam_text))
        } else {
            binding.ivNetworkStatus.setImageResource(R.drawable.ic_network_disconnected)
            binding.ivNetworkStatus.setColorFilter(getColor(R.color.network_disconnected))
            binding.tvNetworkStatus.text = getString(R.string.exam_network_disconnected)
            binding.tvNetworkStatus.setTextColor(getColor(R.color.exam_error))
            
            // Handle network disconnection
            handleNetworkDisconnection()
        }
    }
    
    private fun handleNetworkDisconnection() {
        if (isExamLoaded) {
            Toast.makeText(this, "Network disconnected. Exam paused.", Toast.LENGTH_LONG).show()
            // You can pause the exam or show a waiting screen here
        }
    }
    
    private fun handleCheatAttempt() {
        cheatAttempts = app.incrementCheatAttempts()
        updateViolationCounter()
        
        if (cheatAttempts >= MAX_CHEAT_ATTEMPTS) {
            autoSubmitExam()
        } else {
            showCheatWarning()
        }
    }
    
    private fun showCheatWarning() {
        if (isWarningShown) return
        
        isWarningShown = true
        binding.warningOverlay.visibility = View.VISIBLE
        binding.warningOverlay.alpha = 0f
        binding.warningOverlay.animate()
            .alpha(1f)
            .setDuration(300)
            .start()
        
        binding.tvWarningTitle.text = getString(R.string.security_cheat_attempt)
        binding.tvWarningMessage.text = "Attempt ${cheatAttempts}/${MAX_CHEAT_ATTEMPTS}. " +
                "Continuing to attempt to exit will result in automatic exam submission."
        
        // Auto-hide warning after delay
        Handler(Looper.getMainLooper()).postDelayed({
            hideWarning()
        }, VIOLATION_WARNING_DURATION)
    }
    
    private fun hideWarning() {
        if (!isWarningShown) return
        
        binding.warningOverlay.animate()
            .alpha(0f)
            .setDuration(300)
            .withEndAction {
                binding.warningOverlay.visibility = View.GONE
                isWarningShown = false
            }
            .start()
    }
    
    private fun autoSubmitExam() {
        MaterialAlertDialogBuilder(this)
            .setTitle("Exam Auto-Submitted")
            .setMessage(getString(R.string.security_auto_submit))
            .setPositiveButton("OK") { _, _ ->
                finishExam()
            }
            .setCancelable(false)
            .show()
    }
    
    private fun updateViolationCounter() {
        val maxAttempts = MAX_CHEAT_ATTEMPTS
        binding.tvViolationCounter.text = "Violations: ${cheatAttempts}/${maxAttempts}"
        
        if (cheatAttempts > 0) {
            binding.tvViolationCounter.alpha = 1f
            
            // Change color based on severity
            val color = when {
                cheatAttempts >= maxAttempts -> getColor(R.color.exam_error)
                cheatAttempts >= maxAttempts - 1 -> getColor(R.color.exam_warning)
                else -> getColor(R.color.exam_text)
            }
            binding.tvViolationCounter.setTextColor(color)
        }
    }
    
    private fun handleExitAttempt() {
        handleCheatAttempt()
    }
    
    private fun showAdminPinDialog() {
        val input = android.widget.EditText(this)
        input.inputType = android.text.InputType.TYPE_CLASS_NUMBER or android.text.InputType.TYPE_NUMBER_VARIATION_PASSWORD
        input.hint = "Enter Admin PIN"
        
        AlertDialog.Builder(this)
            .setTitle("Admin PIN Required")
            .setMessage("Enter admin PIN to exit exam:")
            .setView(input)
            .setPositiveButton("Exit") { _, _ ->
                val enteredPin = input.text.toString()
                if (enteredPin == app.getAdminPin()) {
                    finishExam()
                } else {
                    Toast.makeText(this, "Invalid PIN", Toast.LENGTH_SHORT).show()
                    handleCheatAttempt()
                }
            }
            .setNegativeButton("Cancel") { dialog, _ ->
                dialog.dismiss()
                hideWarning()
            }
            .show()
    }
    
    private fun finishExam() {
        // Stop services
        stopService(Intent(this, ProctoringService::class.java))
        stopService(Intent(this, NetworkMonitoringService::class.java))
        
        // Cancel coroutines
        timerJob?.cancel()
        proctoringJob?.cancel()
        networkMonitorJob?.cancel()
        
        // Exit lock task mode
        try {
            stopLockTask()
        } catch (e: Exception) {
            // Handle exit lock task failure
        }
        
        // Clear exam data
        app.clearExamData()
        
        // Navigate to admin login
        val intent = Intent(this, AdminLoginActivity::class.java)
        intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
        startActivity(intent)
        finish()
    }
    
    private fun showLoading(show: Boolean) {
        binding.loadingOverlay.visibility = if (show) View.VISIBLE else View.GONE
        binding.loadingOverlay.alpha = if (show) 1f else 0f
    }
    
    private fun showError(show: Boolean) {
        binding.errorOverlay.visibility = if (show) View.VISIBLE else View.GONE
        binding.errorOverlay.alpha = if (show) 1f else 0f
    }
    
    // Override key events to prevent exit
    override fun onKeyDown(keyCode: Int, event: KeyEvent?): Boolean {
        when (keyCode) {
            KeyEvent.KEYCODE_BACK,
            KeyEvent.KEYCODE_HOME,
            KeyEvent.KEYCODE_MENU,
            KeyEvent.KEYCODE_APP_SWITCH -> {
                handleCheatAttempt()
                return true
            }
        }
        return super.onKeyDown(keyCode, event)
    }
    
    override fun onBackPressed() {
        handleCheatAttempt()
    }
    
    override fun onUserLeaveHint() {
        super.onUserLeaveHint()
        handleCheatAttempt()
    }
    
    override fun onPause() {
        super.onPause()
        if (isExamLoaded) {
            handleCheatAttempt()
        }
    }
    
    override fun onDestroy() {
        super.onDestroy()
        
        // Clean up
        timerJob?.cancel()
        proctoringJob?.cancel()
        networkMonitorJob?.cancel()
        
        // Stop services
        stopService(Intent(this, ProctoringService::class.java))
        stopService(Intent(this, NetworkMonitoringService::class.java))
    }
}