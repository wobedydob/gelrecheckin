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
        $serviceDesks = ServiceDesk::with(['balienummer'])->all();
        $this->authenticate();
        View::new()->render('views/templates/service-desk/service-desk-login.php', compact('serviceDesks'));
    }

    public function authenticate(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $deskId = $post['desk_id'] ? \Util\StringHelper::sanitize($post['desk_id']) : false;
        $password = $post['password'] ?: false;

        $serviceDesk = ServiceDesk::with(['wachtwoord'])->where('balienummer', '=', $deskId)->first();
        $verify = password_verify($password, $serviceDesk->wachtwoord);
        $secondVerify = ServiceDesk::where('balienummer', '=', $deskId)->where('wachtwoord', '=', $password)->exists(); // only because the given database has not used any hashed passwords... stupid han sometimes...

        if (!$verify && !$secondVerify) {
            $this->errors['error'] = 'invalid credentials';
        }

        if (!$deskId) {
            $this->errors['desk_id'] = 'none given';
        }

        if (!$password) {
            $this->errors['password'] = 'none given';
        }

        if (!empty($this->errors)) {
            View::new()->render('views/templates/service-desk/service-desk-login.php', ['errors' => $this->errors]);
            return;
        }

        $user = ['id' => (int)$deskId, 'role' => 'service_desk'];

        Session::instance()->set('user', $user);
        Redirect::to('/dashboard');
    }

}
