<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function(){
    return redirect('/login');
});
Route::post('/logout',['uses' => 'Auth\LoginController@logout','as'   => 'logout'])->middleware('auth');
Route::get('/get-user-roles', 'CommonFunctionController@getUserRoles')->middleware('auth');
Route::get('/get-user-data', 'CommonFunctionController@getUserData')->middleware('auth');
Route::get('/get-quarter', 'CommonFunctionController@getQuarter')->middleware('auth');
Route::get('/get-teachers', 'CommonFunctionController@getTeachers')->middleware('auth');
Route::get('/get-subject', 'CommonFunctionController@getSubject')->middleware('auth');
Route::get('/get-subject-section-teacher', 'CommonFunctionController@getSubjectByTeacher')->middleware('auth');
Route::get('/get-subject-year', 'CommonFunctionController@getSubjectByYear')->middleware('auth');
Route::get('/get-subject-list', 'CommonFunctionController@getSubjectList')->middleware('auth');
Route::get('/get-subject-list-percentage', 'CommonFunctionController@getSubjectListPercentage')->middleware('auth');
Route::get('/get-subject-teacher', 'CommonFunctionController@getSubjectTeachers')->middleware('auth');
Route::get('/get-year', 'CommonFunctionController@getYear')->middleware('auth');
Route::get('/get-year-user', 'CommonFunctionController@getYearByUser')->middleware('auth');
Route::get('/get-section', 'CommonFunctionController@getSection')->middleware('auth');
Route::get('/get-year-section', 'CommonFunctionController@getSectionByYear')->middleware('auth');
Route::get('/get-student-list', 'CommonFunctionController@getStudentList')->middleware('auth');
Route::get('/get-teacher-list', 'CommonFunctionController@getTeacherList')->middleware('auth');
Route::get('/get-student-data-list', 'CommonFunctionController@getStudentDataList')->middleware('auth');
Route::get('/get-faculty-id', 'CommonFunctionController@getFacultyId')->middleware('auth');
Route::get('/get-student-id', 'CommonFunctionController@getStudentId')->middleware('auth');
Route::get('/get-criteria-list', 'CommonFunctionController@getCriteriaList')->middleware('auth');
Route::get('/get-subject-section', 'CommonFunctionController@getSubjectSectionList')->middleware('auth');
Route::get('/get-class-advisory-list', 'CommonFunctionController@getClassAdvisoryList')->middleware('auth');
Route::get('/get-subject-schedule', 'CommonFunctionController@getSubjectSchedule')->middleware('auth');
Route::get('/get-subject-criteria', 'CommonFunctionController@getCriteriaPercentageBySubject')->middleware('auth');
Route::get('/get-assessments', 'CommonFunctionController@getAssessments')->middleware('auth');
Route::get('/get-student-grade', 'CommonFunctionController@getStudentGrade')->middleware('auth');
Route::get('/get-students-grade', 'CommonFunctionController@getStudentsGrade')->middleware('auth');
Route::get('/get-student-grade-quarterly', 'CommonFunctionController@getStudentGradePerQuarter')->middleware('auth');
Route::post('/get-compute-subject-grade', 'CommonFunctionController@computeSubjectGrade')->middleware('auth');
Route::post('/get-compute-quarterly-grade', 'CommonFunctionController@computeQuarterlyGrade')->middleware('auth');
Route::get('/get-academic-year', 'CommonFunctionController@getAcademicYear')->middleware('auth');
Route::get('/get-academic-years', 'CommonFunctionController@getAcademicYears')->middleware('auth');

Route::get('/get-subjects-section', 'CommonFunctionController@getSubjectBySection')->middleware('auth');
Route::get('/get-advisory-section', 'CommonFunctionController@getAdvisorySection')->middleware('auth');
Route::get('/get-subject-grade', 'CommonFunctionController@getSubjectGrade')->middleware('auth');
Route::get('/get-adviser-principal', 'CommonFunctionController@getAdviserPrincipal')->middleware('auth');
Route::get('/get-user-info', 'CommonFunctionController@getUserInfo')->middleware('auth');


Route::post('/update-account', 'CommonFunctionController@updateAccount')->middleware('auth');

