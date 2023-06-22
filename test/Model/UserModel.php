<?php

use ErnandesRS\EasyCrud\EasyCrud;

class UserModel extends EasyCrud
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct("users", "id");
    }

    /**
     * Find By E-mail
     *
     * @param string $email
     * @param string $fields
     * @return null|\ErnandesRS\EasyCrud\EasyCrud
     */
    public function findByEmail(string $email, string $fields = "*")
    {
        return $this->where("email", "=", $email)->getOne($fields);
    }
}