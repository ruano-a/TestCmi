<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    const DATA = [
        [
            'title'         => 'What is a website?',
            'content'       => 'Useless blabla',
            'date'          => '2023-02-03 18:40:50',
            'creatorMail'   => 'superadmin@gmail.com'
        ],
        [
            'title'         => 'The egg or the chicken?',
            'content'       => null, // null => generate it
            'date'          => '2023-01-04 19:42:55',
            'creatorMail'   => 'admin@gmail.com'
        ],
    ];

    protected function generateContent(array $articleData): string
    {
        $content = '';

        for ($i = 0; $i < 40; $i++) { 
            $content .= $articleData['title'];
        }

        return $content;
    }

    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        foreach (self::DATA as $articleData) {
            $article = new Article();

            $article->setTitle($articleData['title']);
            if ($articleData['content'])
                $article->setContent($articleData['content']);
            else
                $article->setContent($this->generateContent($articleData));
            if ($articleData['date'])
                $article->setCreationDate(new \DateTime($articleData['date']));
            else
                $article->initCreationDate();
            $creator = $userRepository->findOneByEmail($articleData['creatorMail']);
            if (!$creator)
                throw new \Exception($articleData['creatorMail'] . ' bad email', 1);
            $article->setCreatedByUser($creator);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
