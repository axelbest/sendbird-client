<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\Service;

use Axelbest\SendbirdClient\Api\EndpointReference;
use Axelbest\SendbirdClient\DTO\Request\Channel as ChannelRequest;
use Axelbest\SendbirdClient\DTO\Request\ChannelMetadata;
use Axelbest\SendbirdClient\DTO\Request\User;
use Axelbest\SendbirdClient\DTO\Response\Channel as ChannelResponse;
use Axelbest\SendbirdClient\DTO\Response\Channels;
use Axelbest\SendbirdClient\DTO\Response\User as SendbirdUser;
use Axelbest\SendbirdClient\Service\Contracts\ApiResponseValidatorInterface;
use Axelbest\SendbirdClient\Service\Contracts\SendbirdApiClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SendbirdApiClient implements SendbirdApiClientInterface
{
    private HttpClientInterface $sendbirdClient;
    private SerializerInterface $serializer;
    private NormalizerInterface $normalizer;
    private ApiResponseValidatorInterface $responseValidator;

    public function __construct(
        HttpClientInterface          $sendbirdClient,
        SerializerInterface           $serializer,
        NormalizerInterface           $normalizer,
        ApiResponseValidatorInterface $responseValidator
    )
    {
        $this->sendbirdClient = $sendbirdClient;
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
        $this->responseValidator = $responseValidator;
    }

    public function createUser(User $user): void
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_POST,
            EndpointReference::USER_CREATE,
            [
                'json' => [
                    'user_id' => $user->getUserId(),
                    'nickname' => $user->getNickname(),
                    'profile_url' => $user->getProfileUrl(),
                ],
            ]
        );
        $this->responseValidator->validate($response);
    }

    public function getUser(string $userId): SendbirdUser
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_GET,
            sprintf(EndpointReference::USER_GET_BY_ID, $userId),
        );

        $this->responseValidator->validate($response);

        return $this->serializer->deserialize($response->getContent(false), SendbirdUser::class, JsonEncoder::FORMAT);
    }

    public function assignUsersToChannel(ChannelRequest $channel): void
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_POST,
            sprintf(EndpointReference::CHANNEL_GROUP_INVITE_USERS, $channel->getChannelUrl()),
            [
                'json' => [
                    'user_ids' => $channel->getUserIds(),
                ],
            ]
        );

        $this->responseValidator->validate($response);
    }

    public function createGroupChannel(ChannelRequest $channel): ChannelResponse
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_POST,
            EndpointReference::CHANNEL_GROUP_CREATE,
            [
                'json' => [
                    'user_ids' => $channel->getUserIds(),
                    'name' => $channel->getName(),
                    'cover_url' => $channel->getCoverUrl(),
                    'custom_type' => $channel->getCustomType(),
                    'strict' => true,
                ],
            ]
        );

        $this->responseValidator->validate($response);

        return $this->serializer->deserialize($response->getContent(), ChannelResponse::class, JsonEncoder::FORMAT);
    }

    public function addChannelMetadata(string $channelUrl, ChannelMetadata $metadata, bool $upsert = true): void
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_PUT,
            sprintf(EndpointReference::CHANNEL_GROUP_ADD_METADATA, $channelUrl),
            [
                'json' => [
                    'metadata' => $this->normalizer->normalize($metadata),
                    'upsert' => $upsert,
                ],
            ]
        );

        $this->responseValidator->validate($response);
    }

    public function setUserActive(string $userId, bool $isActive = true): void
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_PUT,
            sprintf(EndpointReference::USER_GET_BY_ID, $userId),
            [
                'json' => [
                    'is_active' => $isActive,
                ],
            ]
        );

        $this->responseValidator->validate($response);
    }

    public function getUserChannels(string $userId, array $queryOptions): Channels
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_GET,
            sprintf(EndpointReference::USER_CHANNELS_GET, $userId),
            [
                'query' => $queryOptions
            ]
        );

        $this->responseValidator->validate($response);

        return $this->serializer->deserialize($response->getContent(), Channels::class, JsonEncoder::FORMAT);
    }

    public function freezeGroupChannel(string $channelUrl): void
    {
        $this->setGroupChannelFreezed($channelUrl);
    }

    public function unfreezeGroupChannel(string $channelUrl): void
    {
        $this->setGroupChannelFreezed($channelUrl, false);
    }

    private function setGroupChannelFreezed(string $channelUrl, bool $isFreezed = true): void
    {
        $response = $this->sendbirdClient->request(
            Request::METHOD_PUT,
            sprintf(EndpointReference::CHANNEL_GROUP_FREEZE, $channelUrl),
            [
                'json' => [
                    'freeze' => $isFreezed,
                ],
            ]
        );

        $this->responseValidator->validate($response);
    }
}
