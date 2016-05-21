[![PHP version](https://img.shields.io/badge/PHP-%3E%3D5.6-8892BF.svg?style=flat-square)](http://php.net)
[![License](https://img.shields.io/github/license/juliangut/tify_example.svg?style=flat-square)](https://github.com//tify_example/blob/master/LICENSE)

[![Build status](https://img.shields.io/travis/juliangut/tify_example.svg?style=flat-square)](https://travis-ci.org/juliangut/tify_example)
[![Style](https://styleci.io/repos/59372625/shield)](https://styleci.io/repos/59372625)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/tify_example.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/tify_example)
[![Code Coverage](https://img.shields.io/coveralls/juliangut/tify_example.svg?style=flat-square)](https://coveralls.io/github/juliangut/tify_example)

# Simple notification server implementation

Implementation of notification service to which devices can register to receive push notifications.

Uses [Tify](https://github.com/juliangut/tify) a Push notification services abstraction layer.

## Installation

```
clone https://github.com/juliangut/tify_example
cd tify_example
composer install
```

## Usage

### Configuration

Create `api.key` and/or `certificate.pem` files in config directory to store your GCM api key and APNS certificate.

### Run PHP builtin server

```
grunt serve
```

Or if you don't have grunt installed

```php
php -S localhost:9000 -t public
```

#### Registering a device

```
curl -X PUT -H "Host: localhost:9000" -H "Accept: application/json" -H "Content-Type: application/json" -d '{"token": "device_token"}' "http://localhost:9000/register"
```

##### Request parameters

* token: device token
* platform: device platform (ios/android)

#### Updating a device registration

Device tokens can and should be changed from time to time, and then updated into the server to correctly receive push notifications.

```
curl -X PUT -H "Host: localhost:9000" -H "Accept: application/json" -H "Content-Type: application/json" -d '{"token": "device_token","previous_token":"previous_device_token"}' "http://localhost:9000/register"
```

##### Request parameters

* token: device token
* previous_token: previously saved device token to be replaced

#### Sending push notification to registered devices

```
curl -X POST -H "Host: localhost:9000" -H "Accept: application/json" -H "Content-Type: application/json" -d '{"title": "Push_title","body": "push_body","platform": "android"}' "http://localhost:9000/send"
```

##### Request parameters

* title: notification title
* body: notification content body
* platform: (optional) platform to send to, all if not provided
* data: (optional) key/value array of extra data to be appended to the notification

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/tify_example/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/tify_example/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/tify_example/blob/master/LICENSE) included with the source code for a copy of the license terms.
