<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=1000)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messagesSent")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $userSender;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messagesReceived")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    private $userReceiver;

    /**
     * @ORM\Column(type="boolean", name="read", options={"default": false})
     */
    private $read;

    /** @ORM\Column(type="datetime", name="date") */
    private $date;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Message
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Message
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set read
     *
     * @param boolean $read
     *
     * @return Message
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set userSender
     *
     * @param \AppBundle\Entity\User $userSender
     *
     * @return Message
     */
    public function setUserSender(\AppBundle\Entity\User $userSender = null)
    {
        $this->userSender = $userSender;

        return $this;
    }

    /**
     * Get userSender
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserSender()
    {
        return $this->userSender;
    }

    /**
     * Set userReceiver
     *
     * @param \AppBundle\Entity\User $userReceiver
     *
     * @return Message
     */
    public function setUserReceiver(\AppBundle\Entity\User $userReceiver = null)
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }

    /**
     * Get userReceiver
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserReceiver()
    {
        return $this->userReceiver;
    }
}