Route::get('/get-system-logo', 'AdviserController@getSystemLogo');
Route::get('/get-card-logos', 'CommonFunctionController@getCardLogos');

// SUPER ADMIN ROUTES
Route::group(['middleware' => ['superadmin', 'auth'], 'prefix' => 'admin'], function () {
    //Routing API

    Route::get('/', function(){
        return redirect('/admin/principal');
    });
    Route::get('/principal', function(){
    	return View::make('roles.admin.users.principal');
    });
    Route::get('/teacher', function(){
        return View::make('roles.admin.users.teacher');
    });
    Route::get('/student', function(){
        return View::make('roles.admin.users.student');
    });

    Route::get('/add-year', function(){
        return View::make('roles.admin.yearsection.year');
    });
    Route::get('/add-section', function(){
        return View::make('roles.admin.yearsection.section');
    });

    Route::get('/subject', function(){
        return View::make('roles.admin.subject.list');
    });
    Route::get('/subject-section', function(){
        return View::make('roles.admin.subject.section');
    });

    Route::get('/academic-year', function(){
        return View::make('roles.admin.settings.academicyear');
    });
    Route::get('/assign-principal', function(){
        return View::make('roles.admin.settings.assignprincipal');
    });
    Route::get('/images', function(){
        return View::make('roles.admin.settings.images');
    });

    Route::get('/profile', function(){
        return View::make('roles.admin.profile.profile');
    });

    //FUNCTION API
    //POST
    Route::post('/save-new-user', 'AdminController@saveNewUser');
    Route::post('/delete-user', 'AdminController@deleteUser');
    Route::post('/update-user', 'AdminController@updateUser');

    Route::post('/save-subject-teacher', 'AdminController@saveSubjectTeacher');
    Route::post('/delete-subject-teacher-schedule', 'AdminController@deleteSubjectTeacherSchedule');
    Route::post('/update-subject-teacher-schedule', 'AdminController@updateSubjectTeacherSchedule');

    Route::post('/save-subject-section', 'AdminController@saveSubjectSection');
    Route::post('/delete-subject-section', 'AdminController@deleteSubjectSection');
    Route::post('/update-subject-section', 'AdminController@updateSubjectSection');
    Route::post('/save-subject', 'AdminController@saveSubject');
    Route::post('/delete-subject', 'AdminController@deleteSubject');
    Route::post('/update-subject', 'AdminController@updateSubject');
    Route::post('/save-section', 'AdminController@saveSection');
    Route::post('/delete-section', 'AdminController@deleteSection');
    Route::post('/update-section', 'AdminController@updateSection');
    Route::post('/grades/save-new-criteria', 'AdminController@saveCriteria');
    Route::post('/grades/delete-criteria', 'AdminController@deleteCriteria');
    Route::post('/grades/update-criteria', 'AdminController@updateCriteria');
    Route::post('/save-subject-criteria-percentage', 'AdminController@saveSubjectCriteriaPercentage');
    Route::post('/update-subject-criteria-percentage', 'AdminController@updateSubjectCriteriaPercentage');
    Route::post('/grades/save-new-assessment', 'AdminController@saveAssessment');
    Route::post('/grades/delete-assessment', 'AdminController@deleteAssessment');
    Route::post('/grades/update-assessment', 'AdminController@updateAssessment');

    Route::post('/save-year', 'AdminController@saveYear');
    Route::post('/delete-year', 'AdminController@deleteYear');

    Route::post('/save-advisory-class', 'AdminController@saveAdvisoryClass');
    Route::post('/remove-advisory', 'AdminController@removeAdvisoryClass');

    Route::post('/save-new-academic-year', 'AdminController@saveAcademicYear');
    Route::post('/delete-academic-year', 'AdminController@deleteAcademicYear');
    Route::post('/update-academic-year', 'AdminController@updateAcademicYear');
    Route::post('/update-academic-year-active', 'AdminController@updateAcademicYearActive');
    Route::post('/update-principal-active', 'AdminController@updatePrincipalActive');

    Route::post('/save-system-image', 'AdminController@setSystemImage');

    
    //GET
    Route::get('/get-principal-list', 'AdminController@getPrincipalList');
    Route::get('/get-section-list', 'AdminController@getSectionList');
    Route::get('/get-subjects', 'AdminController@getSubjectByTeacher');
    Route::get('/get-assessment-list', 'AdminController@getAssessmentList');
    Route::get('/get-score-sheet-list', 'AdminController@getScoreSheetList');
    Route::get('/get-score-sheet-data', 'AdminController@getScoreSheetData');
    Route::get('/get-dashboard-data', 'AdminController@getDashboardData');
    Route::get('/get-section-bar-chart', 'AdminController@getSectionBarChart');
    Route::get('/get-academic-year-list', 'AdminController@getAcademicYearList');
    Route::get('/get-images', 'AdminController@getImages');
});

