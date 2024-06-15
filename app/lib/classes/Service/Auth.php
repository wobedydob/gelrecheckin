<?php

namespace Service;

use Entity\User;
use Model\Passenger;
use Model\ServiceDesk;

class Auth
{

    /**
     * Checks if a user is authenticated.
     *
     * @return bool Returns true if the user is authenticated, false otherwise.
     */
    public function isAuthenticated(): bool
    {
        return Session::instance()->has('user');
    }

    /**
     * Checks if a user is a guest (not authenticated).
     *
     * @return bool Returns true if the user is a guest, false otherwise.
     */
    public function guest(): bool
    {
        return !$this->isAuthenticated();
    }

    /**
     * Retrieves the authenticated user along with associated model based on role.
     *
     * @return User|null Returns a User object if authenticated with associated model, null otherwise.
     */
    public function user(): ?User
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $user = Session::instance()->get('user');
        $role = $user['role'] ?? false;
        $model = null;

        switch($role) {
            case Passenger::USER_ROLE:
                $model = Passenger::with(['naam', 'vluchtnummer', 'geslacht', 'balienummer', 'stoel', 'inchecktijdstip'])->where('passagiernummer', '=', $user['id'])->first();
                break;
            case ServiceDesk::USER_ROLE:
                $model = ServiceDesk::with(['balienummer'])->where('balienummer', '=', $user['id'])->first();
                break;
        }

        return new User($user['id'], $user['role'], $model);
    }

    /**
     * Checks if the authenticated user has a specific role.
     *
     * @param string $role The role to check against.
     * @return bool|null Returns true if the user has the specified role, false if not, null if not authenticated.
     */
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

    /**
     * Checks if the authenticated user has any of the specified roles.
     *
     * @param array $roles Array of roles to check against.
     * @return bool|null Returns true if the user has any of the specified roles, false if not, null if not authenticated.
     */
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