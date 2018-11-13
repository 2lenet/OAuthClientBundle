<?php

namespace Lle\OAuthClientBundle\Service;

use GuzzleHttp\Client;

class OAuthApi{

    private $guzzle;

    public function __construct(Client $guzzle){
        $this->guzzle = $guzzle;
    }

    public function url($url, $method = "GET", $data = null){

        $response = $this->guzzle->request('POST', "/api/login_check"
            ,[
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(["username" => "tmpuser","password"=> "tmppassword"])
            ]);
        $r = json_decode($response->getBody()->getContents());
        $options = ['headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer '.$r->token
        ]];
        if($data){
            $options['body'] = json_encode($data);
        }
        $response = $this->guzzle->request($method, $url, $options);
        return json_decode($response->getBody()->getContents());
    }

    public function delete($id){
        return $this->url('/api/users/'.$id, 'DELETE');
    }

    public function get($page = 1, $code = null){
        $users = $this->url("/api/users?pagination=false&page=".$page."&codeClient=".$code."%2F");
        foreach($users as $user){
            $user->lastConnection = ($user->lastConnection)? new \DateTime($user->lastConnection):null;
        }
        return $users;
    }

    public function put($id, $data){
        foreach($data as $k => $v){
            if($k === 'isActive'){
                $data[$k] = (bool)$v;
            }
        }
        return $this->url('/api/users/'.$id, 'PUT', $data);
    }

    public function post($data){
        return $this->url('/api/utils/users/add', 'POST', $data);
    }

    public function getRoles(){
        $data = $this->url('/api/utils/roles');
        $roles = [];
        foreach($data as $k => $v){
            if($k !== 'ROLE_USER') {
                $roles[str_replace('_', ' ', str_replace('ROLE', '', $k))] = $k;
            }
        }
        return $roles;
    }
}