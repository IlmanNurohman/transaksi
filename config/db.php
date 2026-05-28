<?php

return [
    'class' => \yii\db\Connection::class,
     'dsn' => 'pgsql:host=localhost;port=5432;dbname= my_life',
    'username' => 'postgres',
    'password' => 'iman1220',
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql' => [
            'class' => 'yii\db\pgsql\Schema',
            'defaultSchema' => 'public' //specify your schema here
        ]
    ],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];