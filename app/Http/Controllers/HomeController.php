<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryVote;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('index');
    }

    public function histori()
    {
        $histori = HistoryVote::all();

        return view('histori',[
            'histori' => $histori
        ]);
    }
}
