<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Criteria;
use App\Quarter;
use App\StudentInfo;
use App\SubjectSection;
use App\SubjectScheduleSection;
use App\QuarterStudentGrade;
use App\StudentCriteriaScore;
use App\ScoreSheetData;

class StudentController extends Controller
{
    
	public function __construct(){
        $this->middleware('auth');
    }
    
	public function getStudentGrade(Request $request){
		$user_id = $request['user_id'];
		$academic_year_id = $request['academic_year_id'];
		$section = StudentInfo::select('section_id')->where('user_id', $user_id)->first();
		$section_id = $section['section_id'];
		$subjects = SubjectSection::where('section_id', $section_id)
									->where('academic_year_id', $academic_year_id)
									->with('subject')
									->get();
		$grades = array();
		foreach ($subjects as $key => $data) {

			$data['schedule'] = SubjectScheduleSection::where([['section_id',$data->section_id],['subject_id', $data->subject_id],['academic_year_id', $academic_year_id]])->first();

			$subject_grades_array = array();

			$subject_grades = QuarterStudentGrade::where([['user_id',$user_id],['academic_year_id', $academic_year_id],['section_id',$data->section_id],['subject_id', $data->subject_id]])->get();

			foreach ($subject_grades as $key => $subject_grade) {
				$subject_grade['quarter'] = Quarter::where('id', $subject_grade['quarter_id'])->first();

				$subject_criteria_grades = StudentcriteriaScore::where([['user_id',$user_id],['academic_year_id', $academic_year_id],['section_id',$data->section_id],['subject_id', $data->subject_id],['quarter_id', $subject_grade['quarter_id']]])->get();

				$criteria_array = array();

				foreach ($subject_criteria_grades as $key => $criteria) {
					$criteria['criteria'] = Criteria::where('id', $criteria['criteria_id'])->where('academic_year_id', $academic_year_id)->first();
					array_push($criteria_array, $criteria);
				}

				$subject_grade['subject_criteria_grades'] = $criteria_array;

				array_push($subject_grades_array, $subject_grade);
			}

			$data['subject_grades'] = $subject_grades_array;

			array_push($grades, $data);
		}

		return $grades;
	}

    public function getScoreSheetData(Request $request){
        return ScoreSheetData::where([['user_id', $request['user_id']],['academic_year_id', $request['academic_year_id']],['section_id', $request['section_id']],['subject_id', $request['subject_id']],['quarter_id', $request['quarter_id']],['criteria_id', $request['criteria_id']]])
                              ->with('sheetData')
                              ->get();
    }
}
