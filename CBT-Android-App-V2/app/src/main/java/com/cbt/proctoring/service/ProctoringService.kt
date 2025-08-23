package com.cbt.proctoring.service

import android.Manifest
import android.app.Notification
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.app.Service
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.graphics.ImageFormat
import android.graphics.Matrix
import android.hardware.camera2.CameraCaptureSession
import android.hardware.camera2.CameraCharacteristics
import android.hardware.camera2.CameraDevice
import android.hardware.camera2.CameraManager
import android.hardware.camera2.CaptureRequest
import android.media.Image
import android.media.ImageReader
import android.os.Build
import android.os.Handler
import android.os.HandlerThread
import android.os.IBinder
import android.util.Base64
import android.util.Log
import android.util.Size
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import com.cbt.proctoring.CBTApplication
import com.cbt.proctoring.R
import com.cbt.proctoring.api.ProctoringApiService
import com.cbt.proctoring.model.ProctoringPhoto
import com.cbt.proctoring.ui.exam.ExamActivity
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.Job
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch
import java.io.ByteArrayOutputStream
import java.nio.ByteBuffer

/**
 * Proctoring Service for camera monitoring during exam
 * Captures photos periodically and sends to backend
 */
class ProctoringService : Service() {

    private lateinit var cameraManager: CameraManager
    private lateinit var imageReader: ImageReader
    private lateinit var backgroundThread: HandlerThread
    private lateinit var backgroundHandler: Handler
    private lateinit var app: CBTApplication
    private lateinit var apiService: ProctoringApiService
    
    private var cameraDevice: CameraDevice? = null
    private var cameraCaptureSession: CameraCaptureSession? = null
    private var cameraId: String? = null
    
    private val serviceScope = CoroutineScope(Dispatchers.IO + Job())
    private var captureJob: Job? = null
    
    companion object {
        private const val TAG = "ProctoringService"
        private const val NOTIFICATION_ID = 1001
        private const val CAMERA_PERMISSION_REQUEST = 1001
        private const val PHOTO_CAPTURE_INTERVAL = 30000L // 30 seconds
        private const val IMAGE_WIDTH = 640
        private const val IMAGE_HEIGHT = 480
        private const val JPEG_QUALITY = 70
    }

    override fun onCreate() {
        super.onCreate()
        Log.d(TAG, "ProctoringService created")
        
        app = CBTApplication.getInstance()
        apiService = ProctoringApiService.create()
        
        initializeComponents()
        createNotificationChannel()
        startForeground(NOTIFICATION_ID, createNotification())
        
        startCamera()
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        Log.d(TAG, "ProctoringService started")
        return START_STICKY // Restart if killed
    }

    override fun onBind(intent: Intent?): IBinder? = null

    private fun initializeComponents() {
        cameraManager = getSystemService(Context.CAMERA_SERVICE) as CameraManager
        
        // Setup background thread for camera operations
        backgroundThread = HandlerThread("CameraBackground").also { it.start() }
        backgroundHandler = Handler(backgroundThread.looper)
        
        // Setup image reader
        imageReader = ImageReader.newInstance(IMAGE_WIDTH, IMAGE_HEIGHT, ImageFormat.JPEG, 2)
        imageReader.setOnImageAvailableListener(imageAvailableListener, backgroundHandler)
    }

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channel = NotificationChannel(
                CBTApplication.CHANNEL_PROCTORING,
                getString(R.string.notification_channel_proctoring),
                NotificationManager.IMPORTANCE_LOW
            ).apply {
                description = getString(R.string.notification_proctoring_text)
                setShowBadge(false)
                enableLights(false)
                enableVibration(false)
            }
            
