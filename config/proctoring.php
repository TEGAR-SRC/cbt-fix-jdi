<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CBT Proctoring Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options untuk sistem proctoring CBT Android App
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default CBT URL
    |--------------------------------------------------------------------------
    |
    | URL default untuk CBT yang akan diakses oleh aplikasi Android.
    | Ini bisa diubah melalui admin panel atau API.
    |
    */
    'default_cbt_url' => env('CBT_DEFAULT_URL', 'https://cbt.edupus.id'),

    /*
    |--------------------------------------------------------------------------
    | Local CBT URL (untuk development)
    |--------------------------------------------------------------------------
    */
    'local_cbt_url' => env('CBT_LOCAL_URL', 'http://localhost:8000'),

    /*
    |--------------------------------------------------------------------------
    | Admin PIN Configuration
    |--------------------------------------------------------------------------
    */
    'default_admin_pin' => env('CBT_ADMIN_PIN', '1234'),
    'admin_pin_length' => [
        'min' => 4,
        'max' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Proctoring Intervals & Timing
    |--------------------------------------------------------------------------
    */
    'photo_interval' => env('PROCTORING_PHOTO_INTERVAL', 30), // seconds
    'network_check_interval' => env('PROCTORING_NETWORK_INTERVAL', 10), // seconds
    'heartbeat_interval' => env('PROCTORING_HEARTBEAT_INTERVAL', 60), // seconds

    /*
    |--------------------------------------------------------------------------
    | Violation & Security Settings
    |--------------------------------------------------------------------------
    */
    'max_violations' => env('PROCTORING_MAX_VIOLATIONS', 3),
    'violation_threshold' => env('PROCTORING_VIOLATION_THRESHOLD', 5),
    'auto_submit_enabled' => env('PROCTORING_AUTO_SUBMIT', true),

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'disk' => env('PROCTORING_STORAGE_DISK', 'public'),
        'photo_path' => env('PROCTORING_PHOTO_PATH', 'proctoring/photos'),
        'max_photo_size' => env('PROCTORING_MAX_PHOTO_SIZE', 2048), // KB
        'allowed_photo_types' => ['jpeg', 'jpg', 'png'],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limit' => env('API_RATE_LIMIT', 60), // per minute
        'proctoring_rate_limit' => env('API_PROCTORING_RATE_LIMIT', 120), // per minute
        'token_expiry' => env('API_TOKEN_EXPIRY', 24), // hours
        'timeout' => env('API_TIMEOUT', 30), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Features
    |--------------------------------------------------------------------------
    */
    'security' => [
        'flag_secure' => true,
        'lock_task_mode' => true,
        'anti_root_check' => env('PROCTORING_ANTI_ROOT', true),
        'anti_emulator_check' => env('PROCTORING_ANTI_EMULATOR', true),
        'screenshot_detection' => true,
        'screen_recording_detection' => true,
        'multi_window_detection' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Proctoring Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        'camera_monitoring' => true,
        'network_monitoring' => true,
        'activity_logging' => true,
        'violation_reporting' => true,
        'photo_capture' => true,
        'real_time_alerts' => env('PROCTORING_REAL_TIME_ALERTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => env('PROCTORING_NOTIFICATIONS', true),
        'channels' => ['database', 'mail'], // database, mail, slack, etc.
        'violation_threshold_notify' => 2, // notify after this many violations
        'critical_violation_notify' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    */
    'retention' => [
        'session_data_days' => env('PROCTORING_SESSION_RETENTION', 90),
        'photo_retention_days' => env('PROCTORING_PHOTO_RETENTION', 30),
        'log_retention_days' => env('PROCTORING_LOG_RETENTION', 365),
        'auto_cleanup' => env('PROCTORING_AUTO_CLEANUP', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Android App Configuration
    |--------------------------------------------------------------------------
    */
    'android' => [
        'app_version' => env('ANDROID_APP_VERSION', '1.0.0'),
        'min_android_version' => env('MIN_ANDROID_VERSION', '7.0'),
        'target_sdk' => env('TARGET_SDK_VERSION', 34),
        'package_name' => env('ANDROID_PACKAGE_NAME', 'com.cbt.proctoring'),
    ],

    /*
    |--------------------------------------------------------------------------
    | WebView Configuration
    |--------------------------------------------------------------------------
    */
    'webview' => [
        'javascript_enabled' => true,
        'dom_storage_enabled' => true,
        'zoom_controls' => false,
        'zoom_support' => false,
        'copy_paste_disabled' => true,
        'long_press_disabled' => true,
        'context_menu_disabled' => true,
        'user_agent' => env('CBT_WEBVIEW_USER_AGENT', 'CBTProctoring/1.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Device Admin Policies
    |--------------------------------------------------------------------------
    */
    'device_admin' => [
        'lock_task_mode' => true,
        'disable_camera' => false, // Allow camera for proctoring
        'disable_keyguard' => true,
        'disable_notifications' => true,
        'disable_recent_apps' => true,
        'disable_home_button' => true,
        'disable_back_button' => false,
        'disable_status_bar' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'level' => env('PROCTORING_LOG_LEVEL', 'info'), // debug, info, warning, error
        'channels' => ['single', 'daily'], // log channels to use
        'max_files' => env('PROCTORING_LOG_MAX_FILES', 14),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'queue_enabled' => env('PROCTORING_QUEUE_ENABLED', false),
        'queue_connection' => env('PROCTORING_QUEUE_CONNECTION', 'database'),
        'cache_enabled' => env('PROCTORING_CACHE_ENABLED', true),
        'cache_ttl' => env('PROCTORING_CACHE_TTL', 3600), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing & Development
    |--------------------------------------------------------------------------
    */
    'testing' => [
        'mock_camera' => env('PROCTORING_MOCK_CAMERA', false),
        'mock_violations' => env('PROCTORING_MOCK_VIOLATIONS', false),
        'debug_mode' => env('PROCTORING_DEBUG', false),
        'test_mode' => env('PROCTORING_TEST_MODE', false),
    ],

];