Intercom is a customer relationship management and messaging tool for web app owners

This library provides connectivity with the Intercom API (https://api.intercom.io)

[![Build Status](https://travis-ci.org/nubera-ebusiness/intercom-php.png?branch=master)](https://travis-ci.org/nubera-ebusiness/intercom-php)

## License

Copyright 2013 Nubera eBusiness S.L. All rights reserved.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to
deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
sell copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
IN THE SOFTWARE.

## Basic usage:

### Configure Intercom with your access credentials

```php
<?php
$intercom = new Intercom('dummy-app-id', 'dummy-api-key');
?>
```

### Get all users

```php
<?php
$intercom = new Intercom('dummy-app-id', 'dummy-api-key');

$users = $intercom->getAllUsers();
var_dump($users);
?>
```

### Create a new user

```php
<?php
$intercom = new Intercom('dummy-app-id', 'dummy-api-key');

$res = $intercom->createUser('userId001', 'email@example.com');
?>
```

### Create a new impression

```php
<?php
$intercom = new Intercom('dummy-app-id', 'dummy-api-key');

$res = $intercom->createImpression('userId001');
?>
```
