<?php

namespace Controller;

use JetBrains\PhpStorm\NoReturn;
use Model\Luggage;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Redirect;
use Service\View;
use Util\StringHelper;

class LuggageController
{
    private array $errors = [];

    public function handle(): void
    {
        $role = auth()->user()->getRole();
        if ($role === Passenger::USER_ROLE) {
            $this->passenger();
        } elseif ($role === ServiceDesk::USER_ROLE) {
            $this->serviceDesk();
        } else {
            Redirect::to('/logout');
        }
    }

    public function serviceDesk(): void
    {

//        (new ServiceDeskController())->luggages();
    }

    public function passenger(): void
    {
        $user = auth()->user();
        View::new()->render('views/templates/passenger/passenger-luggage.php', compact('user'));
    }

    public function show($id, $followId)
    {
        $luggage = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->first();
        View::new()->render('views/templates/luggage/luggage.php', compact('luggage'));
    }

    public function add($id): void
    {
        $passenger = Passenger::find($id);
        if(!$passenger) {
            Redirect::to('/passagiers');
        }

        View::new()->render('views/templates/luggage/luggage-add.php', compact('passenger'));
    }

    public function addLuggage($id): void
    {
        $passenger = Passenger::find($id);
        if(!$passenger) {
            Redirect::to('/passagiers');
        }

        $post = $this->handlePost();
        $action = false;

        if (!empty($this->errors)) {
            View::new()->render('views/templates/luggage/luggage-add.php', ['errors' => $this->errors]);
            return;
        }

        if($post) {

            $action = Luggage::create([
                'passagiernummer' => $id,
                'gewicht' => $post['weight'],
            ]);

        }

        if(!$action) {
            $error = ['errors' => 'Luggage could not be added'];
            View::new()->render('views/templates/luggage/luggage-add.php', $error);
        }

        Redirect::to('/passagiers/' . $id);
    }

    public function edit($id, $followId): void
    {
        $passenger = Passenger::find($id);
        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        $luggage = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->first();
        if (!$luggage) {
            Redirect::to('/passagiers/' . $id);
        }

        View::new()->render('views/templates/luggage/luggage-edit.php', compact('luggage', 'passenger'));
    }

    public function editLuggage($id, $followId): void
    {
        $passenger = Passenger::find($id);
        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        $luggage = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->first();
        if (!$luggage) {
            Redirect::to('/passagiers/' . $id);
        }

        $post = $this->handlePost();
        $action = false;

        if (!empty($this->errors)) {
            View::new()->render('views/forms/luggage-edit-form.php', ['errors' => $this->errors]);
            return;
        }

        if($post) {

            $action = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->update([
                'passagiernummer' => $id,
                'objectvolgnummer' => $followId,
                'gewicht' => $post['weight'],
            ]);

        }

        if(!$action) {
            $error = ['errors' => 'Luggage could not be edited'];
            View::new()->render('views/templates/luggage/luggage-edit.php', $error);
        }

        Redirect::to('/passagiers/' . $id);
    }

    #[NoReturn] public function delete($id, $followId): void
    {
        Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->delete();
        Redirect::to('/passagiers/' . $id);
    }

    public function handlePost(): array
    {
        $post = [];

        if (!isset($_POST['submit'])) {
            return $post;
        }

        $passengerId = $_POST['passenger_id'] ? $post['passenger_id'] = StringHelper::sanitize($_POST['passenger_id']) : false;
        $followId = isset($_POST['follow_id']) ? $post['follow_id'] = StringHelper::sanitize($_POST['follow_id']) : false;
        $weight = $_POST['weight'] ? $post['weight'] = StringHelper::sanitize($_POST['weight']) : false;

        if (!$passengerId) {
            $this->errors['passenger_id'] = 'none given';
        }

        if (!isset($followId)) {
            $this->errors['follow_id'] = 'none given';
        }

        if (!$weight) {
            $this->errors['weight'] = 'none given';
        }

        return $post;
    }

}
