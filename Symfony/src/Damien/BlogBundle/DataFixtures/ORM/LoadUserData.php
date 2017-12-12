<?php
namespace Damien\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Damien\BlogBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        $listNames = array('admin', 'user');
//        $date = new DateTime('2017-01-01');
//        $date_user = $date->format('Y-m-d');
        
        foreach($listNames as $name){
        
            $userAdmin = new User();
            $userAdmin->setUsername($name);
            $userAdmin->setEmail("myemail");
            $userAdmin->setDateUser(new \DateTime());
            $userAdmin->setIsActive(true);
            $hash = $this->container->get('security.password_encoder')->encodePassword($userAdmin, $name);
            $userAdmin->setPassword($hash);
            $userAdmin->setRoles(array("ROLE_USER"));
            $manager->persist($userAdmin);
        }
        $manager->flush();
    }
}