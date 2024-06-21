<?php

class Sequrityy

{
    public function hash (string $password): string {

        return password_hash($password, PASSWORD_BCRYPT);

    }

}



?>