<?php

namespace App\DataFixtures;

use DateTime;
//use Faker\Factory;
use Faker\Generator;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR'); // ANTISLASH( \ ) devant pour indiquer le NAMESPACE GLOBAL

        // Créer 3 catégorie fake
        for ($i=1 ; $i <= 3 ; $i++)
        {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Créer 4 et 6 articles
            for ($j=1 ; $j <= mt_rand(4, 6) ; $j++)
            {
            $article = new Article(); 
            $content = '<p>' . join( $faker->paragraphs(5), '</p><p>' ) . '</p>';
            $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

            $manager->persist($article);

                // Créer 4 a 10 commentaires
                for($k = 1; $k <= mt_rand(4, 10); $k++)
                {
                    $comment = new Comment();
                    $content = '<p>' . join( $faker->paragraphs(2), '</p><p>' ) . '</p>'; 

                    $days = ((new \DateTime())->diff($article->getCreatedAt()))->days; // -100 days
                    
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