// ADMIN/PRINCIPAL ROUTES
Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'principal'], function () {
    //Routing API
    Route::get('/', function(){
    	return View::make('roles.principal.dashboard');
    });
    Route::get('/staff', function(){
        return View::make('roles.principal.users.staff');
    });
    Route::get('/teacher', function(){
        return View::make('roles.principal.users.teacher');
    });
    Route::get('/student', function(){
        return View::make('roles.principal.users.student');
    });
    Route::get('/add-year', function(){
        return View::make('roles.principal.yearsection.year');
    });
    Route::get('/add-section', function(){
        return View::make('roles.principal.yearsection.section');
    });
    Route::get('/advisory-list', function(){
        return View::make('roles.principal.yearsection.classadvisory');
    });
    Route::get('/subject', function(){
        return View::make('roles.principal.subject.list');
    });
    Route::get('/subject-teacher', function(){
        return View::make('roles.principal.subject.teacher');
    });
    Route::get('/subject-section', function(){
        return View::make('roles.principal.subject.section');
    });
    Route::get('/grades', function(){
        return View::make('roles.principal.grade.list');
    });
    Route::get('/grades-criteria', function(){
        return View::make('roles.principal.grade.criteria');
    });
    Route::get('/grades-assessment-level', function(){
        return View::make('roles.principal.grade.assessmentlevel');
    });
    Route::get('/academic-year', function(){
        return View::make('roles.principal.grade.academicyear');
    });
    Route::get('/students', function(){
        return View::make('roles.principal.search.students');
    });
    Route::get('/subjects', function(){
        return View::make('roles.principal.search.subjects');
    });
    Route::get('/scoresheets', function(){
        return View::make('roles.principal.search.scoresheets');
    });
    Route::get('/grades-period', function(){
        return View::make('roles.principal.grade.period');
    });
    Route::get('/grades-quarter', function(){
        return View::make('roles.principal.grade.quarter');
    });

    Route::get('/profile', function(){
        return View::make('roles.principal.profile.profile');
    });
    //FUNCTION API
    //POST
    Route::post('/save-new-user', 'PrincipalController@saveNewUser');
    Route::post('/delete-user', 'PrincipalController@deleteUser');
    Route::post('/update-user', 'PrincipalController@updateUser');

    Route::post('/save-subject-teacher', 'PrincipalController@saveSubjectTeacher');
    Route::post('/delete-subject-teacher-schedule', 'PrincipalController@deleteSubjectTeacherSchedule');
    Route::post('/update-subject-teacher-schedule', 'PrincipalController@updateSubjectTeacherSchedule');

    Route::post('/save-subject-section', 'PrincipalController@saveSubjectSection');
    Route::post('/delete-subject-section', 'PrincipalController@deleteSubjectSection');
    Route::post('/update-subject-section', 'PrincipalController@updateSubjectSection');
    Route::post('/save-subject', 'PrincipalController@saveSubject');
    Route::post('/delete-subject', 'PrincipalController@deleteSubject');
    Route::post('/update-subject', 'PrincipalController@updateSubject');
    Route::post('/save-section', 'PrincipalController@saveSection');
    Route::post('/delete-section', 'PrincipalController@deleteSection');
    Route::post('/update-section', 'PrincipalController@updateSection');
    Route::post('/grades/save-new-criteria', 'PrincipalController@saveCriteria');
    Route::post('/grades/delete-criteria', 'PrincipalController@deleteCriteria');
    Route::post('/grades/update-criteria', 'PrincipalController@updateCriteria');
    Route::post('/save-subject-criteria-percentage', 'PrincipalController@saveSubjectCriteriaPercentage');
    Route::post('/update-subject-criteria-percentage', 'PrincipalController@updateSubjectCriteriaPercentage');
    Route::post('/grades/save-new-assessment', 'PrincipalController@saveAssessment');
    Route::post('/grades/delete-assessment', 'PrincipalController@deleteAssessment');
    Route::post('/grades/update-assessment', 'PrincipalController@updateAssessment');

    Route::post('/save-year', 'PrincipalController@saveYear');
    Route::post('/delete-year', 'PrincipalController@deleteYear');

    Route::post('/save-advisory-class', 'PrincipalController@saveAdvisoryClass');
    Route::post('/remove-advisory', 'PrincipalController@removeAdvisoryClass');

    Route::post('/save-new-academic-year', 'PrincipalController@saveAcademicYear');
    Route::post('/delete-academic-year', 'PrincipalController@deleteAcademicYear');
    Route::post('/update-academic-year', 'PrincipalController@updateAcademicYear');
    Route::post('/update-academic-year-active', 'PrincipalController@updateAcademicYearActive');
    
    //GET
    Route::get('/get-section-list', 'PrincipalController@getSectionList');
    Route::get('/get-subjects', 'PrincipalController@getSubjectByTeacher');
    Route::get('/get-assessment-list', 'PrincipalController@getAssessmentList');
    Route::get('/get-score-sheet-list', 'TeacherController@getScoreSheetList');
    Route::get('/get-score-sheet-data', 'TeacherController@getScoreSheetData');
    Route::get('/get-dashboard-data', 'PrincipalController@getDashboardData');
    Route::get('/get-section-bar-chart', 'PrincipalController@getSectionBarChart');
    Route::get('/get-academic-year-list', 'PrincipalController@getAcademicYearList');

});


