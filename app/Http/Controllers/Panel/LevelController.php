<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Level;
use App\Models\Category;
use App\Models\Webinar;
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
        $students = User::where('id', '!=', $user->id)
            ->where('category_id', $user->category_id)
            ->where('location_id', $user->location_id)->get();
        return view(getTemplate() . '.panel.stages.teacher', ['students' => json_decode($students, true)]);
    }

    public function webinar()
    {
        $user = auth()->user();
        $this->handleAuthorize($user);
        $webinars = Webinar::where('id', '!=', $user->id)
            ->where('category_id', $user->category_id)->with(['category'])->get();
        return view(getTemplate() . '.panel.stages.webinar', ['webinars' => $webinars]);
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
    public function update(Request $request, Level $level)
    {
        //
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
