<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace LCV\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LCV\PlaylistBundle\Entity\PlaylistCategory;

class LoadCategory implements FixtureInterface {
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager) {
        // Liste des noms de catégorie à ajouter
        $names = array('Default');

        foreach ($names as $name) {
            // On crée la catégorie
            $category = new PlaylistCategory();
            $category -> setName($name);
            $category -> setRemovable(false);

            // On la persiste
            $manager -> persist($category);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager -> flush();
    }
}