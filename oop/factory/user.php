<?php

class User
{
    private $_db,
    $_data,
    $_isLoggedIn,
    $_cookieName,
    $_roles = array(
        'normal' => 'Normal Account',
        'mod'    => 'Moderator',
        'admin'  => 'Admin',
        'artist' => 'Artist',
        'ban'    => 'Banned',
    );
    public function __construct($user = null)
    {
        $this->_cookieName = Settings::get('remberMe>cookie_name');
        $this->_db         = DB::run();

        if ($user == null) {
            $id = Session::get('id');
            if ($this->find($id)) {
                $this->_isLoggedIn = true;
            }
        } else {
            return $this->find($user);
        }
    }
    public function addUser($params = array())
    {
        if (!$this->_db->insert('usr', $params)) {
            throw new \Exception("Error Processing Request");
        }
        $result = $this->find($params['usrnm']);
        $this->_db->insert('usr_group', array(
            'usr_id'     => $this->_data->usr_id,
            'permission' => '
                        {
                          "usr": {
                            "accType" : "normal",
                            "permission": {
                              "post": false,
                              "comment": false
                            }
                          }
                        }',
        ));

    }
    public function getArtist()
    {
        DB::run()->getAll('usr_group');
        $users      = DB::run()->getResult();
        $artistList = array();
        foreach ($users as $user) {
            if ($this->getPermission($user->usr_id)->usr->permission->post) {
                $this->find($user->usr_id);
                $artistList[$user->usr_id] = $this->_data->usrnm;
                // $artistList[] = $this->_data->usrnm;

            }
        }
        return $artistList;
    }
    public function getRole($usr = null)
    {
        if ($usr == null) {
            if (Session::exist('id')) {
                $id = Session::get('id');
                if ($this->find($id)) {
                    DB::run()->get('usr_group', array('usr_id', '=', "$id"));
                    $data = DB::run()->getResult()[0];
                    $role = json_decode($data->permission)->usr->accType;
                    if (array_key_exists($role, $this->_roles)) {
                        return $this->_roles[$role];
                    } else {
                        return 'unknown';
                    }
                }
            }
        } elseif ($usr !== null) {
            if ($this->find($usr)) {
                DB::run()->get('usr_group', array('usr_id', '=', "$usr"));
                $data = DB::run()->getResult()[0];
                $role = json_decode($data->permission)->usr->accType;
                if (array_key_exists($role, $this->_roles)) {
                    return $this->_roles[$role];
                } else {
                    return 'unknown';
                }
            }
        }
    }
    public function find($term = null)
    {
        if (is_numeric($term)) {
            $data = DB::run()->get('usr', array('usr_id', '=', $term));
        } else {
            $data = DB::run()->get('usr', array('usrnm', '=', $term));
        }
        if ($data->getCount()) {
            $this->_data = $data->getResult()[0];
            return true;
        }
        return false;

    }
    public function update($params = array(), $id = null)
    {
        if ($id === null && $this->_isLoggedIn === true) {
            $id = $this->getData()->usr_id;
        }
        if (!DB::Run()->update('usr', array('usr_id' => $id), $params)) {
            throw new Exception('update error');
        } else {
            return true;
        }
    }
    public function updateGroup($params = array(), $id = null)
    {
        if ($id === null && $this->_isLoggedIn === true) {
            $id = $this->getData()->usr_id;
        }
        if (!DB::Run()->update('usr_group', array('usr_id' => $id), $params)) {
            throw new Exception('update error');
        } else {
            return true;
        }
    }
    public function getData()
    {
        return $this->_data;
    }
    public function verifyPass($pass, $passhash)
    {
        $passSalt = $pass;
        if (password_verify($passSalt, $passhash)) {
            return true;
        }
        return false;
    }
    public function Login($usrname = null, $pass = null, $remember = false)
    {
        $username = Validate::sanitize($usrname);
        $pass     = Validate::sanitize($pass);
        if ($this->find($usrname)) {
            $salt = $this->_data->pwd;
            if ($this->verifyPass($pass, $salt)) {
                if ($remember) {
                    $hash      = PassHasher::randHash();
                    $hashCheck = $this->_db->get('session', array(
                        'usr_id', '=', $this->getData()->id));
                    if (!$hashCheck->getCount()) {
                        $this->_db->insert('session', array(
                            'usr_id' => $this->getData()->id,
                            'hash'   => $hash,
                        ));
                    } else {
                        $hash = $hashCheck->getResult()[0]->hash;
                    }
                    Cookie::bake($this->_cookieName, $hash, Settings::get('remberMe>cookie_expiry'));
                }
                return true;
            }
        }
        return false;
    }
    public function getPermission($usr = null)
    {
        if ($usr == null) {
            if (Session::exist('id')) {
                $id = Session::get('id');
                if ($this->find($id)) {
                    DB::run()->get('usr_group', array('usr_id', '=', "$id"));

                    $data = DB::run()->getResult()[0];
                    return json_decode($data->permission);
                }
            }

        } elseif ($usr !== null) {
            if ($this->find($usr)) {
                DB::run()->get('usr_group', array('usr_id', '=', "$usr"));
                $data = DB::run()->getResult()[0];
                return json_decode($data->permission);
            }
        }
    }
    public function getLogin()
    {
        return $this->_isLoggedIn;
    }
}
