<?php

namespace App\Http\Controllers;

use App\Mail\MyCustomMail;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Sale;
use App\Models\Webinar;
use App\Models\WebinarAssignmentHistory;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mpdf\Mpdf;
use Carbon\Carbon;

class EmailController extends Controller
{
    public function index()
    {

        $emails = [
            "fuad.strummer@gmail.com",
            // "fuad.only@gmail.com",
            "bangpeisongtu01@gmail.com",
        ];

        for ($i = 0; $i < count($emails); $i++) {
            $email = ['email' => $emails[$i]];
            Mail::to($email['email'])->send(new MyCustomMail($email));
        }
    }

    public function pdf($email)
    {
        $monthYear = null;
        $firstDate = null;
        $lastDate = null;
        if(request('month') && request('year')){
            $monthYear = request('month')." ". request('year');
            $first = Carbon::createFromFormat('F Y', $monthYear)->format('Y-m-01');
            $last = Carbon::createFromFormat('F Y', $monthYear)->format('Y-m-t');
            $firstDate = strtotime($first);
            $lastDate = strtotime($last);
        }

        $user = User::where('email', $email)->first();
        $categoriesId = Category::whereNotNull('parent_id')->pluck('id')->toArray();
        $webinars = null;
        if(isset($user['category_id'])){
            $webinars = Webinar::where('category_id', $user['category_id'])
                ->with('category')
                ->with('sales')
                ->orderBy('category_id','ASC')
                ->get();
        }else{
            $webinars = Webinar::with('category')
            ->with('sales')
            ->orderBy('category_id','ASC')
            ->get();
        }
        $generalWebinar = Webinar::whereIn('category_id', $categoriesId)
            ->with('category')
            ->with('sales')
            ->orderBy('category_id','ASC')
            ->get();

        $webinars = $webinars->merge($generalWebinar);

        foreach ($webinars as $webinar) {
            $studentsIds = [];
            if(request('month') && request('year')){
                $studentsIds = Sale::where('webinar_id', $webinar->id)->whereBetween('created_at',[$firstDate,$lastDate])
                ->whereNull('refund_at')
                ->pluck('buyer_id')
                ->toArray();
            }else{
                $studentsIds = Sale::where('webinar_id', $webinar->id)
                ->whereNull('refund_at')
                ->pluck('buyer_id')
                ->toArray();
            }
            
            $quizzesIds = $webinar->quizzes->pluck('id')->toArray();
            $assignmentsIds = $webinar->assignments->pluck('id')->toArray();
            $students = User::whereIn('id', $studentsIds)->where('location_id', $user->location_id)->get();
            foreach ($students as $std) {
                $std->course_progress = getCourseProgressForStudent($webinar, $std->id);
                $std->passed_quizzes = Quiz::whereIn('quizzes.id', $quizzesIds)
                    ->join('quizzes_results', 'quizzes_results.quiz_id', 'quizzes.id')
                    ->select(DB::raw('count(quizzes_results.id) as count'))
                    ->where('quizzes_results.user_id', $std->id)
                    ->where('quizzes_results.status', QuizzesResult::$passed)
                    ->first()->count;
                $assignmentsHistoriesCount = WebinarAssignmentHistory::whereIn('assignment_id', $assignmentsIds)
                    ->where('student_id', $std->id)
                    ->count();
                $std->unsent_assignments = count($assignmentsIds) - $assignmentsHistoriesCount;
                $std->pending_assignments = WebinarAssignmentHistory::whereIn('assignment_id', $assignmentsIds)
                    ->where('student_id', $std->id)
                    ->where('status', WebinarAssignmentHistory::$pending)
                    ->count();
            }
            $webinar['students'] = $students;
        }
        // return response()->json($webinars[1]);

        $mpdf = new Mpdf();
        $lastIndex = count($webinars) - 1;
        for ($i = 0; $i < count($webinars); $i++) {
            $webinar = $webinars[$i];
            for($j=0; $j<count($webinar['students']); $j++){
                $user = $webinar['students'][$j];
                for($k = 0;$k<count($webinar['sales']);$k++){
                    $sales = $webinar['sales'][$k];
                    if($sales->buyer_id == $user->id){
                        $user['sales']= $sales;
                        break;
                    }
                }
            }
            $mpdf->WriteHTML(view('pdf', ['email' => $email, 'webinar' => $webinar]));
            if ($lastIndex != $i) {
                $mpdf->AddPage();
            }
        }
        $mpdf->OutputHttpInline();
        
        // return response()->json($webinars[1]);
    }
    
    public function getUserByEmail($email)
    {
       $user = User::where('email',$email)->first();
       return response()->json($user);
    }
    public function checkUser($email)
    {
       $user = User::where('email',$email)->first();
       $isExist = isset($user);
       return response()->json($isExist);
    }
}
