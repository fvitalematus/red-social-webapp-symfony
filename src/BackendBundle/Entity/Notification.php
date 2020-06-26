<?php

namespace BackendBundle\Entity;

/**
 * Notification
 */
class Notification {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $typeId;

    /**
     * @var string
     */
    private $readed;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $extra;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     *
     * @return Notification
     */
    public function setTypeId($typeId) {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId() {
        return $this->typeId;
    }

    /**
     * Set readed
     *
     * @param string $readed
     *
     * @return Notification
     */
    public function setReaded($readed) {
        $this->readed = $readed;

        return $this;
    }

    /**
     * Get readed
     *
     * @return string
     */
    public function getReaded() {
        return $this->readed;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set extra
     *
     * @param string $extra
     *
     * @return Notification
     */
    public function setExtra($extra) {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return string
     */
    public function getExtra() {
        return $this->extra;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Notification
     */
    public function setUser(\BackendBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

}
