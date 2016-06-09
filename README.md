# Laravel-Talk

Talk is a Laravel 5 based user conversation (inbox) system. You can easily integrate this package with any laravel based project. Its help to you to develop a messeging system in 25 mins. So lets start :)

![Talk Screenshot](http://i.imgur.com/ELqGVrx.png?1 "Talk Conversation System")

### Installation

Talk is a laravel package so you can install it via composer. write this code in your terminal from your project

```
composer require nahid/talk
```

Wait for a while, composer will be automatically install talk in your project.

### Configuration

After downloading complete now you have to call this package service in `config/app.php` config file. So add this line in `app.php` `providers` section

```php
Nahid\Talk\TalkServiceProvider::class,
```

To use facade you have to add these line in `app.php` `aliases` array

```php
'Talk'      => Nahid\Talk\Facades\Talk::class,
```

Now run this command from your terminal to publish this package resources

```
php artisan vendor:publish --provider="Nahid\Talk\TalkServiceProvider"
```

After run this command all necessary file will be included with your project. This package has two default migrations. So you have to run migrate command like these. Make sure your database configuration was configure correctly.

```shell
php artisan migrate
```

Okay, now you have configure your user model for Talk. Go to `config/talk.php` and config it.

```php
return [
    'user' => [
        'table' => 'your_users_table_name',
        'model' => 'User\Model',
        'columns' => ['column1', 'column2']
    ]
];
```

[NB: Here columns mean which column do you want for inbox query]


### Usage

Its very easy to use. First you have set authenticate user id to Talk. 

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
- [getConversationsById](https://github.com/nahid/talk#getconversationbyid)
- [getConversationsByUserId](https://github.com/nahid/talk#getconversationbyuserid)
- [makeSeen](https://github.com/nahid/talk#makeseen)
- [deleteMessage](https://github.com/nahid/talk#deletemessage)
- [deleteForever](https://github.com/nahid/talk#deleteforever)
- [deleteConversations](https://github.com/nahid/talk#deleteconversations)


#### setAuthUserId

`setAuthUserId` method set the user id which you pass from parameter

**Syntax**

```php
void setAuthUserId($userid)
```


#### isConversationExists

This method check currently logged in user and given user is already conversation

**Syntax**

```php
int/false isConversationExists($userid)
```

#### isAuthenticUser

isAuthenticUser check the given user is exists in given conversation. 

**Syntax**

```php
boolean isAuthenticUser($conversationId, $userId)
```

#### sendMessage

You can send message via conversation id by using this method. If successfully send message it return `Message` model objects otherwise `false` 

**Syntax**

```php
object/false sendMessage($conversationId, $message)
```

#### sendMessageByUserId

You can send message via receiver id by using this method. If successfully send message it return `Message` model objects otherwise `false` 

**Syntax**

```php
object/false sendMessageByUserId($userId, $message)
```

#### getInbox

If you want to get all inbox this method may help you. This method get all inboxes via given user id

**Syntax**

```php
object getInbox([$offset[, $take]])
```

#### getConversationsById

When you want to get all conversations by using your desire conversation id you can try it. This method return all conversations with users objects

**Syntax**

```php
object getConversationsById($conversationId)
```

#### getConversationsByUserId

When you want to get all conversations by using your desire receiver id you can try it. This method return all conversations with users objects

**Syntax**

```php
object getConversationsByUserId($receiverId)
```


#### getReceiverInfo

This method return all information about message receiver. 

**Syntax**

```php
object getReceiverInfo($conversationId)
```

#### makeSeen

If you want set a message as seen you can use it.

**Syntax**

```php
boolean makeSeen($messageId)
```

#### deleteMessage

When you want to delete a specific message from conversation you have to use it. This method soft delete message for both user-end individually.

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

This method is used for permanently delete all conversations

**Syntax**

```php
boolean deleteConversations($conversationId)
```
