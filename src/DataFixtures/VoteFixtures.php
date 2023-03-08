<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Vote;
use App\DataFixtures\CommentFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VoteFixtures extends Fixture implements DependentFixtureInterface
{
    const DATA = [
        [
            'userId'    => 1,
            'commentId' => 3,
            'value'     => -1,
            'date'      => '2023-02-03 13:10:20',
        ],
        [
            'userId'    => 2,
            'commentId' => 4,
            'value'     => 1,
            'date'      => '2023-02-03 13:40:20',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $commentRepository = $manager->getRepository(Comment::class);
        $userRepository = $manager->getRepository(User::class);

        foreach (self::DATA as $voteData) {
            $vote = new Vote();

            $comment = $commentRepository->find($voteData['commentId']);
            if (!$comment)
                throw new \Exception($voteData['commentId'] . ' bad comment id', 1);
            $vote->setValue($voteData['value']);
            $vote->setComment($comment);

            if ($voteData['date'])
                $vote->setCreationDate(new \DateTime($voteData['date']));
            else
                $vote->initCreationDate();
            $creator = $userRepository->find($voteData['userId']);
            if (!$creator)
                throw new \Exception($voteData['userId'] . ' bad user id', 1);
            $vote->setCreatedByUser($creator);

            $manager->persist($vote);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CommentFixtures::class,
            UserFixtures::class,
        ];
    }
}
