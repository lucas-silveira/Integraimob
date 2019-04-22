<?php

class xml_olx{

	/**
	 * @var string O nome do arquivo
	 */
	private $nome_do_arquivo;

	/**
	 * @var obj Instância da classe xml_writer
	 */
	private $global;

	/**
	 * @var func Adiciona um nó
	 */
	private $imoveis;

	/**
	 * @var func Adiciona um nó
	 */
	private $imovel;

	/**
	 * @var func Adiciona um nó
	 */
	private $fotos;

	/**
	 * @var func Adiciona um nó
	 */
	private $foto;


	/* Variáveis Wordpress */

	/**
	 * @var obj Instância da classe WP_Query
	 */
	private $the_query;

	/**
	 * @var array Conjunto de filtros para a WP_Query
	 */
	private $args;

	/**
	 * @var array Array de objetos
	 */
	private $imovel_meta_data;

	/**
	 * @var obj Taxonomia Tipos
	 */
	private $tipos;

	/**
	 * @var array Nome do tipo
	 */
	private $tipo_name;

	function __construct($nome, $array){

		$this->nome_do_arquivo = $nome;
		$this->imoveis_list = $array;

		 // Inicia o arquivo XML
		$this->global = new xml_writer($this->nome_do_arquivo.".xml", "Carga");

		$this->global->add_node("Imoveis");

		$this->imoveis = $this->global->get_node($this->global->get_nodes_count() - 1);

		// The Query
		foreach($this->imoveis_list as $this->imovel_item){

				// START MAIN LOOP
				$this->imoveis->add_node("Imovel");
				$this->imovel = $this->imoveis->get_node($this->imoveis->get_nodes_count() - 1);
				$this->imovel->add_node("CodigoCliente", $this->imovel_item['Título']);
				$this->imovel->add_node("CodigoImovel", $this->imovel_item['ID']);
				$this->imovel->add_node("TipoImovel", $this->imovel_item['Tipo']);
				$this->imovel->add_node("SubTipoImovel", $this->imovel_item['Subtipo']);
				$this->imovel->add_node("CategoriaImovel", $this->imovel_item['Categoria']);
				$this->imovel->add_node("Cidade", $this->imovel_item['Cidade']);
				$this->imovel->add_node("Bairro", $this->imovel_item['Bairro']);
				$this->imovel->add_node("Endereco", $this->imovel_item['Endereço']);
				$this->imovel->add_node("Numero", $this->imovel_item['Num']);
				$this->imovel->add_node("CEP", $this->imovel_item['Cep']);
				$this->imovel->add_node("PrecoVenda", $this->imovel_item['Preço']);
				$this->imovel->add_node("PrecoLocacao", $this->imovel_item['Locação']);
				$this->imovel->add_node("PrecoCondominio", $this->imovel_item['Condomínio']);
				$this->imovel->add_node("QtdVagas", $this->imovel_item['Vagas']);
				$this->imovel->add_node("AreaUtil", $this->imovel_item['Área']);
				$this->imovel->add_node("QtdDormitorios", $this->imovel_item['Quartos']);
				$this->imovel->add_node("Observacao", "<![CDATA[" . $this->imovel_item['Content'] . "]]>");
				$this->imovel->add_node("Fotos");
				$this->fotos = $this->imovel->get_node($this->imovel->get_nodes_count() - 1);

				// START FOTO LOOP
				foreach($this->imovel_item['Galeria'] as $this->imovel_imagem){
					$this->fotos->add_node("Foto");
					$this->foto = $this->fotos->get_node($this->fotos->get_nodes_count() - 1);
					$this->foto->add_node("NomeArquivo", $this->imovel_imagem['Nome']);
					$this->foto->add_node("URLArquivo", $this->imovel_imagem['Url']);
				}

				// END FOTO LOOP
				//END MAIN LOOP

		}
	}

	function write(){

		// Podem ser adicionados tantos arquivos de estilo quanto forem necessários
		// A outra opção seria usar um arquivo XSLT para isso
		// Neste caso a sintaxe seria:
		// $this->global->add_xslt("stilo.xsl");

		// Grava em um arquivo
		return $this->global->write();
		// header('location: xml/'.$this->nome_do_arquivo.'.xml');
		// Outras possibilidades:
		//
		// Enviar para a saída do navegador
		// $this->global->send();
		//
		// Obter a forma textual do arquivo, sem o cabeçalho padrão
		// $this->global->get_text_node();
		//
		// Obter a extrutura gerada em um array
		// $this->global->get_array_node();
	}
}