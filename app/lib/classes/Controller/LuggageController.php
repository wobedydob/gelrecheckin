<?php

namespace Controller;

use Enums\CRUDAction;
use JetBrains\PhpStorm\NoReturn;
use Model\Flight;
use Model\Luggage;
use Model\Passenger;
use Model\ServiceDesk;
use Service\Error;
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
        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        View::new()->render('views/templates/luggage/luggage-add.php', compact('passenger'));
    }

    public function addLuggage($id): void
    {
        $passenger = Passenger::find($id);
        if (!$passenger) {
            Redirect::to('/passagiers');
        }
        $_POST['passenger_id'] = $id;

        $post = $this->handlePost(CRUDAction::ACTION_CREATE);

        $post['passenger_id'] = $id;
        $post['follow_id'] = Luggage::nextFollowId($id);

        if ($post) {

            if(empty($this->errors)) {
                $action = Luggage::create([
                    'passagiernummer' => $id,
                    'objectvolgnummer' => $post['follow_id'],
                    'gewicht' => $post['weight'],
                ]);

                if ($action) {
                    $this->errors['success'] = 'Bagage succesvol toegevoegd';
                    Redirect::to('/passagiers/' . $id);
                } else {
                    $this->errors['error'] = 'Bagage kon niet worden toegevoegd';
                }
            }

            $luggage = new Luggage();
            $luggage->passagiernummer = $id;
            $luggage->gewicht = $post['weight'];

            View::new()->render('views/templates/luggage/luggage-add.php', compact('passenger', 'luggage'));
        }

        Error::set($this->errors);
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

        $_POST['passenger_id'] = $id;
        $_POST['follow_id'] = $followId;

        $post = $this->handlePost(CRUDAction::ACTION_CREATE);
        if($post) {

            if(empty($this->errors)) {
                Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->update([
                    'passagiernummer' => $id,
                    'objectvolgnummer' => $followId,
                    'gewicht' => $post['weight'],
                ]);
                Redirect::to('/passagiers/' . $id);
            }

            $luggage = new Luggage();
            $luggage->passagiernummer = $id;
            $luggage->objectvolgnummer = $followId;
            $luggage->gewicht = $post['weight'];
            View::new()->render('views/templates/luggage/luggage-edit.php', compact('passenger', 'luggage'));
        }
        Error::set($this->errors);
    }

    public function eeeeditLuggage($id, $followId): void
    {
        $passenger = Passenger::find($id);
        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        $luggage = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->first();
        if (!$luggage) {
            Redirect::to('/passagiers/' . $id);
        }

        $post = $this->handlePost(CRUDAction::ACTION_UPDATE);
        $action = false;

        if (!empty($this->errors)) {
            View::new()->render('views/forms/luggage-edit-form.php', ['errors' => $this->errors]);
            return;
        }

        if ($post) {

            $action = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->update([
                'passagiernummer' => $id,
                'objectvolgnummer' => $followId,
                'gewicht' => $post['weight'],
            ]);

        }

        if (!$action) {
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

    public function handlePost(CRUDAction $action): array
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

        if ($action == CRUDAction::ACTION_CREATE) {
            $post['follow_id'] = Luggage::nextFollowId($passengerId);
        }

        if ($action == CRUDAction::ACTION_UPDATE) {
            $post['follow_id'] = $followId;
        }

        $this->checkCapacity($passengerId, $followId, $weight);
        return $post;
    }

    function checkCapacity(int $passengerId, string $followId, string $weight): void
    {
        if (Passenger::exceedsWeightLimit($passengerId, $followId, $weight)) {
            $this->errors['weight'] = 'Het gewicht van het bagage is te groot.';
        }
    }

}
