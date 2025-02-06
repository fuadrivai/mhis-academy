<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Level;
use App\Location;
use App\Models\Category;
use App\Models\Webinar;
use App\StageDivision;
use App\StageDivisionDetail;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Utilities\Request as UtilitiesRequest;

class LevelController extends Controller
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
        //
    }

    public function teacher()
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $students = User::where('id', '!=', $user->id)
            ->where('category_id', $user->category_id)
            ->where('location_id', $user->location_id)->get();
        $levels = Level::all();
        return view(
            getTemplate() . '.panel.stages.teacher',
            [
                'students' => json_decode($students, true),
                'levels' => $levels
            ]
        );
    }

    public function webinar()
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $targets = StageDivision::where('category_id', $user->category_id)
            ->where('location_id', $user->location_id)->get();
        return view(getTemplate() . '.panel.stages.webinar', ['targets' => json_decode($targets, true)]);
    }

    public function webinar_create()
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $locations = Location::all();
        $levels = Level::all();
        $categories = Category::whereNull('parent_id')->where('slug', '!=', 'sub-general')->get();
        return view(getTemplate() . '.panel.stages.webinar-form', [
            'branches' => $locations,
            'divisions' => $categories,
            'levels' => $levels,
            'user' => $user
        ]);
    }

    public function webinar_edit($id)
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $locations = Location::all();
        $levels = Level::all();
        $categories = Category::whereNull('parent_id')->where('slug', '!=', 'sub-general')->get();
        return view(getTemplate() . '.panel.stages.webinar-form', [
            'branches' => $locations,
            'divisions' => $categories,
            'levels' => $levels,
            'user' => $user
        ]);
    }
    public function webinar_datatables(UtilitiesRequest $request)
    {
        $user = auth()->user();
        $categories_id = Category::whereNotNull('parent_id')->pluck('id')->toArray();
        $webinars1 = Webinar::where('category_id', $user->category_id)->where('status', 'active')->with(['category'])->get();
        $webinars2 = Webinar::whereIn('category_id', $categories_id)->where('status', 'active')->with(['category'])->get();
        $webinars = $webinars1->merge($webinars2);
        return response()->json($webinars);
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
    public function store_target(Request $request)
    {
        try {
            $user = auth()->user();
            $sd = new StageDivision();
            $sd->level_id = $request['level_id'];
            $sd->category_id = $request['category_id'];
            $sd->location_id = $request['location_id'];
            $sd->created_by_id = $user->id;
            $sd->stage_name = Level::find($request['level_id'])->stage;
            $sd->category_name = Category::find($request['category_id'])->slug;
            $sd->location_name = Location::find($request['location_id'])->name;
            $sd->save();

            for ($i = 0; $i < count($request->details); $i++) {
                $detail = new StageDivisionDetail();
                $detail->stage_divisions_id = $sd->id;
                $detail->webinar_id = $request->details["webinar"]['id'];
                $detail->save();
            }
            $newSd = StageDivision::find($sd->id);
            return response()->json($newSd);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }

    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function edit(Level $level)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            User::where('id', $id)->update([
                'level_id' => $request->input('level')
            ]);
            return back()->with('success', 'Data saved successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage(), $th->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        //
    }
}
