<?php
/*****************************************************************
* Projet    : W-Academy - Angela
* Code PHP 	: TestFactureEnseignant.php
*****************************************************************
* Auteur : Njine Chuangueu
* E-mail : <chuanguen@gmail.com>
*****************************************************************
* Date de création      : 18-08-2024 (18 Aout 2024)
* Derniére modification : 19-08-2024 (19 Aout 2024)
*****************************************************************
* Description
* 	Les codes ci-dessous permettent de tester les méthodes de la
* 	classe Synchronisation.  
****************************************************************/

include '../../../autoload.php';

use Njine\Odoo\Synchronisation as TestSynchronisation;

$test = new TestSynchronisation();
$info = $test->connexionOdoo();
if($info["resultat"] == "success")
{ 
	$info = $test->factureEnseignant(
		"5", 
		"2024-08-18 02:49:45",
		10,
		"Chimie I (UP CHM 301) - Volume horaire total : 75 - Heure(s) déjà facturée(s) : 22 - Heure(s) actuellement facturée(s) : 6 - Volume horaire restant : 47",
		6,
		5000
	);                 
}

var_export($info);  

?>

