<?php

use App\Database;

return [
    Database::class => function(){
        return new Database(host: 'ip_adress',
                            dbname:  'idbname',
                            user: 'username',
                            password: 'password');
    }
];

