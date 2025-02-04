<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Location;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Webinar;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class UserReportController extends Controller
{
    private function handleAuthorize($user)
    {
        if ($user->isUser()) {
            abort(404);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $students = User::where('id', '!=', $user->id)
            ->where('category_id', $user->category_id)
            ->where('location_id', $user->location_id)->with(['category', 'location'])->get();
        return view(getTemplate() . '.panel.report.user-report', ['students' => $students]);
    }
    public function division()
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $locations = Location::all();
        $categories = Category::whereNull('parent_id')->where('slug', '!=', 'sub-general')->get();

        return view(
            getTemplate() . '.panel.report.division-report',
            [
                'branches' => $locations,
                'divisions' => $categories
            ]
        );
    }
    public function getByDivision(Request $request)
    {
        $students = $this->generateUserCourse($request);
        return response()->json($students);
    }
    public function getPdfByDivision(Request $request)
    {
        $students = $this->generateUserCourse($request);
        $mpdf = new Mpdf();
        $lastIndex = count($students) - 1;
        for ($i = 0; $i < count($students); $i++) {
            $student = $students[$i];
            $mpdf->WriteHTML(view('user-report', ['student' => $student]));
            if ($lastIndex != $i) {
                $mpdf->AddPage();
            }
        }
        $mpdf->OutputHttpInline();
    }

    public function byPeriod($id, Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $first = Carbon::createFromFormat('F Y', $from)->format('Y-m-01');
        $last = Carbon::createFromFormat('F Y', $to)->format('Y-m-t');
        $firstDate = strtotime($first);
        $endDate = strtotime($last);
        $sales = Sale::where('buyer_id', $id)->whereBetween('created_at', [$firstDate, $endDate])
            ->whereNull('refund_at')->with(['webinar' => function ($query) {
                $query->with('category');
            }])->get();

        for ($i = 0; $i < count($sales); $i++) {
            $sale = $sales[$i];
            $sale->webinar->course_progress = getCourseProgressForStudent($sale->webinar, $id);
        }

        return response()->json($sales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::find($id);
        return view(getTemplate() . '.panel.report.report-form', ['student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function generateUserCourse(Request $request)
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $division = $request->input('division');
        $branch = $request->input('branch');
        $from = $request->input('from');
        $to = $request->input('to');
        $first = Carbon::createFromFormat('F Y', $from)->format('Y-m-01');
        $last = Carbon::createFromFormat('F Y', $to)->format('Y-m-t');
        $firstDate = strtotime($first);
        $lastDate = strtotime($last);

        $students = User::orderBy('location_id', 'ASC')->orderBy('category_id', 'ASC')->orderBy('full_name', 'ASC');
        if ($branch != "all") {
            $students->where('location_id', $branch);
        }

        if ($division != "all") {
            $students->where('category_id', $division);
        }
        $students = $students->get();
        foreach ($students as $student) {
            $sales = Sale::where('buyer_id', $student->id)->whereBetween('created_at', [$firstDate, $lastDate])
                ->whereNull('refund_at')->with(['webinar' => function ($query) {
                    $query->with('category');
                }])->get();
            for ($i = 0; $i < count($sales); $i++) {
                $sale = $sales[$i];
                $sale->webinar->course_progress = getCourseProgressForStudent($sale->webinar, $sale->buyer_id);
            }
            $student->sales = $sales;
        }
        return $students;
    }
}
