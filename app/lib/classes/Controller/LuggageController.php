<?php

namespace Controller;

use Enums\CRUDAction;
use JetBrains\PhpStorm\NoReturn;
use Model\Luggage;
use Model\Passenger;
use Service\Error;
use Service\Redirect;
use Service\View;
use Util\StringHelper;

class LuggageController
{
    private array $errors = [];

    public function passenger($id): void
    {
        PassengerController::validate($id);
        $passenger = Passenger::where('passagiernummer', '=', $id)->first();
        $luggages = Luggage::with(['objectvolgnummer', 'gewicht'])->where('passagiernummer', '=', $id)->all();

        View::new()->render('views/templates/passenger/passenger-luggage.php', compact('passenger', 'luggages'));
    }

    public function show($id, $followId)
    {
        PassengerController::validate($id);

        $luggage = Luggage::where('passagiernummer', '=', $id)->where('objectvolgnummer', '=', $followId)->first();
        View::new()->render('views/templates/luggage/luggage.php', compact('luggage'));
    }

    public function add($id): void
    {
        PassengerController::validate($id);

        $passenger = Passenger::find($id);
        if (!$passenger) {
            Redirect::to('/passagiers');
        }

        View::new()->render('views/templates/luggage/luggage-add.php', compact('passenger'));
    }

    public function addLuggage($id): void
    {
        PassengerController::validate($id);

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
        PassengerController::validate($id);

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
        PassengerController::validate($id);

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

    #[NoReturn] public function delete($id, $followId): void
    {
        PassengerController::validate($id);

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
