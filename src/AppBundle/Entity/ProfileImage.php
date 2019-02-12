<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileImage
 *
 * @ORM\Table(name="profile_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileImageRepository")
 */
class ProfileImage
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
     * @ORM\Column(name="url", type="string", length=1023)
     */
    private $url;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="User", inversedBy="profileImages", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;


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
     * Set url
     *
     * @param string $url
     *
     * @return ProfileImage
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return ProfileImage
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
}
