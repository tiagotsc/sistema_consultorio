<?php

/**
 * DadosBanco_model
 * 
 * Classe que realiza consultas genéricas no banco
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class DadosBanco_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
    /**
    * Função que pega os sexos existentes
    * @return Retorna os dados dos sexos
    */
	public function sexo(){
		return $this->db->get('sexo')->result();
	}
	
    /**
    * Função que pega as nacionalidades existentes
    * @return Retorna as nacionalidades
    */
	public function nacionalidade(){
		return $this->db->get('nacionalidade')->result();
	}
	
    /**
    * Função que pega os estados existentes
    * @return Retorna os estados
    */
	public function estado(){
		return $this->db->get('estado')->result();
	}
    
    /**
    * Função que pega os planos existentes
    * @return Retorna os planos
    */
	public function plano(){
		return $this->db->get('plano')->result();
	}
    
    /**
    * Função que pega o endereço
    * 
    * @param $cep CEP para buscar endereço
    * 
    * @return Retorna o endereço encontrado
    */
	public function endereco($cep){
       
        $this->db->where('cep', str_replace('-', '', $cep));
       
		return $this->db->get('endereco')->result();
	}
    
    /**
    * Função que pega o status
    * @return Retorna os status existentes
    */
	public function status(){
		return $this->db->get('status')->result();
	}
    
    /**
    * Função que pega as especialidades que o médico possa ter
    * @return Retorna as especialidades
    */
	public function especialidade(){
		return $this->db->get('especialidade')->result();
	}
    
    /**
    * Função que pega os dados do menu
    * @return Retorna o menu
    */
	public function menu($permitidos = false){
	   
        if($permitidos){
            $this->db->where("cd_permissao IN (".implode(',',$permitidos).")");
        }
       
        $this->db->where("status_menu =  'A'");
        $this->db->order_by("ordem_menu", "asc"); 
		return $this->db->get('menu')->result();
	}
    
    /**
    * Função que pega o id dos menus pai
    * @return Retorna todos os dados do funcionário
    */
	public function paisMenu($permitidos = false){
        
        if($permitidos){
            $this->db->where("cd_permissao IN (".implode(',',$permitidos).")");
        }
        
        $this->db->distinct();
        $this->db->select('pai_menu');
		$this->db->where('pai_menu <> 0');
        $this->db->where("status_menu =  'A'");
		$paisMenu = $this->db->get('menu')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $paisMenu;
	}
    
    /**
    * Função que os dados do cliente
    * @return Retorna os dados do cliente
    */
	public function cliente(){
		return $this->db->get('cliente')->result();
	}
    
    /**
    * Função que os telefones do cliente
    * @return Retorna os telefones
    */
	public function telefoneCliente(){
		return $this->db->get('telefone_cliente')->result();
	}
    
    /**
    * DadosBanco_model::permissoes()
    * 
    * Função que pega os dados das permissões
    * @return Retorna as permissões
    */
	public function permissoes($permitidos = false){
	       
        if($permitidos){
            $this->db->where("cd_permissao IN (".implode(',',$permitidos).")");
        }
       
        $this->db->where("status_permissao =  'A'");
        #$this->db->order_by("nome_permissao", "asc");
        $this->db->order_by("ordem_permissao", "asc");  
		return $this->db->get('permissao')->result();
	}
    
    /**
    * DadosBanco_model::paiPermissao()
    * 
    * Função que pega o id das permissões pai
    * @return Retorna todos os dados das permissões
    */
	public function paiPermissao($permitidos = false){
	
        if($permitidos){
            $this->db->where("cd_permissao IN (".implode(',',$permitidos).")");
        }
    
        $this->db->distinct();
        $this->db->select('pai_permissao');
		$this->db->where('pai_permissao <> 0');
        $this->db->where("status_permissao =  'A'");
		$paisPemissao = $this->db->get('permissao')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $paisPemissao;
	}

}