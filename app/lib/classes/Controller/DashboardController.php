<?php

namespace Controller;

use Model\Passenger;
use Model\ServiceDesk;
use Service\Redirect;
use Service\View;

class DashboardController
{

    public function handle(): void
    {
        $role = auth()->user()->getRole();

        if($role === Passenger::USER_ROLE) {
            $this->passenger();
        } elseif($role === ServiceDesk::USER_ROLE) {
            $this->serviceDesk();
        } else {
            Redirect::to('/logout');
        }
    }

    public function serviceDesk(): void
    {
        $user = auth()->user();
        View::new()->render('views/templates/service-desk/service-desk-dashboard.php', compact('user'));
    }

    public function passenger(): void
    {
        $user = auth()->user();
        View::new()->render('views/templates/passenger/passenger-dashboard.php', compact('user'));
    }

}
