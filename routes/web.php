<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
        
    //route resource assignments (Tugas Harian)
    Route::resource('/assignments', \App\Http\Controllers\Admin\AssignmentController::class, ['as' => 'admin']);
    // assignment questions (admin)
    Route::get('/assignments/{assignment}/questions', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'index'])->name('admin.assignments.questions.index');
    Route::get('/assignments/{assignment}/questions/create', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'create'])->name('admin.assignments.questions.create');
    Route::post('/assignments/{assignment}/questions', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'store'])->name('admin.assignments.questions.store');
    Route::get('/assignments/{assignment}/questions/{question}/edit', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'edit'])->name('admin.assignments.questions.edit');
    Route::put('/assignments/{assignment}/questions/{question}', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'update'])->name('admin.assignments.questions.update');
    Route::delete('/assignments/{assignment}/questions/{question}', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'destroy'])->name('admin.assignments.questions.destroy');
    // Assignment questions import (excel)
    Route::get('/assignments/{assignment}/questions/import', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'import'])->name('admin.assignments.questions.import');
    Route::post('/assignments/{assignment}/questions/import', [\App\Http\Controllers\Admin\AssignmentQuestionController::class, 'storeImport'])->name('admin.assignments.questions.storeImport');
    // Assignment AI import
    Route::get('/assignments/{assignment}/questions/ai-import', [\App\Http\Controllers\Admin\AssignmentAIImportController::class, 'create'])->name('admin.assignments.questions.aiImport');
    Route::post('/assignments/{assignment}/questions/ai-import/generate', [\App\Http\Controllers\Admin\AssignmentAIImportController::class, 'generate'])->name('admin.assignments.questions.aiImportGenerate');
    Route::post('/assignments/{assignment}/questions/ai-import/confirm', [\App\Http\Controllers\Admin\AssignmentAIImportController::class, 'confirm'])->name('admin.assignments.questions.aiImportConfirm');
    
    //route resource tryouts
    Route::resource('/tryouts', \App\Http\Controllers\Admin\TryoutController::class, ['as' => 'admin']);
    // tryout questions
    Route::get('/tryouts/{tryout}/questions', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'index'])->name('admin.tryouts.questions.index');
    Route::get('/tryouts/{tryout}/questions/create', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'create'])->name('admin.tryouts.questions.create');
    Route::post('/tryouts/{tryout}/questions', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'store'])->name('admin.tryouts.questions.store');
    Route::get('/tryouts/{tryout}/questions/{question}/edit', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'edit'])->name('admin.tryouts.questions.edit');
    Route::put('/tryouts/{tryout}/questions/{question}', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'update'])->name('admin.tryouts.questions.update');
    Route::delete('/tryouts/{tryout}/questions/{question}', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'destroy'])->name('admin.tryouts.questions.destroy');
    Route::get('/tryouts/{tryout}/questions/import', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'import'])->name('admin.tryouts.questions.import');
    Route::post('/tryouts/{tryout}/questions/import', [\App\Http\Controllers\Admin\TryoutQuestionController::class,'storeImport'])->name('admin.tryouts.questions.storeImport');
    // Assignments enrollments
    Route::get('/assignments/{assignment}/enrollments', [\App\Http\Controllers\Admin\AssignmentEnrollmentController::class,'index'])->name('admin.assignments.enrollments.index');
    Route::get('/assignments/{assignment}/enrollments/create', [\App\Http\Controllers\Admin\AssignmentEnrollmentController::class,'create'])->name('admin.assignments.enrollments.create');
    Route::post('/assignments/{assignment}/enrollments', [\App\Http\Controllers\Admin\AssignmentEnrollmentController::class,'store'])->name('admin.assignments.enrollments.store');
    Route::delete('/assignments/{assignment}/enrollments/{enrollment}', [\App\Http\Controllers\Admin\AssignmentEnrollmentController::class,'destroy'])->name('admin.assignments.enrollments.destroy');
    // Assignment results listing & detail
    Route::get('/assignments/{assignment}/results', [\App\Http\Controllers\Admin\AssignmentResultController::class,'index'])->name('admin.assignments.results.index');
    Route::get('/assignments/{assignment}/results/{submission}', [\App\Http\Controllers\Admin\AssignmentResultController::class,'show'])->name('admin.assignments.results.show');
    // Tryout enrollments
    Route::get('/tryouts/{tryout}/enrollments', [\App\Http\Controllers\Admin\TryoutEnrollmentController::class,'index'])->name('admin.tryouts.enrollments.index');
    Route::get('/tryouts/{tryout}/enrollments/create', [\App\Http\Controllers\Admin\TryoutEnrollmentController::class,'create'])->name('admin.tryouts.enrollments.create');
    Route::post('/tryouts/{tryout}/enrollments', [\App\Http\Controllers\Admin\TryoutEnrollmentController::class,'store'])->name('admin.tryouts.enrollments.store');
    Route::delete('/tryouts/{tryout}/enrollments/{enrollment}', [\App\Http\Controllers\Admin\TryoutEnrollmentController::class,'destroy'])->name('admin.tryouts.enrollments.destroy');
    // Tryout results listing & detail
    Route::get('/tryouts/{tryout}/results', [\App\Http\Controllers\Admin\TryoutResultController::class,'index'])->name('admin.tryouts.results.index');
    Route::get('/tryouts/{tryout}/results/{attempt}', [\App\Http\Controllers\Admin\TryoutResultController::class,'show'])->name('admin.tryouts.results.show');
    // Tryout AI import
    Route::get('/tryouts/{tryout}/questions/ai-import', [\App\Http\Controllers\Admin\TryoutAIImportController::class,'create'])->name('admin.tryouts.questions.aiImport');
    Route::post('/tryouts/{tryout}/questions/ai-import/generate', [\App\Http\Controllers\Admin\TryoutAIImportController::class,'generate'])->name('admin.tryouts.questions.aiImportGenerate');
    Route::post('/tryouts/{tryout}/questions/ai-import/confirm', [\App\Http\Controllers\Admin\TryoutAIImportController::class,'confirm'])->name('admin.tryouts.questions.aiImportConfirm');
        
    //route resource admins (manage admin users)
    Route::resource('/admins', \App\Http\Controllers\Admin\AdminController::class, ['as' => 'admin']);

    // route resource guardians (parents)
    Route::resource('/guardians', \App\Http\Controllers\Admin\GuardianController::class, ['as' => 'admin']);
    Route::post('/guardians/{guardian}/account', [\App\Http\Controllers\Admin\GuardianController::class, 'createAccount'])->name('admin.guardians.account.create');
    Route::put('/guardians/{guardian}/account', [\App\Http\Controllers\Admin\GuardianController::class, 'updateAccount'])->name('admin.guardians.account.update');
    Route::delete('/guardians/{guardian}/account', [\App\Http\Controllers\Admin\GuardianController::class, 'deleteAccount'])->name('admin.guardians.account.delete');
        
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

    // unified results (Hasil Ujian) - exams, assignments, tryouts
    Route::get('/results', [\App\Http\Controllers\Admin\UnifiedResultController::class,'index'])->name('admin.results.index');
    Route::get('/results/export', [\App\Http\Controllers\Admin\UnifiedResultController::class,'export'])->name('admin.results.export');

    // settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // monitoring routes
    Route::get('/monitor', [\App\Http\Controllers\Admin\MonitorController::class, 'index'])->name('admin.monitor.index');
    Route::get('/monitor/{grade}', [\App\Http\Controllers\Admin\MonitorController::class, 'show'])->name('admin.monitor.show');

    // proctoring routes
    Route::get('/proctoring/sessions', function () {
        return Inertia::render('Admin/Proctoring/Sessions');
    })->name('admin.proctoring.sessions');

    Route::get('/proctoring/violations', function () {
        return Inertia::render('Admin/Proctoring/Violations');
    })->name('admin.proctoring.violations');

    Route::get('/proctoring/photos', function () {
        return Inertia::render('Admin/Proctoring/Photos');
    })->name('admin.proctoring.photos');

    Route::get('/proctoring/activities', function () {
        return Inertia::render('Admin/Proctoring/Activities');
    })->name('admin.proctoring.activities');

    Route::get('/proctoring/network', function () {
        return Inertia::render('Admin/Proctoring/Network');
    })->name('admin.proctoring.network');

    Route::get('/proctoring/settings', function () {
        return Inertia::render('Admin/Proctoring/Settings');
    })->name('admin.proctoring.settings');
    
    Route::get('/test-sidebar', function () {
        return Inertia::render('Admin/TestSidebar');
    })->name('admin.test.sidebar');
    Route::post('/monitor/{grade}/unlock', [\App\Http\Controllers\Admin\MonitorController::class, 'unlock'])->name('admin.monitor.unlock');
    Route::post('/monitor/{grade}/stop', [\App\Http\Controllers\Admin\MonitorController::class, 'stop'])->name('admin.monitor.stop');
    Route::post('/monitor/{grade}/reopen', [\App\Http\Controllers\Admin\MonitorController::class, 'reopen'])->name('admin.monitor.reopen');
    Route::post('/monitor/{grade}/add-time', [\App\Http\Controllers\Admin\MonitorController::class, 'addTime'])->name('admin.monitor.add_time');

    // separate exam control page
    Route::get('/exam-control', [\App\Http\Controllers\Admin\ExamControlController::class, 'index'])->name('admin.exam_control.index');

    // Admin-accessible Dinas monitoring (read-only proxy)
    Route::prefix('/dinas')->group(function(){
        // Dashboard
        Route::get('/', [\App\Http\Controllers\Dinas\DashboardController::class, 'index'])->name('admin.dinas.dashboard');
        // Monitoring list
        Route::get('/monitor', [\App\Http\Controllers\Dinas\MonitorController::class, 'index'])->name('admin.dinas.monitor.index');
        // Monitoring detail
        Route::get('/monitor/{grade}', [\App\Http\Controllers\Dinas\MonitorController::class, 'show'])->name('admin.dinas.monitor.show');
    });
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
    if ($role === 'teacher') { return redirect()->route('teacher.dashboard'); }
    if ($role === 'operator') { return redirect()->route('operator.dashboard'); }
    if ($role === 'parent') { return redirect()->route('parent.grades.index'); }
    if ($role === 'dinas') { return redirect()->route('dinas.dashboard'); }
    return redirect()->route('admin.dashboard');
    }
    return \Inertia\Inertia::render('Auth/Login');
})->name('login');

