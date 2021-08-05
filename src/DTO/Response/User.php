<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DTO\Response;

class User
{
    private string $userId;
    private string $nickname;
    private string $profileUrl;
    private bool $isActive;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getProfileUrl(): string
    {
        return $this->profileUrl;
    }

    public function setProfileUrl(string $profileUrl): self
    {
        $this->profileUrl = $profileUrl;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
