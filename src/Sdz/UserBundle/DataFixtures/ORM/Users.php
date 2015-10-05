<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 30/09/2015
 * Time: 13:03
 */

namespace Sdz\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Users implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        // Les noms d'utilisateurs � cr�er
        $noms = array('winzou', 'John', 'Talus');
        $encoder = $this->container->get('security.password_encoder');
        foreach ($noms as $i => $nom) {
            // On cr�e l'utilisateur
            $users[$i] = new User;
            // Le nom d'utilisateur et le mot de passe sont identiques
            $users[$i]->setUsername($nom);
            $password = $encoder->encodePassword($users[$i], $nom);
            $users[$i]->setPassword($password);
            // Le sel et les r�les sont vides pour l'instant
            $users[$i]->setSalt('');
            $users[$i]->setRoles(array('ROLE_USER'));
            // On le persiste
            $manager->persist($users[$i]);
        }

        $admin = new User;
        $admin->setUsername('Kahel');
        $password = $encoder->encodePassword($admin, 'fuckpassword');
        $users[$i]->setPassword($password);
        $users[$i]->setSalt('');
        $users[$i]->setRoles(array('ROLE_ADMIN'));
        $manager->persist($admin);



        // On d�clenche l'enregistrement
        $manager->flush();
    }
}