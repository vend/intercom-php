Intercom is a customer relationship management and messaging tool for web app owners
  
This library provides connectivity with the Intercom API (https://api.intercom.io)
  
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