<?php 

    $data = shell_exec("cd /home && ls");
    print("<pre>$data</pre>");