<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;

class Languagecontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // language list
    public function index()
    {
        $user = DB::table('users')->where('id', '=', Auth::user()->id)->first();

        return view('language.list', compact('user'));
    }

    // direction list
    public function index1()
    {
        return view('direction.list');
    }

    // direction store
    public function store1(Request $request)
    {
        $direction = $request->direction;
        $dire_table = DB::table('tbl_language_directions')->orderBy('id', 'desc')->first();
        $id = $dire_table->id;
        DB::table('tbl_language_directions')->where('id', $id)->update(['direction' => $direction]);

        return redirect('/setting/language/direction/list')->with('message', 'Successfully Updated');
    }
}
