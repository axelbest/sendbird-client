<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DTO\Request;

class Channel
{
    /**
     * @var array|string[]
     */
    private array $userIds;
    private ?string $channelUrl;
    private ?string $name;
    private ?string $coverUrl;
    private ?string $customType;

    /**
     * @param array|string[] $userIds    max 100
     * @param null|string    $channelUrl
     * @param null|string    $name
     * @param null|string    $coverUrl
     * @param null|string    $customType
     */
    public function __construct(
        array $userIds,
        ?string $channelUrl = null,
        ?string $name = null,
        ?string $coverUrl = null,
        ?string $customType = null
    ) {
        $this->channelUrl = $channelUrl;
        $this->userIds = $userIds;
        $this->name = $name;
        $this->coverUrl = $coverUrl;
        $this->customType = $customType;
    }

    public function getChannelUrl(): ?string
    {
        return $this->channelUrl;
    }

    /**
     * @return array|string[]
     */
    public function getUserIds(): array
    {
        return $this->userIds;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function getCustomType(): ?string
    {
        return $this->customType;
    }
}
