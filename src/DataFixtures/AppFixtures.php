<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Entity\Comment;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private  $passwordEncoder;
    /**
     * @var \Faker\Factory
     */
    private $faker;



    private const USERS =[
    [
        'username'=>'admin',
        'email'=>'admin@gmail.com',
        'name'=>'admin',
        'password'=>'secret123#'
    ],
        [
            'username'=>'aziz',
            'email'=>'aziz@gmail.com',
            'name'=>'admin',
            'password'=>'secret123#'
        ],
        [
            'username'=>'ali',
            'email'=>'ali@gmail.com',
            'name'=>'admin',
            'password'=>'secret123#'
        ],
    ];
    public  function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
        $this->faker= \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
$this->loadBlogPosts($manager);
$this->loadComments($manager);
    }


    public  function loadBlogPosts(ObjectManager $manager)
    {

        for($i=0;$i<100;$i++)
        {
            $blog = new BlogPost();
            $authorreference = $this->getRandUser();
            $blog->setAuthor($authorreference);
            $blog->setContent($this->faker->realText(30));
            $blog->setPublished($this->faker->dateTimeThisYear());
            $blog->setTitle($this->faker->realText());
            $blog->setSlug($this->faker->slug);
            $this->setReference("blog_post_$i",$blog);
            $manager->persist($blog);

        }

        $manager->flush();
    }
    public function  loadComments(ObjectManager $manager)
    {
        for($i=0;$i<100;$i++)
        {
            for($j=0;$j<rand(0,5);$j++)
            {
                $comment= new Comment();
                $authorreference = $this->getRandUser();

                $comment->setAuthor($authorreference);

                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setContent($this->faker->realText());
                $comment->setBlogpost($this->getReference("blog_post_$i"));
                $manager->persist($comment);

            }
        }
        $manager->flush();
    }
    public function  loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userfixture)
        {
            $user =new User();
            $user->setUsername($userfixture['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,$userfixture['password']));
            $user->setName($userfixture['name']);
            $user->setEmail($userfixture['email']);
            $this->addReference('user_'.$userfixture['username'],$user);
            $manager->persist($user);
        }

        $manager->flush();

    }

    protected function getRandUser(): User
    {
        return $this->getReference('user_'.self::USERS[rand(0,2)]['username']);

    }
}
