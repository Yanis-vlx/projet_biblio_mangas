<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use App\Entity\Author;
use App\Entity\Manga;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'un éditeur
        $editor = new Editor();
        $editor->setName('Kana');
        $manager->persist($editor);

        // Création d'un auteur
        $author = new Author();
        $author->setName('Eiichiro Oda');
        $manager->persist($author);

        // Création d'un manga
        $manga = new Manga();
        $manga->setTitle('One Piece');
        $manga->setEditor($editor);
        $manga->addAuthor($author);
        $manager->persist($manga);
        $author->setNationality('Japonais');
        

        // Envoi en base
        $manager->flush();
    }
}
