<script src="https://js.pusher.com/3.2/pusher.min.js"></script>
<script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var pusher = new Pusher('{{$talk__appKey}}', {!! $talk__options !!});

    @if(!empty($talk__userChannel['name']))
    var userChannel = pusher.subscribe('{{$talk__userChannel['name']}}');
    userChannel.bind('talk-send-message', function(data) {
        @foreach($talk__userChannel['callback'] as $callback)
        {!! $callback . '(data);'  !!}
        @endforeach
    });

    @endif

    @if(!empty($talk__conversationChannel['name']))
    var conversationChannel = pusher.subscribe('{{$talk__conversationChannel['name']}}');
    conversationChannel.bind('talk-send-message', function(data) {
        @foreach($talk__conversationChannel['callback'] as $callback)
        {!! $callback . '(data);'  !!}
        @endforeach
    });
    @endif
</script>
