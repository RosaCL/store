<?php
    $db_name = 'mysql:host=localhost; dbname=store';
    $user = 'root';
    $pass = '';

    $conn = new PDO($db_name, $user, $pass);

    function create_unique_id() {
        $characters = '0123456789abcdefghijlmnopqrstuvxzykABCDEFGHIJLMNOPQRSTUVXZYK';
        $characters_length = strlen($characters);
        $random_string = '';

        for ($i = 0; $i < 20; $i++) {
            $random_string .= $characters[mt_rand(0, $characters_length - 1)];
        }
        return $random_string;
    }

    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        $user_id = create_unique_id();
        setcookie('user_id', $user_id, time() + 60*60*24*30);
    }
?>