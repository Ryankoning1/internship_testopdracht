<?php

namespace App\Tests\Account\Helper;

trait AuthenticationHelper
{
    public array $accounts = [
        'admin' => [
            'email' => 'admin@admin.nl',
            'password' => 'Admin123!',
            'roles' => ['ROLE_ADMIN', 'ROLE_USER'],
            'firstName' => 'Admin',
            'lastName' => 'User',
        ],
        'user' => [
            'email' => 'user@user.nl',
            'password' => 'User123!',
            'roles' => ['ROLE_USER'],
            'firstName' => 'Test',
            'lastName' => 'User',
        ],
    ];

    public function getAdminAuthentication(): array
    {
        return [
            'PHP_AUTH_USER' => $this->accounts['admin']['email'],
            'PHP_AUTH_PW'   => $this->accounts['admin']['password'],
        ];
    }

    public function getUserAuthentication(): array
    {
        return [
            'PHP_AUTH_USER' => $this->accounts['user']['email'],
            'PHP_AUTH_PW'   => $this->accounts['user']['password'],
        ];
    }
}
