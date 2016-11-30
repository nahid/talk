<?php

namespace Nahid\Talk\Conversations;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
    public $timestamps = true;
    public $fillable = [
        'user_one',
        'user_two',
        'status'
    ];

    protected $authUser;

    public function messages()
    {
        return $this->hasMany('Nahid\Talk\Messages\Message', 'conversation_id')
            ->with('sender');
    }


    public function userone()
    {

        return $this->belongsTo(config('talk.user.model', 'App\User'),  'user_one');
    }

    public function usertwo()
    {

        return $this->belongsTo(config('talk.user.model', 'App\User'),  'user_two');
    }


    public  function threads()
    {
        return $this->messages()->latest('updated_at')

            ->where('user_id', '!=', $this->authUserId)
            ->where('deleted_from_sender', 0)->get();
    }
}
