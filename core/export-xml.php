<?php

function export_xml($array){

	$olx = new xml_olx('core/xml/xml-olx', $array);
	//$zap = new xml_zap('core/xml/xml-zap');
	//$viva_real = new xml_viva_real('core/xml/xml-viva-real');
	//$imovel_web = new xml_imovel_web_xml('core/xml/xml-imovel-web');

	$exported = array();

	if(get_option("option_olx_ativo") == "true"){
		$olx->write();
		$exported[] = array("name" => "Olx", "arquivo" => "core/xml/xml-olx");
	}

	if(get_option("option_zap_ativo") == "true"){
		$zap->write();
		$exported[] = array("name" => "Zap", "arquivo" => "core/xml/xml-zap");
	}

	if(get_option("option_viva_real_ativo") == "true"){
		$viva_real->write();
		$exported[] = array("name" => "Viva Real", "arquivo" => "core/xml/xml-viva-real");
	}

	if(get_option("option_imovel_web_ativo") == "true"){
		$imovel_web->write();
		$exported[] = array("name" => "Imóvel Web", "arquivo" => "core/xml/xml-imovel-web");
	}

	return $exported;

}