<?php

use Illuminate\Support\Facades\Route;

//prefix "admin"
Route::prefix('admin')->group(function() {

    //middleware auth + role admin
    Route::group(['middleware' => ['auth','role:admin']], function () {

        //route dashboard
        Route::get('/dashboard', App\Http\Controllers\Admin\DashboardController::class)->name('admin.dashboard');
    
        //route resource lessons    
        Route::resource('/lessons', \App\Http\Controllers\Admin\LessonController::class, ['as' => 'admin']);

        //route resource classrooms    
        Route::resource('/classrooms', \App\Http\Controllers\Admin\ClassroomController::class, ['as' => 'admin']);
        
    //route resource admins (manage admin users)
    Route::resource('/admins', \App\Http\Controllers\Admin\AdminController::class, ['as' => 'admin']);
        
        //route student import
        Route::get('/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])->name('admin.students.import');

        //route student store import
        Route::post('/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'storeImport'])->name('admin.students.storeImport');

        //route resource students    
        Route::resource('/students', \App\Http\Controllers\Admin\StudentController::class, ['as' => 'admin']);
    
        //route resource exams    
        Route::resource('/exams', \App\Http\Controllers\Admin\ExamController::class, ['as' => 'admin']);
        
        //custom route for create question exam
        Route::get('/exams/{exam}/questions/create', [\App\Http\Controllers\Admin\ExamController::class, 'createQuestion'])->name('admin.exams.createQuestion');

        //custom route for store question exam
        Route::post('/exams/{exam}/questions/store', [\App\Http\Controllers\Admin\ExamController::class, 'storeQuestion'])->name('admin.exams.storeQuestion');
    
        //custom route for edit question exam
        Route::get('/exams/{exam}/questions/{question}/edit', [\App\Http\Controllers\Admin\ExamController::class, 'editQuestion'])->name('admin.exams.editQuestion');

        //custom route for update question exam
        Route::put('/exams/{exam}/questions/{question}/update', [\App\Http\Controllers\Admin\ExamController::class, 'updateQuestion'])->name('admin.exams.updateQuestion');
    
        //custom route for destroy question exam
        Route::delete('/exams/{exam}/questions/{question}/destroy', [\App\Http\Controllers\Admin\ExamController::class, 'destroyQuestion'])->name('admin.exams.destroyQuestion');
    
        //route student import
        Route::get('/exams/{exam}/questions/import', [\App\Http\Controllers\Admin\ExamController::class, 'import'])->name('admin.exam.questionImport');

        //route student import
        Route::post('/exams/{exam}/questions/import', [\App\Http\Controllers\Admin\ExamController::class, 'storeImport'])->name('admin.exam.questionStoreImport');

    // AI Import routes
    Route::get('/exams/{exam}/questions/ai-import', [\App\Http\Controllers\Admin\AIImportController::class, 'create'])->name('admin.exam.questionAIImport');
    Route::post('/exams/{exam}/questions/ai-import/generate', [\App\Http\Controllers\Admin\AIImportController::class, 'generate'])->name('admin.exam.questionAIImportGenerate');
    Route::post('/exams/{exam}/questions/ai-import/confirm', [\App\Http\Controllers\Admin\AIImportController::class, 'confirm'])->name('admin.exam.questionAIImportConfirm');
    
        //route resource exam_sessions    
        Route::resource('/exam_sessions', \App\Http\Controllers\Admin\ExamSessionController::class, ['as' => 'admin']);
    
        //custom route for enrolle create
        Route::get('/exam_sessions/{exam_session}/enrolle/create', [\App\Http\Controllers\Admin\ExamSessionController::class, 'createEnrolle'])->name('admin.exam_sessions.createEnrolle');

        //custom route for enrolle store
        Route::post('/exam_sessions/{exam_session}/enrolle/store', [\App\Http\Controllers\Admin\ExamSessionController::class, 'storeEnrolle'])->name('admin.exam_sessions.storeEnrolle');
        
        //custom route for enrolle destroy
        Route::delete('/exam_sessions/{exam_session}/enrolle/{exam_group}/destroy', [\App\Http\Controllers\Admin\ExamSessionController::class, 'destroyEnrolle'])->name('admin.exam_sessions.destroyEnrolle');
   
        //route index reports
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');

        //route index reports filter
        Route::get('/reports/filter', [\App\Http\Controllers\Admin\ReportController::class, 'filter'])->name('admin.reports.filter');

        //route index reports export
        Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.reports.export');

    // settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // monitoring routes
    Route::get('/monitor', [\App\Http\Controllers\Admin\MonitorController::class, 'index'])->name('admin.monitor.index');
    Route::get('/monitor/{grade}', [\App\Http\Controllers\Admin\MonitorController::class, 'show'])->name('admin.monitor.show');
    Route::post('/monitor/{grade}/unlock', [\App\Http\Controllers\Admin\MonitorController::class, 'unlock'])->name('admin.monitor.unlock');
    Route::post('/monitor/{grade}/stop', [\App\Http\Controllers\Admin\MonitorController::class, 'stop'])->name('admin.monitor.stop');
    Route::post('/monitor/{grade}/reopen', [\App\Http\Controllers\Admin\MonitorController::class, 'reopen'])->name('admin.monitor.reopen');
    Route::post('/monitor/{grade}/add-time', [\App\Http\Controllers\Admin\MonitorController::class, 'addTime'])->name('admin.monitor.add_time');

    // separate exam control page
    Route::get('/exam-control', [\App\Http\Controllers\Admin\ExamControlController::class, 'index'])->name('admin.exam_control.index');
    });
});

//route homepage -> keep student landing
Route::get('/', function () {
    //cek session student
    if(auth()->guard('student')->check()) {
        return redirect()->route('student.dashboard');
    }
    return \Inertia\Inertia::render('Student/Login/Index');
});

// admin/teacher login page
Route::get('/login', function () {
    if(auth()->check()) {
        // already logged in: redirect by role
        $role = auth()->user()->role ?? 'admin';
        return $role === 'teacher' ? redirect()->route('teacher.dashboard') : redirect()->route('admin.dashboard');
    }
    return \Inertia\Inertia::render('Auth/Login');
})->name('login');

// simple beranda page after student logout
Route::get('/beranda', function () {
    return \Inertia\Inertia::render('Student/Logout/Beranda');
})->name('student.beranda');

// prefix "teacher"
Route::prefix('teacher')->group(function () {
    Route::group(['middleware' => ['auth','role:teacher']], function () {
        // teacher dashboard
        Route::get('/dashboard', App\Http\Controllers\Teacher\DashboardController::class)->name('teacher.dashboard');

    // Teacher exams (CRUD limited to own subject)
    Route::get('/exams', [\App\Http\Controllers\Teacher\ExamController::class, 'index'])->name('teacher.exams.index');
    Route::get('/exams/create', [\App\Http\Controllers\Teacher\ExamController::class, 'create'])->name('teacher.exams.create');
    Route::post('/exams', [\App\Http\Controllers\Teacher\ExamController::class, 'store'])->name('teacher.exams.store');
    Route::get('/exams/{exam}', [\App\Http\Controllers\Teacher\ExamController::class, 'show'])->name('teacher.exams.show');
    Route::get('/exams/{exam}/edit', [\App\Http\Controllers\Teacher\ExamController::class, 'edit'])->name('teacher.exams.edit');
    Route::put('/exams/{exam}', [\App\Http\Controllers\Teacher\ExamController::class, 'update'])->name('teacher.exams.update');
    Route::delete('/exams/{exam}', [\App\Http\Controllers\Teacher\ExamController::class, 'destroy'])->name('teacher.exams.destroy');

    // Questions under a teacher's exam
    Route::get('/exams/{exam}/questions/create', [\App\Http\Controllers\Teacher\ExamController::class, 'createQuestion'])->name('teacher.exams.questions.create');
    Route::post('/exams/{exam}/questions', [\App\Http\Controllers\Teacher\ExamController::class, 'storeQuestion'])->name('teacher.exams.questions.store');
    Route::get('/exams/{exam}/questions/{question}/edit', [\App\Http\Controllers\Teacher\ExamController::class, 'editQuestion'])->name('teacher.exams.questions.edit');
    Route::put('/exams/{exam}/questions/{question}', [\App\Http\Controllers\Teacher\ExamController::class, 'updateQuestion'])->name('teacher.exams.questions.update');
    Route::delete('/exams/{exam}/questions/{question}', [\App\Http\Controllers\Teacher\ExamController::class, 'destroyQuestion'])->name('teacher.exams.questions.destroy');

    // Teacher: Import questions (Excel) like admin
    Route::get('/exams/{exam}/questions/import', [\App\Http\Controllers\Teacher\ExamController::class, 'import'])->name('teacher.exams.questions.import');
    Route::post('/exams/{exam}/questions/import', [\App\Http\Controllers\Teacher\ExamController::class, 'storeImport'])->name('teacher.exams.questions.storeImport');

    // Bank Soal index (placeholder)
    Route::get('/questions', function () { return \Inertia\Inertia::render('Teacher/Questions/Index'); })->name('teacher.questions.index');
    });
});

//login students
Route::post('/students/login', \App\Http\Controllers\Student\LoginController::class)->name('student.login');

//student logout custom
Route::post('/students/logout', \App\Http\Controllers\Student\LogoutController::class)->name('student.logout');

//prefix "student"
Route::prefix('student')->group(function() {

    //middleware "student"
    Route::group(['middleware' => ['student','student.single']], function () {
        
        //route dashboard
        Route::get('/dashboard', App\Http\Controllers\Student\DashboardController::class)->name('student.dashboard');
    
        //route exam confirmation
        Route::get('/exam-confirmation/{id}', [App\Http\Controllers\Student\ExamController::class, 'confirmation'])->name('student.exams.confirmation');
    
        //route exam start
        Route::get('/exam-start/{id}', [App\Http\Controllers\Student\ExamController::class, 'startExam'])->name('student.exams.startExam');
        
         //route exam show
         Route::get('/exam/{id}/{page}', [App\Http\Controllers\Student\ExamController::class, 'show'])->name('student.exams.show');
    
        //route exam update duration
        Route::put('/exam-duration/update/{grade_id}', [App\Http\Controllers\Student\ExamController::class, 'updateDuration'])->name('student.exams.update_duration');
        
        //route answer question
        Route::post('/exam-answer', [App\Http\Controllers\Student\ExamController::class, 'answerQuestion'])->name('student.exams.answerQuestion');
        
    //route exam end
        Route::post('/exam-end', [App\Http\Controllers\Student\ExamController::class, 'endExam'])->name('student.exams.endExam');
        
        //route exam result
        Route::get('/exam-result/{exam_group_id}', [App\Http\Controllers\Student\ExamController::class, 'resultExam'])->name('student.exams.resultExam');

    //heartbeat endpoint for monitoring
    Route::post('/heartbeat', [App\Http\Controllers\Student\ExamController::class, 'heartbeat'])->name('student.exams.heartbeat');

    //explicit exit exam endpoint
    Route::post('/exam-exit', [App\Http\Controllers\Student\ExamController::class, 'exitExam'])->name('student.exams.exit');
    });

});