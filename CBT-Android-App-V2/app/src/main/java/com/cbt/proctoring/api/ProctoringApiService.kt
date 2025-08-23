package com.cbt.proctoring.api

import com.cbt.proctoring.model.*
import com.google.gson.GsonBuilder
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Response
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*
import java.util.concurrent.TimeUnit

/**
 * Proctoring API Service for communication with Laravel backend
 */
interface ProctoringApiService {

    /**
     * Start exam session
     */
    @POST("proctoring/session/start")
    suspend fun startExamSession(@Body session: ExamSession): Response<ApiResponse<ExamSession>>

    /**
     * End exam session
     */
    @POST("proctoring/session/end")
    suspend fun endExamSession(@Body session: ExamSession): Response<ApiResponse<SimpleResponse>>

    /**
     * Log photo capture
     */
    @POST("proctoring/photo")
    suspend fun logPhotoCapture(@Body photo: ProctoringPhoto): Response<ApiResponse<SimpleResponse>>

    /**
     * Log general proctoring event
     */
    @POST("proctoring/log")
    suspend fun logEvent(@Body log: ProctoringLog): Response<ApiResponse<SimpleResponse>>

    /**
     * Report cheat attempt
     */
    @POST("proctoring/cheat-attempt")
    suspend fun reportCheatAttempt(@Body cheatAttempt: CheatAttempt): Response<ApiResponse<SimpleResponse>>

    /**
     * Log network event
     */
    @POST("proctoring/network-event")
    suspend fun logNetworkEvent(@Body networkEvent: NetworkEvent): Response<ApiResponse<SimpleResponse>>

    /**
     * Report auto-submit event
     */
    @POST("proctoring/auto-submit")
    suspend fun reportAutoSubmit(@Body autoSubmit: AutoSubmitEvent): Response<ApiResponse<SimpleResponse>>

    /**
     * Log security violation
     */
    @POST("proctoring/security-violation")
    suspend fun logSecurityViolation(@Body violation: SecurityViolation): Response<ApiResponse<SimpleResponse>>

    /**
     * Submit batch proctoring data
     */
    @POST("proctoring/batch")
    suspend fun submitBatchData(@Body batchData: BatchProctoringData): Response<ApiResponse<SimpleResponse>>

    /**
     * Register device fingerprint
     */
    @POST("proctoring/device-fingerprint")
    suspend fun registerDeviceFingerprint(@Body fingerprint: DeviceFingerprint): Response<ApiResponse<SimpleResponse>>

    /**
     * Get exam session info
     */
    @GET("proctoring/session/{sessionId}")
    suspend fun getExamSession(@Path("sessionId") sessionId: String): Response<ApiResponse<ExamSession>>

    /**
     * Get proctoring statistics
     */
    @GET("proctoring/stats/{sessionId}")
    suspend fun getProctoringStats(@Path("sessionId") sessionId: String): Response<ApiResponse<ProctoringStats>>

    /**
     * Health check endpoint
     */
    @GET("proctoring/health")
    suspend fun healthCheck(): Response<ApiResponse<SimpleResponse>>

    /**
     * Test connection
     */
    @GET("proctoring/ping")
    suspend fun ping(): Response<ApiResponse<SimpleResponse>>

    companion object {
        private const val BASE_URL = "http://192.168.1.100:8000/api/"
        private const val CONNECT_TIMEOUT = 30L
        private const val READ_TIMEOUT = 60L
        private const val WRITE_TIMEOUT = 60L

        fun create(baseUrl: String = BASE_URL): ProctoringApiService {
            val gson = GsonBuilder()
                .setLenient()
                .create()

            val loggingInterceptor = HttpLoggingInterceptor().apply {
                level = HttpLoggingInterceptor.Level.BODY
            }

            val okHttpClient = OkHttpClient.Builder()
                .addInterceptor(loggingInterceptor)
                .addInterceptor { chain ->
                    val request = chain.request().newBuilder()
                        .addHeader("Content-Type", "application/json")
                        .addHeader("Accept", "application/json")
                        .addHeader("User-Agent", "CBT-Proctoring-Android/1.0")
                        .build()
                    chain.proceed(request)
                }
                .connectTimeout(CONNECT_TIMEOUT, TimeUnit.SECONDS)
                .readTimeout(READ_TIMEOUT, TimeUnit.SECONDS)
                .writeTimeout(WRITE_TIMEOUT, TimeUnit.SECONDS)
                .retryOnConnectionFailure(true)
                .build()

            val retrofit = Retrofit.Builder()
                .baseUrl(baseUrl)
                .client(okHttpClient)
                .addConverterFactory(GsonConverterFactory.create(gson))
                .build()

            return retrofit.create(ProctoringApiService::class.java)
        }
    }
}

