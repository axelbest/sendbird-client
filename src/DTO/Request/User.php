<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DTO\Request;

class User
{
    private string $userId;
    private string $nickname;
    private string $profileUrl;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getProfileUrl(): string
    {
        return $this->profileUrl;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function setProfileUrl(string $profileUrl): self
    {
        $this->profileUrl = $profileUrl;

        return $this;
    }
}
