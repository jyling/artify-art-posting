
<?php
class Page{
  public static function redirect($target, $params = array()){
    $url = $target . self::urlGetMaker($params);
    header("Location: $url");
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
    $a = array_flip(Settings::get('nav>items'));
    $pageName = $a[self::pageFile()];
    return $pageName;

  }
  public static function pageFile(){
    return basename($_SERVER['SCRIPT_NAME']);
  }

  public static function addNav() {
    $path = new Settings();
    $content = $path->get('nav>items');
    echo "<nav class='navbar navbar-expand-sm bg-dark navbar-dark'>";
    echo "<ul class='navbar-nav'> <a class='navbar-brand' href='" . $path->get('nav>brand>url') . "'>".$path->get('nav>brand>title')."</a>";
    foreach ($content as $key => $link) {
      if (self::pageFile() !== $link) {
        echo <<<nav
        <li class="nav-item">
        <a class="nav-link" href="$link">$key</a>
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
