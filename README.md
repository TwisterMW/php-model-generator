# [DEV-TOOL] PHP MODEL GENERATOR FROM SQL DATABASE
In order to save time generating our data models based on our database structures and mantain a DAL separated from the API you can use this generator.

## Using
In order to use it, first you need to configure your database constant values on 'core/db-config.php':
```php
    define("DB_NAME", "");
    define("DB_HOST", "");
    define("DB_USER", "");
    define("DB_PASS", "");
```

Then you only need to run the 'generator/processor.php' script file on an Apache server and it will output a 'models' folder with all models (folder-separated) with each model class file implementing the CRUD actions of the DataModel interface (located on 'core/').

Every time you modify your database you can run again the processor in order to update your DataModels, because they are all isolated from the logic of the backend and the API.

## The DataModel Interface
In order to perform the CRUD actions there is an interface declared for each model generated.
The methods of the interface are expecting for extra parameters in order to generate the proper queries to database.

This parameters will be stored on an associative array with two available keys (all optional):

    -id (in case of read, update, delete)
    
    -data (in case of create, update)

Then the arguments that the constructor of the DataModel is expecting are the CRUD action to be performed and the extra parameters.

## Defining a webservice
Once you've generated your models you can create a 'ws' folder with the webservice files of your backend.
Then in these files you can instantiate your models and perform the CRUD actions in order to manage data with your Front-End application.

(Example of WS file)
```php
    require '../models/YourModel.php;

    $params = array("id" => 1)
    $yourModel = new YourModel("read", $params);
```

In the example above we've performed a READ action to a desired DataModel, and once we've instantiated the data model we can obtain any of it's properties according to the database structure.

Then we can return to the Front-End any structure of composed data like this:
```php
    print_r(
        json_encode(
            "prop1" => $yourModel->prop1,
            "prop2" => $yourModel->prop2,
            "prop3" => $yourModel->prop3
        )
    )
```

## Annotations
The generator is developed assuming the following principles:

    - The PRIMARY_KEY of each table should be named 'id'

    - The names of the tables should be written in lowercase and using Delimiter-separated words with underscore (_)