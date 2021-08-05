<?php
declare(strict_types=1);

namespace Axelbest\SendbirdClient\Api;

class EndpointReference
{
    public const CHANNEL_GROUP_INVITE_USERS = 'group_channels/%s/invite';
    public const CHANNEL_GROUP_ADD_METADATA = 'group_channels/%s/metadata';
    public const CHANNEL_GROUP_CREATE = 'group_channels';
    public const CHANNEL_GROUP_FREEZE = 'group_channels/%s/freeze';
    public const USER_CHANNELS_GET = 'users/%s/my_group_channels';
    public const USER_GET_BY_ID = 'users/%s';
    public const USER_CREATE = 'users';
}