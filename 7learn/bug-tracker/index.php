<?php

$sql = "SELECT * FROM users WHERE id = 1";

$orm->table('users')->select('*')->where('id', 1);
$orm->table('users')->where('name', 'Reza')->delete();

$orm->table('users')->where('name', 'Reza')->update([
    'email' => 'seyedrezabazyar@gmail.com',
    'name' => 'Seyed Reza'
]);
