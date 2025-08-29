<?php

namespace Lle\OAuthClientBundle\Service;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @deprecated Use ConnectApiService
 */
class OAuthApi
{

    private $guzzle;

    private $username;

    private $password;

    public function __construct(string $apiconnect, string $username, string $password)
    {
        $this->guzzle = new Client(['base_uri' => $apiconnect]);
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @deprecated Use ConnectApiClient
     */
    public function url($url, $method = "GET", $data = null)
    {
        $options = [
            'auth' => [$this->username, $this->password],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]];
        if ($data) {
            $options['body'] = json_encode($data);
        }
        $response = $this->guzzle->request($method, $url, $options);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @deprecated Use ConnectApiService::delete
     */
    public function delete($id)
    {
        return $this->url('/api/users/' . $id, 'DELETE');
    }

    /**
     * @deprecated Use ConnectApiService::list
     */
    public function autoCompletion($query, $codeClient = null, $page = null): JsonResponse
    {
        $url = "/api/utils/users?query=" . $query . (($codeClient) ? '&codeclient=' . $codeClient : '');
        if ($page) $url .= '&page=' . $page;

        return new JsonResponse($this->url($url, 'POST'));
    }

    /**
     * @deprecated Use ConnectApiService::list
     */
    public function get(array $data = [])
    {
        $url = '/api/users?';
        $i = 0;
        foreach ($data as $k => $param) {
            $url .= (($i === 0) ? '' : '&') . $k . '=' . $param;
            $i++;
        }
        $users = $this->url($url);
        foreach ($users as $user) {
            $user->lastConnection = ($user->lastConnection) ? new \DateTime($user->lastConnection) : null;
        }

        return $users;
    }

    /**
     * @deprecated Use ConnectApiService::get
     */
    public function find($id)
    {
        return $this->get(['id' => $id])[0];
    }

    /**
     * @deprecated Use ConnectApiService::edit
     */
    public function put($id, $data)
    {
        foreach ($data as $k => $v) {
            if ($k === 'isActive') {
                $data[$k] = (bool)$v;
            }
        }

        return $this->url('/api/users/' . $id, 'PUT', $data);
    }

    /**
     * @deprecated Use ConnectApiService::edit
     */
    public function putPassword($id, $password)
    {
        return $this->url('/api/utils/pass/' . $id, 'PUT', ['pass' => $password, 'hash' => md5($id . $password)]);
    }

    /**
     * @deprecated Use ConnectApiService::resetPassword
     */
    public function resetPassword($id)
    {
        return $this->url('/api/utils/users/reset/' . $id, 'POST');
    }

    /**
     * @deprecated Use ConnectApiService::new
     */
    public function post($data)
    {
        return $this->url('/api/utils/users/add', 'POST', $data);
    }

    /**
     * @deprecated Use ConnectApiService::edit
     */
    public function update($id, $data)
    {
        return $this->url('/api/utils/users/edit/' . $id, 'POST', $data);
    }

    /**
     * @deprecated Removed
     */
    public function sendEmail()
    {
        return $this->url('/api/utils/send', 'POST');
    }

    /**
     * @deprecated Removed
     */
    public function checkUser(string $username, string $password)
    {
        $options = [
            'auth' => [$username, $password],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]];
        try {
            $response = $this->guzzle->request('POST', '/profile', $options);
        } catch (\Exception $exception) {
            return null;
        }
        if ($response->getStatusCode() === 200) {
            return $this->get(['username' => $username])[0]->id;
        } else {
            return null;
        }
    }

}
