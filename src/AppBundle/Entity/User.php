<?php

namespace AppBundle\Entity;

use AppBundle\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=63)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=127)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     */
    private $birthdate;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=127, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(name="registration_date", type="datetime")
     */
    private $registrationDate;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="ProfileImage", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id"="DESC"})
     */
    private $profileImages;

    /**
     * @ORM\ManyToMany(targetEntity="Groupe", inversedBy="users", cascade={"persist", "remove"})
     */
    private $groups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profileImages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set registrationDate
     *
     * @param \DateTime $registrationDate
     *
     * @return User
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set ulrImage
     *
     * @param string $urlImage
     *
     * @return User
     */
    public function setUrlImage($urlImage)
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    public function getAge()
    {
        $now = new \DateTime();
        $diff = $now->diff($this->getBirthdate());
        return $diff->y;
    }

    /**
     * Add profileImage
     *
     * @param \AppBundle\Entity\ProfileImage $profileImage
     *
     * @return User
     */
    public function addProfileImage(\AppBundle\Entity\ProfileImage $profileImage)
    {
        $this->profileImages[] = $profileImage;

        return $this;
    }

    /**
     * Remove profileImage
     *
     * @param \AppBundle\Entity\ProfileImage $profileImage
     */
    public function removeProfileImage(\AppBundle\Entity\ProfileImage $profileImage)
    {
        $this->profileImages->removeElement($profileImage);
    }

    /**
     * Get profileImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfileImages()
    {
        return $this->profileImages;
    }

    /**
     * Retourne l'image la plus récente de l'utilisteur en question
     * Si aucune image n'est trouvée, on renvoie une image par defaut
     */
    public function getImg()
    {
        $profileImages = $this->getProfileImages();

        if (count($profileImages) > 0)
        {
            return $profileImages[0]->getUrl();
        } else {
            return 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Circle-icons-profile.svg/1024px-Circle-icons-profile.svg.png';
        }
    }

    /**
     * Add group
     *
     * @param \AppBundle\Entity\Groupe $group
     *
     * @return User
     */
    public function addGroup(\AppBundle\Entity\Groupe $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \AppBundle\Entity\Groupe $group
     */
    public function removeGroup(\AppBundle\Entity\Groupe $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
