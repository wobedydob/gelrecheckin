<?php

namespace Controller;

use Model\ServiceDesk;
use Service\Redirect;
use Service\Session;
use Service\View;

class ServiceDeskController
{

    public function login(): void
    {
        View::render('views/templates/login/service-desk-login.php');
    }

    public function authenticate(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $deskId = $post['desk_id'] ? \Util\StringHelper::sanitize($post['desk_id']) : false;
        $password = $post['password'] ?: false;

        if (!$deskId) {
            $this->errors['desk_id'] = 'none given';
        }

        if (!$password) {
            $this->errors['password'] = 'none given';
        }

        if (!ServiceDesk::where('balienummer', '=', $deskId)->where('wachtwoord', '=', $password)->exists()) {
            $this->errors['error'] = 'invalid credentials';
        }

        if(!empty($this->errors)) {
            View::render('views/templates/login/service-desk-login-form.php', ['errors' => $this->errors]);
            return;
        }

        $user = ['id' => (int) $deskId, 'role' => 'service_desk'];

        Session::instance()->set('user', $user);
        Redirect::to('/service-desk/dashboard');
    }

    public function dashboard(): void
    {
        View::render('views/templates/dashboard/service-desk-dashboard.php');
    }

}
