# Laravel-Talk

Talk is a Laravel 5 based user conversation (inbox) system. You can easily integrate this package with any Laravel based project. It helps you to develop a messaging system in just 25 mins. So let's start :)



![Talk Screenshot](http://i.imgur.com/ELqGVrx.png?1 "Talk Conversation System")

### Installation

Talk is a Laravel package so you can install it via composer. Run this command in your terminal from your project directory.

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
        'table' => 'your_users_table_name',
        'model' => 'User\Model',
        'columns' => ['column1', 'column2']
    ]
];
```

[NB: Here columns mean, the columns that you want should be used for inbox queries]


### Usage

Its very easy to use. First you have to set authenticate user id to Talk. 

```php
Talk::setAuthUserId(auth()->user()->id);
```

Now you may use any method what you need. Please see the API Doc.

### API List


- [setAuthUserId](https://github.com/nahid/talk#setauthuserid)
- [isConversationExists](https://github.com/nahid/talk#isconversationexists)
- [isAuthenticUser](https://github.com/nahid/talk#isauthenticuser)
- [sendMessage](https://github.com/nahid/talk#sendmessage)
- [sendMessageByUserId](https://github.com/nahid/talk#sendmessagebyuserid)
- [getInbox](https://github.com/nahid/talk#getinbox)
- [getInboxAll](https://github.com/nahid/talk#getinboxAll)
- [getConversationsById](https://github.com/nahid/talk#getconversationbyid)
- [getConversationsByUserId](https://github.com/nahid/talk#getconversationbyuserid)
- [makeSeen](https://github.com/nahid/talk#makeseen)
- [getReceiverInfo](https://github.com/nahid/talk#getreceiverinfo)
- [deleteMessage](https://github.com/nahid/talk#deletemessage)
- [deleteForever](https://github.com/nahid/talk#deleteforever)
- [deleteConversations](https://github.com/nahid/talk#deleteconversations)


#### setAuthUserId

`setAuthUserId` method sets the currently loggedin user id, which you pass through parameter. If you pass `null` or `empty` value then its return false.

**Syntax**

```php
void setAuthUserId($userid)
```

**Example**
Contructor of a Controller is the best place to write this method. 

```php
function __construct()
{
    Talk::setAuthUserId(auth()->user()->id);
}
```

When you pass logged in user ID, Talk will know who is currently authenticated for this system. So Talk retrieve all information based on this user.


#### isConversationExists

This method checks currently logged in user and if given user is already in conversation

**Syntax**

```php
int/false isConversationExists($userid)
```

#### isAuthenticUser

isAuthenticUser checks if  the given user exists in given conversation.

**Syntax**

```php
boolean isAuthenticUser($conversationId, $userId)
```

#### sendMessage

You can send messages via conversation id by using this method. If the message is successfully sent, it will return objects of Message model otherwise, it will return `false`

**Syntax**

```php
object/false sendMessage($conversationId, $message)
```

#### sendMessageByUserId

You can send message via receiver id by using this method. If the message is successfully sent, it will return objects of Message model otherwise, it will return `false`

**Syntax**

```php
object/false sendMessageByUserId($userId, $message)
```

#### getInbox

If you want to get all the inboxes, this method may help you. This method gets all the inboxes via given user id

**Syntax**

```php
object getInbox([$offset[, $take]])
```

#### getInboxAll

If you want to get all the inboxes with soft deleted messages, this method may help you. This method gets all the inboxes via given user id

**Syntax**

```php
object getInboxAll([$offset[, $take]])
```

#### getConversationsById

When you want to get all the conversations using your desire conversation id, you can try this method. This method returns all the conversations with user's objects

**Syntax**

```php
object getConversationsById($conversationId)
```

#### getConversationsByUserId

When you want to get all the conversations using your desire receiver id, you can try this method. This method returns all the conversations with user's objects

**Syntax**

```php
object getConversationsByUserId($receiverId)
```


#### getReceiverInfo

This method returns all the information about message receiver. 

**Syntax**

```php
object getReceiverInfo($conversationId)
```

#### makeSeen

If you want to set a message as seen you can use this method.

**Syntax**

```php
boolean makeSeen($messageId)
```

#### deleteMessage

When you want to delete a specific message from a conversation, you have to use this method. This method soft delete message for both user-end individually.

**Syntax**

```php
boolean deleteMessage($messageId)
```

#### deleteForever

If you want to hard delete or permanently delete a specific message then you have to use this method

**Syntax**

```php
boolean deleteForever($messageId)
```

#### deleteConversations

This method is used to permanently delete all conversations

**Syntax**

```php
boolean deleteConversations($conversationId)
```


#### Special Thanks To
[Shipu Ahamed](https://github.com/shipu)

Thanks :)

## Support for this project
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/nahid/talk/badge.svg?style=beer-square)](https://beerpay.io/nahid/talk)  [![Beerpay](https://beerpay.io/nahid/talk/make-wish.svg?style=flat-square)](https://beerpay.io/nahid/talk?focus=wish)

