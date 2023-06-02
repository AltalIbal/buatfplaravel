<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Vote,Category};

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class VoteController extends Controller
{

    public function index()
    {
        $vote = Vote::latest()->get();

        return view('vote.index',[
            'vote' => $vote
        ]);
    }

    public function create()
    {
        $action = 'add';
        $category = Category::all();

        return view('vote.save', [
            'action' => $action,
            'category' => $category
        ]);
    }

    public function store(Request $request)
    {
        $image = $request->file('image');
        $filename='';
        if ($image != '') {
            $filename = time().$image->getClientOriginalName();
        }

        $insert = $request->all();

        $insert['image'] = $filename;
        $insert['vote'] = 0;

        Vote::create($insert);

        // PROSES UPLOAD
        if ($image != '') {
            $path = 'uploads/vote/';
            $image->move($path,$filename);
        }

        return redirect()->route('votes.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Vote $vote)
    {
        $action = 'edit';
        $category = Category::all();

        return view('vote.save',[
            'action' => $action,
            'row' => $vote,
            'category' => $category
        ]);
    }

    public function update(Request $request, Vote $vote)
    {
        $image = $request->file('image');
        $filename = $vote->image;
        $imageOld = $vote->image;

        if ($image != '') {
            $filename = time().$image->getClientOriginalName();
        } 

        $update = $request->all();

        $update['image'] = $filename;
        
        $vote->update($update);

        // PROSES UPLOAD
        if ($image != '') {
            $path = 'uploads/vote/';
            $image->move($path,$filename);

            File::delete($path.$imageOld);
        }

        return redirect()->route('votes.index');
    }

    public function destroy(Vote $vote)
    {
        File::delete('uploads/vote/'.$vote->image);

        $vote->delete();

        return redirect()->route('votes.index');
    }
}
