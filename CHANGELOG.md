# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [0.2.0] - 2015-06-13
### Added
- Support for listing all channel types, groups and DMs by ID.
- `RealTimeClient` connections can now be closed.
- Client objects can now be safely serialized to JSON.
- You can now get more info about DMs.
- Groups can be opened and closed.

### Changed
- New message posting API with attachment support. Messages can be created using `MessageBuilder` and sent using `ApiClient::postMessage()`.
- Channels, groups, and DMs now implement `ChannelInterface` which is used for any channel-like object checking.
- `ClientObject` now extends the more general `DataObject`.
- `$data` in client objects is now public.

### Removed
- `PostableInterface` has been superseded by `ChannelInterface`.

### Fixed
- Don't error when server replies to a message more than once.

## [0.1.1] - 2015-06-05
### Fixed
- Fix incorrect interface type used in both client types.
- Fix client looking for `okay` instead of `ok` in server responses.

## 0.1.0 - 2015-06-05
### Added
- Initial release.
- Completely event-based, asynchronous API using React and Guzzle 6.
- Web API access with nice object abstractions, e.g. User objects, etc.
- Working client for Slack's Real-Time Messaging API with support for all server events.
- Ability to send messages to any open channel, group or DM, either with the web API or with the RTM API.

[unreleased]: https://github.com/coderstephen/slack-client/compare/v0.2.0...HEAD
[0.2.0]: https://github.com/coderstephen/slack-client/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/coderstephen/slack-client/compare/v0.1.0...v0.1.1
