<?php

namespace Caxy\CityOfLearning;

use GuzzleHttp\Psr7;
use Namshi\JOSE\JWS;
use Psr\Http\Message\RequestInterface;


/**
 * Class JwtMiddleware.
 */
class JwtMiddleware
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var int
     */
    private $exp;

    /**
     * JwtMiddleware constructor.
     *
     * @param string $secret
     * @param int    $exp
     */
    public function __construct($token, $secret, $exp = 60)
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->exp = $exp;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function __invoke(RequestInterface $request)
    {
        $uri = $request->getUri();

        $payload = [
          'payload' => [
            'key' => $this->token
          ],
        ];

        $jws = new JWS([
          'typ' => 'JWT',
          'alg' => 'HS256',
        ]);
        $jws->setPayload($payload)->sign($this->secret);
        $token = $jws->getTokenString();

        /** @var RequestInterface $request */
        $query = Psr7\parse_query($uri->getQuery());
        $query['jwt'] = $token;
        $uri = $uri->withQuery(Psr7\build_query($query));
        $request = $request->withUri($uri);

        return $request->withHeader('Authorization', 'JWT Token='.$this->token.'');
    }
}
