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

        $talk__userChannel['name'] = isset($options['user']['id']) ? $talk__appName.'-user-'.$options['user']['id'] : '';
        $talk__conversationChannel['name'] = isset($options['conversation']['id']) ? $talk__appName.'-conversation-'.$options['conversation']['id'] : '';
        $talk__userChannel['callback'] = isset($options['user']['callback']) ? $options['user']['callback'] : [];
        $talk__conversationChannel['callback'] = isset($options['conversation']['callback']) ? $options['conversation']['callback'] : [];

        return view('talk::pusherjs', compact('talk__appKey', 'talk__userChannel', 'talk__conversationChannel'))->render();
    }
}
