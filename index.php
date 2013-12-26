<?php
include "faucet.php";
include "includes/recaptchalib.php";

$error = false;
$success = false;

$captcha = "<script type=\"text/javascript\" src=\"http://www.google.com/recaptcha/api/challenge?k={$faucet->config['recaptcha_public']}\"></script>";

if(isset($_POST['submit'])){
	$resp = recaptcha_check_answer($faucet->config['recaptcha_private'],$_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	
	if($resp->is_valid){
		//The CAPTCHA was good, let's get on
		$address = $_POST['address'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$result = $faucet->drip($address, $ip);

		if(!is_string($result)){
			$error = true;
			switch($result){
				case 1:
					$error_msg = "The address you entered is invalid.";
					break;
				case 2:
					$error_msg = "Sorry, but you've already had coins in the last 12 hours.";
					break;
				case 3:
					$error_msg = "There aren't enough coins in the faucets wallet for us to drip you some.";
					break;
				case 4:
					$error_msg = "There was an error sending the coins.";
				default:
					$error_msg = "An unknown error occurred";
			}
		}else{
			//Whoohoo, everything worked out
			$success = true;
		}
	}else{
		$error = true;
		$error_msg = "Your CAPTCHA was invalid, please try again.";
	}
}
?>
<!doctype html>
<html>
	<head>
		<title>FedoraCoin &raquo; Faucet</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		
		<script type="text/javascript">
		var RecaptchaOptions = {
			theme : 'custom',
			custom_theme_widget: 'recaptcha_widget'
		};
		</script>
	</head>
	<body>
		<section id="faucet">
			<nav>
				<div class="container">
					<span class="brand">
						<img src="img/logo.png"> FedoraCoin
					</span>
					<span class="page-title">
						Faucet
					</span>
				</div>
			</nav>
			<div id="content">
				<div class="container">
					<p class="lead">
						The TIPS faucet is now open! <small>Enter your address below to win between <?php echo $faucet->config['min_amount']; ?> and <?php echo $faucet->config['max_amount']; ?> TIPS!</small>
					</p>
					<?php
						if($error){
							echo "<p class='text-danger'>$error_msg</p>";
						}
					?>
					<?php
						if($success){
							print <<<ENDHTML
					<p class="text-success">You got {$faucet->sent_amount} TIPS! Your transaction id is <a href="http://fedorachain.info/tx/{$result}">{$result}</a>.</p>
ENDHTML;
						}else{
							print <<<ENDHTML
					<form action="index.php" method="POST" class="form-horizontal center-block" role="form">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Wallet address</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="inputWallet" name="address" placeholder="E9001FOR1337EUPHORIC">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">reCAPTCHA</label>
							<div class="col-sm-9">
								<a id="recaptcha_image" href="#" class="thumbnail"></a>
								<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="recaptcha_only_if_image control-label col-sm-3">Enter the CAPTCHA</label>
							<label class="recaptcha_only_if_audio control-label col-sm-3">Enter what you hear</label>

							<div class="col-sm-9">
								<div class="input-group">
									<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="input-recaptcha form-control" />
									<span class="input-group-btn">
										<button class="btn btn-default" href="#" onclick="Recaptcha.reload();return false;"><span class="glyphicon glyphicon-refresh"></span></button>
										<button class="btn btn-default recaptcha_only_if_image" href="#" onclick="Recaptcha.switch_type('audio');return false;"><span title="Get an audio CAPTCHA" class="glyphicon glyphicon-headphones"></span></button>
										<button class="btn btn-default recaptcha_only_if_audio" href="#" onclick="Recaptcha.switch_type('image');return false;"><span title="Get an image CAPTCHA" class="glyphicon glyphicon-picture"></span></button>
										<button class="btn btn-default" href="#" onclick="Recaptcha.showhelp();return false;"><span class="glyphicon glyphicon-question-sign"></span></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<button type="submit" class="btn btn-default" name="submit" id="submit">Send me TIPS!</button>
							</div>
						</div>

						$captcha
					</form>
ENDHTML;
					}
					?>
				</div>
			</div>
		</section>
	</body>
</html>
