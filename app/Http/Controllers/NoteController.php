<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Auth;
class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $note = new Note;
        $note->title =  $request->title;
        $note->note =  $request->note;
        $note->user_id =  Auth::user()->id;
        if($note->save())
        {
            return ['success'=>1];
        }else{
            return ['success'=>0];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $noteData = Note::where('user_id',Auth::user()->id)->get();
         return ['success'=>1,'notes'=> $noteData];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $note = Note::find($request->id);
        $field=$request->field;
        $note->$field = $request->value;
        if($note->save())
        {
            return ['success'=>1];
        }else{
            return ['success'=>0];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $note = Note::find($request->id);
          if($note->delete())
        {
            return ['success'=>1];
        }else{
            return ['success'=>0];
        }
    }
}