/**
 * API Client wrapper with error handling
 */
class ProctoringApiClient(private val apiService: ProctoringApiService) {

    /**
     * Safe API call with error handling
     */
    suspend fun <T> safeApiCall(apiCall: suspend () -> Response<T>): ApiResult<T> {
        return try {
            val response = apiCall()
            if (response.isSuccessful) {
                ApiResult.Success(response.body())
            } else {
                ApiResult.Error("API Error: ${response.code()} ${response.message()}")
            }
        } catch (e: Exception) {
            ApiResult.Error("Network Error: ${e.message}")
        }
    }

    /**
     * Start exam session with error handling
     */
    suspend fun startExamSession(session: ExamSession): ApiResult<ApiResponse<ExamSession>> {
        return safeApiCall { apiService.startExamSession(session) }
    }

    /**
     * End exam session with error handling
     */
    suspend fun endExamSession(session: ExamSession): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.endExamSession(session) }
    }

    /**
     * Log photo capture with error handling
     */
    suspend fun logPhotoCapture(photo: ProctoringPhoto): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.logPhotoCapture(photo) }
    }

    /**
     * Log event with error handling
     */
    suspend fun logEvent(log: ProctoringLog): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.logEvent(log) }
    }

    /**
     * Report cheat attempt with error handling
     */
    suspend fun reportCheatAttempt(cheatAttempt: CheatAttempt): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.reportCheatAttempt(cheatAttempt) }
    }

    /**
     * Log network event with error handling
     */
    suspend fun logNetworkEvent(networkEvent: NetworkEvent): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.logNetworkEvent(networkEvent) }
    }

    /**
     * Report auto-submit with error handling
     */
    suspend fun reportAutoSubmit(autoSubmit: AutoSubmitEvent): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.reportAutoSubmit(autoSubmit) }
    }

    /**
     * Log security violation with error handling
     */
    suspend fun logSecurityViolation(violation: SecurityViolation): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.logSecurityViolation(violation) }
    }

    /**
     * Submit batch data with error handling
     */
    suspend fun submitBatchData(batchData: BatchProctoringData): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.submitBatchData(batchData) }
    }

    /**
     * Register device fingerprint with error handling
     */
    suspend fun registerDeviceFingerprint(fingerprint: DeviceFingerprint): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.registerDeviceFingerprint(fingerprint) }
    }

    /**
     * Health check with error handling
     */
    suspend fun healthCheck(): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.healthCheck() }
    }

    /**
     * Ping with error handling
     */
    suspend fun ping(): ApiResult<ApiResponse<SimpleResponse>> {
        return safeApiCall { apiService.ping() }
    }
}

/**
 * API Result wrapper
 */
sealed class ApiResult<T> {
    data class Success<T>(val data: T?) : ApiResult<T>()
    data class Error<T>(val message: String) : ApiResult<T>()
    data class Loading<T>(val message: String = "Loading...") : ApiResult<T>()
}

/**
 * Extension function to handle API results
 */
inline fun <T> ApiResult<T>.onSuccess(action: (T?) -> Unit): ApiResult<T> {
    if (this is ApiResult.Success) action(data)
    return this
}

inline fun <T> ApiResult<T>.onError(action: (String) -> Unit): ApiResult<T> {
    if (this is ApiResult.Error) action(message)
    return this
}

inline fun <T> ApiResult<T>.onLoading(action: (String) -> Unit): ApiResult<T> {
    if (this is ApiResult.Loading) action(message)
    return this
}