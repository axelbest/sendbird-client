<?php

namespace Axelbest\SendbirdClient\Tests\Service;

use Axelbest\SendbirdClient\DTO\Request\User;
use Axelbest\SendbirdClient\DTO\Request\Channel;
use Axelbest\SendbirdClient\DTO\Request\ChannelMetadata;
use Axelbest\SendbirdClient\DTO\Response\Channel as ChannelResponse;
use Axelbest\SendbirdClient\DTO\Response\Channels;
use Axelbest\SendbirdClient\DTO\Response\User as SendbirdUser;
use Axelbest\SendbirdClient\Service\Contracts\ApiResponseValidatorInterface;
use Axelbest\SendbirdClient\Service\SendbirdApiClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SendbirdApiClientTest extends TestCase
{
    /**
     * @var HttpClientInterface|MockObject
     */
    private $httpClient;

    /**
     * @var MockObject|SerializerInterface
     */
    private $serializer;

    /**
     * @var MockObject|NormalizerInterface
     */
    private $normalizer;

    /**
     * @var ApiResponseValidatorInterface|MockObject
     */
    private $responseValidator;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->responseValidator = $this->createMock(ApiResponseValidatorInterface::class);

        parent::setUp();
    }

    public function testCreateUser(): void
    {
        $userId = '123abc';

        /** @var User $user */
        $user = $this->createMock(User::class)
            ->setUserId($userId)
            ->setNickname('Dick Tracy')
            ->setProfileUrl(sprintf('https://robohash.org/%s?size=128x128', $userId));

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_POST,
                'users',
                [
                    'json' => [
                        'user_id' => $user->getUserId(),
                        'nickname' => $user->getNickname(),
                        'profile_url' => $user->getProfileUrl(),
                    ],
                ]
            )
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->getSendbirdClient()->createUser($user);
    }

    public function testGetUser(): void
    {
        $fakeId = '826';
        $fakeImage = sprintf('https://robohash.org/%s?size=128x128', $fakeId);
        $fakeNickName = sprintf('test-nick-%s', $fakeId);

        $user = $this->createMock(SendbirdUser::class);
        $user->setUserId($fakeId)
            ->setNickname($fakeNickName)
            ->setProfileUrl($fakeImage);

        $this->responseValidator
            ->expects(self::once())
            ->method('validate');

        $content = json_encode([
            'user_id' => $fakeId,
            'profile_url' => $fakeImage,
            'nickname' => $fakeNickName,
            'phone_number' => '',
            'has_ever_logged_in' => false,
            'discovery_keys' => [],
            'require_auth_for_profile_image' => false,
            'access_token' => '',
            'preferred_languages' => [],
            'created_at' => 1626246031,
            'is_active' => true,
            'locale' => '',
            'is_hide_me_from_friends' => false,
            'session_tokens' => [],
            'is_online' => false,
            'last_seen_at' => 0,
            'is_shadow_blocked' => false,
            'metadata' => [],
        ]);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::once())
            ->method('getContent')
            ->willReturn($content);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_GET,
                sprintf('users/%s', $fakeId))
            ->willReturn($response);

        $this->serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($content, SendbirdUser::class, JsonEncoder::FORMAT)
            ->willReturn($user);

        $sendbirdUser = $this->getSendbirdClient()->getUser($fakeId);

        $this->assertInstanceOf(SendbirdUser::class, $sendbirdUser);
    }

    public function testAssignUsersToChannel(): void
    {
        $channelUrl = 'fake_channel_url';
        $userIds = ['fake_user_id'];

        $channel = $this->createMock(Channel::class);
        $channel
            ->expects(self::once())
            ->method('getChannelUrl')
            ->willReturn($channelUrl);
        $channel
            ->expects(self::once())
            ->method('getUserIds')
            ->willReturn($userIds);

        $url = sprintf('group_channels/%s/invite', $channelUrl);
        $options = [
            'json' => [
                'user_ids' => $userIds,
            ],
        ];

        $response = $this->createMock(ResponseInterface::class);
        $this->responseValidator
            ->expects(self::once())
            ->method('validate');

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $url, $options)
            ->willReturn($response);

        $this->getSendbirdClient()->assignUsersToChannel($channel);
    }

    public function testCreateGroupChannel(): void
    {
        $channelName = 'fake_channel_name';
        $userIds = ['fake_user_id'];
        $coverUrl = 'fake_cover_url';
        $customType = 'fake_custom_type';

        $channelRequest = $this->createMock(Channel::class);
        $channelRequest->expects(self::once())->method('getName')->willReturn($channelName);
        $channelRequest->expects(self::once())->method('getUserIds')->willReturn($userIds);
        $channelRequest->expects(self::once())->method('getCoverUrl')->willReturn($coverUrl);
        $channelRequest->expects(self::once())->method('getCustomType')->willReturn($customType);
        
        $this->responseValidator->expects(self::once())
            ->method('validate');

        $this->httpClient->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_POST,
                'group_channels',
                [
                    'json' => [
                        'user_ids' => $userIds,
                        'name' => $channelName,
                        'cover_url' => $coverUrl,
                        'custom_type' => $customType,
                        'strict' => true,
                    ],
                ]
            )
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->serializer->expects(self::once())
            ->method('deserialize')
            ->with('', ChannelResponse::class, JsonEncoder::FORMAT)
            ->willReturn($this->createMock(ChannelResponse::class));

        $result = $this->getSendbirdClient()->createGroupChannel($channelRequest);

        self::assertInstanceOf(ChannelResponse::class, $result);
    }

    /**
     * @dataProvider provideFreezeChannelData
     */
    public function testFreezeOrUnfreezeGroupChannel(bool $isFreezed, string $method): void
    {
        $channelUrl = 'fake_channel_url';

        $this->responseValidator
            ->expects(self::once())
            ->method('validate');

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_PUT,
                sprintf('group_channels/%s/freeze', $channelUrl),
                $options = [
                    'json' => [
                        'freeze' => $isFreezed,
                    ],
                ]
            )
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->getSendbirdClient()->{$method}($channelUrl);
    }

    public function provideFreezeChannelData(): array
    {
        return [
            [true, 'freezeGroupChannel'],
            [false, 'unfreezeGroupChannel'],
        ];
    }

    public function testAddChannelMetadata(): void
    {
        $branchName = 'sample_branch';

        $candidateName = 'John Travolta';
        $candidateUserId = 'candidate_john_travolta_420';

        $metadataRequestMock = $this->createMock(ChannelMetadata::class);
        $metadataRequestMock
            ->setBranch($branchName)
            ->setCandidateName($candidateName)
            ->setCandidateUserId($candidateUserId);

        $this->normalizer
            ->expects(self::once())
            ->method('normalize')
            ->with($metadataRequestMock)
            ->willReturn(
                [
                    'branch' => $branchName,
                    'candidateName' => $candidateName,
                    'candidateUserId' => $candidateUserId,
                ]
            );

        $this->getSendbirdClient()->addChannelMetadata($branchName, $metadataRequestMock);
    }

    public function testAddChannelMetadataForNonExistingChannel(): void
    {
        $notExistingChannel = 'not-existing-channel';

        $this->responseValidator
            ->expects(self::once())
            ->method('validate');

        $metadataRequestMock = $this->createMock(ChannelMetadata::class);
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_PUT,
                sprintf('group_channels/%s/metadata', $notExistingChannel),
                [
                    'json' => [
                        'metadata' => $this->normalizer->normalize($metadataRequestMock),
                        'upsert' => true,
                    ],
                ]
            )
            ->willReturn($response);

        $this->getSendbirdClient()->addChannelMetadata($notExistingChannel, $metadataRequestMock);
    }

    /**
     * @dataProvider provideUserActiveFlag
     */
    public function testSetUserActive(bool $isActive): void
    {
        $userId = 'fake_user_id';

        $this->responseValidator
            ->expects(self::once())
            ->method('validate');


        $response = $this->createMock(ResponseInterface::class);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                Request::METHOD_PUT,
                sprintf('users/%s', $userId),
                [
                    'json' => [
                        'is_active' => $isActive,
                    ],
                ]
            )
            ->willReturn(
                $response
            );

        $this->getSendbirdClient()->setUserActive($userId, $isActive);
    }

    public function provideUserActiveFlag(): array
    {
        return [
            [true],
            [false],
        ];
    }

    public function testGetUserChannels(): void
    {
        $userId = 'fake_user_id';
        $url = sprintf('users/%s/my_group_channels', $userId);
        $options = [
            'custom_types' => 'default',
            'show_empty' => 'true',
        ];

        $content = '';

        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::once())
            ->method('getContent')
            ->willReturn($content);

        $this->responseValidator
            ->expects(self::once())
            ->method('validate');

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_GET, $url,
                [
                    'query' => $options
                ]
            )
            ->willReturn($response);

        $channels = $this->createMock(Channels::class);

        $this->serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($content, Channels::class, JsonEncoder::FORMAT)
            ->willReturn($channels);

        $this->getSendbirdClient()->getUserChannels($userId, $options);
    }

    private function getSendbirdClient()
    {
        return new SendbirdApiClient(
            $this->httpClient,
            $this->serializer,
            $this->normalizer,
            $this->responseValidator,
        );
    }
}
