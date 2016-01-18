# Talk

Talk is a Laravel 5 based user conversation (inbox) system. You can easily integrate this package with your all laravel based project. Its help to you to develop a messeging system in 25 mins. So lets start :)

![Talk Screenshot](http://i.imgur.com/ELqGVrx.png?1 "Talk Conversation System")

### Installation

Talk is a laravel package so you can install it via composer. write this code in your terminal from your project

```
composer require nahid/talk
```

Wait for a while, composer will be autometically install it in your project.

### Configuration

After downloading complete now you have to call this package service in `config/app.php` config file. So add this line in `app.php` `providers` section

```php
Nahid\Talk\TalkServiceProvider::class,
```

Now run this command in your terminal for publish package resources

```
php artisan vendor:publish
```

After run this command all neccessary file will be included with your project. This package has two default migration and two models. So you have to run migrate command like these. Make sure your database configuration was complete.

```
php artisan migrate
```

### Usage

Talk conversation system work with laravel builtin authentication system so make sure you are using `Auth`. Talk work with your system users so your to insert your user table name in `config/talk.php` 

```php
'user_table'	=>	'your_user_table_name_without_prefix'
```

### API List


- [checkConversationExists](https://github.com/nahid/talk#checkconversationexists)
- [isUserAuthConversation](https://github.com/nahid/talk#isuserauthconversation)
- [sendMessageByConversationId](https://github.com/nahid/talk#sendmessagebyconversationid)
- [sendMessageByUserId](https://github.com/nahid/talk#sendmessagebyuserid)
- [getInbox](https://github.com/nahid/talk#getinbox)
- [getAllConversations](https://github.com/nahid/talk#getallconversations)
- [getAllConversationsByUserId](https://github.com/nahid/talk#getallconversationsbyuserid)
- [makeSeen](https://github.com/nahid/talk#makeseen)
- [deleteMessage](https://github.com/nahid/talk#deletemessage)
- [deleteForever](https://github.com/nahid/talk#deleteforever)
- [deleteConversations](https://github.com/nahid/talk#deleteconversations)


#### checkConversationExists

`checkConversationExists` method check is this two users already make conversation. It return conversation id if conversation already exists otherwise `false`

**Syntax**

```php
int/boolean checkConversationExists($user1, $user2)
```

#### isUserAuthConversation

This method check currently logged in user is authenticate for given conversation. If authenticate it return `true` otherwise `false`

**Syntax**

```php
boolean isUserAuthConversation($conversation_id)
```

#### sendMessageByConversationId

You can send message to another user by using this method. You have to give a conversation id system will autometically send the message your desire user. If successfully send message it return `true` otherwise `false` 

**Syntax**

```php
boolean sendMessageByConversationId($conversation_id, $message)
```

#### sendMessageByUserId

You can send message via user id by using this method. If successfully send message it return `true` otherwise `false` 

**Syntax**

```php
boolean sendMessageByUserId($user_id, $message)
```

#### getInbox

This method return all inbox list as JSON for currently logged in user. 

**Syntax**

```php
json sendMessageByUserId()
```

#### getAllConversations

When you want to get all conversations by using your desire conversation id you can try it. This method return all conversations with currently logged in user.


**Syntax**

```php
json getAllConversations($conversation_id)
```

#### getAllConversationsByUserId

When you want to get all conversations by using your desire user id you can try it. This method return all conversations with currently logged in user.

**Syntax**

```php
json getAllConversationsByUserId($user_id)
```

#### makeSeen

For making a message as seen you have to use this method.

**Syntax**

```php
boolean makeSeen($message_id)
```

#### deleteMessage

When you want to delete a specific message from conversation you have to use it. This method soft delete message for both user-end individually.

**Syntax**

```php
boolean deleteMessage($message_id)
```

#### deleteForever

If you want to hard delete or permanently delete a specific message then you have to use this method

**Syntax**

```php
boolean deleteForever($message_id)
```

#### deleteConversations

This method is used for permanently delete all conversations

**Syntax**

```php
boolean deleteConversations($conversation_id)
```