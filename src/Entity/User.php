<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *
 *     itemOperations={
 *     "get"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *     "normalization_context"={"groups"={"get"}}
 *
 *     },
 *     "put"={"access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *     "denormalization_context"={"groups"={"put"}},
 *         "normalization_context"={"groups"={"get"}}
 *     }
 *     },
 *     collectionOperations={"post"={
 *     "denormalization_context"={"groups"={"post"}},
 *      *     "normalization_context"={"groups"={"get"}}

 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get","post"})
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="225")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"put","post"})
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="password must be at least 7 characters long that  containers at least one uppercase  character and  one number . "
     * )
     */
    private $password;

    /**
     * @Groups({"put","post"})
     * @Assert\NotBlank()
     * @Assert\Expression(
     *     "this.getPassword() ==  this.getRetypedPassword() ",
     *     message="password does not match "
     * )
     */
    private $RetypedPassword;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get","put","post"})
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="225")
     */
    private $name;

    /**
     * @Groups({"put","post"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost",mappedBy="author")
     * @Groups({"get"})
     */
    private $posts;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="author")
     * @Groups({"get"})
     */
    private $comments;

    public function  __construct()
    {
        $this->posts=new ArrayCollection();
        $this->comments=new ArrayCollection();
    }


    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     */
    public function setPosts(ArrayCollection $posts): void
    {
        $this->posts = $posts;
    }


    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     */
    public function setComments(ArrayCollection $comments): void
    {
        $this->comments = $comments;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];


    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getRetypedPassword()
    {
        return $this->RetypedPassword;
    }


    public function setRetypedPassword($RetypedPassword): void
    {
        $this->RetypedPassword = $RetypedPassword;
    }


}
