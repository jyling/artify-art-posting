<?php
class Account
{
    public function update($json, $type = 'm')
    {
        $db      = DB::run();
        $accType = '';
        switch (strToLower($type)[0]) {
            case 'n':
                $accType = 'normal';
                break;
            case 'a':
                $accType = 'admin';
                break;
            case 'm':
                $accType = 'moderator';
                break;
            case 'b':
                $accType = 'banned';
                break;
            default:
                # code...
                break;
        }
        $json->accType = $accType;
        return $json;
    }
}