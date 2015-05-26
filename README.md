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

## License
This library is licensed under the MIT license. See the [LICENSE](LICENSE) file for details.
