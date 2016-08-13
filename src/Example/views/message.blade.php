    @extends('talk::layouts.master')

    @section('body')
    <div class="center screen niceScroll" id="talk-inbox" style="top:60px;bottom:150px">
        <div class="box">
            <div class="col v-bottom wrapper" id="talk-conversations">

                @foreach($messages as $message)
                <div class="media">
                    <div class="h5">
                        <a href="" class="thumb-sm avatar">
                            <label class="" for="">{{$message->user->id == auth()->user()->id?'You':$message->user->name}}</label>
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="{{$message->user->id == auth()->user()->id?'pos-rlt wrapper b b-light r r-2x bg-light':'pos-rlt wrapper b b-light r r-2x'}}">
                            <p class="m-b-none">{{$message->message}}</p>
                        </div>
                        <small class="text-muted"><i class="fa fa-check-circle text-success hide"></i> {{$message->time_ago}}</small>
                    </div>
                </div>
                    @endforeach

            </div>
        </div>
    </div>

    <div class="bottom bg-white w-full wrapper b-t b-light b-dk b-2x m-t-lg" style="z-index: 10">
        <div class="media">
            <form action="{{url('laravel-talk/example/message/send')}}" method="post">
                <div class="media-left">
                    {{--<a href="" class="thumb-sm avatar"><img src="imgs/a0.jpg" alt="..."></a>--}}
                </div>
            <div class="media-body">
                <div class="form-group m-b-none b b-light b-dk wrapper-xs bg-white niceScroll" style="min-height:60px; max-height:150px; overflow-y: auto !important;">
                    <textarea style="min-height:60px; resize: none;" class="form-control autoglow wrapper-none b-none" name="message" placeholder="Write your message" rows="1"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="_conversation_id" value="{{$id}}">
                    {{csrf_field()}}
                    <input type="submit" name="submit" value="Send" class="btn btn-info m-t-md pull-right">
                </div>

            </div>
            </form>
        </div>

    </div>
    @endsection
