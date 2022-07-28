<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 * @UniqueEntity(
 *      "email",
 *      message="Hum, ce mail est déjà pris !"
 * )
 */
class Articles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2500)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 2500,
     *      maxMessage = "Outch ! Votre article doit être inférieur à 2500 caractères"
     * )
     */
    private $article;





    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 180,
     *      maxMessage = "Outch ! Votre pseudo doit être inférieur à 180 caractères"
     * )
     */
    private $pseudo_user;











    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article): void
    {
        $this->article = $article;
    }













    public function getPseudo_user(): ?string
    {
        return $this->pseudo_user;
    }

    public function setPseudo_user(string $pseudo_user): self
    {
        $this->pseudo_user = $pseudo_user;

        return $this;
    }






    /**
     * Get the value of confirm_password
     */ 
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * Set the value of confirm_password
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirm_password)
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    public function __toString()
    {
        return $this->pseudo;
    }

    public function getLastConnection(): ?LastConnection
    {
        return $this->lastConnection;
    }

    public function setLastConnection(?LastConnection $lastConnection): self
    {
        $this->lastConnection = $lastConnection;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $lastConnection === null ? null : $this;
        if ($newUser !== $lastConnection->getUser()) {
            $lastConnection->setUser($newUser);
        }

        return $this;
    }

}
