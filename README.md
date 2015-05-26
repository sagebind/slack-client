# PHP Slack API Client
[![Build](https://img.shields.io/scrutinizer/build/g/coderstephen/slack-client.svg)](https://scrutinizer-ci.com/g/coderstephen/slack-client)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/coderstephen/slack-client.svg)](https://scrutinizer-ci.com/g/coderstephen/slack-client)
[![Code Quality](https://img.shields.io/scrutinizer/g/coderstephen/slack-client.svg)](https://scrutinizer-ci.com/g/coderstephen/slack-client)
[![License](https://img.shields.io/packagist/l/coderstephen/slack-client.svg)](https://packagist.org/packages/coderstephen/slack-client)

This is an API client for [Slack](http://slack.com) for PHP clients, with support for the [Real Time Messaging API](http://api.slack.com/rtm) (RTM API) using web sockets.

## Overview
This library was created primarily for [Slackyboy](https://github.com/coderstephen/slackyboy), but was branched off into its own codebase so it could be used in other projects as well. I created this client because existing clients were either too complicated to use, or buggy, or incomplete. This is also the first PHP client I am aware of to support Slack's RTM API.

## Installation
Install with [Composer](http://getcomposer.org), obviously:

```sh
$ composer require coderstephen/slack-client
```

## Usage
First, you need to create a client object to connect to the Slack servers. You will need to acquire an API token for your app first from Slack, then pass the token to the client object for logging in.

```php
$client = new \Slack\ApiClient();
$client->setToken('YOUR-TOKEN-HERE');
```

Assuming your token is valid, you are good to go! You can now use wrapper methods for accessing most of the [Slack API](http://api.slack.com). Below is an example of posting a message to a channel as the logged in user:

```php
$channel = $client->getChannelByName('general');
$client->send('Hello from PHP!', $channel);
```

### Real Time Messaging API
You can also connect to Slack using the [Real Time Messaging API](http://api.slack.com/rtm). This is often useful for creating Slack bots or message clients. The real-time client is like the regular client, but it enables real-time incoming events. First, you need to create the client:

```php
$client = new \Slack\RealTimeClient();
$client->setToken('YOUR-TOKEN-HERE');
```

Then you can use the client as normal; `RealTimeClient` extends `ApiClient`, and has the same API for sending requests. You can attach a callback to handle incoming Slack events using `RealTimeClient::on()`:

```php
$client->on('file_created', function($data) {
    echo 'A file was created called ' . $data['file']['name'] . '!\n';
});
```

See the [Slack API documentation](http://api.slack.com/events) for a list of possible events.

## License
This library is licensed under the MIT license. See the [LICENSE](LICENSE) file for details.
