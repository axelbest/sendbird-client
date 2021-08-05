<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DTO\Response;

class Channels
{
    /**
     * @var Channel[]
     */
    private array $channels;

    /**
     * @return Channel[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * @param Channel[] $channels
     *
     * @return self
     */
    public function setChannels(array $channels): self
    {
        $this->channels = $channels;

        return $this;
    }
}
