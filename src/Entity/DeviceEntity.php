<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Class Installation
 *
 * @ORM\Entity(repositoryClass="\Jgut\Pusher\Repository\DeviceRepository")
 * @ORM\Table(name="device")
 */
class DeviceEntity implements \JsonSerializable
{
    const PLATFORM_ANDROID = 'android';
    const PLATFORM_IOS = 'ios';

    const ANDROID_TOKEN_PATTERN = '/^[0-9a-zA-Z-_.]+$/';
    const IOS_TOKEN_PATTERN = '/^[\da-f]{64}$/';

    /**
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var \Ramsey\Uuid\Uuid
     */
    protected $uuid;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     *
     * @var string
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=10)
     *
     * @var string
     */
    private $platform;

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param Uuid $uuid
     *
     * @return $this
     */
    public function setUuid(Uuid $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setToken($token)
    {
        $token = trim($token);

        if (!preg_match(static::ANDROID_TOKEN_PATTERN, $token) && !preg_match(static::IOS_TOKEN_PATTERN, $token)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a supported device token', $token));
        }

        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setPlatform($platform)
    {
        $validPlatforms = [self::PLATFORM_ANDROID, self::PLATFORM_IOS];

        $platform = strtolower(trim($platform));
        if (!in_array($platform, $validPlatforms, true)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid device platform', $platform));
        }

        $this->platform = $platform;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'uuid' => $this->uuid,
            'token' => $this->token,
            'platform' => $this->platform,
        ];
    }
}
