<?php

namespace Nahid\Talk\Conversations;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
    public $timestamps = true;
    public $fillable = ['user_one', 'user_two', 'status'];

    public function messages()
    {
        return $this->hasMany('Nahid\Talk\Models\Message', 'conversation_id');
    }
}
