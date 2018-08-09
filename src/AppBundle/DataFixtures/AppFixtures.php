<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 08/08/2018
 * Time: 15:57
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $teacher = new Teacher();
        $teacher->setEmail('teacher@gmail.com');

        $password = $this->encoder->encodePassword($teacher, 'password123');
        $teacher->setPassword($password);

        $student = new Student();
        $student->setEmail('student@gmail.com');

        $password1 = $this->encoder->encodePassword($student, 'password123');
        $student->setPassword($password1);
        $manager->persist($student);
        $manager->persist($teacher);
        $manager->flush();
    }
}

