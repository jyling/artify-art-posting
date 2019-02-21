
<?php
class Page{
  public static function redirect($target, $params = array()){
    $url = $target . self::urlGetMaker($params);
    header("Location: $url");
    ob_end_flush();
    exit();
  }

  public static function urlGetMaker($params = array()){
    $url = '';
    $counter = 0;
    foreach ($params as $key => $value) {
      if ($counter == 1) {
        $url .= "&$key=$value";
      }
      else {
        $url .= "?$key=$value";
        $counter++;
      }
    }
    return $url;
  }
  public static function addHead() {
    $read = new Reader();
    $read->read('header.txt');
    if ($read->success()) {
      echo $read->modify(array(
        '$pageName' => self::getPageName(),
        '$cssOrigin' => Settings::get('css>source')
      ));
    }
  }

  public static function addFoot() {
    $read = new Reader();
    $read->read('tail.txt');
    if ($read->success()) {
      echo $read->modify();
    }
  }

  public static function getPageName(){
    $a = Settings::get('nav>items');
    foreach ($a as $key => $value) {
      $lookup[$value['file']] = $key;
    }
      $pageName = $lookup[lcfirst(self::pageFile())];
    return $pageName;

  }
  public static function pageFile(){
    return basename($_SERVER['SCRIPT_NAME']);
  }

  public static function addNav() {
    $path = new Settings();
    $content = $path->get('nav>items');
    $user = new User();
    echo "<nav class='navbar navbar-expand-sm bg-dark navbar-dark'>";
    echo "<ul class='navbar-nav'> <a class='navbar-brand' href='" . $path->get('nav>brand>url') . "'>".$path->get('nav>brand>title')."</a>";
    foreach ($content as $key => $link) {
      if (isset($link['showOnLogin'])) {
        if (
          (!$user->getLogin()
          &&
          $link['showOnLogin'] == 1)
          ||
          ($user->getLogin()
          &&
          $link['showOnLogin'] == 0)) {
          continue;
        }
      }
      elseif(isset($link['visible'])) {
        if ($link['visible'] == false) {
          continue;
        }
      }

      if (self::pageFile() !== $link['file']) {
        echo <<<nav
        <li class="nav-item">
        <a class="nav-link" href="$link[file]">$key</a>
        </li>
nav;
      }
      else {
        echo <<<nav
        <li class="nav-item">
        <a class="nav-link active" href="#" disabled>$key <span class="sr-only">(current)</span></a>
        </li>
nav;
      }
    }
    echo "</ul>";
    echo "</nav>";
  }

public static function alertUser($input){
  echo "<script>alert('$input')</script>";
}

}
