<?php if ( ! defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge, chrome=1" />
	<meta name="viewport" content="widht=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

	<title><?php echo $this->titulo;?></title>

	<meta name="description" content="<?php echo $this->descricao;?>" />
	<meta name="keywords" content="<?php echo $this->keyword;?>" />
	<meta name="robots" content="<?php echo $this->robots;?>" />
	<meta name="rating" content="General" />
	<meta name="expires" content="0" />
	<meta name="language" content="portuguese, PT-BR" />
	<meta name="distribution" content="Global" />
	<meta name="revisit-after" content="7 Days" />

	<meta name="author" content="<?php echo $this->author;?>" />
	<meta name="publisher" content="<?php echo PUBLISHER;?>" />
	<meta name="copyright" content="<?php echo PUBLISHER.' - '.ANO_PROJETO?>" />

	<link rel="shortcut icon" href="<?php echo URL.FAVICON;?>" />

	<?php
		$json = file_get_contents(ABSPATH.'/config_assets.json');
		$load_css = json_decode($json, true);
		foreach ($load_css['css'] as $value) {
			echo'<link href="'.PATH_CSS.'/'.$value.'.css" rel="stylesheet" />'."\n";
		}
	?>

	<?php if(IS_TAG_MANAGER == true){?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo TAG_MANAGER;?>');</script>
		<!-- End Google Tag Manager -->
	<?php } ?>

</head>
