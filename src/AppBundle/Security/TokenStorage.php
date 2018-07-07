<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 07/07/18
 * Time: 13:10
 */

namespace AppBundle\Security;

use Predis\Client;

class TokenStorage
{
    const KEY_SUFFIX = '-token';
    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function storeToken($username, $token)
    {
        $this->redisClient->set($username.self::KEY_SUFFIX, $token);
        $this->redisClient->expire($username.self::KEY_SUFFIX, 3600);
    }

    public function invalidateToken($username){
        $this->redisClient->del($username.self::KEY_SUFFIX);
    }

    public function isTokenValid($username, $token){
        return $this->redisClient->get($username.self::KEY_SUFFIX) === $token;
    }

}