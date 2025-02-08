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
        if ($user->isPrincipal()) {
            $students = User::where('category_id', $user->category_id)
                ->where('location_id', $user->location_id)->orderBy('level_id', 'DESC')->get();
        } else if ($user->isHos()) {
            $students = User::where('location_id', $user->location_id)->orderBy('level_id', 'DESC')->get();
        } else {
            $students = User::all();
        }
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
        if ($user->isHos()) {
            $targets = StageDivision::where('location_id', $user->location_id)->get();
        } else if ($user->isPrincipal()) {
            $targets = StageDivision::where('category_id', $user->category_id)
                ->where('location_id', $user->location_id)->get();
        } else {
            $targets = StageDivision::all();
        }
        return view(getTemplate() . '.panel.stages.webinar', ['targets' => json_decode($targets, true)]);
    }

    public function get_target_webinar_by_id($id)
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $target = StageDivision::find($id);
        return response()->json($target);
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
        return view(getTemplate() . '.panel.stages.webinar-form', [
            'user' => $user,
            'id' => $id,
        ]);
    }

    public function webinar_datatables()
    {
        $user = auth()->user();
        if ($user->isPrincipal()) {
            $categories_id = Category::whereNotNull('parent_id')->pluck('id')->toArray();
            $webinars1 = Webinar::where('category_id', $user->category_id)->where('status', 'active')->with(['category'])->get();
            $webinars2 = Webinar::whereIn('category_id', $categories_id)->where('status', 'active')->with(['category'])->get();
            $webinars = $webinars1->merge($webinars2);
        } else {
            $webinars = Webinar::all();
        }
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
            $exists  = $exists = StageDivision::where([
                ['level_id', '=', $request['level_id']],
                ['category_id', '=', $request['category_id']],
                ['location_id', '=', $request['location_id']],
            ])->exists();

            if (!$exists) {
                $user = auth()->user();
                $sd = new StageDivision();
                $sd->level_id = $request['level_id'];
                $sd->category_id = $request['category_id'];
                $sd->location_id = $request['location_id'];
                $sd->created_by_id = $user->id;
                $sd->level = Level::find($request['level_id'])->level;
                $sd->stage_name = Level::find($request['level_id'])->stage;
                $sd->category_name = Category::find($request['category_id'])->slug;
                $sd->location_name = Location::find($request['location_id'])->name;
                $sd->save();

                for ($i = 0; $i < count($request['details']); $i++) {
                    $detail = new StageDivisionDetail();
                    $detail->stage_divisions_id = $sd->id;
                    $detail->webinar_id = $request['details'][$i]["webinar"]['id'];
                    $detail->title = $request['details'][$i]["webinar"]['title'];
                    $detail->save();
                }
                $newSd = StageDivision::find($sd->id);
                return response()->json($newSd);
            } else {
                return response()->json(['message' => "Data already exist"], 400);
            }
            // return response()->json($exists);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }
    public function update_target(Request $request)
    {
        try {
            StageDivisionDetail::where('stage_divisions_id', $request['id'])->delete();
            for ($i = 0; $i < count($request['details']); $i++) {
                $detail = new StageDivisionDetail();
                $detail->stage_divisions_id = $request['id'];
                $detail->webinar_id = $request['details'][$i]["webinar"]['id'];
                $detail->title = $request['details'][$i]["webinar"]['title'];
                $detail->save();
            }
            $sd = StageDivision::find($request['id']);
            return response()->json($sd);
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