// simple beranda page after student logout
Route::get('/beranda', function () {
    return \Inertia\Inertia::render('Student/Logout/Beranda');
})->name('student.beranda');
 
// prefix "parent" (orang tua)
Route::prefix('parent')->group(function () {
    // Logged-in parent accounts see linked students automatically
    Route::middleware(['auth','role:parent'])->group(function(){
        Route::get('/grades', [\App\Http\Controllers\Parent\GradeController::class, 'index'])->name('parent.grades.index');
    });
    // Public lookup by NIS (optional for non-logged-in visitors)
    Route::get('/grades/filter', [\App\Http\Controllers\Parent\GradeController::class, 'filter'])->name('parent.grades.filter');
});

// prefix "dinas" (Dinas Pendidikan) — read-only monitoring
Route::prefix('dinas')->group(function () {
    Route::middleware(['auth','role:dinas'])->group(function(){
        Route::get('/dashboard', [\App\Http\Controllers\Dinas\DashboardController::class, 'index'])->name('dinas.dashboard');
        Route::get('/monitor', [\App\Http\Controllers\Dinas\MonitorController::class, 'index'])->name('dinas.monitor.index');
        Route::get('/monitor/{grade}', [\App\Http\Controllers\Dinas\MonitorController::class, 'show'])->name('dinas.monitor.show');
    });
});

