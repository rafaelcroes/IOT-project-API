<?php

use App\Database;

return [
    Database::class => function(){
        return new Database(host: 'host',
                            dbname:  'dbname',
                            user: 'user',
                            password: 'password');
    }
];
