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
        $serviceDesks = \Model\ServiceDesk::with(['balienummer'])->all();
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
            View::new()->render('views/templates/service-desk/service-desk-login.php', ['errors' => $this->errors]);
            return;
        }

        $user = ['id' => (int) $deskId, 'role' => 'service_desk'];

        Session::instance()->set('user', $user);
        Redirect::to('/service-desk/dashboard');
    }

    public function dashboard(): void
    {
//        \Model\Flight::create([
//            'bestemming' => 'AMS',
//            'gatecode' => 'B',
//            'max_aantal' => '160',
//            'max_gewicht_pp' => '40.00',
//            'max_totaalgewicht' => '4600.00',
//            'vertrektijd' => '2024-06-08 12:00:00',
//            'maatschappijcode' => 'KL',
//        ]); // create

        $user = auth()->user();
        View::new()->render('views/templates/service-desk/service-desk-dashboard.php', compact('user'));
    }

}
