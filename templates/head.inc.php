<?php
    if (is_file($head_file_inc)) {
        require($head_file_inc);
    }
?>

<meta property="og:title" content="Ikuslang" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo URL_BASE; ?>" />
<meta property="og:image" content="<?php echo URL_BASE; ?>images/logoa.png" />
<meta property="og:site_name" content="Ikuslang" />
<meta property="og:description" content="Ikuslang" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="content-language" content="<?php echo $hizkuntza["gako"]; ?>" />
<meta name="description" content="<?php echo $hto->motz ("meta_description"); ?>" />
<meta name="author" content="iametza interaktiboa" />
<meta name="copyright" content="2013 Zer non" />
<meta name="keywords" content="<?php echo $hto->motz ("meta_keywords"); ?>" />
<meta name="Distribution" content="Global" />
<meta name="Revisit" content="7 days" />
<meta name="Robots" content="All" />
<link rel="author" href="mailto:info@iametza.com" title="iametza interaktiboarekin harremanetan jarri" />

<title><?php echo $meta_title; ?></title>

<!--link rel="stylesheet/less" href="<?php echo URL_BASE; ?>less/bootstrap.less" type="text/css" /-->
<!--link rel="stylesheet/less" href="<?php echo URL_BASE; ?>less/responsive-utilities.less" type="text/css" /-->
<!--script src="<?php echo URL_BASE; ?>js/less-1.3.3.min.js"></script-->
<!--append ‘#!watch’ to the browser URL, then refresh the page. -->

<link href="<?php echo URL_BASE; ?>css/bootstrap.css" rel="stylesheet" />
<link href="<?php echo URL_BASE; ?>css/itxura.css" rel="stylesheet" />
<link href="<?php echo URL_BASE; ?>css/cookiecuttr.css" rel="stylesheet" type="text/css" media="all" />

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]><script src="<?php echo URL_BASE; ?>js/html5shiv.js"></script><![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo URL_BASE; ?>images/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo URL_BASE; ?>images/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo URL_BASE; ?>images/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="<?php echo URL_BASE; ?>images/apple-touch-icon-57-precomposed.png" />
<link rel="shortcut icon" href="<?php echo URL_BASE; ?>images/favicon.png" />

<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.min.js"></script>
<?php /*<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery-ui-1.11.1.custom.min.js"></script>*/ ?>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>js/jquery.cookiecuttr.js"></script>
