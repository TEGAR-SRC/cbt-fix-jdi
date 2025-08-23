<?php

use Illuminate\Support\Facades\Route;

//prefix "admin"
Route::prefix('admin')->group(function() {

    //middleware "auth"
    Route::group(['middleware' => ['auth']], function () {

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

//route homepage
Route::get('/', function () {

    //cek session student
    if(auth()->guard('student')->check()) {
        return redirect()->route('student.dashboard');
    }

    //return view login
    return \Inertia\Inertia::render('Student/Login/Index');
});

// simple beranda page after student logout
Route::get('/beranda', function () {
    return \Inertia\Inertia::render('Student/Logout/Beranda');
})->name('student.beranda');

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