            val notificationManager = getSystemService(NotificationManager::class.java)
            notificationManager.createNotificationChannel(channel)
        }
    }

    private fun createNotification(): Notification {
        val intent = Intent(this, ExamActivity::class.java)
        val pendingIntent = PendingIntent.getActivity(
            this, 0, intent, 
            PendingIntent.FLAG_UPDATE_CURRENT or PendingIntent.FLAG_IMMUTABLE
        )

        return NotificationCompat.Builder(this, CBTApplication.CHANNEL_PROCTORING)
            .setContentTitle(getString(R.string.proctoring_title))
            .setContentText(getString(R.string.proctoring_message))
            .setSmallIcon(R.drawable.ic_camera)
            .setContentIntent(pendingIntent)
            .setOngoing(true)
            .setSilent(true)
            .setPriority(NotificationCompat.PRIORITY_LOW)
            .setCategory(NotificationCompat.CATEGORY_SERVICE)
            .build()
    }

    private fun startCamera() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.CAMERA) 
            != PackageManager.PERMISSION_GRANTED) {
            Log.e(TAG, "Camera permission not granted")
            stopSelf()
            return
        }

        try {
            // Get front camera ID
            for (id in cameraManager.cameraIdList) {
                val characteristics = cameraManager.getCameraCharacteristics(id)
                val cameraDirection = characteristics.get(CameraCharacteristics.LENS_FACING)
                
                if (cameraDirection == CameraCharacteristics.LENS_FACING_FRONT) {
                    cameraId = id
                    break
                }
            }

            if (cameraId == null) {
                Log.e(TAG, "Front camera not found")
                stopSelf()
                return
            }

            // Open camera
            cameraManager.openCamera(cameraId!!, cameraStateCallback, backgroundHandler)
            
        } catch (e: Exception) {
            Log.e(TAG, "Error starting camera", e)
            stopSelf()
        }
    }

    private val cameraStateCallback = object : CameraDevice.StateCallback() {
        override fun onOpened(camera: CameraDevice) {
            Log.d(TAG, "Camera opened")
            cameraDevice = camera
            createCameraPreviewSession()
        }

        override fun onDisconnected(camera: CameraDevice) {
            Log.d(TAG, "Camera disconnected")
            cameraDevice?.close()
            cameraDevice = null
        }

        override fun onError(camera: CameraDevice, error: Int) {
            Log.e(TAG, "Camera error: $error")
            cameraDevice?.close()
            cameraDevice = null
            stopSelf()
        }
    }

    private fun createCameraPreviewSession() {
        try {
            val surface = imageReader.surface
            val captureRequestBuilder = cameraDevice?.createCaptureRequest(CameraDevice.TEMPLATE_STILL_CAPTURE)
            captureRequestBuilder?.addTarget(surface)

            cameraDevice?.createCaptureSession(
                listOf(surface),
                object : CameraCaptureSession.StateCallback() {
                    override fun onConfigured(session: CameraCaptureSession) {
                        Log.d(TAG, "Camera capture session configured")
                        cameraCaptureSession = session
                        startPhotoCapture()
                    }

                    override fun onConfigureFailed(session: CameraCaptureSession) {
                        Log.e(TAG, "Camera capture session configuration failed")
                        stopSelf()
                    }
                },
                backgroundHandler
            )
        } catch (e: Exception) {
            Log.e(TAG, "Error creating camera preview session", e)
            stopSelf()
        }
    }

    private fun startPhotoCapture() {
        captureJob = serviceScope.launch {
            while (true) {
                try {
                    capturePhoto()
                    delay(PHOTO_CAPTURE_INTERVAL)
                } catch (e: Exception) {
                    Log.e(TAG, "Error in photo capture loop", e)
                    delay(5000) // Wait before retrying
                }
            }
        }
    }

    private fun capturePhoto() {
        try {
            val reader = imageReader
            val captureBuilder = cameraDevice?.createCaptureRequest(CameraDevice.TEMPLATE_STILL_CAPTURE)
            captureBuilder?.addTarget(reader.surface)
            
            // Set capture parameters
            captureBuilder?.set(CaptureRequest.CONTROL_MODE, CaptureRequest.CONTROL_MODE_AUTO)
            captureBuilder?.set(CaptureRequest.JPEG_QUALITY, JPEG_QUALITY.toByte())

            val captureCallback = object : CameraCaptureSession.CaptureCallback() {
                // Capture callback implementation if needed
            }

            cameraCaptureSession?.capture(captureBuilder?.build()!!, captureCallback, backgroundHandler)
            
        } catch (e: Exception) {
            Log.e(TAG, "Error capturing photo", e)
        }
    }

    private val imageAvailableListener = ImageReader.OnImageAvailableListener { reader ->
        var image: Image? = null
        try {
            image = reader.acquireLatestImage()
            if (image != null) {
                processImage(image)
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error processing captured image", e)
        } finally {
            image?.close()
        }
    }

    private fun processImage(image: Image) {
        try {
            val buffer = image.planes[0].buffer
            val bytes = ByteArray(buffer.remaining())
            buffer.get(bytes)
            
            // Convert to bitmap
            val bitmap = BitmapFactory.decodeByteArray(bytes, 0, bytes.size)
            
            // Rotate image for front camera (usually needs 90 degree rotation)
            val rotatedBitmap = rotateBitmap(bitmap, 270f) // Adjust rotation as needed
            
            // Compress bitmap
            val outputStream = ByteArrayOutputStream()
            rotatedBitmap.compress(Bitmap.CompressFormat.JPEG, JPEG_QUALITY, outputStream)
            val compressedBytes = outputStream.toByteArray()
            
            // Convert to base64
            val base64Image = Base64.encodeToString(compressedBytes, Base64.DEFAULT)
            
            // Send to server
            sendPhotoToServer(base64Image)
            
            // Update last photo timestamp
            app.getAppPreferences().edit()
                .putLong(CBTApplication.PREF_LAST_PHOTO_TIMESTAMP, System.currentTimeMillis())
                .apply()
            
            Log.d(TAG, "Photo captured and processed successfully")
            
        } catch (e: Exception) {
            Log.e(TAG, "Error processing image", e)
        }
    }

    private fun rotateBitmap(bitmap: Bitmap, degrees: Float): Bitmap {
        val matrix = Matrix()
        matrix.postRotate(degrees)
        return Bitmap.createBitmap(bitmap, 0, 0, bitmap.width, bitmap.height, matrix, true)
    }

    private fun sendPhotoToServer(base64Image: String) {
        serviceScope.launch {
            try {
                val sessionId = app.getAppPreferences().getString(CBTApplication.PREF_EXAM_SESSION_ID, "unknown") ?: "unknown"
                val studentId = "student_${System.currentTimeMillis()}" // Replace with actual student ID
                
                val proctoringPhoto = ProctoringPhoto(
                    sessionId = sessionId,
                    studentId = studentId,
                    photoData = base64Image,
                    timestamp = System.currentTimeMillis(),
                    deviceInfo = getDeviceInfo()
                )

                val response = apiService.logPhotoCapture(proctoringPhoto)
                
                if (response.isSuccessful) {
                    Log.d(TAG, "Photo sent to server successfully")
                } else {
                    Log.e(TAG, "Failed to send photo to server: ${response.code()}")
                }
                
            } catch (e: Exception) {
                Log.e(TAG, "Error sending photo to server", e)
            }
        }
    }

    private fun getDeviceInfo(): Map<String, String> {
        return mapOf(
            "manufacturer" to Build.MANUFACTURER,
            "model" to Build.MODEL,
            "androidVersion" to Build.VERSION.RELEASE,
            "appVersion" to "1.0.0",
            "timestamp" to System.currentTimeMillis().toString()
        )
    }

    override fun onDestroy() {
        super.onDestroy()
        Log.d(TAG, "ProctoringService destroyed")
        
        // Cancel capture job
        captureJob?.cancel()
        
        // Close camera
        cameraCaptureSession?.close()
        cameraDevice?.close()
        
        // Clean up background thread
        backgroundThread.quitSafely()
        try {
            backgroundThread.join()
        } catch (e: InterruptedException) {
            Log.e(TAG, "Error stopping background thread", e)
        }
        
        // Close image reader
        imageReader.close()
    }
}