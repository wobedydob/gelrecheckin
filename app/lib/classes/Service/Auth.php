<?php

namespace Service;

use Entity\Passenger;
use Entity\ServiceDesk;
use Entity\User;

class Auth
{

    public function isAuthenticated(): bool
    {
        return Session::instance()->has('user');
    }

    public function guest(): bool
    {
        return !$this->isAuthenticated();
    }

    public function user(): ?User
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $user = Session::instance()->get('user');
        $role = $user['role'] ?? false;
        $model = null;

        switch($role) {
            case \Model\Passenger::USER_ROLE:
                $model = \Model\Passenger::with(['naam', 'vluchtnummer', 'geslacht', 'balienummer', 'stoel', 'inchecktijdstip'])->where('passagiernummer', '=', $user['id'])->first();
                break;
            case \Model\ServiceDesk::USER_ROLE:
                $model = \Model\ServiceDesk::with(['balienummer'])->where('balienummer', '=', $user['id'])->first();
                break;
        }

        return new User($user['id'], $user['role'], $model);
    }

    public function withRole(string $role): ?bool
    {
        $user = $this->user();
        if (!$user) {
            return null;
        }

        if ($user->getRole() === $role) {
            return true;
        }

        return false;
    }

    public function withRoles(array $roles): ?bool
    {
        $user = $this->user();
        if (!$user) {
            return null;
        }

        if (in_array($user->getRole(), $roles)) {
            return true;
        }

        return false;
    }

}