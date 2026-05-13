<?php

namespace App\DataFixtures;

use App\Entity\Kudos;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){}


    public function load(ObjectManager $manager): void
    {
        $userData = 
        [
            ['firstName' => 'Alice',
             'lastName' => 'Smith',
             'email' => 'alice12@gmail.com',
             'username' => 'alice123',
             'profilePic' => 'alice.jpg'],

            ['firstName' => 'Bob',
             'lastName' => 'Jones',
             'email' => 'bjones@gmail.com',
             'username' => 'bobby_j',
             'profilePic' => 'bob.jpg'],

            ['firstName' => 'Carol',
             'lastName' => 'White',
             'email' => 'cwhite@gmail.com',
             'username' => 'carol_w',
             'profilePic' => 'carol.jpg',]
        ];

        $users = [];
        foreach($userData as $data)
        {
            $user = new User();
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setUsername($data['username']);
            $user->setProfilePic($data['profilePic']);
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);
            $user->setPassword(
                $this->hasher->hashPassword($user, 'pass_1234')
            );

            $manager->persist($user);
            $users[$data['username']]= $user;
        }

        $kudosData = 
        [
            ['sender' => 'alice123',
             'receiver' => 'bobby_j',
             'msgContent' => 'Bob crushed that client presentation today, incredible work, Bob!!!',
             'createdAt' => new \DateTime('2026-05-11 09:00:00')],

             ['sender' => 'carol_w',
             'receiver' => 'alice123',
             'msgContent' => 'Alice helped me debug for 2 hours, absolute legend!',
             'createdAt' => new \DateTime('2026-05-11 10:30:00')]

        ];

        foreach($kudosData as $data)
        {
            $kudos = new Kudos();
            $kudos->setSender($users[$data['sender']]);
            $kudos->setReceiver($users[$data['receiver']]);
            $kudos->setMsgContent($data['msgContent']);
            $kudos->setCreatedAt($data['createdAt']);
           
            $manager->persist($kudos);
            
        }


        $manager->flush();
    }
}
