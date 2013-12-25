<?php
include "faucet.php";
include "includes/recaptchalib.php";

if(isset($_POST['submit']))
{
$resp = recaptcha_check_answer ($faucet->config['recaptcha_private'],
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
die( "The CAPTCHA was invalid." );
}

$address = $_POST['address'];
$ip = $_SERVER['REMOTE_ADDR'];
$result = $faucet->drip($address, $ip);

$message = "You won {$faucet->sent_amount} TIPS! txid: {$result}";
if($result == 1)
    $message = "The address you entered is invalid.";
else if($result == 2)
    $message = "Sorry, but you've already had coins in the last 12 hours.";
else if($result == 3)
    $message = "There aren't enough coins in the faucets wallet for us to drip you some.";

echo $message;

} else {

$captcha = recaptcha_get_html($faucet->config['recaptcha_public']);
echo <<<ENDHTML
The TIPS faucet is now open! Enter your address below to win between {$faucet->config['min_amount']} and {$faucet->config['max_amount']} TIPS!<br /><br />
<form action="index.php" method="POST">
Wallet address: <input type="text" name="address" id="address" /><br />
CAPTCHA:
{$captcha}
<br />
<input type="submit" name="submit" id="submit" value="Send me TIPS!" />
</form>
ENDHTML;
}
?>