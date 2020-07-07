<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\UserInfo;
use App\StudentInfo;
use App\UserRole;
use App\Subject;
use App\SubjectTeacher;
use App\SubjectSection;
use App\Year;
use App\Section;
use App\Criteria;
use App\AdvisoryClass;
use App\SubjectCriteriaPercentage;
use App\SubjectScheduleSection;
use App\Quarter;
use App\ScoreSheet;
use App\ScoreSheetData;
use App\AssessmentLevel;
use App\CriteriaTotalScore;
use App\StudentCriteriaScore;
use App\QuarterStudentGrade;
use App\TransmutationTable;
use App\AcademicYear;
use App\SystemImages;
use Illuminate\Support\Facades\Log;


class CommonFunctionController extends Controller
{
	public function __construct(){
        $this->middleware('auth');
    }
    
	public function getUserRoles(Request $request){
		return UserRole::get();
	}
	public function getUserData(Request $request){
		return StudentInfo::where('user_id', $request['user_id'])->with(['section','year'])->first();
	}
	public function getUserInfo(Request $request){
		$user = User::where('id', $request['id'])->first();
		$user_role = $user['user_role'];
		if($user_role == 5){
			return StudentInfo::where('user_id', $user['id'])->first();
		}else{
			return UserInfo::where('user_id', $user['id'])->first();
		}
	}
	public function getAdviserPrincipal(Request $request){
		$adviser = UserInfo::where('user_id',$request['user_id'])->first();
		$principal = User::where('user_role',2)->where('isPrincipal', 1)->with('info')->first();
		return [ 'adviser'=> $adviser , 'principal'=> $principal];
	}
	public function getQuarter(Request $request){
		if($request['quarter_id']){
			return Quarter::where('id', $request['quarter_id'])->first();
		}else{
			return Quarter::get();
		}
	}
	public function getTeachers(Request $request){
		$response = UserInfo::select(['*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name")])
						    ->whereHas('user', function ($query) use ($request) {
						        return $query->where('user_role',4)->orWhere('user_role',3);
						    })
							->get();
		return $response;
	}
	public function getSubject(Request $request){
		if($request['subject_id']){
			$response = Subject::where('id',$request['subject_id'])
                                ->where('academic_year_id',$request['academic_year_id'])
								->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
								->with('year')
								->first();
		}else if($request['year_id']){
			$response = Subject::where('year_id',$request['year_id'])
                                ->where('academic_year_id',$request['academic_year_id'])
								->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
								->with('year')->get();
		}else if($request['section_id']){
			$response = Section::with("subjectSections.subject")
                                ->where('academic_year_id',$request['academic_year_id'])
								->where('id',$request['section_id'])
								->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
								->first();
		}else{
			$response = Subject::with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
								->with('year')
                                ->where('academic_year_id',$request['academic_year_id'])
                                ->get();
		}
		return $response;
	}
	public function getSubjectByTeacher(Request $request){
		$response = SubjectScheduleSection::with("subject")
							->where('academic_year_id',$request['academic_year_id'])
							->where('user_id',$request['user_id'])
							// ->where('year_id',$request['year_id'])
							->where('section_id',$request['section_id'])
							->groupBy('subject_id')
							->get();
		return $response;
	}
	public function getSubjectBySection(Request $request){
		if($request['subject_id']){
			$response = SubjectSection::where('id',$request['subject_id'])->with('year')->first();
		}else if($request['year_id']){
			$response = SubjectSection::where('year_id',$request['year_id'])->with('year')->get();
		}else{
			$response = SubjectSection::with('year')->get();
		}
		return $response;
	}

    public function getSubjectSectionList(Request $request){
    	if($request['section_id']){
        	$response = SubjectSection::with('year')
                                    ->with('subject')
                                    ->with('section')
                                    ->with(['academicYear' => function($q) use ($request){
	                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
	                                }])
                                    ->where('section_id', $request['section_id'])
									->where('academic_year_id', $request['academic_year_id'])
                                    ->get();

    	}else{
        	$response = SubjectSection::with('year')
                                    ->with('subject')
                                    ->with('section')
                                    ->with(['academicYear' => function($q) use ($request){
	                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
	                                }])
									->where('academic_year_id', $request['academic_year_id'])
                                    ->get();

    	}
        return $response;
    }

	public function getSubjectList(Request $request){
		if($request['year_id']){			
			$response = Subject::where('year_id',$request['year_id'])->with('year')->get();

		}else{
			$response = Subject::with('year')->get();
		}
		return $response;
	}
	public function getSubjectListPercentage(Request $request){
		if($request['year_id']){			
			$response = Subject::where('year_id',$request['year_id'])
						->where('academic_year_id',$request['academic_year_id'])
						->with('year')
						->with('criteria_percentage')
						->with(['academicYear' => function($q) use ($request){
                            $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                        }])
						->get();

		}else{
			$response = Subject::with('year')
						->with('criteria_percentage')
						->with(['academicYear' => function($q) use ($request){
                            $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                        }])
						->get();
		}
		return $response;
	}
	public function getSubjectByYear(Request $request){
		$response = Subject::where('year_id',$request['year_id'])->get();
		return $response;
	}
	public function getYear(Request $request){
		if($request['year_id']){
			$response = Year::where('id',$request['year_id'])->first();
		}else{
			$response = Year::get();
		}
		return $response;
	}
	public function getSection(Request $request){
		if($request['section_id']){
			$response = Section::where('id',$request['section_id'])->first();
		}else{
			$response = Section::get();
		}
		return $response;
	}
	public function getSectionByYear(Request $request){
		$response = Section::where([['academic_year_id',$request['academic_year_id']],['year_id',$request['year_id']]])->get();
		return $response;
	}

	public function getStudentList(Request $request){
		$year_id = $request['year_id'];
		$section_id = $request['section_id'];
		$academic_year_id = $request['academic_year_id'];
		if($year_id && $section_id){
			$response = StudentInfo::select(['*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name")])
									->where('year_id',$year_id)
									->where('section_id',$section_id)
									->where('academic_year_id',$academic_year_id)
									->with('user')
									->get();
		}else if($section_id){
			$response = StudentInfo::select(['*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name")])
									->where('section_id',$section_id)
									->where('academic_year_id',$academic_year_id)
									->with('user')
									->get();
		}else{
			$response = StudentInfo::select(['*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name")])
									->with('user')
									->where('academic_year_id',$academic_year_id)
									->get();
		}
		return $response;
	}

	public function getSubjectTeachers(Request $request){
		$response = SubjectTeacher::with(["userInfo" => function($query) use ($request){
										 $query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
									}])
									->with('year')
									->with('subject')
									->with(["scheduleSection" => function($query) use ($request){
										$query->with('subject')
											->with(["section" => function($query) use ($request){
											 	$query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"));
											}]);
									}])
									->with(['academicYear' => function($q) use ($request){
	                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
	                                }])
	                                ->where('academic_year_id',$request['academic_year_id'])
	                                ->groupBy('subject_id')
									->get();
		return $response;
	}	

	public function getTeacherList(Request $request){
		$response = User::where('user_role',4)
						  ->orWhere('user_role',3)
						  ->with(['info' => function($query) use ($request){
						  	$query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
						  }])
						  ->get();
		return $response;
	}
	public function getFacultyId(Request $request){
		// Get the last created
		$lastFacultyNumber = UserInfo::orderBy('created_at', 'desc')->first();
		if ( ! $lastFacultyNumber )
		    // We get here if there is no at all
		    // If there is no number set it to 0, which will be 1 at the end.
		    $number = 0;
		else 
		    $number = substr($lastFacultyNumber->faculty_number, 6);
		// If we have ORD000001 in the database then we only want the number
		// So the substr returns this 000001

		// Add the string in front and higher up the number.
		// the %05d part makes sure that there are always 6 numbers in the string.
		// so it adds the missing zero's when needed.

		return 'F-'. date("Y") . sprintf('%03d', intval($number) + 1);
	}

	public function getStudentDataList(Request $request){
		$subject_id = $request['subject_id'];
		$section_id = $request['section_id'];
		$academic_year_id = $request['academic_year_id'];
		if($subject_id && $section_id){
			$response =	StudentInfo::select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"))
									->with(['user' => function($query) use ($request){
									  	// $query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"))
									  	// 	->where('section_id', $request['section_id']);
									  }])
									->where('section_id', $section_id)
									->where('academic_year_id', $academic_year_id)
									->get();
		}else{
			$response = User::where('user_role',5)
							->with(['studentInfo' => function($query) use ($request){
						  		$query->select('*',DB::raw("CONCAT(first_name,' ',last_name) AS full_name"))
						  	      ->with(['academicYear' => function($q) use ($request){
						  	      	$q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
						  	      }]);
						  	}])
						   ->get();

		}
		return $response;
	}
	public function getStudentId(Request $request){
		// Get the last created
		$lastStudentNumber = StudentInfo::orderBy('created_at', 'desc')->first();
		if ( ! $lastStudentNumber )
		    // We get here if there is no at all
		    // If there is no number set it to 0, which will be 1 at the end.
		    $number = 0;
		else 
		    $number = substr($lastStudentNumber->student_id, 6);
		// If we have ORD000001 in the database then we only want the number
		// So the substr returns this 000001

		// Add the string in front and higher up the number.
		// the %05d part makes sure that there are always 6 numbers in the string.
		// so it adds the missing zero's when needed.

		return 'S-'. date("Y") . sprintf('%03d', intval($number) + 1);
	}

	public function getCriteriaList(Request $request){
		if($request['criteria_id']){
			$response = Criteria::where('id',$request['criteria_id'])
								->where('academic_year_id',$request['academic_year_id'])
								->with('assessment')
								->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
								->first();
		}else if($request['subject_id']){
			$response = SubjectCriteriaPercentage::where('subject_id', $request['subject_id'])
													->with('criteria')
													->with(['academicYear' => function($q) use ($request){
					                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
					                                }])
													->get();
		}else{
			$response = Criteria::with('assessment')
								->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
								->get();
		}
		return $response;
	}

	public function getClassAdvisoryList(Request $request){
		if($request['user_id']){
			$response = User::with(["advisory.section" => function($query) use ($request){
							 	$query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"))
								->with(['academicYear' => function($q) use ($request){
                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                }])
							 	->with(["students" => function($q) use ($request){
							 		$q->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
							 	}]);
							}])
							->whereIn('user_role',[3,4])
							->where('id', $request['user_id'])
							->get();
		}else{
			$response = User::with(["info" => function($query) use ($request){
							 $query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
						}])
						->with(["advisory" => function($query) use ($request){
							$query->with(["section" => function($query) use ($request){
							 	$query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"))
							 	->with(["students" => function($q) use ($request){
							 		$q->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
							 	}]);
							}]);
						}])
						->whereIn('user_role',[3,4])
						->get();

		}
		return $response;
	}	

	public function getYearByUser(Request $request){
		$response = SubjectTeacher::where('user_id', $request['user_id'])
						->with('year')
						->distinct('user_id')
						->groupBy('year_id')
						->get();
		return $response;
	}


	public function getSubjectSchedule(Request $request){
		$response = DB::table('subject_teacher')
					    ->select('*')
					    ->join('subject_schedule_section', 'subject_schedule_section.subject_teacher_id', '=', 'subject_teacher.id')
					    ->where('subject_teacher.user_id', $request['user_id'])
					    ->where('subject_schedule_section.section_id', $request['section_id'])
					    ->where('subject_teacher.academic_year_id', $request['academic_year_id'])
					    ->where('subject_schedule_section.academic_year_id', $request['academic_year_id'])
					    ->get();
		return $response;
	}

	public function getCriteriaPercentageBySubject(Request $request){
		$response = SubjectCriteriaPercentage::where('subject_id', $request['subject_id'])
											->where('academic_year_id', $request['academic_year_id'])
											->get();
		return $response;
	}
	public function computeSubjectGrade(Request $request){
		$user_id = $request['user_id'];
		$academic_year_id = $request['academic_year_id'];
		$quarter_id = $request['quarter_id'];
		$year_id = $request['year_id'];
		$section_id = $request['section_id'];
		$subject_id = $request['subject_id'];

		$score_sheet_list = DB::table('score_sheet')
								->select('*', DB::raw('SUM(total_score) as criteria_total_score'))
								->where('academic_year_id', $academic_year_id)
								->where('user_id', $user_id)
								->where('quarter_id', $quarter_id)
								->where('section_id', $section_id)
								->where('subject_id', $subject_id)
								->groupBy(['subject_id','category_id','quarter_id','section_id','user_id'])
								->get();

		foreach ($score_sheet_list as $key => $data) {
			$criteria_total_score = $data->criteria_total_score;
			$criteria_id = $data->category_id;
			$exists = CriteriaTotalScore::where('subject_id', $subject_id)
										->where('academic_year_id', $academic_year_id)
										->where('quarter_id', $quarter_id)
										->where('section_id', $section_id)
										->where('category_id', $criteria_id)
										->first();
			if($exists){
				$exists->criteria_total_score = $criteria_total_score;
				$exists->save();
				$this->updateScore(true, $exists->id, $academic_year_id, $subject_id, $quarter_id, $section_id, $criteria_id, $criteria_total_score);
			}else{
				$insert = new CriteriaTotalScore;
				$insert->academic_year_id = $academic_year_id;
				$insert->subject_id = $subject_id;
				$insert->quarter_id = $quarter_id;
				$insert->section_id = $section_id;
				$insert->category_id = $criteria_id;
				$insert->criteria_total_score = $criteria_total_score;
				$insert->school_year = date('Y');
				$insert->save();
				$criteria_total_score_id = $insert['id'];
				$this->updateScore(false, $academic_year_id, $criteria_total_score_id, $subject_id, $quarter_id, $section_id, $criteria_id, $criteria_total_score);
			}
		}
		$student_criteria_score = DB::table('student_criteria_score')
								->select('*', DB::raw('SUM(weighted_score) as total_weighted_score'))
								->where('academic_year_id', $academic_year_id)
								->where('quarter_id', $quarter_id)
								->where('section_id', $section_id)
								->where('subject_id', $subject_id)
								->groupBy(['user_id','section_id', 'subject_id','quarter_id'])
								->get();

		foreach ($student_criteria_score as $key => $data) {
			$user_id = $data->user_id;
			$subject_id = $data->subject_id;
			$quarter_id = $data->quarter_id;
			$section_id = $data->section_id;

			$initial_grade = number_format($data->total_weighted_score, 2, '.', ',');

			$transmutation_table = TransmutationTable::where('initial_grade_from', '<=', $initial_grade)
													->where('initial_grade_to', '>=', $initial_grade)
													->first();

			if($transmutation_table){
				$final_grade = $transmutation_table->transmuted_grade;
			}else{
				$final_grade = 0;
			}

			$exists = QuarterStudentGrade::where('academic_year_id', $academic_year_id)
										->where('subject_id', $subject_id)
										->where('quarter_id', $quarter_id)
										->where('section_id', $section_id)
										->where('user_id', $user_id)
										->first();
			if($exists){
				$exists->initial_grade = $initial_grade;
				$exists->final_grade = $final_grade;
				$exists->save();
			}else{
				$insert = new QuarterStudentGrade;
				$insert->user_id = $user_id;
				$insert->academic_year_id = $academic_year_id;
				$insert->section_id = $section_id;
				$insert->quarter_id = $quarter_id;
				$insert->subject_id = $subject_id;
				$insert->initial_grade = $initial_grade;
				$insert->final_grade = $final_grade;
				$insert->save();
			}
		}

		return $student_criteria_score;
	}
	private function updateScore($exists, $academic_year_id, $criteria_total_score_id, $subject_id, $quarter_id, $section_id, $criteria_id, $criteria_total_score){
		$score_sheet_data = DB::table('score_sheet_data')
								->select('*', DB::raw('SUM(score) as total_score'))
								->where('academic_year_id', $academic_year_id)
								->where('quarter_id', $quarter_id)
								->where('subject_id', $subject_id)
								->where('section_id', $section_id)
								->groupBy(['user_id','quarter_id','section_id','subject_id','criteria_id'])
								->get();
		foreach ($score_sheet_data as $key => $sheet_data) {
			if($criteria_id == $sheet_data->criteria_id){
				$weighted_score_percentage = SubjectCriteriaPercentage::where('subject_id', $subject_id)
																	->where('criteria_id', $sheet_data->criteria_id)
																	->first();
				$average_score = ($sheet_data->total_score / $criteria_total_score) * 100;
				$weighted_score = $average_score * ($weighted_score_percentage->percentage / 100); 
				if($exists){
					$student_score_exists = StudentCriteriaScore::where('criteria_total_score_id', $criteria_total_score_id)
										->where('academic_year_id', $academic_year_id)
										->where('user_id', $sheet_data->user_id)
										->where('subject_id', $subject_id)
										->where('quarter_id', $quarter_id)
										->where('section_id', $section_id)
										->where('criteria_id', $sheet_data->criteria_id)
										->first();
					if($student_score_exists){
						$student_score_exists->total_score = $sheet_data->total_score;
						$student_score_exists->criteria_total_score = $criteria_total_score;
						$student_score_exists->average_score = number_format($average_score, 2, '.', ',');
						$student_score_exists->weighted_score = number_format($weighted_score, 2, '.', ',');
						$student_score_exists->save();
					}else{
						$insert = new StudentCriteriaScore;
						$insert->criteria_total_score_id = $criteria_total_score_id;
						$insert->academic_year_id = $academic_year_id;
						$insert->user_id = $sheet_data->user_id;
						$insert->subject_id = $subject_id;
						$insert->quarter_id = $quarter_id;
						$insert->section_id = $section_id;
						$insert->criteria_id = $sheet_data->criteria_id;
						$insert->total_score = $sheet_data->total_score;
						$insert->average_score = number_format($average_score, 2, '.', ',');
						$insert->weighted_score = number_format($weighted_score, 2, '.', ',');
						$insert->criteria_total_score = $criteria_total_score;
						$insert->save();
					}
				}else{
					$insert = new StudentCriteriaScore;
					$insert->criteria_total_score_id = $criteria_total_score_id;
					$insert->user_id = $sheet_data->user_id;
					$insert->academic_year_id = $academic_year_id;
					$insert->subject_id = $subject_id;
					$insert->quarter_id = $quarter_id;
					$insert->section_id = $section_id;
					$insert->criteria_id = $sheet_data->criteria_id;
					$insert->total_score = $sheet_data->total_score;
					$insert->average_score = number_format($average_score, 2, '.', ',');
					$insert->weighted_score = number_format($weighted_score, 2, '.', ',');
					$insert->criteria_total_score = $criteria_total_score;
					$insert->save();
				}
			}			

		}

	}

	public function computeQuarterlyGrade(Request $request){
		$user_id = $request['user_id'];
		$year_id = $request['year_id'];
		$section_id = $request['section_id'];
		$subject_id = $request['subject_id'];
		$academic_year_id = $request['academic_year_id'];

		$quarterly_grade = array();
		$quarters = Quarter::get();
		foreach ($quarters as $key => $quarter) {
			$quarter_id = $quarter['id'];
			$score_sheet_list = DB::table('score_sheet')
									->select('*', DB::raw('SUM(total_score) as criteria_total_score'))
									->where('academic_year_id', $academic_year_id)
									->where('user_id', $user_id)
									->where('quarter_id', $quarter_id)
									->where('section_id', $section_id)
									->where('subject_id', $subject_id)
									->groupBy(['subject_id','category_id','quarter_id','section_id','user_id'])
									->get();
			foreach ($score_sheet_list as $key => $data) {
				$criteria_total_score = $data->criteria_total_score;
				$criteria_id = $data->category_id;
				$exists = CriteriaTotalScore::where('subject_id', $subject_id)
											->where('academic_year_id', $academic_year_id)
											->where('quarter_id', $quarter_id)
											->where('section_id', $section_id)
											->where('category_id', $criteria_id)
											->first();
				if($exists){
					$exists->criteria_total_score = $criteria_total_score;
					$exists->save();
					$this->updateQuarterlyScore(true, $academic_year_id, $exists->id, $subject_id, $quarter_id, $section_id, $criteria_id, $criteria_total_score);
				}else{
					$insert = new CriteriaTotalScore;
					$insert->academic_year_id = $academic_year_id;
					$insert->subject_id = $subject_id;
					$insert->quarter_id = $quarter_id;
					$insert->section_id = $section_id;
					$insert->category_id = $criteria_id;
					$insert->criteria_total_score = $criteria_total_score;
					$insert->school_year = date('Y');
					$insert->save();
					$criteria_total_score_id = $insert['id'];
					$this->updateQuarterlyScore(false, $academic_year_id, $criteria_total_score_id, $subject_id, $quarter_id, $section_id, $criteria_id, $criteria_total_score);
				}
			}
			$student_criteria_score = DB::table('student_criteria_score')
									->select('*', DB::raw('SUM(weighted_score) as total_weighted_score'))
									->where('academic_year_id', $academic_year_id)
									->where('quarter_id', $quarter_id)
									->where('section_id', $section_id)
									->where('subject_id', $subject_id)
									->groupBy(['user_id','section_id', 'subject_id','quarter_id'])
									->get();

			foreach ($student_criteria_score as $key => $data) {
				array_push($quarterly_grade, $data);
				$student_user_id = $data->user_id;
				$subject_id = $data->subject_id;
				$quarter_id = $data->quarter_id;
				$section_id = $data->section_id;

				$initial_grade = number_format($data->total_weighted_score, 2, '.', ',');

				$transmutation_table = TransmutationTable::where('initial_grade_from', '<=', $initial_grade)
														->where('initial_grade_to', '>=', $initial_grade)
														->first();

				if($transmutation_table){
					$final_grade = $transmutation_table->transmuted_grade;
				}else{
					$final_grade = 0;
				}

				$exists = QuarterStudentGrade::where('subject_id', $subject_id)
											->where('academic_year_id', $academic_year_id)
											->where('quarter_id', $quarter_id)
											->where('section_id', $section_id)
											->where('user_id', $student_user_id)
											->first();
				if($exists){
					$exists->initial_grade = $initial_grade;
					$exists->final_grade = $final_grade;
					$exists->save();
				}else{

					$insert = new QuarterStudentGrade;
					$insert->user_id = $student_user_id;
					$insert->academic_year_id = $academic_year_id;
					$insert->section_id = $section_id;
					$insert->quarter_id = $quarter_id;
					$insert->subject_id = $subject_id;
					$insert->initial_grade = $initial_grade;
					$insert->final_grade = $final_grade;
					$insert->save();
				}
			}
		}
		return $quarterly_grade;
	}
	private function updateQuarterlyScore($exists, $academic_year_id, $criteria_total_score_id, $subject_id, $quarter_id, $section_id, $criteria_id, $criteria_total_score){
		$score_sheet_data = DB::table('score_sheet_data')
								->select('*', DB::raw('SUM(score) as total_score'))
								->where('academic_year_id', $academic_year_id)
								->where('quarter_id', $quarter_id)
								->where('subject_id', $subject_id)
								->where('section_id', $section_id)
								->groupBy(['user_id','quarter_id','section_id','subject_id','criteria_id'])
								->get();
		foreach ($score_sheet_data as $key => $sheet_data) {
			if($criteria_id == $sheet_data->criteria_id){
				$weighted_score_percentage = SubjectCriteriaPercentage::where('subject_id', $subject_id)
																	->where('criteria_id', $sheet_data->criteria_id)
																	->first();
				$average_score = ($sheet_data->total_score / $criteria_total_score) * 100;
				$weighted_score = $average_score * ($weighted_score_percentage->percentage / 100); 
				if($exists){
					$student_score_exists = StudentCriteriaScore::where('criteria_total_score_id', $criteria_total_score_id)
										->where('academic_year_id', $academic_year_id)
										->where('user_id', $sheet_data->user_id)
										->where('subject_id', $subject_id)
										->where('quarter_id', $quarter_id)
										->where('section_id', $section_id)
										->where('criteria_id', $sheet_data->criteria_id)
										->first();
					if($student_score_exists){
						$student_score_exists->total_score = $sheet_data->total_score;
						$student_score_exists->criteria_total_score = $criteria_total_score;
						$student_score_exists->average_score = number_format($average_score, 2, '.', ',');
						$student_score_exists->weighted_score = number_format($weighted_score, 2, '.', ',');
						$student_score_exists->save();
					}else{
						$insert = new StudentCriteriaScore;
						$insert->criteria_total_score_id = $criteria_total_score_id;
						$insert->user_id = $sheet_data->user_id;
						$insert->academic_year_id = $academic_year_id;
						$insert->subject_id = $subject_id;
						$insert->quarter_id = $quarter_id;
						$insert->section_id = $section_id;
						$insert->criteria_id = $sheet_data->criteria_id;
						$insert->total_score = $sheet_data->total_score;
						$insert->average_score = number_format($average_score, 2, '.', ',');
						$insert->weighted_score = number_format($weighted_score, 2, '.', ',');
						$insert->criteria_total_score = $criteria_total_score;
						$insert->save();
					}
				}else{
					$insert = new StudentCriteriaScore;
					$insert->criteria_total_score_id = $criteria_total_score_id;
					$insert->user_id = $sheet_data->user_id;
					$insert->academic_year_id = $academic_year_id;
					$insert->subject_id = $subject_id;
					$insert->quarter_id = $quarter_id;
					$insert->section_id = $section_id;
					$insert->criteria_id = $sheet_data->criteria_id;
					$insert->total_score = $sheet_data->total_score;
					$insert->average_score = number_format($average_score, 2, '.', ',');
					$insert->weighted_score = number_format($weighted_score, 2, '.', ',');
					$insert->criteria_total_score = $criteria_total_score;
					$insert->save();
				}
			}			

		}

	}
    public function getAssessments(Request $request){
        return AssessmentLevel::get();
    }
    public function getStudentGrade(Request $request){
		$quarter_id = $request['quarter_id'];
		$section_id = $request['section_id'];
		$subject_id = $request['subject_id'];
		$academic_year_id = $request['academic_year_id'];

		if($quarter_id && $section_id && $subject_id){
			$grades = QuarterStudentGrade::where('subject_id', $subject_id)
										->where('quarter_id', $quarter_id)
										->where('section_id', $section_id)
										->where('academic_year_id', $academic_year_id)
										->with('subject')
										->with(["studentInfo" => function($q) use ($request){
									 		$q->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
									 	}])
									 	->with(['academicYear' => function($q) use ($request){
		                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
		                                }])
										->get();
		}else if($request['user_id']){
			$grades = StudentInfo::with(['grades' => function($q) use ($request){
										$q->where('subject_id', $request['subject_id'])
										->with('quarter');
									}])
								 	->with(['academicYear' => function($q) use ($request){
	                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
	                                }])
									->where('user_id', $request['user_id'])
									->where('academic_year_id', $academic_year_id)
									->get();

		}else{
			$grades = StudentInfo::with(['grades' => function($q) use ($request){
										$q->where('subject_id', $request['subject_id'])
										->with('quarter');
									}])
								 	->with(['academicYear' => function($q) use ($request){
	                                    $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
	                                }])
									->where('section_id', $section_id)
									->where('academic_year_id', $academic_year_id)
									->get();

		}
		return $grades;
    }
    public function getStudentsGrade(Request $request){
		$section_id = $request['section_id'];
		$academic_year_id = $request['academic_year_id'];
		$students = StudentInfo::where('section_id', $section_id)->get();
		$students_array = array();
		foreach ($students as $key => $student) {
			$subjects = SubjectSection::where('section_id', $section_id)
										->with('subject')
										->get();
			$grades = array();
			foreach ($subjects as $key => $data) {
				$data['schedule'] = SubjectScheduleSection::where([['section_id',$data->section_id],['subject_id', $data->subject_id]])->first();

				$subject_grades_array = array();

				$subject_grades = QuarterStudentGrade::where([['user_id',$student['user_id']],['section_id',$data->section_id],['subject_id', $data->subject_id]])->get();

				foreach ($subject_grades as $key => $subject_grade) {
					$subject_grade['quarter'] = Quarter::where('id', $subject_grade['quarter_id'])->first();

					$subject_criteria_grades = StudentcriteriaScore::where([['user_id',$student['user_id']],['section_id',$data->section_id],['subject_id', $data->subject_id],['quarter_id', $subject_grade['quarter_id']]])->get();

					$criteria_array = array();

					foreach ($subject_criteria_grades as $key => $criteria) {
						$criteria['criteria'] = Criteria::where('id', $criteria['criteria_id'])->first();
						array_push($criteria_array, $criteria);
					}

					$subject_grade['subject_criteria_grades'] = $criteria_array;

					array_push($subject_grades_array, $subject_grade);
				}

				$data['subject_grades'] = $subject_grades_array;

				array_push($grades, $data);
			}
			$student['grades'] = $grades;
			array_push($students_array, $student);
		}


		return $students_array;
	}

    public function getStudentGradePerQuarter(Request $request){
    	$response = StudentCriteriaScore::where('user_id', $request['user_id'])
    									->where('quarter_id', $request['quarter_id'])
    									->where('section_id', $request['section_id'])
    									->where('subject_id', $request['subject_id'])
    									->with('criteria')
    									->get();

    	return $response;
    }
    public function getAcademicYear(Request $request){
    	$response = AcademicYear::where('isActive', 1)->first();

    	return $response;
    }
    public function getAcademicYears(Request $request){
    	$response = AcademicYear::get();
    	return $response;
    }
    public function getAdvisorySection(Request $request){
    	$response = DB::table('section')
            ->leftJoin('advisory_class', 'section.academic_year_id', '=', 'advisory_class.academic_year_id')
            ->select('section.*')
            ->where('section.year_id',$request['year_id'])
            ->where('advisory_class.academic_year_id',$request['academic_year_id'])
            ->where('advisory_class.teacher_id',$request['user_id'])
            ->distinct('section.id')
            ->get();
    	return $response;
    }

    public function getSubjectGrade(Request $request){
        $user_id = $request['user_id'];
        $academic_year_id = $request['academic_year_id'];
        $section = StudentInfo::where('user_id',$user_id)->first();
        $section_id = $section['section_id'];
        $subjects = SubjectSection::where([['section_id',$section_id],['academic_year_id', $academic_year_id]])
	        						->with('subject')
	        						->get();

        $grades = array();
        foreach ($subjects as $key => $subject) {
        	$quarters = QuarterStudentGrade::where([['user_id',$user_id],['section_id',$section_id],['academic_year_id', $academic_year_id],['subject_id',$subject['subject_id']]])->get();

        	$subject_final_grade = 0;
        	foreach ($quarters as $key => $quarter) {
        		$subject_final_grade += $quarter['final_grade'];
        	}
        	$size = sizeof($quarters);
        	if($size > 0){
        		$subject_final_grade = $subject_final_grade / $size;
        	}
        	$subject['quarterly_grade'] = $quarters;
        	$subject['subject_final_grade'] = $subject_final_grade;
        	array_push($grades, $subject);
        }
        return $grades;
    }


    public function getSystemLogo(){
    	return SystemImages::where('type','system_logo')->first();
    }
    public function getCardLogos(){
    	$card_logo = SystemImages::where('type','card_logo')->first();
    	$card_logo2 = SystemImages::where('type','card_second_logo')->first();
    	return ['card_logo' => $card_logo['image'],'card_logo2' => $card_logo2['image']];
    }



    public function updateAccount(Request $request){
    	$info = $request['info'];
    	if($request['user_role'] == 5){
    		$update = StudentInfo::where('user_id', $info['user_id'])->first();
    		$update->date_of_birth = $info['date_of_birth'];
    		$update->address = $info['address'];
    		$update->phone_number = $info['phone_number'];
    		$update->save();
    		return $update;
    	}else{
    		$update = UserInfo::where('user_id', $info['user_id'])->first();
    		$update->date_of_birth = $info['date_of_birth'];
    		$update->address = $info['address'];
    		$update->phone_number = $info['phone_number'];
    		$update->save();
    		return $update;
    	}
    }

    
}
