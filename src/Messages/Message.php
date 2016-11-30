<?php
namespace Nahid\Talk\Messages;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{

    protected $table='messages';

    public $timestamps=true;
    public $fillable = [
        'message',
        'is_seen',
        'deleted_from_sender',
        'deleted_from_receiver',
        'user_id',
        'conversation_id'
    ];

    public function getHumansTimeAttribute(){
        $date = $this->created_at;
        $now = $date->now();
        return $date->diffForHumans($now, true);
    }

    public function getNothingAttribute(){
        if($this->user_id == $this->conversation->user_one) {
            return 'user_two';
        }elseif($this->user_id == $this->conversation->user_two) {
            return 'user_one';
        }
    }

    public function conversation()
    {
        return $this->belongsTo('Nahid\Talk\Conversations\Conversation');
    }


    public function user()
    {
        return $this->belongsTo(config('talk.user.model', 'App\User'));
    }

    public function sender()
    {
        return $this->user();
    }

    public function threads()
    {
        return $this->orderBy('updated_at', 'desc')->groupBy('conversation_id')->max('updated_at')->get();
    }
}
