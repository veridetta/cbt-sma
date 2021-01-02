<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'sekolah' => ['required', 'string', 'max:255'],
            'hp' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'va' => [ 'string', 'max:255'],
            'my_ref' => [ 'string', 'max:255'],
            'friend_ref' => ['nullable','string', 'max:255'],
            'role' => [ 'string', 'max:255'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'sekolah' => $input['sekolah'],
            'hp' => $input['hp'],
            'va' => '',
            'my_ref' => '',
            'friend_ref' => $input['friend_ref'],
            'role' => 'user',
        ]);
    }
}
