<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\Service\Contracts;

use Axelbest\SendbirdClient\DTO\Request\Channel as ChannelRequest;
use Axelbest\SendbirdClient\DTO\Request\ChannelMetadata;
use Axelbest\SendbirdClient\DTO\Response\Channel as ChannelResponse;
use Axelbest\SendbirdClient\DTO\Response\Channels;
use Axelbest\SendbirdClient\DTO\Response\User as SendbirdUser;
use Axelbest\SendbirdClient\DTO\Request\User;
use Symfony\Component\Security\Core\User\UserInterface;

interface SendbirdApiClientInterface
{
    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/user#2-create-a-user.
     */
    public function createUser(User $user): void;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/user#2-view-a-user.
     */
    public function getUser(string $user): SendbirdUser;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/user#2-update-a-user.
     */
    public function setUserActive(string $userId, bool $isActive = true): void;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/group-channel#2-create-a-channel.
     */
    public function createGroupChannel(ChannelRequest $channel): ChannelResponse;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/user-and-channel-metadata#2-create-a-channel-metadata
     */
    public function addChannelMetadata(string $channelUrl, ChannelMetadata $metadata): void;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/user#2-list-my-group-channels.
     */
    public function getUserChannels(string $userId, array $queryOptions): Channels;



    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/group-channel#2-freeze-a-channel.
     */
    public function freezeGroupChannel(string $channelUrl): void;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/group-channel#2-freeze-a-channel.
     *
     * Consultant create channel (send first message) #3
     * Dormant room status #14
     */
    public function unfreezeGroupChannel(string $channelUrl): void;

    /**
     * https://sendbird.com/docs/chat/v3/platform-api/guides/group-channel#2-invite-as-members.
     */
    public function assignUsersToChannel(ChannelRequest $channel): void;

}
