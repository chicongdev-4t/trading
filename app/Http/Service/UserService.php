<?php

namespace App\Http\Service;

use App\Models\User;

class UserService
{
    public function validateUserId(int $userId) {
        $user = User::query()->find($userId);
        if(!$user) {
            throw new \Exception('userId not found');
        }

        return $user;
    }

}