// TEACHER ROUTES
Route::group(['middleware' => ['teacher', 'auth'], 'prefix' => 'teacher'], function () {
    //Routing API
    Route::get('/', function(){
    	return View::make('roles.teacher.dashboard');
    });

    Route::get('/score-sheet', function(){
        return View::make('roles.teacher.scoresheet');
    });
    Route::get('/add-score-sheet', function(){
        return View::make('roles.teacher.addscoresheet');
    });
    Route::get('/score-sheet-list', function(){
        return View::make('roles.teacher.scoresheetlist');
    });
    Route::get('/score-sheet-list-print', function(){
        return View::make('roles.teacher.scoresheetlistprint');
    });
    Route::get('/class-advisory', function(){
        return View::make('roles.teacher.classadvisory');
    });
    Route::get('/subjects', function(){
        return View::make('roles.teacher.subject');
    });
    Route::get('/reports-computation-per-period', function(){
        return View::make('roles.teacher.report.period');
    });
    Route::get('/reports-computation-per-quarter', function(){
        return View::make('roles.teacher.report.quarter');
    });
    Route::get('/progress-report', function(){
        return View::make('roles.teacher.report.progress');
    });
    Route::get('/progress-report/student', function(){
        return View::make('roles.teacher.report.student');
    });

    Route::get('/profile', function(){
        return View::make('roles.teacher.profile.profile');
    });
    //FUNCTION API
    //POST
    Route::post('/save-score-sheet', 'TeacherController@saveScoreSheet');
    Route::post('/update-score-sheet', 'TeacherController@updateScoreSheet');
    //GET
    Route::get('/get-score-sheet-list', 'TeacherController@getScoreSheetList');
    Route::get('/get-score-sheet-list-print', 'TeacherController@getScoreSheetListPrint');
    Route::get('/get-score-sheet-data', 'TeacherController@getScoreSheetData');
    Route::get('/get-subjects', 'TeacherController@getSubjectByTeacher');

});
// STUDENT ROUTES
Route::group(['middleware' => ['student', 'auth'], 'prefix' => 'student'], function () {
    //Routing API
    Route::get('/', function(){
    	return View::make('roles.student.dashboard');
    });

    Route::get('/profile', function(){
        return View::make('roles.student.profile.profile');
    });
    //FUNCTION API
    //GET
    Route::get('/get-student-grade', 'StudentController@getStudentGrade')->middleware('auth');
    Route::get('/get-score-sheet-data', 'StudentController@getScoreSheetData');
});
