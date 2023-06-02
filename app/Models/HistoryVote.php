<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVote extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->hasOne(User::class ,'id' ,'user_id');
    }

    public function vote() {
        return $this->hasOne(Vote::class ,'id' ,'vote_id');
    }
}