// prefix "teacher"
Route::prefix('teacher')->group(function () {
    Route::group(['middleware' => ['auth','role:teacher']], function () {
        // teacher dashboard
        Route::get('/dashboard', App\Http\Controllers\Teacher\DashboardController::class)->name('teacher.dashboard');

        // Teacher assignments (reuse Admin AssignmentController but limit listing if needed later)
        Route::get('/assignments', [\App\Http\Controllers\Admin\AssignmentController::class, 'index'])->name('teacher.assignments.index');
        Route::get('/assignments/{assignment}/questions', [\App\Http\Controllers\Teacher\AssignmentQuestionController::class, 'index'])->name('teacher.assignments.questions.index');
        Route::get('/assignments/{assignment}/questions/create', [\App\Http\Controllers\Teacher\AssignmentQuestionController::class, 'create'])->name('teacher.assignments.questions.create');
        Route::post('/assignments/{assignment}/questions', [\App\Http\Controllers\Teacher\AssignmentQuestionController::class, 'store'])->name('teacher.assignments.questions.store');
        Route::get('/assignments/{assignment}/questions/{question}/edit', [\App\Http\Controllers\Teacher\AssignmentQuestionController::class, 'edit'])->name('teacher.assignments.questions.edit');
        Route::put('/assignments/{assignment}/questions/{question}', [\App\Http\Controllers\Teacher\AssignmentQuestionController::class, 'update'])->name('teacher.assignments.questions.update');
        Route::delete('/assignments/{assignment}/questions/{question}', [\App\Http\Controllers\Teacher\AssignmentQuestionController::class, 'destroy'])->name('teacher.assignments.questions.destroy');

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

    // Teacher: AI Import routes
    Route::get('/exams/{exam}/questions/ai-import', [\App\Http\Controllers\Teacher\AIImportController::class, 'create'])->name('teacher.exam.questionAIImport');
    Route::post('/exams/{exam}/questions/ai-import/generate', [\App\Http\Controllers\Teacher\AIImportController::class, 'generate'])->name('teacher.exam.questionAIImportGenerate');
    Route::post('/exams/{exam}/questions/ai-import/confirm', [\App\Http\Controllers\Teacher\AIImportController::class, 'confirm'])->name('teacher.exam.questionAIImportConfirm');

    // Bank Soal index
    Route::get('/questions', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'index'])->name('teacher.questions.index');

    // Reports (Hasil Ujian)
    Route::get('/reports', [\App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('teacher.reports.index');
    Route::get('/reports/filter', [\App\Http\Controllers\Teacher\ReportController::class, 'filter'])->name('teacher.reports.filter');
    Route::get('/reports/export', [\App\Http\Controllers\Teacher\ReportController::class, 'export'])->name('teacher.reports.export');

    // Teacher: Exam Sessions CRUD + enrollee management
    Route::get('/exam-sessions', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'index'])->name('teacher.exam_sessions.index');
    Route::get('/exam-sessions/create', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'create'])->name('teacher.exam_sessions.create');
    Route::post('/exam-sessions', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'store'])->name('teacher.exam_sessions.store');
    Route::get('/exam-sessions/{exam_session}', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'show'])->name('teacher.exam_sessions.show');
    Route::get('/exam-sessions/{exam_session}/edit', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'edit'])->name('teacher.exam_sessions.edit');
    Route::put('/exam-sessions/{exam_session}', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'update'])->name('teacher.exam_sessions.update');
    Route::delete('/exam-sessions/{exam_session}', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'destroy'])->name('teacher.exam_sessions.destroy');
    Route::get('/exam-sessions/{exam_session}/enrolle/create', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'createEnrolle'])->name('teacher.exam_sessions.createEnrolle');
    Route::post('/exam-sessions/{exam_session}/enrolle/store', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'storeEnrolle'])->name('teacher.exam_sessions.storeEnrolle');
    Route::delete('/exam-sessions/{exam_session}/enrolle/{exam_group}/destroy', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'destroyEnrolle'])->name('teacher.exam_sessions.destroyEnrolle');

    // Teacher: Exam Control and Monitoring actions
    Route::get('/exam-control', [\App\Http\Controllers\Teacher\ExamControlController::class, 'index'])->name('teacher.exam_control.index');
    Route::get('/monitor', [\App\Http\Controllers\Teacher\MonitorController::class, 'index'])->name('teacher.monitor.index');
    Route::get('/monitor/{grade}', [\App\Http\Controllers\Teacher\MonitorController::class, 'show'])->name('teacher.monitor.show');
    Route::post('/monitor/{grade}/unlock', [\App\Http\Controllers\Teacher\MonitorController::class, 'unlock'])->name('teacher.monitor.unlock');
    Route::post('/monitor/{grade}/stop', [\App\Http\Controllers\Teacher\MonitorController::class, 'stop'])->name('teacher.monitor.stop');
    Route::post('/monitor/{grade}/reopen', [\App\Http\Controllers\Teacher\MonitorController::class, 'reopen'])->name('teacher.monitor.reopen');
    Route::post('/monitor/{grade}/add-time', [\App\Http\Controllers\Teacher\MonitorController::class, 'addTime'])->name('teacher.monitor.add_time');

    // Teacher: Classrooms CRUD
    Route::get('/classrooms', [\App\Http\Controllers\Teacher\ClassroomController::class, 'index'])->name('teacher.classrooms.index');
    Route::get('/classrooms/create', [\App\Http\Controllers\Teacher\ClassroomController::class, 'create'])->name('teacher.classrooms.create');
    Route::post('/classrooms', [\App\Http\Controllers\Teacher\ClassroomController::class, 'store'])->name('teacher.classrooms.store');
    Route::get('/classrooms/{classroom}/edit', [\App\Http\Controllers\Teacher\ClassroomController::class, 'edit'])->name('teacher.classrooms.edit');
    Route::put('/classrooms/{classroom}', [\App\Http\Controllers\Teacher\ClassroomController::class, 'update'])->name('teacher.classrooms.update');
    Route::delete('/classrooms/{classroom}', [\App\Http\Controllers\Teacher\ClassroomController::class, 'destroy'])->name('teacher.classrooms.destroy');

    // Teacher: Students CRUD + import
    Route::get('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('teacher.students.index');
    Route::get('/students/export', [\App\Http\Controllers\Teacher\StudentController::class, 'export'])->name('teacher.students.export');
    Route::get('/students/create', [\App\Http\Controllers\Teacher\StudentController::class, 'create'])->name('teacher.students.create');
    Route::post('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'store'])->name('teacher.students.store');
    Route::get('/students/import', [\App\Http\Controllers\Teacher\StudentController::class, 'import'])->name('teacher.students.import');
    Route::post('/students/import', [\App\Http\Controllers\Teacher\StudentController::class, 'storeImport'])->name('teacher.students.storeImport');
    Route::get('/students/{student}/edit', [\App\Http\Controllers\Teacher\StudentController::class, 'edit'])->name('teacher.students.edit');
    Route::put('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'update'])->name('teacher.students.update');
    Route::delete('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'destroy'])->name('teacher.students.destroy');

    // Teacher: Questions export (by exam or bank soal filters)
    Route::get('/exams/{exam}/questions/export', [\App\Http\Controllers\Teacher\ExamController::class, 'exportQuestions'])->name('teacher.exams.questions.export');
    Route::get('/questions/export', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'export'])->name('teacher.questions.export');
    });
});

