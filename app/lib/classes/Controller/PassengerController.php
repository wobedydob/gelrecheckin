<?php

namespace Controller;

use Model\Passenger;
use Model\ServiceDesk;
use Service\Redirect;
use Service\Session;
use Service\View;

class PassengerController
{

    public function login(): void
    {
        $this->authenticate();
        View::new()->render('views/templates/passenger/passenger-login.php');
    }

    public function authenticate(): void
    {
        $post = $_POST;

        if (!isset($post['submit'])) {
            return;
        }

        $passengerId = $post['passenger_id'] ? \Util\StringHelper::sanitize($post['passenger_id']) : false;
        $password = $post['password'] ?: false;

        if (!$passengerId) {
            $this->errors['passenger_id'] = 'none given';
        }

        if (!$password) {
            $this->errors['password'] = 'none given';
        }

        if (!Passenger::where('passagiernummer', '=', $passengerId)->where('wachtwoord', '=', $password)->exists()) {
            $this->errors['error'] = 'invalid credentials';
        }

        if(!empty($this->errors)) {
            View::new()->render('views/templates/login/service-desk-login-form.php', ['errors' => $this->errors]);
            return;
        }

        $user = ['id' => (int) $passengerId, 'role' => 'passenger'];

        Session::instance()->set('user', $user);
        Redirect::to('/dashboard');
    }

    public function dashboard(): void
    {
        $user = auth()->user();
        $id = $user['id'];

        $passenger = Passenger::with(['passagiernummer', 'naam', 'geslacht'])->where('passagiernummer', '=', $id)->first();

        View::new()->render('views/templates/passenger/passenger-dashboard.php', compact('passenger'));
    }

}
