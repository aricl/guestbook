<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->encoderFactory->getEncoder(Admin::class)->encodePassword('admin', null));
        $manager->persist($admin);

        $amsterdam = new Conference(
            'Amsterdam',
            true,
            2019
        );
        $manager->persist($amsterdam);

        $paris = new Conference(
            'Paris',
            true,
            2020
        );
        $manager->persist($paris);

        $comment = Comment::createWithoutPhoto(
            $amsterdam,
            'Fabien',
            'Hot damn! That\'s one hell of a conference!',
            'fabien@example.com'
        );
        $manager->persist($comment);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
