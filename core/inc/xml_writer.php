<?php

/*
 * (English)
 * This file is part of filExp.
 *
 * filExp is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation.
 *
 * filExp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with filExp.  If not, see <http://www.gnu.org/licenses/>.
 * ***
 * (Português)
 * Este arquivo faz parto do filExp.
 *
 * filExp é um software livre: você pode redistribui-lo ou modificá-lo
 * sob os termos da GNU General Public License como publicada pela
 * Free Software Foundation.
 *
 * filExp está sendo distribuido no intuito de ser útil,
 * mas SEM NENHUMA GARANTIA; sem jamais implicar em garantia por
 * COMÉRCIO ou USO PARA PROPÓSITOS PARTICULARES. Veja a
 * GNU General Public License para mais detalhes.
 *
 * Você deve ter recebido uma cópia da GNU General Public License
 * junto com o filExp. Caso contrário, visite <http://ww.gnu.org/license/>
 *
 */

/**
 * @author Wanderson Regis Silva <wanderson@wandersonregis.com>
 * @version 0.1.0.0 2009-04-10
 */

/**
 * Gerenciador de nós do documento
 */
class xml_node {
  /**
   * @var string O nome deste nó
   */
  private $node;

  /**
   * @var string O conteúdo interno do nó
   */
  private $contents;

  /**
   * @var array Os parâmetros do nó
   */
  private $params;

  /**
   * @var mixed Os subnós
   */
  private $nodes;

  /**
   * Inicializa o nó, com nome e possível conteúdo
   * @param string $nodename Nome do nó
   * @param string $contents Conteúdo do nó
   */
  function __construct($nodename, $contents = '') {
    $this->itialize($nodename, $contents);
  }
  
