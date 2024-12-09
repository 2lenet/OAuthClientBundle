<?php

namespace Lle\OAuthClientBundle\Service;

use Lle\OAuthClientBundle\Dto\Api\ProfileDto;
use Lle\OAuthClientBundle\Dto\Api\UserDto;

class ConnectApiService
{
    public function __construct(
        private ConnectApiClient $client
    ) {
    }

    public function get(int $id): UserDto
    {
        $result = $this->client->call('/api/users/' . $id, 'GET');

        $dto = UserDto::new()
            ->setConnectId($result['connectId'])
            ->setUsername($result['username'])
            ->setLastName($result['lastName'])
            ->setFirstName($result['firstName'])
            ->setEmail($result['email'])
            ->setMobilePhone($result['mobilePhone'])
            ->setA2fActivated($result['a2fActivated'])
            ->setA2fProvider($result['a2fProvider'])
            ->setRoles($result['roles'])
            ->setIsActive($result['isActive'])
            ->setCodeClient($result['codeClient'])
            ->setData($result['data'])
            ->setAutoRedirectUrl($result['autoRedirectUrl'])
            ->setLocale($result['locale']);

        $profiles = [];
        foreach ($result['profiles'] as $profile) {
            $profiles[] = ProfileDto::new()
                ->setName($profile['name']);
        }
        $dto->setProfiles($profiles);

        return $dto;
    }
}
