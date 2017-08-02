<?php
class XMLgenerator {
	
	public static function ajouter_element(array $data, $fichier) {
		foreach ($data as $key => $value) {
			fputs($fichier, "<$key>$value</$key>\n");	//en-tête XML
		}
	}
	
}