// prefix "operator"
Route::prefix('operator')->group(function () {
    Route::group(['middleware' => ['auth','role:operator']], function () {
        Route::get('/dashboard', App\Http\Controllers\Operator\DashboardController::class)->name('operator.dashboard');

    // Operator assignments (read & questions management)
    Route::get('/assignments', [\App\Http\Controllers\Admin\AssignmentController::class, 'index'])->name('operator.assignments.index');
    Route::get('/assignments/{assignment}/questions', [\App\Http\Controllers\Operator\AssignmentQuestionController::class, 'index'])->name('operator.assignments.questions.index');
    Route::get('/assignments/{assignment}/questions/create', [\App\Http\Controllers\Operator\AssignmentQuestionController::class, 'create'])->name('operator.assignments.questions.create');
    Route::post('/assignments/{assignment}/questions', [\App\Http\Controllers\Operator\AssignmentQuestionController::class, 'store'])->name('operator.assignments.questions.store');
    Route::get('/assignments/{assignment}/questions/{question}/edit', [\App\Http\Controllers\Operator\AssignmentQuestionController::class, 'edit'])->name('operator.assignments.questions.edit');
    Route::put('/assignments/{assignment}/questions/{question}', [\App\Http\Controllers\Operator\AssignmentQuestionController::class, 'update'])->name('operator.assignments.questions.update');
    Route::delete('/assignments/{assignment}/questions/{question}', [\App\Http\Controllers\Operator\AssignmentQuestionController::class, 'destroy'])->name('operator.assignments.questions.destroy');

    // Operator: Exams (reuse Teacher controller)
    Route::get('/exams', [\App\Http\Controllers\Teacher\ExamController::class, 'index'])->name('operator.exams.index');
    Route::get('/exams/create', [\App\Http\Controllers\Teacher\ExamController::class, 'create'])->name('operator.exams.create');
    Route::post('/exams', [\App\Http\Controllers\Teacher\ExamController::class, 'store'])->name('operator.exams.store');
    Route::get('/exams/{exam}', [\App\Http\Controllers\Teacher\ExamController::class, 'show'])->name('operator.exams.show');
    Route::get('/exams/{exam}/edit', [\App\Http\Controllers\Teacher\ExamController::class, 'edit'])->name('operator.exams.edit');
    Route::put('/exams/{exam}', [\App\Http\Controllers\Teacher\ExamController::class, 'update'])->name('operator.exams.update');
    Route::delete('/exams/{exam}', [\App\Http\Controllers\Teacher\ExamController::class, 'destroy'])->name('operator.exams.destroy');

    // Questions under exam
    Route::get('/exams/{exam}/questions/create', [\App\Http\Controllers\Teacher\ExamController::class, 'createQuestion'])->name('operator.exams.questions.create');
    Route::post('/exams/{exam}/questions', [\App\Http\Controllers\Teacher\ExamController::class, 'storeQuestion'])->name('operator.exams.questions.store');
    Route::get('/exams/{exam}/questions/{question}/edit', [\App\Http\Controllers\Teacher\ExamController::class, 'editQuestion'])->name('operator.exams.questions.edit');
    Route::put('/exams/{exam}/questions/{question}', [\App\Http\Controllers\Teacher\ExamController::class, 'updateQuestion'])->name('operator.exams.questions.update');
    Route::delete('/exams/{exam}/questions/{question}', [\App\Http\Controllers\Teacher\ExamController::class, 'destroyQuestion'])->name('operator.exams.questions.destroy');
    Route::get('/exams/{exam}/questions/import', [\App\Http\Controllers\Teacher\ExamController::class, 'import'])->name('operator.exams.questions.import');
    Route::post('/exams/{exam}/questions/import', [\App\Http\Controllers\Teacher\ExamController::class, 'storeImport'])->name('operator.exams.questions.storeImport');
    Route::get('/exams/{exam}/questions/export', [\App\Http\Controllers\Teacher\ExamController::class, 'exportQuestions'])->name('operator.exams.questions.export');

    // AI import
    Route::get('/exams/{exam}/questions/ai-import', [\App\Http\Controllers\Teacher\AIImportController::class, 'create'])->name('operator.exam.questionAIImport');
    Route::post('/exams/{exam}/questions/ai-import/generate', [\App\Http\Controllers\Teacher\AIImportController::class, 'generate'])->name('operator.exam.questionAIImportGenerate');
    Route::post('/exams/{exam}/questions/ai-import/confirm', [\App\Http\Controllers\Teacher\AIImportController::class, 'confirm'])->name('operator.exam.questionAIImportConfirm');

    // Bank Soal
    Route::get('/questions', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'index'])->name('operator.questions.index');
    Route::get('/questions/export', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'export'])->name('operator.questions.export');

    // Exam Sessions
    Route::get('/exam-sessions', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'index'])->name('operator.exam_sessions.index');
    Route::get('/exam-sessions/create', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'create'])->name('operator.exam_sessions.create');
    Route::post('/exam-sessions', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'store'])->name('operator.exam_sessions.store');
    Route::get('/exam-sessions/{exam_session}', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'show'])->name('operator.exam_sessions.show');
    Route::get('/exam-sessions/{exam_session}/edit', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'edit'])->name('operator.exam_sessions.edit');
    Route::put('/exam-sessions/{exam_session}', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'update'])->name('operator.exam_sessions.update');
    Route::delete('/exam-sessions/{exam_session}', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'destroy'])->name('operator.exam_sessions.destroy');
    Route::get('/exam-sessions/{exam_session}/enrolle/create', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'createEnrolle'])->name('operator.exam_sessions.createEnrolle');
    Route::post('/exam-sessions/{exam_session}/enrolle/store', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'storeEnrolle'])->name('operator.exam_sessions.storeEnrolle');
    Route::delete('/exam-sessions/{exam_session}/enrolle/{exam_group}/destroy', [\App\Http\Controllers\Teacher\ExamSessionController::class, 'destroyEnrolle'])->name('operator.exam_sessions.destroyEnrolle');

    // Exam Control + Monitor
    Route::get('/exam-control', [\App\Http\Controllers\Teacher\ExamControlController::class, 'index'])->name('operator.exam_control.index');
    Route::get('/monitor', [\App\Http\Controllers\Teacher\MonitorController::class, 'index'])->name('operator.monitor.index');
    Route::get('/monitor/{grade}', [\App\Http\Controllers\Teacher\MonitorController::class, 'show'])->name('operator.monitor.show');
    Route::post('/monitor/{grade}/unlock', [\App\Http\Controllers\Teacher\MonitorController::class, 'unlock'])->name('operator.monitor.unlock');
    Route::post('/monitor/{grade}/stop', [\App\Http\Controllers\Teacher\MonitorController::class, 'stop'])->name('operator.monitor.stop');
    Route::post('/monitor/{grade}/reopen', [\App\Http\Controllers\Teacher\MonitorController::class, 'reopen'])->name('operator.monitor.reopen');
    Route::post('/monitor/{grade}/add-time', [\App\Http\Controllers\Teacher\MonitorController::class, 'addTime'])->name('operator.monitor.add_time');

    // Students
    Route::get('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('operator.students.index');
    Route::get('/students/create', [\App\Http\Controllers\Teacher\StudentController::class, 'create'])->name('operator.students.create');
    Route::post('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'store'])->name('operator.students.store');
    Route::get('/students/import', [\App\Http\Controllers\Teacher\StudentController::class, 'import'])->name('operator.students.import');
    Route::post('/students/import', [\App\Http\Controllers\Teacher\StudentController::class, 'storeImport'])->name('operator.students.storeImport');
    Route::get('/students/export', [\App\Http\Controllers\Teacher\StudentController::class, 'export'])->name('operator.students.export');
    Route::get('/students/{student}/edit', [\App\Http\Controllers\Teacher\StudentController::class, 'edit'])->name('operator.students.edit');
    Route::put('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'update'])->name('operator.students.update');
    Route::delete('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'destroy'])->name('operator.students.destroy');

    // Classrooms
    Route::get('/classrooms', [\App\Http\Controllers\Teacher\ClassroomController::class, 'index'])->name('operator.classrooms.index');
    Route::get('/classrooms/create', [\App\Http\Controllers\Teacher\ClassroomController::class, 'create'])->name('operator.classrooms.create');
    Route::post('/classrooms', [\App\Http\Controllers\Teacher\ClassroomController::class, 'store'])->name('operator.classrooms.store');
    Route::get('/classrooms/{classroom}/edit', [\App\Http\Controllers\Teacher\ClassroomController::class, 'edit'])->name('operator.classrooms.edit');
    Route::put('/classrooms/{classroom}', [\App\Http\Controllers\Teacher\ClassroomController::class, 'update'])->name('operator.classrooms.update');
    Route::delete('/classrooms/{classroom}', [\App\Http\Controllers\Teacher\ClassroomController::class, 'destroy'])->name('operator.classrooms.destroy');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('operator.reports.index');
    Route::get('/reports/filter', [\App\Http\Controllers\Teacher\ReportController::class, 'filter'])->name('operator.reports.filter');
    Route::get('/reports/export', [\App\Http\Controllers\Teacher\ReportController::class, 'export'])->name('operator.reports.export');

    // Lessons management (use Operator controller)
    Route::resource('/lessons', \App\Http\Controllers\Operator\LessonController::class, ['as' => 'operator']);
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

    // Student assignments & tryouts list + play
    Route::get('/assignments', [App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('student.assignments.index');
    Route::match(['get','post'],'/assignments/{assignment}/start', [App\Http\Controllers\Student\AssignmentPlayController::class, 'start'])->name('student.assignments.start');
    // new per-question navigation show route (like exam UI)
    Route::get('/assignments/{assignment}/{page}', [App\Http\Controllers\Student\AssignmentPlayController::class, 'show'])->whereNumber('page')->name('student.assignments.show');
    // result page after finish
    Route::get('/assignments/{assignment}/result', [App\Http\Controllers\Student\AssignmentPlayController::class, 'result'])->name('student.assignments.result');
    Route::post('/assignments/{assignment}/answer', [App\Http\Controllers\Student\AssignmentPlayController::class, 'answer'])->name('student.assignments.answer');
    Route::post('/assignments/{assignment}/finish', [App\Http\Controllers\Student\AssignmentPlayController::class, 'finish'])->name('student.assignments.finish');
    Route::get('/tryouts', [App\Http\Controllers\Student\TryoutController::class, 'index'])->name('student.tryouts.index');
    Route::match(['get','post'],'/tryouts/{tryout}/start', [App\Http\Controllers\Student\TryoutPlayController::class, 'start'])->name('student.tryouts.start');
    Route::get('/tryouts/{tryout}/{page}', [App\Http\Controllers\Student\TryoutPlayController::class, 'show'])->whereNumber('page')->name('student.tryouts.show');
    Route::get('/tryouts/{tryout}/result', [App\Http\Controllers\Student\TryoutPlayController::class, 'result'])->name('student.tryouts.result');
    Route::post('/tryouts/{tryout}/answer', [App\Http\Controllers\Student\TryoutPlayController::class, 'answer'])->name('student.tryouts.answer');
    Route::post('/tryouts/{tryout}/finish', [App\Http\Controllers\Student\TryoutPlayController::class, 'finish'])->name('student.tryouts.finish');

    //heartbeat endpoint for monitoring
    Route::post('/heartbeat', [App\Http\Controllers\Student\ExamController::class, 'heartbeat'])->name('student.exams.heartbeat');

    //explicit exit exam endpoint
    Route::post('/exam-exit', [App\Http\Controllers\Student\ExamController::class, 'exitExam'])->name('student.exams.exit');
    });

});