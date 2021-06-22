<?php

class userSession 
{
    public $userId;
    public $userStatus;
    public $userName;

    public function __construct()
	{
		if(session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}

    public function create()
    {
        $_SESSION['user'] = [
            'userId'    => $this->userId,
            'userName'  => $this->userName,
            'userStatus' => $this->userStatus
        ];
    }

    public function destroy()
    {
        $_SESSION = array();
        session_destroy();
    }

    public function getUserId()
    {
        if($this->isAuthenticated() == false) {
            return null;
        }
        return $_SESSION['user']['userId'];
    }

    public function getUserName() {
        if($this->isAuthenticated() == false) {
            return null;
        }
        return $_SESSION['user']['userName'];
    }

    public function getUserStatus() {
        if($this->isAuthenticated() == false) {
            return null;
        }
        return $_SESSION['user']['userStatus'];

    }

	public function isAuthenticated()
	{
		if(array_key_exists('user', $_SESSION)) {
			if(!empty($_SESSION['user'])) {
				return true;
			}
		}
		return false;
	}
}