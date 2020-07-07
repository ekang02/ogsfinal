<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UserInfo;
use App\StudentInfo;
use App\Subject;
use App\SubjectTeacher;
use App\SubjectSection;
use App\SubjectScheduleSection;
use App\Section;
use App\Year;
use App\Criteria;
use App\SubjectCriteriaPercentage;
use App\AdvisoryClass;
use App\AssessmentLevel;
use App\AcademicYear;
use App\SystemImages;

class AdminController extends Controller
{
     public function __construct(){
        $this->middleware('auth');
    }

    public function getPrincipalList(Request $request){
        $response = User::where('user_role',2)
                  ->with(['info' => function($query) use ($request){
                    $query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
                  }])
                  ->get();
        return $response;
    }

    
    public function saveNewUser(Request $request){
        $user_role = $request->user_role;

         $validatedData = $request->validate([
            'user_role' => ['required', 'digits:1', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        
        $insert = new User;
        $insert->user_role = $user_role;
        $insert->username = $request['username'];
        $insert->email = $request['email'];
        $insert->password = Hash::make($request['password']);
        $insert->status = 'Active';
        if($user_role == 2){
            $insert->isPrincipal = 0;
        }
        $insert->save();

        if($user_role == 5){
            $insertStudentInfo = new StudentInfo;
            $insertStudentInfo->user_id = $insert->id;
            $insertStudentInfo->academic_year_id = $request['academic_year_id'];
            $insertStudentInfo->student_id = $request['student_id'];
            $insertStudentInfo->year_id = $request['year_id'];
            $insertStudentInfo->section_id = $request['section_id'];
            $insertStudentInfo->first_name = $request['first_name'];
            $insertStudentInfo->middle_name = $request['middle_name'];
            $insertStudentInfo->last_name = $request['last_name'];
            $insertStudentInfo->phone_number = $request['phone_number'];
            $insertStudentInfo->gender = $request['gender'];
            $insertStudentInfo->address = $request['address'];
            $insertStudentInfo->date_of_birth = $request['date_of_birth'];
            $insertStudentInfo->age = $request['age'];
            $insertStudentInfo->save();
            return $insertStudentInfo;
        }else if($user_role == 4){
            $insertInfo = new UserInfo;
            $insertInfo->faculty_number = $request['faculty_id'];
            $insertInfo->user_id = $insert->id;
            $insertInfo->first_name = $request['first_name'];
            $insertInfo->middle_name = $request['middle_name'];
            $insertInfo->last_name = $request['last_name'];
            $insertInfo->phone_number = $request['phone_number'];
            $insertInfo->gender = $request['gender'];
            $insertInfo->address = $request['address'];
            $insertInfo->date_of_birth = $request['date_of_birth'];
            $insertInfo->age = $request['age'];
            $insertInfo->save();
            return $insertInfo;
        }else if($user_role == 2){
            $insertInfo = new UserInfo;
            $insertInfo->user_id = $insert->id;
            $insertInfo->first_name = $request['first_name'];
            $insertInfo->middle_name = $request['middle_name'];
            $insertInfo->last_name = $request['last_name'];
            $insertInfo->phone_number = $request['phone_number'];
            $insertInfo->gender = $request['gender'];
            $insertInfo->address = $request['address'];
            $insertInfo->date_of_birth = $request['date_of_birth'];
            $insertInfo->age = $request['age'];
            $insertInfo->save();
        }
    }
    public function deleteUser(Request $request){
        $deleteUser = User::where('id', $request['user_id'])->delete();
        if($request['user_role'] == 5){
            StudentInfo::where('user_id', $request['user_id'])->delete();
        }else{
            UserInfo::where('user_id', $request['user_id'])->delete();
        }
        return $deleteUser;
    }
    public function updateUser(Request $request){
        $user_id = $request['id'];
        $update = User::where('id', $user_id)->first();
        $update->username = $request['username'];
        $update->email = $request['email'];
        if(!empty($request['password'])){
            $insert->password = Hash::make($request['password']);
        }
        $update->save();
        if($update->user_role == 2){
            $updateInfo = UserInfo::where('user_id', $user_id)->first();
            $updateInfo->first_name = $request['first_name'];
            $updateInfo->middle_name = $request['middle_name'];
            $updateInfo->last_name = $request['last_name'];
            $updateInfo->phone_number = $request['phone_number'];
            $updateInfo->gender = $request['gender'];
            $updateInfo->address = $request['address'];
            $updateInfo->date_of_birth = $request['date_of_birth'];
            $updateInfo->age = $request['age'];
            $updateInfo->save();
            return $updateInfo;
        }else if($update->user_role == 4){
            $updateInfo = UserInfo::where('user_id', $user_id)->first();
            $updateInfo->first_name = $request['first_name'];
            $updateInfo->middle_name = $request['middle_name'];
            $updateInfo->last_name = $request['last_name'];
            $updateInfo->phone_number = $request['phone_number'];
            $updateInfo->gender = $request['gender'];
            $updateInfo->address = $request['address'];
            $updateInfo->date_of_birth = $request['date_of_birth'];
            $updateInfo->age = $request['age'];
            $updateInfo->save();
            return $updateInfo;
        }else if($update->user_role == 5){
            $updateInfo = StudentInfo::where('user_id', $user_id)->first();
            $updateInfo->year_id = $request['year_id'];
            $updateInfo->section_id = $request['section_id'];
            $updateInfo->first_name = $request['first_name'];
            $updateInfo->middle_name = $request['middle_name'];
            $updateInfo->last_name = $request['last_name'];
            $updateInfo->phone_number = $request['phone_number'];
            $updateInfo->gender = $request['gender'];
            $updateInfo->address = $request['address'];
            $updateInfo->date_of_birth = $request['date_of_birth'];
            $updateInfo->age = $request['age'];
            $updateInfo->save();
            return $updateInfo;
        }
    }

    public function saveSubject(Request $request){
        $insert = new Subject;
        $insert->academic_year_id = $request['academic_year_id'];
        $insert->year_id = $request['year_id'];
        $insert->label = $request['subject'];
        $insert->subject_code = $request['subject_code'];
        $insert->status = 'Active';
        $insert->save();
        return $insert;
    }
    public function deleteSubject(Request $request){
        $delete = Subject::where('id', $request['id'])->delete();
        return $delete;
    }
    public function updateSubject(Request $request){
        $update = Subject::where('id', $request['subject_id'])->first();
        $update->year_id = $request['year_id'];
        $update->label = $request['subject'];
        $update->subject_code = $request['subject_code'];
        $update->status = 'Active';
        $update->save();
        return $update;
    }

    public function saveSubjectTeacher(Request $request){
        $days = collect($request['days']);
        $searchSubjectTeacher = SubjectTeacher::where('user_id', $request['user_id'])
                                            ->where('academic_year_id', $request['academic_year_id'])
                                            // ->where('year_id', $request['year_id'])
                                            // ->where('section_id', $request['section_id'])
                                            ->where('subject_id', $request['subject_id'])
                                            ->first();

        
        if($searchSubjectTeacher){
            if (SubjectScheduleSection::where('subject_teacher_id', $searchSubjectTeacher['id'])->where('section_id', $request['section_id'])->where('academic_year_id', $request['academic_year_id'])->exists()) {
                abort(422, 'Subject Already have schedule!');
            }else{
                $subject_teacher_id = $searchSubjectTeacher['id'];
                $insertSubjectScheduleSection = new SubjectScheduleSection;
                $insertSubjectScheduleSection->subject_teacher_id = $subject_teacher_id;
                $insertSubjectScheduleSection->academic_year_id = $request['academic_year_id'];
                $insertSubjectScheduleSection->user_id = $request['user_id'];
                $insertSubjectScheduleSection->section_id = $request['section_id'];
                $insertSubjectScheduleSection->subject_id = $request['subject_id'];
                $insertSubjectScheduleSection->days = $days->implode('label', ',');;
                $insertSubjectScheduleSection->start_time = $request['start_time'];
                $insertSubjectScheduleSection->end_time = $request['end_time'];
                $insertSubjectScheduleSection->status = 'Active';
                $insertSubjectScheduleSection->save();

            }
        }else{
            $insertSubjectTeacher = new SubjectTeacher;
            $insertSubjectTeacher->academic_year_id = $request['academic_year_id'];
            $insertSubjectTeacher->user_id = $request['user_id'];
            $insertSubjectTeacher->year_id = $request['year_id'];
            $insertSubjectTeacher->subject_id = $request['subject_id'];
            // $insertSubjectTeacher->section_id = $request['section_id'];
            $insertSubjectTeacher->status = 'Active';
            $insertSubjectTeacher->save();

            $subject_teacher_id = $insertSubjectTeacher->id;
            $insertSubjectScheduleSection = new SubjectScheduleSection;
            $insertSubjectScheduleSection->subject_teacher_id = $subject_teacher_id;
            $insertSubjectScheduleSection->academic_year_id = $request['academic_year_id'];
            $insertSubjectScheduleSection->user_id = $request['user_id'];
            $insertSubjectScheduleSection->section_id = $request['section_id'];
            $insertSubjectScheduleSection->subject_id = $request['subject_id'];
            $insertSubjectScheduleSection->days = $days->implode('label', ', ');;
            $insertSubjectScheduleSection->start_time = $request['start_time'];
            $insertSubjectScheduleSection->end_time = $request['end_time'];
            $insertSubjectScheduleSection->status = 'Active';
            $insertSubjectScheduleSection->save();

        }

        return $insertSubjectScheduleSection;
    }

    public function deleteSubjectTeacherSchedule(Request $request){
        $delete = SubjectScheduleSection::where('id', $request['id'])->delete();
        return $delete;
    }
    public function updateSubjectTeacherSchedule(Request $request){
        $days = collect($request['days'])->implode('label', ',');
        $update = SubjectScheduleSection::where('id', $request['schedule_id'])->first();
        $update->days = $days;
        $update->start_time = $request['start_time'];
        $update->end_time = $request['end_time'];
        $update->save();
        return $update;
    }

    public function saveSubjectSection(Request $request){
        $subjects = $request['subject'];
        foreach ($subjects as $subject) {
            if (
                SubjectSection::where('year_id', $request['year_id'])
                ->where('section_id', $request['section_id'])
                ->where('subject_id', $subject['id'])
                ->exists()) {
                abort(422, 'Subject is Already Assigned!');
            }else{
                $insert = new SubjectSection;
                $insert->academic_year_id = $request['academic_year_id'];
                $insert->year_id = $request['year_id'];
                $insert->section_id = $request['section_id'];
                $insert->subject_id = $subject['id'];
                $insert->status = 'Active';
                $insert->save();
            }
        }
        return 'saved';
    }

    public function deleteSubjectSection(Request $request){
        $delete = SubjectSection::where('id', $request['id'])->delete();
        return $delete;
    }

    // public function updateSubjectSection(Request $request){
    //     if (SubjectSection::where('year_id', $request['year_id'])->where('section_id', $request['section_id'])->where('subject_id', $request['subject_id'])->exists()) {
    //         abort(422, 'Subject is Already Assigned!');
    //     }else{

    //         $update = SubjectSection::where('id', $request['id']);
    //         $update->year_id = $request['year_id'];
    //         $update->section_id = $request['section_id'];
    //         $update->subject_id = $request['subject_id'];
    //         $update->save();
    //         return $update;
    //     }
    // }

    //Section
    public function getSectionList(Request $request){
        if($request['hasAdviser']){
            $response = Section::where('hasAdviser', $request['hasAdviser'])
                                ->where('academic_year_id',$request['academic_year_id'])
                                ->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
                                ->with('year')->get();
        }else{
            $response = Section::where('academic_year_id',$request['academic_year_id'])
                                ->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
                                ->with('year')->get();
        }
        return $response;
    }

    public function saveSection(Request $request){
        $insert = new Section;
        $insert->year_id = $request['year_id'];
        $insert->section = $request['section'];
        $insert->label = $request['section_label'];
        $insert->academic_year_id = $request['academic_year_id'];
        $insert->status = 'Active';
        $insert->hasAdviser = 'false';
        $insert->save();
        return $insert;
    }

    public function deleteSection(Request $request){
        $delete = Section::where('id', $request['id'])->delete();
        return $delete;
    }
    public function updateSection(Request $request){
        $update = Section::where('id', $request['section_id'])->first();
        $update->year_id = $request['year_id'];
        $update->section = $request['section'];
        $update->label = $request['section_label'];
        $update->save();
        return $update;
    }

    public function saveYear(Request $request){
         if (Year::where('year', $request['year'])->exists()) {
                abort(422, 'Year Already Exists!');
            }else{
                $insert = new Year;
                $insert->year = $request['year'];
                $insert->status = 'Active';
                $insert->save();
                return $insert;
            }
    }
    public function deleteYear(Request $request){
        $delete = Year::where('id', $request['id'])->delete();
        return $delete;
    }

    public function getSubjectbyTeacher(Request $request){
        $response = SubjectTeacher::with(["userInfo" => function($query) use ($request){
                                         $query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
                                    }])
                                    ->with('year')
                                    ->with('subject')
                                    ->with(["scheduleSection" => function($query) use ($request){
                                        $query->with(["section" => function($query) use ($request){
                                            $query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"));
                                        }]);
                                    }])
                                    ->get();
        return $response;
    }

    public function saveCriteria(Request $request){
        $insert = new Criteria;
        $insert->academic_year_id = $request['academic_year_id'];
        $insert->label = $request['criteria'];
        $insert->assessment_id = $request['assessment_id'];
        $insert->save();
        return $insert;
    }
    public function deleteCriteria(Request $request){
        $delete = Criteria::where('id', $request['id'])->delete();
        return $delete;
    }
    public function updateCriteria(Request $request){
        $update = Criteria::where('id', $request['id'])->first();
        $update->label = $request['criteria'];
        $update->assessment_id = $request['assessment_id'];
        $update->save();
        return $update;
    }

    public function saveSubjectCriteriaPercentage(Request $request){
        $assessments = $request['assessments'];
        $subject_id = $request['subject_id'];
        $academic_year_id = $request['academic_year_id'];
        foreach ($assessments as $assessment) {
            $assessment_id = $assessment['id'];
            foreach ($assessment['grades'] as $key => $data) {
                $insert = new SubjectCriteriaPercentage;
                $insert->academic_year_id = $academic_year_id;
                $insert->assessment_id = $assessment_id;
                $insert->subject_id = $subject_id;
                $insert->criteria_id = $data['category']['id'];
                $insert->percentage = $data['percentage'];
                $insert->save();
            }
        }
        return 'true';
    }
    public function updateSubjectCriteriaPercentage(Request $request){
        $assessments = $request['assessments'];
        $subject_id = $request['subject_id'];
        $subject_criteria_ids = $request['subject_criteria_ids'];
        foreach ($assessments as $assessment) {
            $assessment_id = $assessment['id'];
            foreach ($assessment['grades'] as $key => $data) {
                if(isset($data['id'])){
                    $update = SubjectCriteriaPercentage::where('id',$data['id'])->first();
                    $update->assessment_id = $assessment_id;
                    $update->criteria_id = $data['category']['id'];
                    $update->percentage = $data['percentage'];
                    $update->save();
                }else{
                    $insert = new SubjectCriteriaPercentage;
                    $insert->assessment_id = $assessment_id;
                    $insert->subject_id = $subject_id;
                    $insert->criteria_id = $data['category']['id'];
                    $insert->percentage = $data['percentage'];
                    $insert->save();
                }
            }
        }
        if(count($subject_criteria_ids) > 0){
            foreach ($subject_criteria_ids as $data) {
                $delete = SubjectCriteriaPercentage::where('id', $data['id'])->delete();
            }
        }
        return 'true';
    }

    public function saveAdvisoryClass(Request $request){
        if (AdvisoryClass::where('academic_year_id', $request['academic_year_id'])->where('teacher_id', $request['teacher_id'])->where('section_id', $request['section_id'])->exists()) {
            abort(422, 'Section Already have Adviser!');
        }else{
            $updateSection = Section::where('id', $request['section_id'])->first();
            $updateSection->hasAdviser = 'true';
            $updateSection->save();
            
            $insert = new AdvisoryClass;
            $insert->academic_year_id = $request['academic_year_id'];
            $insert->teacher_id = $request['teacher_id'];
            $insert->section_id = $request['section_id'];
            $insert->save();
        }
    }

    public function removeAdvisoryClass(Request $request){

        $updateSection = Section::where('id', $request['section_id'])->first();
        $updateSection->hasAdviser = 'false';
        $updateSection->save();

        $delete = AdvisoryClass::where('id', $request['id'])->delete();
        return $delete;
    }

    public function getAssessmentList(Request $request){
        return AssessmentLevel::with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])->get();
    }

    public function saveAssessment(Request $request){
        $insert = new AssessmentLevel;
        $insert->academic_year_id = $request['academic_year_id'];
        $insert->label = $request['assessment'];
        $insert->percentage = $request['percentage'];
        $insert->save();
        return $insert;
    }
    public function deleteAssessment(Request $request){
        $delete = AssessmentLevel::where('id', $request['id'])->delete();
        return $delete;
    }
    public function updateAssessment(Request $request){
        $update = AssessmentLevel::where('id', $request['id'])->first();
        $update->label = $request['assessment'];
        $update->percentage = $request['percentage'];
        $update->save();
        return $update;
    }

    public function getCriteriaID($data){
        $res = array();
        foreach($data as $k=>$v) {
            $res[] = $v['id'];
        }
        return implode(',', $res);
    }

    public function getAcademicYearList(Request $request){
        return AcademicYear::get();
    }

    public function saveAcademicYear(Request $request){
        $exists  = AcademicYear::where('from_year', $request['from_year'])
                                ->where('to_year', $request['to_year'])
                                ->first();
        if(!$exists){
            $insert = new AcademicYear;
            $insert->from_year = $request['from_year'];
            $insert->to_year = $request['to_year'];
            $insert->isActive = false;
            $insert->save();
            return $insert;
        }else{
            return 'exists';
        }
    }
    public function deleteAcademicYear(Request $request){
        $delete = AcademicYear::where('id', $request['id'])->delete();
        return $delete;
    }
    public function updateAcademicYear(Request $request){
        $update = AcademicYear::where('id', $request['id'])->first();
        $update->from_year = $request['from_year'];
        $update->to_year = $request['to_year'];
        $update->save();
        return $update;
    }
    public function updateAcademicYearActive(Request $request){
        $update = AcademicYear::where('id', $request['id'])->first();
        $update->isActive = true;
        $update->save();
        AcademicYear::where('id', '!=',  $request['id'])->update(['isActive' => false]);
        return $update;
    }
    public function updatePrincipalActive(Request $request){
        $update = User::where('id', $request['id'])->where('user_role', 2)->first();
        $update->isPrincipal = 1;
        $update->save();
        User::where('id', '!=',  $request['id'])->where('user_role', 2)->update(['isPrincipal' => 0]);
        return $update;
    }


    public function getImages(Request $request){
        return SystemImages::get();
    }

    public function setSystemImage(Request $request){
        $images = SystemImages::where('type', $request['type'])->first();
        if($images){
            $images->image = $request['image'];
            $images->save();
            return $images;
        }else{
            $images = new SystemImages;
            $images->type = $request['type'];
            $images->image = $request['image'];
            $images->save();
            return $images;
        }
    }

}