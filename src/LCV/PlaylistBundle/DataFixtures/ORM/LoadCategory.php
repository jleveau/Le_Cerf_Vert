<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace LCV\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LCV\PlaylistBundle\Entity\PlaylistCategory;
use LCV\PlaylistBundle\Entity\AudioCategory;


class LoadCategory implements FixtureInterface {
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager) {
        // Liste des noms de catégorie à ajouter
        $names_playlist_cat = array('Default');
        $names_audio_cat = array('Racine');

        foreach ($names_playlist_cat as $name) {
            // On crée la catégorie
            $category = new PlaylistCategory();
            $category -> setName($name);
            $category -> setRemovable(false);

            // On la persiste
            $manager -> persist($category);
        }
        foreach ($names_audio_cat as $name) {
            // On crée la catégorie
            $manager->getRepository("LCVPlaylistBundle:AudioCategory");
            $category = new AudioCategory();
            $category -> setName($name);
            $category -> setRemovable(false);

            // On la persiste
            $manager -> persistAsFirstChild($category);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager -> flush();
    }
}