  /**
   * Inicializa o nó, com nome e possível conteúdo, útil para limpar o conteúdo ou em classes herdeiras
   * @param string $nodename Nome do nó
   * @param string $contents Conteúdo do nó
   */
  function itialize($nodename, $contents = '') {
    $this->node = str_replace(" ", "_", $nodename);
    $this->contents = $contents;
    $this->nodes = array();
    $this->params = array();
    if($nodename == "Carga"){
      $this->add_param("xmlns:xsd", "http://www.w3.org/2001/XMLSchema");
      $this->add_param("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    }
  }

  /**
   * Modifica o conteúdo textual do nó
   * @param string $contents O conteúdo textual
   * @param boolean $replace Se é para sobrescrever o conteúdo anterior (true) ou adicionar ao fim (false)
   */
  function alter_node_content($contents, $replace = true) {
    if($replace)
      $this->contents = $contents;
    else
      $this->contents .= $contents;
  }

  /**
   * Adiciona um novo parâmetro
   * @param string $nodename Nome do novo nó
   * @param string $contents Conteúdo textual do nó
   */
  function add_param($param, $content = '') {
    $count = count($this->params);
  
    $this->params[$count][0] = str_replace(" ", "_", $param);
    $this->params[$count][1] = $content;
  }

  /**
   * Adiciona um novo subnó
   * @param string $nodename Nome do novo nó
   * @param string $contents Conteúdo textual do nó
   */
  function add_node($nodename, $contents = '') {
    $this->nodes[] = new xml_node($nodename, $contents);
  }

  /**
   * Adiciona uma árvore já pronta de nós
   * @param mixed $tree Árvore de nós
   */
  function add_node_tree($tree) {
    $this->nodes[] = $tree;
  }

  /**
   * Retorna o total atual de subnós dentro deste nó
   * @return int
   */
  function get_nodes_count() {
    return count($this->nodes);
  }

  /**
   * Retorna todos os subnós do nó atual
   * @return array
   */
  function get_nodes() {
    return $this->nodes;
  }

  /**
   * Retorna um nó específico, em caso de nó um inexistente, retorna false
   * @param int Índice do nó
   * @return mixed
   */
  function get_node($nodenumber) {
    if($nodenumber >= $this->get_nodes_count())
      return false;

    return $this->nodes[$nodenumber];
  }

  /**
   * Retorna a versão textual da extrutura
   * @return string A extrutura XML gerada
   */
  function get_text_node() {
    $text = "<" . $this->node;
    
    for($i = 0; $i < count($this->params); $i++) {
      $text .= " " . $this->params[$i][0] . "=\"" . $this->params[$i][1] . "\"";
    }
    
    $text .= ">";
    
    $text .= $this->contents . ( ! empty($this->contents) ? "" : NULL);

    if($this->get_nodes_count()) {
      for($i = 0; $i < $this->get_nodes_count(); $i++) {
        $text .= $this->nodes[$i]->get_text_node();
      }
    }
    
    $text .= "</" . $this->node . ">\n";
    
    return $text;
  }

  /**
   * Retorna umma array contendo a extrutura
   * @return array A extrutura XML gerada
   */
  function get_array_node() {
    $array['name'] = $this->node;
    
    for($i = 0; $i < count($this->params); $i++) {
      $array['param'][] = array(
        'name' => $this->params[$i][0],
        'value' => $this->params[$i][1]
      );
    }
    
    $array['content'] = $this->contents;
    
    $array['nodes'] = array();
    if($this->get_nodes_count()) {
      for($i = 0; $i < $this->get_nodes_count(); $i++) {
        $array['nodes'][] = $this->nodes[$i]->get_array_node();
      }
    }
    
    return $array;
  }
}

/**
 * Constrói uma extrutura de arquivo XML
 */
class xml_writer extends xml_node {
  /**
   * @var string Arquivo de saída
   */
  private $filename;

  /**
   * @var string Arquivo css de estilização
   */
  private $css;

  /**
   * @var string Arquivo XSLT de estilização
   */
  private $xslt;

  /**
   * Inicializa a extrutura, com o possível node de arquivo de saída, com nome e possível conteúdo, útil para limpar o conteúdo ou em classes herdeiras
   * @param string $filename Nome do arquivo de saída
   * @param string $mainnode Nome do nó
   * @param string $maincontent Conteúdo do nó
   */
  function __construct($filename = 'xmlfile.xml', $mainnode = 'Carga', $maincontent = '') {
    $this->filename = $filename;
    parent::itialize($mainnode, $maincontent);
    $this->css = array();
    $this->xslt = array();
  }

  /**
   * Adiciona um arquivo css para estilização
   * @param string $filename Nome do arquivo
   */
  function add_css($filename) {
    $this->css[] = $filename;
  }

  /**
   * Adiciona um arquivo xslt para estilização
   * @param string $filename Nome do arquivo
   */
  function add_xslt($filename) {
    $this->xslt[] = $filename;
  }
  
  /**
   * Envia a extrutura gerada para a saída do navegador
   */
  function send() {
    header("Pragma: public\n");
    header("Expires: 0\n");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0\n");
    header("Content-Type: text/xml\n");
    header("Content-Disposition: attachment;filename=" . $this->filename . "\n");

    echo "<?xml version=\"1.0\" charset=\"utf-8\"?>\n";
    
    if(count($this->css)) {
      foreach($this->css as $css)
        echo "<?xml-stylesheet type=\"text/css\" href=\"" . $css . "\"?>\n";
    }
    
    if(count($this->xslt)) {
      foreach($this->xslt as $xslt)
        echo "<?xml-stylesheet type=\"text/xsl\" href=\"" . $xslt . "\"?>\n";
    }
    
    echo parent::get_text_node();
  }
  
  /**
   * Grava a extrutura gerada para um arquivo
   */
  function write() {
    $data =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    
    if(count($this->css)) {
      foreach($this->css as $css)
        $data .= "<?xml-stylesheet type=\"text/css\" href=\"" . $css . "\"?>\n";
    }
    
    if(count($this->xslt)) {
      foreach($this->xslt as $xslt)
        $data .= "<?xml-stylesheet type=\"text/xsl\" href=\"" . $xslt . "\"?>\n";
    }
    
    $data .= $this->get_text_node();

    $file = @fopen($this->filename, "w");

    if($file) {
      fwrite($file, $data);
      fclose($file);
      return true;
    } else return false;
  }
}