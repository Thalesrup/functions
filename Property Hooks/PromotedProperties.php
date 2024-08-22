<?php

readonly class User
{
    public string $fullName {
        get => $this->firstName.' '.$this->lastName;
    }
 
    public string $firstName {
        set(string $name) => ucfirst($name);
    }
 
    public string $lastName {
        set(string $name) => ucfirst($name);
    }
 
    public function __construct(
        string $firstName,
        string $lastName,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}

$user = new User('thales', 'ruppenthal');

echo $user->fullName;
