<?php
class Captcha{
  private static $_seckey = '6Lec9YQUAAAAAKugwbkkDYMOtRfzH_lwmiZ99EI-';
  private static $_pubkey = '6Lec9YQUAAAAALIPEDc2TJmKh0Tyq04J5he6v3QS';
  public static function add() {
    $key = self::$_pubkey;
    echo <<<end
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback"
    async defer></script>
    <script>
      var onloadCallback = function() {
          grecaptcha.execute();
        };

      function setResponse(response) {
        document.getElementById('captcha-response').value = response;
      }
    </script>
    <div class="g-recaptcha" data-sitekey="$key"
    data-badge="inline" data-size="invisible" data-callback="setResponse"></div>


end;
  }
  public static function verify($post){
    if (!isset($post['g-recaptcha-response']) || !empty($post['g-recaptcha-response'])) {
      return self::getResponse($post['g-recaptcha-response']);
    }

  }
  public static function getResponse($response) {
    $key = self::$_seckey;
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$key&response=$response";
    $verifyResponse = file_get_contents($url);
    return json_decode($verifyResponse,true);
  }
}
