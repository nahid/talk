# Laravel-Talk

[![Awesome Laravel](https://img.shields.io/badge/Awesome-Laravel-brightgreen.svg)](https://github.com/nahid/talk)
[![GitHub license](https://img.shields.io/badge/license-CC0-blue.svg)](https://raw.githubusercontent.com/nahid/talk/master/LICENSE)
[![Build Status](https://travis-ci.org/nahid/talk.svg?branch=master)](https://travis-ci.org/nahid/talk)

Talk is a Laravel 5 based user conversation (inbox) system with realtime messaging. You can easily integrate this package with any Laravel based project. It helps you to develop a messaging system in just few minutes. Here is a project screenshot that was developed by Talk.   

Talk v2.1.0 supports realtime messaging. Learn more about [Talk Live Messaging](https://github.com/nahid/talk#realtime-messaging) 


#### Feedback

If you already used Talk, please share your experience with us. It will make the project better. 

[Give us your feedback](https://github.com/nahid/talk/issues/43) 

#### Built with Talk

If you are using Talk in your project please share your project URL or project name with us. It will inspire other people to use Talk.

See which project was [Built with Talk](https://github.com/nahid/talk/issues/42).

## Caution 

> Do not migrate 1.1.7 from its higher version directly. Please try our [sample project](https://github.com/nahid/talk-example) first and then apply it on your project.


![Talk-Example Screenshot](http://i.imgur.com/uQ7sgmI.png "Talk-Example Project")

You may try [Talk-Example](https://github.com/nahid/talk-example) project.

Or you can try live [Demo](http://portal.inilabs.net/baseapp/v1.0/admin/message/inbox) by using this credentials:

```
username: admin   
password: admin
```



So let's start your tour :)

### Features

* Head to head messaging
* Realtime messaging
* Creating new conversation
* Message threads with latest one
* View conversations by user id or conversation id
* Support pagination in threads and messages
* Delete (soft delete) message from both end. Sender and receiver can delete their message from their end
* Permanent delete message
* Mark message as seen
* Only participant can view or access there message or message threads
* Inline url render using oembed specifications

### Installation

Talk is a Laravel package so you can install it via Composer. Run this command in your terminal from your project directory:

```
composer require nahid/talk
```

Wait for a while, Composer will automatically install Talk in your project.

### Configuration

When the download is complete, you have to call this package service in `config/app.php` config file. To do that, add this line in `app.php` in `providers` section:

```php
Nahid\Talk\TalkServiceProvider::class,
```

To use facade you have to add this line in `app.php` in `aliases` array:

```php
'Talk'      => Nahid\Talk\Facades\Talk::class,
```

Now run this command in your terminal to publish this package resources:

```
php artisan vendor:publish --provider="Nahid\Talk\TalkServiceProvider"
```

After running this command, all necessary file will be included in your project. This package has two default migrations. So you have to run migrate command like this. (But make sure your database configuration is configured correctly.)

```shell
php artisan migrate
```

Okay, now you need to configure your user model for Talk. Go to `config/talk.php` and config it:

```php
return [
    'user' => [
        'model' => 'App\User',
        'foreignKey' => null,
        'ownerKey' => null
    ],
    'broadcast' => [
        'enable' => false,
        'app_name' => 'your-app-name',
        'pusher' => [
            'app_id'        => '',
            'app_key'       => '',
            'app_secret'    => '',
            'options' => [
                 'cluster' => 'ap1',
                 'encrypted' => true
            ]
        ]
    ],
    'oembed' => [
        'enabled' => false,
        'url' => null,
        'key' => null
    ]
];
```


### Usage

Its very easy to use. If you want to set authenticate user id globally then you have to set a middleware first. Go to `app/Http/Kernel.php` and set it in `$routeMiddleware` array:

 ```php
 'talk'  =>  \Nahid\Talk\Middleware\TalkMiddleware::class,
 ```

 And now you can use it from anywhere with middleware. Suppose you have a Controller and you want to set authenticate user id globally then write this in controller constructor:

 
 ```php
 $this->middleware('talk');
 ```
 
But instead of set id globally you can use these procedure from any method in controller:


```php
Talk::setAuthUserId(auth()->user()->id);
```


Now you may use any method what you need. But if want pass authentic id instantly, this method may help you:

```php
Talk::user(auth()->user()->id)->anyMethodHere();
```
Please see the API Doc.

### API List


- [setAuthUserId](https://github.com/nahid/talk#setauthuserid)
- [user](https://github.com/nahid/talk#user)
- [isConversationExists](https://github.com/nahid/talk#isconversationexists)
- [isAuthenticUser](https://github.com/nahid/talk#isauthenticuser)
- [sendMessage](https://github.com/nahid/talk#sendmessage)
- [sendMessageByUserId](https://github.com/nahid/talk#sendmessagebyuserid)
- [getInbox](https://github.com/nahid/talk#getinbox)
- [getInboxAll](https://github.com/nahid/talk#getinboxAll)
- [threads](https://github.com/nahid/talk#threads)
- [threadsAll](https://github.com/nahid/talk#threadsall)
- [getConversationsById](https://github.com/nahid/talk#getconversationbyid)
- [getConversationsAllById](https://github.com/nahid/talk#getconversationallbyid)
- [getConversationsByUserId](https://github.com/nahid/talk#getconversationbyuserid)
- [getConversationsAllByUserId](https://github.com/nahid/talk#getconversationallbyuserid)
- [getMessages](https://github.com/nahid/talk#getmessages)
- [getMessagesByUserId](https://github.com/nahid/talk#getmessagesbyuserid)
- [getMessagesAll](https://github.com/nahid/talk#getmessagesall)
- [getMessagesAllByUserId](https://github.com/nahid/talk#getmessagesallbyuserid)
- [readMessage](https://github.com/nahid/talk#readmessage)
- [makeSeen](https://github.com/nahid/talk#makeseen)
- [getReceiverInfo](https://github.com/nahid/talk#getreceiverinfo)
- [deleteMessage](https://github.com/nahid/talk#deletemessage)
- [deleteForever](https://github.com/nahid/talk#deleteforever)
- [deleteConversations](https://github.com/nahid/talk#deleteconversations)


### setAuthUserId

`setAuthUserId` method sets the currently loggedin user id, which you pass through parameter. If you pass `null` or `empty` value then it returns false.

**Syntax**

```php
void setAuthUserId($userid)
```

**Example**

Constructor of a Controller is the best place to write this method. 

```php
function __construct()
{
    Talk::setAuthUserId(auth()->user()->id);
}
```

When you pass logged in user ID, Talk will know who is currently authenticated for this system. So Talk retrieve all information based on this user.

### user

You may use this method instead of `setAuthUserId()` method. When you have to instantly access users conversations then you may use it.
**Syntax**

```php
object user($id)
```
**Example**
When you haven't set authenticated user id globally, then you just use this method directly with others method.

```php
$inboxes = Talk::user(auth()->user()->id)->threads();
return view('messages.threads', compact('inboxes'));
```

### isConversationExists

This method checks currently logged in user and if given user is already in conversation

**Syntax**

```php
int|false isConversationExists($userid)
```

**Example**

```php
if ($conversationId = Talk::isConversationExists($userId)) {
    Talk::sendMessage($conversationId, $message);
} 
```

### isAuthenticUser

isAuthenticUser checks if  the given user exists in given conversation.

**Syntax**

```php
boolean isAuthenticUser($conversationId, $userId)
```

**Example**

```php
if (Talk::isAuthenticUser($conversationId, $userId)) {
    Talk::sendMessage($conversationId, $message);
} 
```

### sendMessage

You can send messages via conversation id by using this method. If the message is successfully sent, it will return objects of Message model otherwise, it will return `false`

**Syntax**

```php
object|false sendMessage($conversationId, $message)
```

**Example**

```php
    $message = Talk::sendMessage($conversationId, $message);
    if ($message) {
        return response()->json(['status'=>'success', 'data'=>$message], 200);
   }
```

### sendMessageByUserId

You can send message via receiver id by using this method. If the message is successfully sent, it will return objects of Message model otherwise, it will return `false`

**Syntax**

```php
object|false sendMessageByUserId($userId, $message)
```

### getInbox

If you want to get all the inboxes except soft deleted message , this method may help you. This method gets all the inboxes via previously assigned authenticated user id. It returns collections of message thread with latest message.

**Syntax**

```php
array getInbox([$order = 'desc'[,$offset = 0[, $take = 20]]])
```


**Example**

```php
// controller method
$inboxes = Talk::getInbox();
return view('message.threads', compact('inboxes'));
```

```html
<!-- messages/threads.blade.php -->
<ul>
    @foreach($inboxes as $inbox)
        <li>
            <h2>{{$inbox->withUser->name}}</h2>
            <p>{{$inbox->thread->message}}</p>
            <span>{{$inbox->thread->humans_time}}</span>
        </li>
    @endforeach
</ul>
```

### getInboxAll

Its similar as `getInbox()` method. If you want to get all the inboxes with soft deleted messages, this method may help you. This method gets all the inboxes via given user id.

**Syntax**

```php
object getInboxAll([$order = 'desc'[,$offset = 0[, $take = 20]]])
```

### threads

This method is an alias of `getInbox()` method.

**Syntax**

```php
array threads([$order = 'desc'[,$offset = 0[, $take = 20]]])
```


### threadsAll

This method is an alias of `getInboxAll()` method.

**Syntax**

```php
array threadsAll([$order = 'desc'[,$offset = 0[, $take = 20]]])
```

### getConversationsById


When you want to get all the conversations using your desire conversation id, you can try this method. This method returns all the conversations (except soft deleted) with `sender` and `withUser` objects

**Syntax**

```php
array getConversationsById($conversationId[, $offset = 0[, $take = 20]])
```

**Example**

```php
// controller method
$conversations = Talk::getConversationsById($conversationId);
$messages = $conversations->messages;
$withUser = $conversations->withUser;

return view('messages.conversations', compact('messages', 'withUser'));
```
This method returns two objects `messages` and `withUser`. `messages` object contains messages collection and `withUser` object contains participant User collections.

Let's see how to use it with your views

```html
<!-- messages/conversations.blade.php -->
<div class="message-container">
    <h2>Chat with {{$withUser->name}}</h2>
    @foreach ($messages as $msg)
     <div class="message">
        <h4>{{$msg->sender->name}}</h4>
        <span>{{$msg->humans_time}}</span>
        <p>
            {{$msg->message}}
       </p> 
    </div>
    @endforeach
</div>
```

### getConversationsAllById

This method is similar as `getConversationsById()`. The only difference between this method is its return all messages with soft deleted items.

**Syntax**

```php
array getConversationsAllById($conversationId[, $offset = 0[, $take = 20]])
```
### getConversationsByUserId

When you want to get all the conversations using your desire receiver id, you can try this method. This method returns all the conversations (except soft deleted message) with user's objects

**Syntax**

```php
object getConversationsByUserId($receiverId [, $offset = 0[, $take = 20]])
```

### getConversationsAllByUserId

This method is similar as `getConversationsByUserId()`. The only difference between this method is it returns all messages with soft deleted items.

**Syntax**

```php
array getConversationsAllByUserId($receiverId[, $offset = 0[, $take = 20]])
```

### getMessages

This is a alias of  `getConversationsById()` method.

**Syntax**

```php
array messages($conversationId[, $offset = 0[, $take = 20]])
```

### getMessagesAll

This is a alias of  `getConversationsAllById()` method.

**Syntax**

```php
array messagesAll($conversationId[, $offset = 0[, $take = 20]])
```

### getMessagesByUserId

This is a alias of  `getConversationsByUserId()` method.

**Syntax**

```php
array messagesByUserId($receiverId[, $offset = 0[, $take = 20]])
```


### getMessagesAllByUserId

This is a alias of  `getConversationsAllByUserId()` method.

**Syntax**

```php
array messagesAllByUserId($receiverId[, $offset = 0[, $take = 20]])
```

### readMessage

If you want to read a single message then you may use it. This message is return a single message object by message id.

**Syntax**

```php
array readMessage($messageId)
```

### getReceiverInfo

This method returns all the information about message receiver. 

> This method is deprecated from version 2.0.0 and it will be removed from version 2.0.2

**Syntax**

```php
object getReceiverInfo($conversationId)
```

### makeSeen

If you want to set a message as seen you can use this method.

**Syntax**

```php
boolean makeSeen($messageId)
```

### deleteMessage

When you want to delete a specific message from a conversation, you have to use this method. This method soft delete message for both user-end individually.

**Syntax**

```php
boolean deleteMessage($messageId)
```

### deleteForever

If you want to hard delete or permanently delete a specific message then you have to use this method.

**Syntax**

```php
boolean deleteForever($messageId)
```

### deleteConversations

This method is used to permanently delete all conversations.

**Syntax**

```php
boolean deleteConversations($conversationId)
```

## Realtime Messaging

Talk also support realtime messaging thats called Talk-Live. Talk use pusher for realtime message. So first you have to configure pusher. Go to `app/talk.php` again and configure.

```php
return [
    'user' => [
        'model' => 'App\User'
    ],
    'broadcast' => [
        'enable' => false,
        'app_name' => 'your-app-name',
        'pusher' => [
            'app_id'        => '',
            'app_key'       => '',
            'app_secret'    => ''
        ]
    ]
];
```

in this new version broadcast section was added with talk config. Here broadcast is disabled by default.
If you want to enable live (realtime) messaging then you have to enable it first. Then add pusher credentials. Thats it. Everytime
when you send message then talk will automatically fire two event, one for specific user and second for specific conversation. So
you may listen or subscribe one or both as per your wish. Finally you have to subscribe these events by using `talk_live()` helper function.
Go to where you want to subscribe to work with message data follow this code.

```
<script>
    var msgshow = function(data) {
        // write what you want with this data
        
        console.log(data);
    }
</script>

{!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}
```

`talk_live()` supports one parameters as array. The first parameter is for channel name which you want to subscribe. You have not know which channel was broadcast.
Talk broadcast two channel by default. One for user and second for conversation. If you want to subscribe channel for currently loggedin user then you have to pass

logedin user id in 'user' key. `['user'=>['id'=>auth()->user()->id, 'callback'=>[]]` or you want to subscribe for conversation id you have pass conversation id as
'conversation' key. `['conversation'=>['id'=>$conversationID, 'callback'=>[]]`. You may pass both if you want.

You can pass a callback for working with pusher recieved data. For both `user` and `conversation` section support callbacks as array. So you can pass multiple callback as array value that was shown in previous example.

You can watch [Talk-Live-Demo](https://youtu.be/bN3s_LbObnQ)

## Oembed support

Talk also supports embed urls simply use `$message->toHtlmString()` in you views to render an embed link

Eg. `This is a youtube embed link: https://www.youtube.com/watch?v=jNQXAC9IVRw`

```html
<div class="message-container">
    <h2>Chat with {{$withUser->name}}</h2>
    @foreach ($messages as $msg)
     <div class="message">
        <h4>{{$msg->sender->name}}</h4>
        <span>{{$msg->humans_time}}</span>
        <p>
            {{$msg->toHtmlString()}}
       </p> 
    </div>
    @endforeach
</div>
``` 

## Custom embed link

If you want to setup your own implementation of oembed you can configure it in the talk config file. You endpoint should follow the [Oembed](https://oembed.com/) specifications

```php
    'user' => [
        'model' => 'App\User',
        'foreignKey' => null,
        'ownerKey' => null
    ],
    'broadcast' => [
        'enable' => false,
        'app_name' => 'your-app-name',
        'pusher' => [
            'app_id'        => '',
            'app_key'       => '',
            'app_secret'    => '',
            'options' => [
                 'cluster' => 'ap1',
                 'encrypted' => true
            ]
        ]
    ],
    'oembed' => [
        'enabled' => true,
        'url' => 'http://your.domain/api/oembed',
        'key' => 'yout-auth-api-key'
    ]
```
### Testing

Talk is backwards compatible with php 5.5.  Use docker to run unit tests.

```bash
docker-compose run php55 composer install
docker-compose run php55 phpunit
```

```bash
docker-compose run php56 composer install
docker-compose run php56 phpunit
```

```bash
docker-compose run php7 composer install
docker-compose run php7 phpunit
```

```bash
docker-compose run hhvm composer install
docker-compose run hhvm phpunit
```

### Try Demo Project
[Talk-Example](https://github.com/nahid/talk-example)

#### Special Thanks To
[Shipu Ahamed](https://github.com/shipu)

Thanks :)

## Support for this project
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/nahid/talk/badge.svg?style=beer-square)](https://beerpay.io/nahid/talk)  [![Beerpay](https://beerpay.io/nahid/talk/make-wish.svg?style=flat-square)](https://beerpay.io/nahid/talk?focus=wish)

