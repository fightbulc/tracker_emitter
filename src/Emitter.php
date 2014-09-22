<?php

namespace Tracker\Emitter;

/**
 * Emitter
 * @package Tracker\Emitter
 * @author Tino Ehrich (tino@bigpun.me)
 */
class Emitter
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $eventId;

    /**
     * @var string|null
     */
    protected $userId;

    /**
     * @var array
     */
    protected $objectId;

    /**
     * @var array
     */
    protected $environment;

    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @return string
     */
    protected function getUrl()
    {
        return (string)$this->url;
    }

    /**
     * @param string $url
     *
     * @return Emitter
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    protected function getAppId()
    {
        return (string)$this->appId;
    }

    /**
     * @param string $appId
     *
     * @return Emitter
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * @return int
     */
    protected function getCreatedAt()
    {
        return (int)$this->createdAt;
    }

    /**
     * @param int $createdAt
     *
     * @return Emitter
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return array
     */
    protected function getEnvironment()
    {
        return (array)$this->environment;
    }

    /**
     * @param array $environment
     *
     * @return Emitter
     */
    public function setEnvironment(array $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * @return string
     */
    protected function getEventId()
    {
        return (string)$this->eventId;
    }

    /**
     * @param string $eventId
     *
     * @return Emitter
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * @return array
     */
    protected function getObjectId()
    {
        return (array)$this->objectId;
    }

    /**
     * @param array $objectId
     *
     * @return Emitter
     */
    public function setObjectId(array $objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getUserId()
    {
        $uid = (string)$this->userId;

        return empty($uid) ? null : $uid;
    }

    /**
     * @param string $userId
     *
     * @return Emitter
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasValidData()
    {
        return
            $this->getAppId() !== ''
            && $this->getEventId() !== ''
            && $this->getCreatedAt() > 0;
    }

    /**
     * @return bool
     */
    public function dispatch()
    {
        if ($this->hasValidData() === true)
        {
            // set request params
            $request = [
                'aid' => $this->getAppId(),
                'eid' => $this->getEventId(),
                'uid' => $this->getUserId(),
                'oid' => $this->getObjectId(),
                'env' => $this->getEnvironment(),
                'cat' => $this->getCreatedAt(),
            ];

            // lets get some json running here
            $requestJson = json_encode($request);

            // curl options
            $opt = [
                CURLOPT_URL            => $this->getUrl(),
                CURLOPT_POSTFIELDS     => $requestJson,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST           => 1,
            ];

            // add some wings and, too
            $curl = curl_init();
            curl_setopt_array($curl, $opt);
            curl_exec($curl);
            curl_close($curl);

            return true;
        }

        return false;
    }
}