<?php
class Permission
{
    private $_this;
    public static function update($permission, $values = array())
    {
        $permission = $permission->usr->permission;
        foreach ($values as $value => $condition) {
            switch ($value) {
                case 'post':
                    $permission->post = self::onlyAllowBool($condition);
                    break;
                case 'comment':
                    $permission->comment = self::onlyAllowBool($condition);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $permission;
    }
    public static function kick()
    {
        $id = Session::get('id');
        if (((new User())->getPermission(Session::get('id'))->usr->accType == 'artist' || (new User())->getPermission(Session::get('id'))->usr->accType == 'normal')) {Page::redirect('index.php');}
    }
    public static function add($permission, $rules = array())
    {
        $permission = json_decode(json_encode($permission), true);
        foreach ($rules as $value => $condition) {
            if (!self::exist($permission, $value)) {
                $permission[$value] = $condition;
            } else {
                die("rules $value already existed in the json");
            }
        }
        $permission = json_decode(json_encode($permission));
        return $permission;
    }
    public static function exist($input, $index)
    {
        return isset($input[$index]);

    }
    public static function onlyAllowBool($condition)
    {
        if ($condition == 0 || $condition == 1) {
            return $condition;
        }
        return false;

    }
    public static function validate($permission, $rules = array())
    {
        foreach ($rules as $rule => $condition) {
            switch ($rule) {
                case 'post':
                    if ($permission->post != $condition) {return false;}
                    break;
                case 'delete':
                    if ($permission->delete != $condition) {return false;}
                    break;
                case 'ban':
                    if ($permission->ban != $condition) {return false;}
                    break;
                case 'owner':
                    if ($permission->owner != $condition) {return false;}
                    break;
                default:
                    # code...
                    break;
            }
        }
        return true;
    }
}

/*
$test = array(
'post' => true,
'owner' => true
);
$ob = json_decode('{
"usr": {
"accType" : "normal",
"permission": {
"post": true,
"delete": false,
"ban": false,
"owner": true
}
}
}');

$permission = new Permission();
var_dump($permission->validate($ob->usr->permission,$test));

$ob->usr->permission = $permission->update($ob->usr->permission,array(
'owner' => true
));

// die();
var_dump($permission->validate($ob->usr->permission,$test));
$ob->usr->permission = $permission->add($ob->usr->permission,array(
'comment' => true
));
echo "<pre>".json_encode($ob,JSON_PRETTY_PRINT)."</pre>";

echo '<br>';
$ob->usr->permission = $permission->add($ob->usr->permission,array(
'subscription' => true
));

echo "<pre>".json_encode($ob,JSON_PRETTY_PRINT)."</pre>";

$ob->usr->permission = $permission->add($ob->usr->permission,array(
'subscription' => true
));

echo "<pre>".json_encode($ob,JSON_PRETTY_PRINT)."</pre>";

bool(true) bool(true)
{
"usr": {
"accType": "normal",
"permission": {
"post": true,
"delete": false,
"ban": false,
"owner": true,
"comment": true
}
}
}

{
"usr": {
"accType": "normal",
"permission": {
"post": true,
"delete": false,
"ban": false,
"owner": true,
"comment": true,
"subscription": true
}
}
}
rules subscription already existed in the json
 */