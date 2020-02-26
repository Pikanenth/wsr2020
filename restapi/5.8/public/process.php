<?php 

    $data = shell_exec("cd .. && php artisan make:controller AuthController");
    
    print("<pre>$data</pre>");