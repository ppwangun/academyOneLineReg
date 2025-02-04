<?php
/*****************************************************************
* Projet    : W-Academy - Angela
* Code PHP 	: TestAjouterEtudiant.php
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
	$info = $test->ajouterEtudiant(
		"24MV004", 
		"Lompa Dieudonné",
		"lompa.dieudonne@gmail.com",
		"670808303",
		"Bangangté, Cameroun"
	);                 
}

var_export($info);  

?>

