<?php

namespace Square1\TollbridgeSocialiteProvider;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;

class TollbridgeSocialiteProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);
        $url = config('tollbridge.account_url').'/oauth/authorize?'.$query;

        return $url;
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return config('tollbridge.account_url').'/oauth/token';
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $user = $this->httpClient->get(config('tollbridge.account_url').'/api/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($user->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->map([
            'name' => Arr::get($user, 'firstname') . ' ' . Arr::get($user, 'lastname'),
            'email' => Arr::get($user, 'email'),
            'plan' => Arr::get($user, 'plan'),
            'id' => Arr::get($user, 'id'),
            'avatar' => Arr::get($user, 'avatar'),
        ]);
    }

    /**
    * Get the POST fields for the token request.
    *
    * @param  string  $code
    * @return array
    */
    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code', // updated
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];
    }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $this->getTokenFields($code),
        ]);

        $response = json_decode($response->getBody(), true);

        return $response;
    }
}
