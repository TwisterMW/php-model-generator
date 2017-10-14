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

Then you only need to run the 'generator/processor.php' script file and it will output a 'models' folder with all models (folder-separated) and with each model class file implementing the CRUD actions of the DataModel interface (located on 'core/').

Every time you modify your database you can run again the processor in order to update your DataModels, because they are all isolated from the logic of the backend and the API.