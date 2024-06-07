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

    public function user()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $user = Session::instance()->get('user');
        $role = Session::instance()->get('role');

        switch($role) {
        }

        return new User(...$user);
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