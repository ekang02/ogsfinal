<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Subject;
use App\ScoreSheet;
use App\ScoreSheetData;
use App\SubjectTeacher;
use App\AcademicYear;
use App\UserInfo;
use App\SubjectSection;

class TeacherController extends Controller
{
   public function __construct(){
        $this->middleware('auth');
    }
    
    public function saveScoreSheet(Request $request){
        $academic_year_id = $request['academic_year_id'];
        $subject_id = $request['subject_id'];
        $quarter_id = $request['quarter_id'];
        $category_id = $request['category_id'];
        $section_id = $request['section_id'];
        $insert = new ScoreSheet;
        $insert->user_id = $request['id'];
        $insert->score_sheet_name = $request['score_sheet_name'];
        $insert->academic_year_id = $academic_year_id;
        $insert->quarter_id = $quarter_id;
        $insert->category_id = $category_id;
        $insert->subject_id = $subject_id;
        $insert->year_id = $request['year_id'];
        $insert->section_id = $section_id;
        $insert->total_score = $request['score'];
        $insert->status = 'Active';
        $insert->save();

        $studentScores = $request['student_score'];
        $scoreSheerId = $insert->id;
        foreach ($studentScores as $key => $studentScore) {
            $insertScoreData = new ScoreSheetData;
            $insertScoreData->score_sheet_id = $scoreSheerId;
            $insertScoreData->academic_year_id = $academic_year_id;
            $insertScoreData->user_id = $studentScore['user_id'];
            $insertScoreData->quarter_id = $quarter_id;
            $insertScoreData->section_id = $section_id;
            $insertScoreData->subject_id = $subject_id;
            $insertScoreData->criteria_id = $category_id;
            $insertScoreData->score = $studentScore['score'];
            $insertScoreData->save();
        }
        return $insert;
    }
    public function updateScoreSheet(Request $request){
        $score_sheet_id = $request['score_sheet_id'];
        $update = ScoreSheet::where('id',$score_sheet_id)->first();
        $update->total_score = $request['total_score'];
        $update->save();

        $studentScores = $request['student_score'];
        foreach ($studentScores as $key => $studentScore) {
            $updateScoreData = ScoreSheetData::where('id',$studentScore['id'])->first();
            $updateScoreData->score = $studentScore['score'];
            $updateScoreData->save();
        }
        return $update;
    }
    public function getScoreSheetListPrint(Request $request){
        $quarter_id  = $request['quarter_id'];
        $year_id  = $request['year_id'];
        $section_id  = $request['section_id'];
        $subject_id  = $request['subject_id'];
        $category_id  = $request['category_id'];

        $response = ScoreSheet::where('user_id',$request['id'])
                        ->where('academic_year_id', $request['academic_year_id'])
                        ->where('quarter_id', $quarter_id)
                        ->where('year_id', $year_id)
                        ->where('section_id', $section_id)
                        ->where('subject_id', $subject_id)
                        ->where('category_id', $category_id)
                        ->with('quarter')
                        ->with('criteria')
                        ->with('subject')
                        ->with('year')
                        ->with(["section" => function($query) use ($section_id){
                             $query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"));
                        }])
                        ->get();
        // $response = DB::table('score_sheet')
        //                 ->join('score_sheet_data', 'score_sheet.id', '=', 'score_sheet_data.score_sheet_id')
        //                 ->select('score_sheet.*', 'score_sheet_data.*')
        //                 ->where('score_sheet.user_id',$request['id'])
        //                 ->where('score_sheet.academic_year_id', $request['academic_year_id'])
        //                 ->where('score_sheet.quarter_id', $quarter_id)
        //                 ->where('score_sheet.year_id', $year_id)
        //                 ->where('score_sheet.section_id', $section_id)
        //                 ->where('score_sheet.subject_id', $subject_id)
        //                 ->where('score_sheet.category_id', $category_id)
        //                 // ->with('quarter')
        //                 // ->with('criteria')
        //                 // ->with('subject')
        //                 // ->with('year')
        //                 // ->with(["section" => function($query) use ($section_id){
        //                 //      $query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"));
        //                 // }])
        //                 ->get();
        return $response;
    }
	public function getScoreSheetList(Request $request){
        if($request['id']){
            $response = ScoreSheet::where('user_id',$request['id'])
                            ->with('quarter')
                            ->with('criteria')
                            ->with('subject')
                            ->with('year')
                            ->with(["section" => function($query) use ($request){
                                 $query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"));
                            }])
                            ->where('academic_year_id', $request['academic_year_id'])
                            ->get();
        }else{
            $response = ScoreSheet::with('quarter')
                            ->with('criteria')
                            ->with('subject')
                            ->with('year')
                            ->with(["section" => function($query) use ($request){
                                 $query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"));
                            }])
                            ->where('academic_year_id', $request['academic_year_id'])
                            ->get();

        }
		return $response;
	}
    public function getScoreSheetData(Request $request){
        if($request['user_id'] && $request['quarter_id'] && $request['subject_id'] && $request['section_id'] && $request['criteria_id'] && $request['academic_year_id']){
            $response = ScoreSheetData::where([['user_id', $request['user_id']],['section_id', $request['section_id']],['subject_id', $request['subject_id']],['quarter_id', $request['quarter_id']],['criteria_id', $request['criteria_id']]])
                                  ->with('sheetData')
                                  ->get();
        }else{
            $response = ScoreSheetData::where('score_sheet_id',$request['score_sheet_id'])
                            ->where('academic_year_id', $request['academic_year_id'])
                            ->with('sheetData')
                            ->with(["studentInfo" => function($query) use ($request){
                              $query->select(['*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name")]);
                            }])->get();
        }
        return $response;
    }
    public function getSubjectbyTeacher(Request $request){
        $response = SubjectTeacher::where('user_id',$request['user_id'])
                                    ->where('academic_year_id',$request['academic_year_id'])
                                    ->with(["userInfo" => function($query) use ($request){
                                         $query->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
                                    }])
                                    ->with('year')
                                    ->with('subject')
                                    ->with(['academicYear' => function($q) use ($request){
                                        $q->select('*',DB::raw("CONCAT(from_year,' - ',to_year) AS academic"));
                                    }])
                                    ->with(["scheduleSection" => function($query) use ($request){
                                        $query->with(["section" => function($query) use ($request){
                                            $query->select('*',DB::raw("CONCAT(section,' (',label,')')  AS full"))
                                            ->with(["students" => function($q) use ($request){
                                                $q->select('*',DB::raw("CONCAT(first_name,' ',last_name)  AS full_name"));
                                            }]);
                                        }]);
                                    }])
                                    ->get();
        return $response;
    }
    
}
