<?php

namespace Service;

class Auth
{

    public function isAuthenticated(): bool
    {
        return Session::instance()->has('user');
    }

    public function user()
    {
        return $this->isAuthenticated() ? Session::instance()->get('user') : null;
    }

}