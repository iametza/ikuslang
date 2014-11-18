$(document).ready(function() {

    $.cookieCuttr ({
		cookieAnalytics: false,
		cookiePolicyLink: '<?php echo URL_BASE . $hto->nice ("kuki_zer_dira"); ?>',
		cookieMessage: '<?php echo $hto->motz ("kuki_oharra"); ?> <a href="{{cookiePolicyLink}}"><?php echo $hto->motz("kuki_zer_dira"); ?></a>',
		cookieAcceptButtonText: '<?php echo $hto->motz("kuki_onartu"); ?>'
	});
    
});

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

if (jQuery.cookie('cc_cookie_accept') == "cc_cookie_accept") {
<?php //require ("inc/analytics.inc.php"); ?>
}


<?php /*
cookieAnalyticsMessage: 'Cookie-ak erabiltzen ditugu bla bla bla bla eta bla!',
cookieWhatAreLinkText: 'Zer dira cookie-ak?',
cookieWhatAreTheyLink: 'http://www.iametza.com'
*/ ?>
