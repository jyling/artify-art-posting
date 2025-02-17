<?php
class Captcha{
  private static $_seckey = '6Lec9YQUAAAAAKugwbkkDYMOtRfzH_lwmiZ99EI-';
  private static $_pubkey = '6Lec9YQUAAAAALIPEDc2TJmKh0Tyq04J5he6v3QS';
  private static $_errors = array(
    'missing-input-secret' => 'an Error Has Occured',
    'invalid-input-secret' => 'an Error Has Occured',
    'missing-input-response' => 'an Error Has Occured',
    'invalid-input-response' => 'an Error Has Occured',
    'bad-request' => 'an Error Has Occured',
    'timeout-or-duplicate' => 'You are submiting the form with the same infromation twice',

  );
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
  self::addNote();
  }

  public static function addNote(){
    echo<<<end
    <p>
   This site is protected by reCAPTCHA and the Google
   <a href="https://policies.google.com/privacy">Privacy Policy</a> and
   <a href="https://policies.google.com/terms">Terms of Service</a> apply.
    </p>
end;
  }

  public static function errorCode($name){
    return self::$_errors[$name];

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
