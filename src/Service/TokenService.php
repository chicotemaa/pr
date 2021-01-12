<?php

namespace App\Service;

use App\Entity\AccessToken;
use App\Entity\RefreshToken;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class TokenService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function crearToken($user)
    {
        $client = $this->em->getRepository(Client::class)->findAll()[0];

        $tokenArray = array(
            'access_token' => $this->genAccessToken(),
            'expires_in' => time() + 3600, //($access_token_lifetime ?: $this->getVariable(self::CONFIG_ACCESS_LIFETIME)),
            'token_type' => 'bearer', //$this->getVariable(self::CONFIG_TOKEN_TYPE),
            'scope' => null, //$scope,
            'refresh_token' => '',
        );

        $refreshTokenArray = array(
            'access_token' => $this->genAccessToken(),
            'expires_in' => time() + 7200, //($access_token_lifetime ?: $this->getVariable(self::CONFIG_ACCESS_LIFETIME)),
            'token_type' => 'bearer', //$this->getVariable(self::CONFIG_TOKEN_TYPE),
            'scope' => null, //$scope,
        );

        $tokenArray['refresh_token'] = $refreshTokenArray['access_token'];
        $accessToken = new AccessToken();
        $accessToken->setClient($client);
        $accessToken->setUser($user);
        $accessToken->setExpiresAt($tokenArray['expires_in']);
        $accessToken->setToken($tokenArray['access_token']);
        $accessToken->setScope($tokenArray['scope']);

        $this->em->persist($accessToken);

        $refreshToken = new RefreshToken();
        $refreshToken->setClient($client);
        $refreshToken->setUser($user);
        $refreshToken->setExpiresAt($refreshTokenArray['expires_in']);
        $refreshToken->setToken($refreshTokenArray['access_token']);
        $refreshToken->setScope($refreshTokenArray['scope']);

        $this->em->persist($refreshToken);

        $this->em->flush();

        return $tokenArray;
    }

    private function genAccessToken()
    {
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100);
        } elseif (function_exists('openssl_random_pseudo_bytes')) { // Get 100 bytes of pseudo-random data
            $bytes = openssl_random_pseudo_bytes(100, $strong);
            if (true === $strong && false !== $bytes) {
                $randomData = $bytes;
            }
        }
        // Last resort: mt_rand
        if (empty($randomData)) { // Get 108 bytes of (pseudo-random, insecure) data
            $randomData = mt_rand().mt_rand().mt_rand().uniqid(mt_rand(), true).microtime(true).uniqid(
                mt_rand(),
                true
            );
        }

        return rtrim(strtr(base64_encode(hash('sha256', $randomData)), '+/', '-_'), '=');
    }
}
