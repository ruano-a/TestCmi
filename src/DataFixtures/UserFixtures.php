<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const DATA = [
        [
            'email'     => 'superadmin@gmail.com',
            'username'  => 'superadmin',
            'roles'     => ['ROLE_SUPER_ADMIN'],
            'password'  => 'superadminpassword'
        ],
        [
            'email'     => 'admin@gmail.com',
            'username'  => 'admin',
            'roles'     => ['ROLE_ADMIN'],
            'password'  => 'adminpassword'
        ],
        [
            'email'     => 'user@gmail.com',
            'username'  => 'user',
            'roles'     => [],
            'password'  => 'userpassword'
        ],
    ];

    public function __construct(protected UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $userData) {
            $user = new User();

            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setRoles($userData['roles']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userData['password']
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
