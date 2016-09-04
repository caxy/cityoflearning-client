<?php

namespace Caxy\CityOfLearning;

use GuzzleHttp\Psr7;
use Namshi\JOSE\JWS;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

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
        /** @var UriInterface $uri */
        $uri = $request->getUri();

        $payload = [
          'payload' => [
            'key' => $this->token,
          ],
        ];

        if (in_array($request->getMethod(), ['PUT', 'POST'])) {
            $json = \GuzzleHttp\json_decode($request->getBody(), true);
            $payload['payload'] = array_merge($payload['payload'], $json);
        }

        $jws = new JWS([
          'typ' => 'JWT',
          'alg' => 'HS256',
        ]);
        $jws->setPayload($payload)->sign($this->secret);
        $token = $jws->getTokenString();

        $query = Psr7\parse_query($uri->getQuery());
        $query['jwt'] = $token;
        $uri = $uri->withQuery(Psr7\build_query($query));
        $request = $request->withUri($uri);

        return $request->withHeader('Authorization', 'JWT Token='.$this->token.'');
    }
}
