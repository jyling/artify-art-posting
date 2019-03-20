<?php
class Page
{
    public static function redirect($target, $params = array())
    {
        $url = $target . self::urlGetMaker($params);
        header("Location: $url");
        ob_end_flush();
        exit();
    }
    public static function loadScript()
    {
        $name = self::getPageName();
        $list = Settings::get('nav>items>' . $name);
        if (isset($list['javascript'])) {
            return $list['javascript'];
        }
    }
    public static function printModal($title, $msg, $type, $target)
    {
        $read = new Reader();
        $read->read('modal.txt');
        if ($read->success()) {
            Session::flash('modal', $read->modify(array(
                '$modalTitle'   => $title,
                '$modalcontent' => $msg,
                '$buttonType'   => $msg,
            )));
            Page::redirect($target);
        }
    }
    public static function hiddenGet()
    {
        foreach ($_GET as $name => $value) {
            echo "<input type='hidden' name='$name' value='$value'>";
        }
    }
    public static function urlGetMaker($params = array())
    {
        $url     = '';
        $counter = 0;
        foreach ($params as $key => $value) {
            if ($counter == 1) {
                $url .= "&$key=$value";
            } else {
                $url .= "?$key=$value";
                $counter++;
            }
        }
        return $url;
    }
    public static function addHead()
    {
        $read = new Reader();
        $read->read('header.txt');
        $script = self::loadScript();
        if ($read->success()) {
            echo $read->modify(array(
                '$pageName'   => self::getPageName(),
                '$javascript' => $script,
                '$cssOrigin'  => Settings::get('css>source'),
            ));
            if (Session::exist('modal')) {
                echo Session::flash('modal');
            }
            $ban = new Ban();
            $ban->banlift();
        }
    }

    public static function addFoot()
    {
        $read = new Reader();
        $read->read('footer.txt');
        if ($read->success()) {
            echo $read->modify();
        }
    }

    public static function getPageName()
    {
        $a = Settings::get('nav>items');
        foreach ($a as $key => $value) {
            $lookup[$value['file']] = $key;
        }
        $pageName = $lookup[lcfirst(self::pageFile())];
        return $pageName;

    }
    public static function autoKick()
    {
        $a = Settings::get('nav>items');
        foreach ($a as $key => $value) {
            $lookup[$value['file']] = $key;
        }
        $pageName = $lookup[lcfirst(self::pageFile())];
        if (isset($a[$pageName]['RequireLogin'])) {
            if ($a[$pageName]['RequireLogin'] == true && !Session::exist('id')) {
                Session::flash('login', "Please Login First");
                Page::redirect('login.php');
            }
        }

    }
    public static function pageFile()
    {
        return basename($_SERVER['SCRIPT_NAME']);
    }

    public static function addNav()
    {
        $path    = new Settings();
        $content = $path->get('nav>items');
        $user    = new User();
        echo "<nav class='navbar navbar-expand-sm bg-dark navbar-dark'>";
        echo "<ul class='navbar-nav'> <a class='navbar-brand' href='" . $path->get('nav>brand>url') . "'>" . $path->get('nav>brand>title') . "</a>";
        foreach ($content as $key => $link) {
            $show = true;
            if (isset($link['showOnLogin'])) {
                if (
                    (!$user->getLogin()
                        &&
                        $link['showOnLogin'] == 1)
                    ||
                    ($user->getLogin()
                        &&
                        $link['showOnLogin'] == 0)) {
                    $show = false;
                }
            }
            if (isset($link['visible'])) {
                if ($link['visible'] == false) {
                    $show = false;
                }
            }
            if (isset($link['permission'])) {
                if ($user->getLogin()) {
                    $show = false;
                    foreach ($link['permission'] as $i) {
                        $usrPerms = $user->getPermission();
                        if ($usrPerms->usr->accType == $i) {
                            $show = true;
                        }
                    }
                }
            }
            if ($show) {
                if (self::pageFile() !== $link['file']) {
                    echo <<<nav
          <li class="nav-item">
        <a class="nav-link" href="$link[file]"> $key</a>
        </li>
nav;
                } else {
                    echo <<<nav
        <li class="nav-item">
        <a class="nav-link active" href="#" disabled>$key <span class="sr-only">(current)</span></a>
        </li>
nav;
                }
            }
        }
        echo "</ul>";
        echo "</nav>";
        ?>
<div class="d-block bg-dark text-white" style="max-width: 120px">
    <p class="m-3 badge badge-primary" style="font-size: 1rem;">coin : <?php echo (new Coin())->checkCoin() ?></p>
</div>

<?php
}

    public static function alertUser($input)
    {
        echo "<script>
alert('$input')
</script>";
    }

}