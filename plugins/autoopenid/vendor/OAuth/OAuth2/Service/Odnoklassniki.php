<?php

namespace OAuth\OAuth2\Service;

use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Uri\UriInterface;

class Odnoklassniki extends AbstractService
{
	/**
	 * Defined scopes, see http://apiok.ru/wiki/pages/viewpage.action?pageId=81822097 for definitions.
	 */
	const SCOPE_VALUABLE_ACCESS                   = 'VALUABLE_ACCESS';
	const SCOPE_GROUP_CONTENT                     = 'GROUP_CONTENT';
	const SCOPE_VIDEO_CONTENT                     = 'VIDEO_CONTENT';
	const SCOPE_APP_INVITE                        = 'APP_INVITE';
	const SCOPE_MESSAGING                         = 'MESSAGING';

	public function __construct(
		CredentialsInterface $credentials,
		ClientInterface $httpClient,
		TokenStorageInterface $storage,
		$scopes = array(),
		UriInterface $baseApiUri = null
	) {
		parent::__construct($credentials, $httpClient, $storage, $scopes, $baseApiUri);

		if (null === $baseApiUri) {
			$this->baseApiUri = new Uri('http://api.odnoklassniki.ru/fb.do');
		}
	}

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint()
    {
        return new Uri('http://www.odnoklassniki.ru/oauth/authorize');
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri('http://api.odnoklassniki.ru/oauth/token.do');
    }

	/**
	 * {@inheritdoc}
	 */
	protected function getAuthorizationMethod()
	{
		return static::AUTHORIZATION_METHOD_QUERY_STRING;
	}

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);
		$token->setLifeTime(30);

		if (isset($data['refresh_token'])) {
			$token->setRefreshToken($data['refresh_token']);
			unset($data['refresh_token']);
		}

        unset($data['access_token']);

        $token->setExtraParams($data);

        return $token;
    }

	/**
	 * {@inheritdoc}
	 */
	public function request($path, $method = 'GET', $body = null, array $extraHeaders = array())
	{
		$token = $this->storage->retrieveAccessToken($this->service());

		$uri = $this->determineRequestUriFromPath($path, $this->baseApiUri);
		//$uri->addToQuery('format', 'json');

		parse_str($uri->getQuery(), $params);
		ksort($params);
		$paramsString = '';
		foreach ($params as $key => $value) {
			$paramsString .= "$key=$value";
		}
		$sig = md5($paramsString . md5($token->getAccessToken().$this->credentials->getConsumerSecret()));
		$uri->addToQuery('sig', $sig);

		return parent::request($uri, $method, $body, $extraHeaders);
	}
}
