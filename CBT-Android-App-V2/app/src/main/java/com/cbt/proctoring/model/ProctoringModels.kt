package com.cbt.proctoring.model

import com.google.gson.annotations.SerializedName

/**
 * Data models for proctoring system
 */

/**
 * Proctoring photo data
 */
data class ProctoringPhoto(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("photo_data")
    val photoData: String, // Base64 encoded image
    @SerializedName("timestamp")
    val timestamp: Long,
    @SerializedName("device_info")
    val deviceInfo: Map<String, String>,
    @SerializedName("photo_type")
    val photoType: String = "periodic_capture"
)

/**
 * General proctoring log entry
 */
data class ProctoringLog(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("event_type")
    val eventType: String,
    @SerializedName("event_data")
    val eventData: Map<String, Any>,
    @SerializedName("timestamp")
    val timestamp: Long,
    @SerializedName("device_info")
    val deviceInfo: Map<String, String>
)

/**
 * Cheat attempt detection
 */
data class CheatAttempt(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("cheat_type")
    val cheatType: String,
    @SerializedName("description")
    val description: String,
    @SerializedName("severity")
    val severity: String,
    @SerializedName("timestamp")
    val timestamp: Long,
    @SerializedName("device_info")
    val deviceInfo: Map<String, String>,
    @SerializedName("screenshot")
    val screenshot: String? = null
)

/**
 * Network disconnection event
 */
data class NetworkEvent(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("event_type")
    val eventType: String, // "disconnected", "reconnected", "poor_quality"
    @SerializedName("network_info")
    val networkInfo: Map<String, String>,
    @SerializedName("timestamp")
    val timestamp: Long,
    @SerializedName("duration")
    val duration: Long? = null // For reconnection events
)

/**
 * Exam session data
 */
data class ExamSession(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("exam_id")
    val examId: String,
    @SerializedName("start_time")
    val startTime: Long,
    @SerializedName("end_time")
    val endTime: Long? = null,
    @SerializedName("status")
    val status: String, // "active", "completed", "terminated", "auto_submitted"
    @SerializedName("device_info")
    val deviceInfo: Map<String, String>,
    @SerializedName("security_info")
    val securityInfo: Map<String, String>
)

/**
 * Auto-submit event
 */
data class AutoSubmitEvent(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("reason")
    val reason: String,
    @SerializedName("cheat_attempts")
    val cheatAttempts: Int,
    @SerializedName("timestamp")
    val timestamp: Long,
    @SerializedName("device_info")
    val deviceInfo: Map<String, String>
)

/**
 * Security violation summary
 */
data class SecurityViolation(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("violation_type")
    val violationType: String,
    @SerializedName("violation_count")
    val violationCount: Int,
    @SerializedName("severity_level")
    val severityLevel: String,
    @SerializedName("timestamp")
    val timestamp: Long,
    @SerializedName("device_info")
    val deviceInfo: Map<String, String>
)

/**
 * API Response wrapper
 */
data class ApiResponse<T>(
    @SerializedName("success")
    val success: Boolean,
    @SerializedName("message")
    val message: String,
    @SerializedName("data")
    val data: T? = null,
    @SerializedName("error_code")
    val errorCode: String? = null
)

/**
 * Simple API response for acknowledgment
 */
data class SimpleResponse(
    @SerializedName("success")
    val success: Boolean,
    @SerializedName("message")
    val message: String,
    @SerializedName("timestamp")
    val timestamp: Long
)

/**
 * Batch proctoring data
 */
data class BatchProctoringData(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("student_id")
    val studentId: String,
    @SerializedName("logs")
    val logs: List<ProctoringLog>,
    @SerializedName("photos")
    val photos: List<ProctoringPhoto>,
    @SerializedName("cheat_attempts")
    val cheatAttempts: List<CheatAttempt>,
    @SerializedName("network_events")
    val networkEvents: List<NetworkEvent>,
    @SerializedName("timestamp")
    val timestamp: Long
)

/**
 * Device fingerprint for security
 */
data class DeviceFingerprint(
    @SerializedName("device_id")
    val deviceId: String,
    @SerializedName("manufacturer")
    val manufacturer: String,
    @SerializedName("model")
    val model: String,
    @SerializedName("brand")
    val brand: String,
    @SerializedName("product")
    val product: String,
    @SerializedName("hardware")
    val hardware: String,
    @SerializedName("fingerprint")
    val fingerprint: String,
    @SerializedName("android_version")
    val androidVersion: String,
    @SerializedName("api_level")
    val apiLevel: Int,
    @SerializedName("build_type")
    val buildType: String,
    @SerializedName("tags")
    val tags: String,
    @SerializedName("security_score")
    val securityScore: Int,
    @SerializedName("timestamp")
    val timestamp: Long
)

/**
 * Proctoring statistics
 */
data class ProctoringStats(
    @SerializedName("session_id")
    val sessionId: String,
    @SerializedName("total_photos")
    val totalPhotos: Int,
    @SerializedName("total_cheat_attempts")
    val totalCheatAttempts: Int,
    @SerializedName("network_disconnections")
    val networkDisconnections: Int,
    @SerializedName("exam_duration")
    val examDuration: Long,
    @SerializedName("security_score")
    val securityScore: Int,
    @SerializedName("violation_score")
    val violationScore: Int,
    @SerializedName("timestamp")
    val timestamp: Long
)

/**
 * Enum classes for type safety
 */
enum class EventType(val value: String) {
    EXAM_STARTED("exam_started"),
    EXAM_FINISHED("exam_finished"),
    EXAM_PAUSED("exam_paused"),
    EXAM_RESUMED("exam_resumed"),
    PHOTO_CAPTURED("photo_captured"),
    CHEAT_DETECTED("cheat_detected"),
    NETWORK_DISCONNECTED("network_disconnected"),
    NETWORK_RECONNECTED("network_reconnected"),
    APP_BACKGROUNDED("app_backgrounded"),
    APP_FOREGROUNDED("app_foregrounded"),
    SECURITY_VIOLATION("security_violation"),
    AUTO_SUBMIT("auto_submit")
}

enum class CheatType(val value: String) {
    EXIT_ATTEMPT("exit_attempt"),
    APP_SWITCH("app_switch"),
    SCREEN_CAPTURE("screen_capture"),
    MULTIPLE_FACES("multiple_faces"),
    NO_FACE_DETECTED("no_face_detected"),
    SUSPICIOUS_BEHAVIOR("suspicious_behavior"),
    EXTERNAL_DEVICE("external_device"),
    NETWORK_MANIPULATION("network_manipulation"),
    UNKNOWN("unknown")
}

enum class SeverityLevel(val value: String) {
    LOW("low"),
    MEDIUM("medium"),
    HIGH("high"),
    CRITICAL("critical")
}

enum class ExamStatus(val value: String) {
    ACTIVE("active"),
    PAUSED("paused"),
    COMPLETED("completed"),
    TERMINATED("terminated"),
    AUTO_SUBMITTED("auto_submitted"),
    EXPIRED("expired")
}