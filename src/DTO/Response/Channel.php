<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DTO\Response;

class Channel
{
    private string $name;
    private string $channelUrl;
    private string $customType;
    private string $data;
    private string $memberCount;
    private int $createdAt;
    private bool $freeze;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getChannelUrl(): string
    {
        return $this->channelUrl;
    }

    public function setChannelUrl(string $channelUrl): self
    {
        $this->channelUrl = $channelUrl;

        return $this;
    }

    public function getCustomType(): string
    {
        return $this->customType;
    }

    public function setCustomType(string $customType): self
    {
        $this->customType = $customType;

        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getMemberCount(): string
    {
        return $this->memberCount;
    }

    public function setMemberCount(string $memberCount): self
    {
        $this->memberCount = $memberCount;

        return $this;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isFreeze(): bool
    {
        return $this->freeze;
    }

    public function setFreeze(bool $freeze): self
    {
        $this->freeze = $freeze;

        return $this;
    }
}
