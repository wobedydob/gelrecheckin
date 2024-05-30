<?php

namespace Service;

class Form
{

    const ACTION_SIGNUP = 'signup';
    const ACTION_LOGIN = 'login';

    public function __construct()
    {
        if (isset($_POST['submit'])) {

            $action = $this->getAction();
            if ($action) {

                switch ($action) {

                    case self::ACTION_LOGIN:
                        (new LoginController())->login();
                        break;

                }

            }

        }
    }

    public static function new(): Form
    {
        return new self();
    }

    public function getAction()
    {
        if (isset($_POST['action']) && !empty($_POST['action'])) {
            return $_POST['action'];
        }

        return false;
    }

}