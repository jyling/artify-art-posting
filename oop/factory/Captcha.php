<?php
class Captcha{
  private static $seckey = '6Lec9YQUAAAAAKugwbkkDYMOtRfzH_lwmiZ99EI-';
  public static function verify($post){
    if (!isset($post['g-recaptcha-response']) || !empty($post['g-recaptcha-response'])) {
      return self::getResponse($post['g-recaptcha-response']);
    }

  }

  public static function getResponse($response) {
    $key = self::$seckey;
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$key&response=$response";
    $verifyResponse = file_get_contents($url);
    return json_decode($verifyResponse,true);
  }
}
print_r($_POST);
print_r(Captcha::verify($_POST));

?>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
<script>
var onloadCallback = function() {
    grecaptcha.execute();
};

function setResponse(response) {
    document.getElementById('captcha-response').value = response;
}
</script>
<form action="" method="post">
    <input type="text" name="name" placeholder="Your name" required >
    <input type="email" name="email" placeholder="Your email address" required>
    <textarea name="message" placeholder="Type your message here...." required></textarea>

    <!-- Google reCAPTCHA widget -->
    <div class="g-recaptcha" data-sitekey="6Lec9YQUAAAAALIPEDc2TJmKh0Tyq04J5he6v3QS" data-badge="inline" data-size="invisible" data-callback="setResponse"></div>

    <input type="hidden" id="captcha-response" name="captcha-response" />
    <input type="submit" name="submit" value="SUBMIT">
</form>
