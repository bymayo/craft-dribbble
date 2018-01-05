<?php
namespace CrewLabs\OAuth2\Client\Test\Provider;

use CrewLabs\OAuth2\Client\Provider\Dribbble;
use Mockery as m;
use ReflectionClass;

class DribbbleTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('CrewLabs\OAuth2\Client\Provider\Dribbble');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function setUp()
    {
        $this->provider = new Dribbble([
            'clientId'      => 'mock_client_id',
            'clientSecret'  => 'mock_secret',
            'redirectUri'   => 'none',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals('/oauth/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals('/oauth/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('{"access_token":"mock_access_token", "token_type":"bearer"}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testUserData()
    {
        $responseData = [
            'id'                      => rand(1000, 9999),
            'name'                    => 'John Jones',
            'username'                => uniqid(),
            'html_url'                => 'http://example.com',
            'avatar_url'              => 'http://example.com',
            'bio'                     => 'DIY plaid aesthetic, beard put a bird on it vice cardigan chillwave trust fund semiotics.',
            'location'                => 'San Diego, CA',
            'links'                   => ['twitter'                                                                                   => 'http://twitter.com/jj'],
            'buckets_count'           => 1,
            'comments_received_count' => 48,
            'followers_count'         => 251,
            'followings_count'        => 783,
            'likes_count'             => 897,
            'likes_received_count'    => 516,
            'projects_count'          => 11,
            'rebounds_received_count' => 1,
            'shots_count'             => 93,
            'teams_count'             => 1,
            'can_upload_shot'         => true,
            'type'                    => 'User',
            'pro'                     => false,
            'buckets_url'             => 'http://example.com',
            'followers_url'           => 'http://example.com',
            'following_url'           => 'http://example.com',
            'likes_url'               => 'http://example.com',
            'projects_url'            => 'http://example.com',
            'shots_url'               => 'http://example.com',
            'teams_url'               => 'http://example.com',
            'created_at'              => '2010-06-09T16:37:48Z',
            'updated_at'              => '2016-12-13T22:21:43Z'
        ];

        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('{"access_token":"mock_access_token", "token_type":"bearer"}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn(200);
        $userResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $userResponse->shouldReceive('getBody')->andReturn(json_encode($responseData));
        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $userResponse->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(2)
            ->andReturn($postResponse, $userResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $user = $this->provider->getResourceOwner($token);

        $this->assertEquals($responseData['id'], $user->getId());
        $this->assertEquals($responseData['id'], $user->toArray()['id']);
        $this->assertEquals($responseData['name'], $user->getName());
        $this->assertEquals($responseData['name'], $user->toArray()['name']);
        $this->assertEquals($responseData['username'], $user->getUsername());
        $this->assertEquals($responseData['username'], $user->toArray()['username']);
        $this->assertEquals($responseData['html_url'], $user->getHtmlUrl());
        $this->assertEquals($responseData['html_url'], $user->toArray()['html_url']);
        $this->assertEquals($responseData['avatar_url'], $user->getAvatarUrl());
        $this->assertEquals($responseData['avatar_url'], $user->toArray()['avatar_url']);
        $this->assertEquals($responseData['bio'], $user->getBio());
        $this->assertEquals($responseData['bio'], $user->toArray()['bio']);
        $this->assertEquals($responseData['location'], $user->getLocation());
        $this->assertEquals($responseData['location'], $user->toArray()['location']);
        $this->assertEquals($responseData['links'], $user->getLinks());
        $this->assertEquals($responseData['links'], $user->toArray()['links']);
        $this->assertEquals($responseData['buckets_count'], $user->getBucketCount());
        $this->assertEquals($responseData['buckets_count'], $user->toArray()['buckets_count']);
        $this->assertEquals($responseData['comments_received_count'], $user->getCommentsReceivedCount());
        $this->assertEquals($responseData['comments_received_count'], $user->toArray()['comments_received_count']);
        $this->assertEquals($responseData['followers_count'], $user->getFollowersCount());
        $this->assertEquals($responseData['followers_count'], $user->toArray()['followers_count']);
        $this->assertEquals($responseData['followings_count'], $user->getFollowingsCount());
        $this->assertEquals($responseData['followings_count'], $user->toArray()['followings_count']);
        $this->assertEquals($responseData['likes_count'], $user->getLikesCount());
        $this->assertEquals($responseData['likes_count'], $user->toArray()['likes_count']);
        $this->assertEquals($responseData['likes_received_count'], $user->getLikesReceivedCount());
        $this->assertEquals($responseData['likes_received_count'], $user->toArray()['likes_received_count']);
        $this->assertEquals($responseData['projects_count'], $user->getProjectsCount());
        $this->assertEquals($responseData['projects_count'], $user->toArray()['projects_count']);
        $this->assertEquals($responseData['rebounds_received_count'], $user->getReboundsReceivedCount());
        $this->assertEquals($responseData['rebounds_received_count'], $user->toArray()['rebounds_received_count']);
        $this->assertEquals($responseData['shots_count'], $user->getShotsCount());
        $this->assertEquals($responseData['shots_count'], $user->toArray()['shots_count']);
        $this->assertEquals($responseData['teams_count'], $user->getTeamsCount());
        $this->assertEquals($responseData['teams_count'], $user->toArray()['teams_count']);
        $this->assertEquals($responseData['can_upload_shot'], $user->canUploadShot());
        $this->assertEquals($responseData['can_upload_shot'], $user->toArray()['can_upload_shot']);
        $this->assertEquals($responseData['type'], $user->getType());
        $this->assertEquals($responseData['type'], $user->toArray()['type']);
        $this->assertEquals($responseData['pro'], $user->isPro());
        $this->assertEquals($responseData['pro'], $user->toArray()['pro']);
        $this->assertEquals($responseData['buckets_url'], $user->getBucketsUrl());
        $this->assertEquals($responseData['buckets_url'], $user->toArray()['buckets_url']);
        $this->assertEquals($responseData['followers_url'], $user->getFollowersUrl());
        $this->assertEquals($responseData['followers_url'], $user->toArray()['followers_url']);
        $this->assertEquals($responseData['following_url'], $user->getFollowingUrl());
        $this->assertEquals($responseData['following_url'], $user->toArray()['following_url']);
        $this->assertEquals($responseData['likes_url'], $user->getLikesUrl());
        $this->assertEquals($responseData['likes_url'], $user->toArray()['likes_url']);
        $this->assertEquals($responseData['projects_url'], $user->getProjectsUrl());
        $this->assertEquals($responseData['projects_url'], $user->toArray()['projects_url']);
        $this->assertEquals($responseData['shots_url'], $user->getShotsUrl());
        $this->assertEquals($responseData['shots_url'], $user->toArray()['shots_url']);
        $this->assertEquals($responseData['teams_url'], $user->getTeamsUrl());
        $this->assertEquals($responseData['teams_url'], $user->toArray()['teams_url']);
        $this->assertEquals($responseData['created_at'], $user->getCreated());
        $this->assertEquals($responseData['created_at'], $user->toArray()['created_at']);
        $this->assertEquals($responseData['updated_at'], $user->getUpdated());
        $this->assertEquals($responseData['updated_at'], $user->toArray()['updated_at']);

    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $message = uniqid();
        $status = rand(400, 600);
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn(' {"error":"'.$message.'"}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }
}
