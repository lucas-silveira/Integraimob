<?php

defined( 'ABSPATH' ) or die( 'Não há nada aqui >:)' );

wp_enqueue_script( 'main_js', plugins_url( 'assets/js/imb-main.js', __FILE__ ), array('jquery'), '', true);
wp_enqueue_style( 'main_js', plugins_url( 'assets/css/imb-style.css', __FILE__ ));

add_action("admin_menu", "add_menu_plugin");

function add_menu_plugin() {

	add_menu_page(
        "Configurar Integraimob",
        "Integraimob",
        "manage_options",
        "imb-admin",
        "imb_admin",
        "dashicons-share"
    );
	add_action('admin_init', 'register_settings_imb');
}


function register_settings_imb() {
    register_setting('integraimob-group', 'option_olx_ativo');
    register_setting('integraimob-group', 'option_zap_ativo');
    register_setting('integraimob-group', 'option_viva_real_ativo');
    register_setting('integraimob-group', 'option_imovel_web_ativo');
}

function imb_admin() {

	function ativo($option){
		if(get_option($option) == "true"){
			echo "checked";
		}
	}

	function set_option($option){
			if ($_POST[$option] === 'true'){
		        update_option($option, 'true');
		    }
		    else if($_POST[$option] === 'false'){
		    	update_option($option, 'false');
		    }
	}
	set_option('option_olx_ativo');
	set_option('option_zap_ativo');
	set_option('option_viva_real_ativo');
	set_option('option_imovel_web_ativo');

	include_once("public/imb-admin.php");
}

function query_imoveis(){

	$imovel = array();

	$args = array(
		'post_type' => 'imoveis',
		'order'=> 'DESC',
		'orderby' => 'date',
		'numberposts' => -1
	);

	$imoveis_list = get_posts( $args );

	foreach($imoveis_list as $imovel_item){
		setup_postdata( $imovel_item );

		$imagens = array();

		$tipo_name = array();

		$imgs_post = get_field('galeria_de_imagens', $imovel_item -> ID);

		if($imgs_post){
			foreach($imgs_post as $img_post){
				$imagens[] = array("Nome" => $img_post['filename'], "Url" => wp_get_attachment_url($img_post['ID']));
			}
		}
		
		/*$images_imovel = get_posts(
			array(
				'post_type' => 'attachment',
				'post_status' => 'any',
				'post_mime_type' => 'image',
				'posts_per_page' => -1,
				'post_parent' => $imovel_item -> ID
			)
		);

		foreach($images_imovel as $imagem_imovel){
			$imagens[] = array("Nome" => $imagem_imovel -> post_title, "Url" => wp_get_attachment_url($imagem_imovel -> ID));
		}
		*/

		$imovel_meta_data = get_post_meta( $imovel_item -> ID );

		$taxonomies = get_taxonomies( array( "public" => true, "_builtin" => false ), "names", "and" );
		foreach( $taxonomies as $taxonomy ){
			$tipos = wp_get_object_terms( $imovel_item -> ID, $taxonomy );
			foreach( $tipos as $tipo ){
				$tipo_name[$taxonomy] = $tipo->name;
			}
		}


		$imoveis[] = array(
			"ID" => $imovel_item -> ID,
			"Título" => $imovel_item -> post_title,
			"Content" => $imovel_item -> post_content,
			"Tipo" => $tipo_name["tipos"],
			"Subtipo" => "",
			"Categoria" => "",
			"Cidade" => $imovel_meta_data['cidade_id'][0],
			"Bairro" => "",
			"Endereço" => "",
			"Num" => "",
			"Cep" => "",
			"Preço" => "",
			"Locação" => "",
			"Condomínio" => "",
			"Vagas" => "",
			"Área" => "",
			"Quartos" => "",
			"Galeria" => $imagens
		);

	}

	wp_reset_postdata();

	return $imoveis;

}

/* Exporta os arquivos xml */
function imb_export_xml_ativos(){

	$imoveis = query_imoveis();

	$exported = export_xml($imoveis);

	if( $exported > 0 ) {
		echo "<p>" . count($exported) . " Arquivo(s) exportado(s)</p>";

		foreach($exported as $value){
			echo "</p>" . $value['name'] . " (<a href='" . plugins_url() . "/integraimob/" . $value['arquivo'] . ".xml' target='_blank'>" . plugins_url() . "/integraimob/" . $value['arquivo'] . ".xml</a>)</p>";
		}

	} else {
		echo "Nenhum arquivo foi exportado.";
	}
}