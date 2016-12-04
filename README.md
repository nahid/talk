# Laravel-Talk

Talk is a Laravel 5 based user conversation (inbox) system. You can easily integrate this package with any Laravel based project. It helps you to develop a messaging system in just few mins. Here is a project screenshot that was developed by Talk.

![Talk-Example Screenshot](http://i.imgur.com/uQ7sgmI.png "Talk-Example Project")

You may try [Talk-Example](https://github.com/nahid/talk-example) project.

So let's start your tour :)

### Features

* Head to head messaging
* Creating new conversation
* Message threads with latest one
* View conversations by user id or conversation id
* Support pagination in threads and messages
* Delete(soft delete) message from both end. Sender and receiver can delete their message from their end.
* Permanent delete message
* Mark message as seen
* Only participant can view or access there message or message threads

### Installation

Talk is a Larravel package so you can install it via composer. Run this command in your terminal from your project directory.

```
composer require nahid/talk
```

Wait for a while, Composer will automatically install Talk in your project.

### Configuration

When the download is complete, you have to call this package service in `config/app.php` config file. To do that, add this line in `app.php` in `providers` section

```php
Nahid\Talk\TalkServiceProvider::class,
```

To use facade you have to add this line in `app.php` in `aliases` array

```php
'Talk'      => Nahid\Talk\Facades\Talk::class,
```

Now run this command in your terminal to publish this package resources

```
php artisan vendor:publish --provider="Nahid\Talk\TalkServiceProvider"
```

After running this command, all necessary file will be included in your project. This package has two default migrations. So you have to run migrate command like this. (But make sure your database configuration is configured correctly.)

```shell
php artisan migrate
```

Okay, now you need to configure your user model for Talk. Go to `config/talk.php` and config it.

```php
return [
    'user' => [
        'model' => 'User\Model'
    ]
];
```


### Usage

Its very easy to use. First you have to set authenticate user id to Talk as globally. 

```php
Talk::setAuthUserId(auth()->user()->id);
```

Now you may use any method what you need. But if want pass authentic id instantly, this method may help you.

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

`setAuthUserId` method sets the currently loggedin user id, which you pass through parameter. If you pass `null` or `empty` value then its return false.

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

You may use this method replacement of `setAuthUserId()` method. When you have to instantly access users conversations then you may use it.
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

If you want to get all the inboxes except soft deleted message , this method may help you. This method gets all the inboxes via previously assigned authenticated user id. Its return collections of message thread with latest message.

**Syntax**

```php
array getInbox([$order = 'desc'[,$offset = 0[, $take = 20]]])
```


**Example**

```php
// controller method
$inboxes = Talk::getInbox();
return view('message.threads', compact('inboxes');
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

When you want to get all the conversations using your desire conversation id, you can try this method. This method returns all the conversations(except soft deleted) with `sender` and `withUser` objects

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
    @foreach($messages as $msg)
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

When you want to get all the conversations using your desire receiver id, you can try this method. This method returns all the conversations(except soft deleted message) with user's objects

**Syntax**

```php
object getConversationsByUserId($receiverId [, $offset = 0[, $take = 20]])
```

### getConversationsAllByUserId

This method is similar as `getConversationsByUserId()`. The only difference between this method is its return all messages with soft deleted items.

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

> This method is deprecated from version 2.0.0 and it will remove from version 2.0.2

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

If you want to hard delete or permanently delete a specific message then you have to use this method

**Syntax**

```php
boolean deleteForever($messageId)
```

### deleteConversations

This method is used to permanently delete all conversations

**Syntax**

```php
boolean deleteConversations($conversationId)
```

### Try Demo Project
[Talk-Example](https://github.com/nahid/talk-example)

#### Special Thanks To
[Shipu Ahamed](https://github.com/shipu)

Thanks :)

## Support for this project
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/nahid/talk/badge.svg?style=beer-square)](https://beerpay.io/nahid/talk)  [![Beerpay](https://beerpay.io/nahid/talk/make-wish.svg?style=flat-square)](https://beerpay.io/nahid/talk?focus=wish)

