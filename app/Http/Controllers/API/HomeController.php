<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\ResponseFormatter;

use App\Models\{User,Vote,HistoryVote,Category};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function login(Request $request)
    {
        // VALIDASI INPUT
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'required' => ':attribute harus diisi.',
            'email' => 'Format email harus benar'
        ]);

        try {
            // MENGECEK Credentials (LOGIN)
            $credentials = request(['email','password']);

            if (!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                    'error' => 'username atau password salah'
                ], 'Authentication Failed', 500);
            }

            // Jika Hash tidak sesuai
            $user = User::where('email',$request->email)->first();
            if(!Hash::check($request->password , $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            // JIKA BERHASIL MAKA LOGINKAN
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 'Authentication Failed',500);
        }
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'password' => ['required','min:8']
        ],[
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'email sudah terdaftar',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }


    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function voting($id = 0) {
        try {
            if ($id == 0) {
                $vote = Vote::all();
            } else {
                $vote = Vote::find($id);
            }

            return ResponseFormatter::success([
                'data' => $vote
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function vote(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            $vote = Vote::find($id);
            $vote->vote += 1;
            $vote->save();

            $history = HistoryVote::create([
                'user_id' => $user->id,
                'vote_id' => $id,
            ]);

            DB::commit();

            return ResponseFormatter::success([
                'message' => 'Berhasil Voting',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function isVoting(Request $request) {
        try {
            $user = $request->user();
            $vote = HistoryVote::where('user_id',$user->id)->count();

            return ResponseFormatter::success([
                'data' => $vote
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function category(Request $request) {
        try {
            $user = $request->user();
            $historyIds = HistoryVote::where('user_id',$user->id)->pluck('vote_id');
            


            $category = Category::selectRaw("*")->with(['vote.history' => function ($q) use ($user) {
                $q->where('history_votes.user_id',$user->id);
            }])->get();

            foreach($category as $key => $item) {
                foreach ($category[$key]['vote'] as $i => $val) {
                    $vote = Vote::selectRaw('category_id,count(category_id) as count')->whereIn('id',$historyIds)->groupBy('category_id');

                    $cnt = $vote->where('category_id',$val->category_id)->first()->count ?? 0;
                    $category[$key]['vote'][$i]['count'] = $cnt;
                }
            }

            return ResponseFormatter::success([
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function voteCheck(Request $request) {
        try {
            $user = $request->user();
            $category = HistoryVote::with('vote')->where('user_id',$user->id)->pluck('vote_id')->unique();
            $vote = Vote::whereIn('id',$category)->get();

            return ResponseFormatter::success([
                'data' => $vote
            ]);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function batal(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            $vote = Vote::find($id);
            $vote->vote -= 1;
            $vote->save();

            HistoryVote::where('vote_id',$id)->where('user_id',$user->id)->delete();

            DB::commit();

            return ResponseFormatter::success([
                'message' => 'Berhasil Voting',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }
    
}
