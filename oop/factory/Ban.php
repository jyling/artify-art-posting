<?php
class Ban
{
    public function has($id = null)
    {
        $db = DB::run();

        if ($id == null) {
            $db->multiCon('SELECT *', 'ban', array(
                'usr_id' => Session::get('id'),
                'valid'  => 1,
            ));
            return ($db->getCount() > 0) ? true : false;
        }
        $db->multiCon('SELECT *', 'ban', array(
            'usr_id' => Session::get('id'),
            'valid'  => 1,
        ));
        return ($db->getCount() > 0) ? true : false;

    }

    public function valid($id)
    {
        $data     = $this->get($id);
        $banSince = $data->ban_date;
        $banTill  = $data->ban_till;

        if (new DateTime() > new DateTime("$banTill")) {
            return false;
        } else {
            return true;
        }

    }

    public function banLength($id)
    {
        $data     = $this->get($id);
        $banSince = $data->ban_date;
        $banTill  = $data->ban_till;
        $banSince = new DateTime(date('Y-m-d', strtotime($data->ban_date)));
        $banTill  = new DateTime(date('Y-m-d', strtotime($data->ban_till)));
        return $banSince->diff($banTill)->days;
    }

    public function banleft($id)
    {
        $data      = $this->get($id);
        $banTill   = $data->ban_till;
        $output    = array();
        $datetime1 = new DateTime("now");
        $datetime2 = date_create($banTill);
        $diff      = date_diff($datetime1, $datetime2);
        $allowed   = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second');
        foreach ($diff as $val => $a) {
            if ($diff->{$val} > 0 && in_array($val, array_keys($allowed))) {
                $output[] = $diff->{$val} . " $allowed[$val]" . ($diff->{$val} > 1 ? "s" : '');
            }
        }
        return implode(',', $output);

    }
    public function get($id)
    {
        $db = DB::run();
        $db->multiCon('SELECT *', 'ban', array(
            'usr_id' => $id,
            'valid'  => 1,
        ))->sortResult('ban_id');
        return $db->getResult()[0];
    }

    public function doBan($id, $length, $msg)
    {
        $user = new User($id);

        if ($user->getPermission($user->getData()->usr_id)->usr->accType != 'admin') {

            $db         = DB::run();
            $today      = (new DateTime())->format("Y-m-d H:i:s");
            $bannedTill = (new DateTime('+' . $length . ' day'))->format("Y-m-d H:i:s");
            $date       = array(
                'ban_date'   => $today,
                'ban_till'   => $bannedTill,
                'ban_reason' => $msg,
                'usr_id'     => $id,
            );
            $db->insert('ban', $date);
            $this->revokePermission($id);
        }
    }

    public function banlift($id = null)
    {
        if ($id == null) {
            $id = Session::get('id');
        }
            if ($this->has($id)) {
                if (!$this->valid($id)) {
                    $this->resetPermission($id);
                    $this->destroyBan($id);
                }

            }
    }


    public function forcedBanLift($id) {
        $this->resetPermission($id);
        $this->destroyBan($id);
    }
    public function destroyBan($id)
    {
        $db   = DB::run();
        $data = $this->get($id);
        // print_r($data);
        $db->update('ban', array('ban_id' => $data->ban_id), array(
            'valid' => false,
        ));
    }
    public function resetPermission($id)
    {
        $target = new User($id);
        if ($target->find($id)) {
            $userPermission                  = $target->getPermission($id);
            $userPermission->usr->accType    = 'normal';
            $userPermission->usr->permission = Permission::update($userPermission, array(
                'post'    => false,
                'comment' => true,
            ));
            $userPermission = json_encode($userPermission);
            $target->updateGroup(array(
                'permission' => $userPermission,
            ), $id);
        }
    }

    public function revokePermission($id)
    {
        $target = new User($id);
        if ($target->find($id)) {
            $userPermission                  = $target->getPermission($id);
            $userPermission->usr->accType    = 'ban';
            $userPermission->usr->permission = Permission::update($userPermission, array(
                'post'    => false,
                'comment' => false,
            ));
            // die(var_dump($userPermission));
            $userPermission = json_encode($userPermission);
            $target->updateGroup(array(
                'permission' => $userPermission,
            ), $id);
        }
    }
}