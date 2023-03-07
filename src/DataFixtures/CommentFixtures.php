<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\DataFixtures\ArticleFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    const DATA = [
        [
            'articleId'                 => 1, // could have been done with the title to be more clear, but it's weird
            'content'                   => 'Useless blabla',
            'childOfPreviousComment'    => false,
            'date'                      => '2023-02-03 13:10:20',
            'creatorMail'               => 'user@gmail.com'
        ],
        [
            'articleId'                 => 2, // could have been done with the title to be more clear, but it's weird
            'content'                   => 'What is love?',
            'childOfPreviousComment'    => false,
            'date'                      => '2023-02-04 14:11:22',
            'creatorMail'               => 'admin@gmail.com'
        ],
        [
            'articleId'                 => 2, // could have been done with the title to be more clear, but it's weird
            'content'                   => 'Baby don\'t hurt me',
            'childOfPreviousComment'    => true,
            'date'                      => '2023-02-05 15:12:23',
            'creatorMail'               => 'admin@gmail.com'
        ],
        [
            'articleId'                 => 2, // could have been done with the title to be more clear, but it's weird
            'content'                   => 'Don\'t hurt me',
            'childOfPreviousComment'    => true,
            'date'                      => '2023-02-06 16:13:34',
            'creatorMail'               => 'admin@gmail.com'
        ],
        [
            'articleId'                 => 2, // could have been done with the title to be more clear, but it's weird
            'content'                   => 'No more',
            'childOfPreviousComment'    => true,
            'date'                      => '2023-02-07 17:14:45',
            'creatorMail'               => 'admin@gmail.com'
        ],
        [
            'articleId'                 => 2, // could have been done with the title to be more clear, but it's weird
            'content'                   => 'Random comment',
            'childOfPreviousComment'    => false,
            'date'                      => '2023-02-07 17:14:49',
            'creatorMail'               => 'admin@gmail.com'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $articleRepository = $manager->getRepository(Article::class);
        $userRepository = $manager->getRepository(User::class);
        $previousComment = null;

        foreach (self::DATA as $commentData) {
            $comment = new Comment();

            $article = $articleRepository->find($commentData['articleId']);
            if (!$article)
                throw new \Exception($commentData['articleId'] . ' bad id', 1);
            $comment->setText($commentData['content']);
            $comment->setArticle($article);
            if ($commentData['childOfPreviousComment'])
                $comment->setParentComment($previousComment);

            if ($commentData['date'])
                $comment->setCreationDate(new \DateTime($commentData['date']));
            else
                $comment->initCreationDate();
            $creator = $userRepository->findOneByEmail($commentData['creatorMail']);
            if (!$creator)
                throw new \Exception($commentData['creatorMail'] . ' bad email', 1);
            $comment->setCreatedByUser($creator);

            $manager->persist($comment);
            $previousComment = $comment;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ArticleFixtures::class,
        ];
    }
}
