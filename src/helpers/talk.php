<?php
/**
 * Created by PhpStorm.
 * User: nahid
 * Date: 12/7/16
 * Time: 4:58 PM.
 */
if (!function_exists('talk_live')) {
    function talk_live($options)
    {
        $talk__appKey = config('talk.broadcast.pusher.app_key');
        $talk__appName = config('talk.broadcast.app_name');
        $talk__options = json_encode(config('talk.broadcast.pusher.options'));

        $talk_user_channel = isset($options['user']['id']) ? $talk__appName.'-user-'.$options['user']['id'] : '';
        $talk_conversation_channel = isset($options['conversation']['id']) ? $talk__appName.'-conversation-'.$options['conversation']['id'] : '';
        $talk__userChannel['name'] = sha1($talk_user_channel);
        $talk__conversationChannel['name']  = sha1($talk_conversation_channel);
        $talk__userChannel['callback'] = isset($options['user']['callback']) ? $options['user']['callback'] : [];
        $talk__conversationChannel['callback'] = isset($options['conversation']['callback']) ? $options['conversation']['callback'] : [];

        return view('talk::pusherjs', compact('talk__appKey', 'talk__options', 'talk__userChannel', 'talk__conversationChannel'))->render();
    }
}
