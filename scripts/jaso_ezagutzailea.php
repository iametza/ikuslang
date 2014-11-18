<?php

require ("../inc/db.inc.php");
require ("../inc/konfig.inc.php");
require ("../inc/libs/dbo.lib.php");
require ("../inc/funtzioak/globalak.fun.php");

$dbo = new DBO (DB_SERV, DB_USER, DB_PASS, DB_NAME);

// ezagutzaile scriptak bidaltzen duen erantzuna jasotzeko.
// POST egiten du, erantzuna aldagaian daude datu guztiak

$erantzuna = $_POST["erantzuna"];

#$erantzuna = '{"fitxategia_id":"123","transkribapena":"izan bazen behintzat eta hilaren hamabostean egingo da sobran izan zinen min asko ibiltzen da eta laurden inguruko behin eta ehun eta hori a metala erran du jose maria de teruelen milizien indarra ez da gogorra izango da eta prezio bera ere bai nahikoa izan zen ematen zuen seiehun eta lehenengo ontzat eman dio zenbaki majikoa batzutan fernando andreu ze galerazia geratzen bada ere bai erderaz eskaini mariano rajoi izan zenuen\n","azpitituluak":"1\n00:00:00,000 --> 00:00:10,790\n<font color=\"#FFFFFF\">izan bazen behintzat eta hilaren\nhamabostean egingo da sobran izan zinen<\/font>\n\n2\n00:00:10,820 --> 00:00:12,510\n<font color=\"#FFFFFF\">min asko ibiltzen<\/font>\n\n3\n00:00:16,490 --> 00:00:28,400\n<font color=\"#FFFFFF\">da eta laurden inguruko behin eta ehun\neta<\/font>\n\n4\n00:00:31,870 --> 00:00:34,950\n<font color=\"#FFFFFF\">hori a<\/font>\n\n5\n00:00:41,390 --> 00:00:41,970\n<font color=\"#FFFFFF\">metala<\/font>\n\n6\n00:00:45,030 --> 00:01:00,460\n<font color=\"#FFFFFF\">erran du jose maria de teruelen milizien\nindarra ez<\/font>\n\n7\n00:01:04,100 --> 00:01:24,720\n<font color=\"#FFFFFF\">da gogorra izango da eta prezio bera ere\nbai nahikoa izan zen ematen zuen seiehun<\/font>\n\n8\n00:01:29,280 --> 00:01:30,570\n<font color=\"#FFFFFF\">eta lehenengo ontzat<\/font>\n\n9\n00:01:36,990 --> 00:01:41,750\n<font color=\"#FFFFFF\">eman dio zenbaki majikoa batzutan<\/font>\n\n10\n00:01:44,960 --> 00:01:55,790\n<font color=\"#FFFFFF\">fernando andreu ze galerazia geratzen\nbada ere bai erderaz eskaini mariano<\/font>\n\n11\n00:01:59,110 --> 00:02:00,070\n<font color=\"#FFFFFF\">rajoi izan zenuen<\/font>\n\n"}';
$erantzuna_obj = json_decode($erantzuna);
$transkribapena = testu_formatua_sql ($erantzuna_obj->transkribapena);
$azpitituluak = testu_formatua_sql ($erantzuna_obj->azpitituluak);


// datu basean sartu informazioa
$sql = "INSERT INTO ikus_entzunezkoak_azpitituluak
                (fk_elem, transkribapena, azpitituluak, noiz, egoera)
        VALUES  ({$erantzuna_obj->fitxategia_id}, '$transkribapena', '$azpitituluak', NOW(), 'amaituta')
        ";
$dbo->query($sql);

// fitxategiak sortu
file_put_contents("../ezagutzailetik/".$erantzuna_obj->fitxategia_id.".txt", $transkribapena);
file_put_contents("../ezagutzailetik/".$erantzuna_obj->fitxategia_id.".srt", $azpitituluak);



