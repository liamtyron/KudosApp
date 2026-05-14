<?php

namespace App\DataFixtures;

use App\Entity\Kudos;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){}


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        // $userData = 
        // [
        //     ['firstName' => 'Alice',
        //      'lastName' => 'Smith',
        //      'email' => 'alice12@gmail.com',
        //      'username' => 'alice123',
        //      'profilePic' => 'alice.jpg'],

        //     ['firstName' => 'Bob',
        //      'lastName' => 'Jones',
        //      'email' => 'bjones@gmail.com',
        //      'username' => 'bobby_j',
        //      'profilePic' => 'bob.jpg'],

        //     ['firstName' => 'Carol',
        //      'lastName' => 'White',
        //      'email' => 'cwhite@gmail.com',
        //      'username' => 'carol_w',
        //      'profilePic' => 'carol.jpg',]
        // ];

        // foreach($userData as $data)
        // {
        //     $user = new User();
        //     $user->setFirstName($data['firstName']);
        //     $user->setLastName($data['lastName']);
        //     $user->setEmail($data['email']);
        //     $user->setUsername($data['username']);
        //     $user->setProfilePic($data['profilePic']);
        //     $user->setRoles(['ROLE_USER']);
        //     $user->setIsVerified(true);
        //     $user->setPassword(
        //         $this->hasher->hashPassword($user, 'pass_1234')
        //     );

        //     $manager->persist($user);
        //     $users[$data['username']]= $user;
        // }
        
        $users = [];
        for($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());
            $user->setUsername($faker->unique()->userName());
            $user->setProfilePic("https://picsum.photos" . $faker->word . "/200");
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);
            $user->setPassword(
                $this->hasher->hashPassword($user, 'pass_1234')
            );

            $manager->persist($user);
            $users[] = $user;
        }

        // $kudosData = 
        // [
        //     ['sender' => 'alice123',
        //      'receiver' => 'bobby_j',
        //      'msgContent' => 'Bob crushed that client presentation today, incredible work, Bob!!!',
        //      'createdAt' => new \DateTime('2026-05-11 09:00:00')],

        //      ['sender' => 'carol_w',
        //      'receiver' => 'alice123',
        //      'msgContent' => 'Alice helped me debug for 2 hours, absolute legend!',
        //      'createdAt' => new \DateTime('2026-05-11 10:30:00')]

        // ];

        // foreach($kudosData as $data)
        // {
        //     $kudos = new Kudos();
        //     $kudos->setSender($users[$data['sender']]);
        //     $kudos->setReceiver($users[$data['receiver']]);
        //     $kudos->setMsgContent($data['msgContent']);
        //     $kudos->setCreatedAt($data['createdAt']);
           
        //     $manager->persist($kudos);
            
        // }

        for($i = 0; $i < 20; $i++)
        {
            $kudos = new Kudos();

            $sender = $faker->randomElement($users);
            $receiver = $faker->randomElement($users);

            $kudos->setSender($sender);
            $kudos->setReceiver($receiver);
            $kudos->setMsgContent($faker->realText(100));
            $kudos->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));
           
            $manager->persist($kudos);
            
        }


        $manager->flush();
    }
}
