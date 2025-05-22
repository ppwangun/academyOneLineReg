<?php
/*****************************************************************
* Projet    : W-Academy - Angela
* Code PHP 	: TestAjouterEnseignant.php
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
	$info = $test->ajouterEnseignant(
		"5", 
		"Towo Marcus",
		"marcus.towo@agenla.edu",
		"677882244",
		"Yaoundé, Cameroun"
	);                 
}

var_export($info);  

?>

