<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DTO\Request;

class ChannelMetadata
{
    private string $candidateName;
    private string $candidateUserId;
    private string $branch;

    public function getCandidateName(): string
    {
        return $this->candidateName;
    }

    public function setCandidateName(string $candidateName): self
    {
        $this->candidateName = $candidateName;

        return $this;
    }

    public function getCandidateUserId(): string
    {
        return $this->candidateUserId;
    }

    public function setCandidateUserId(string $candidateUserId): self
    {
        $this->candidateUserId = $candidateUserId;

        return $this;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
