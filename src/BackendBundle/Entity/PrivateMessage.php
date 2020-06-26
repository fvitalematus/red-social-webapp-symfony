<?php

namespace BackendBundle\Entity;

/**
 * PrivateMessage
 */
class PrivateMessage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $readed;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $emitter;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $receiver;


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
     * Set message
     *
     * @param string $message
     *
     * @return PrivateMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return PrivateMessage
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PrivateMessage
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set readed
     *
     * @param string $readed
     *
     * @return PrivateMessage
     */
    public function setReaded($readed)
    {
        $this->readed = $readed;

        return $this;
    }

    /**
     * Get readed
     *
     * @return string
     */
    public function getReaded()
    {
        return $this->readed;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PrivateMessage
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set emitter
     *
     * @param \BackendBundle\Entity\User $emitter
     *
     * @return PrivateMessage
     */
    public function setEmitter(\BackendBundle\Entity\User $emitter = null)
    {
        $this->emitter = $emitter;

        return $this;
    }

    /**
     * Get emitter
     *
     * @return \BackendBundle\Entity\User
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

    /**
     * Set receiver
     *
     * @param \BackendBundle\Entity\User $receiver
     *
     * @return PrivateMessage
     */
    public function setReceiver(\BackendBundle\Entity\User $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \BackendBundle\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}

