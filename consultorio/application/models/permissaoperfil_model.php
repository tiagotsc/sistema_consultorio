<?php
/**
* Classe que realiza todas as intera��es com a entidade agenda
*/
class PermissaoPerfil_model extends CI_Model{
	
	/**
	 * PermissaoPerfil_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
		$this->load->library('Util', '', 'util');
	}
    
    /**
     * PermissaoPerfil_model::inserePerfil()
     * 
     * Cadastra o perfil
     * 
     * @return Retorna o CD caso verdadeiro ou false
     */
    public function inserePerfil(){
        
        $valorFormatado = $this->util->removeAcentos($this->input->post('nome_perfil'));
        $valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
        
        $this->db->trans_begin();
        
        $sql = "INSERT INTO perfil(nome_perfil, status_perfil) VALUES(".$valorFormatado.", '".$this->input->post('status_perfil')."');";
        $this->db->query($sql);
        $cd = $this->db->insert_id();
        
        if($cd){
        
            foreach($this->input->post('permissao') as $perm){
                
                $sql = "INSERT INTO permissao_perfil (cd_permissao, cd_perfil) VALUES (".$perm.", ".$cd.");";
                $this->db->query($sql);
                
            }
        
        }
            
         if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
            return $cd;
         }else{
            $this->db->trans_rollback();
            return false;
         } 
        
    }
    
    /**
     * PermissaoPerfil_model::atualizaPerfil()
     * 
     * Atualiza o perfil
     * 
     * @param mixed $cd Cd do perfil para atualiza��o
     * @return Retorna o Cd caso verdadeiro ou false
     */
    public function atualizaPerfil($cd){
        
        $valorFormatado = $this->util->removeAcentos($this->input->post('nome_perfil'));
        $valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
        
        $this->db->trans_begin();
        
        $sql = "UPDATE perfil SET nome_perfil = ".$valorFormatado.", status_perfil = '".$this->input->post('status_perfil')."' WHERE cd_perfil = ".$cd;
        
        if($this->db->query($sql)){
            
            $sql = "DELETE FROM permissao_perfil WHERE cd_perfil = ".$cd;
            $this->db->query($sql);
            
            foreach($this->input->post('permissao') as $perm){
                
                $sql = "INSERT INTO permissao_perfil (cd_permissao, cd_perfil) VALUES (".$perm.", ".$cd.");";
                $this->db->query($sql);
                
            }
            
        }
        
        if($this->db->trans_status() === TRUE){
            $this->db->trans_commit();
            return $cd;
         }else{
            $this->db->trans_rollback();
            return false;
         } 
        
    }
    
	/**
	 * PermissaoPerfil_model::perfil()
	 * 
     * Pega os perfis ativos
     * 
	 * @return Retorna os ativos
	 */
	public function perfil(){
	   
        $this->db->where("status_perfil =  'A'");
        $this->db->order_by("nome_perfil", "asc"); 
		return $this->db->get('perfil')->result();
	}
    
    /**
     * PermissaoPerfil_model::permissoesDoPerfil()
     * 
     * Pega as permiss�es do perfil
     * 
     * @param mixed $cd
     * @return Retorna as permiss�es encontradas
     */
    public function permissoesDoPerfil($cd){
        
        $this->db->select('cd_permissao');
        $this->db->where("cd_perfil", $cd);
		return $this->db->get('permissao_perfil')->result_array();
        
    }
    
    /**
    * PermissaoPerfil_model::dadosPerfil()
    * 
    * Fun��o que monta um array com todos os dados do perfil
    * @param $cd do perfil para recupera��o dos dados
    * @return Retorna todos os dados do perfil
    */
	public function dadosPerfil($cd){
	
		$this->db->where('cd_perfil', $cd);
		$funcionario = $this->db->get('perfil')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $funcionario[0];
	}
	
    /**
    * PermissaoPerfil_model::camposPerfil()
    * 
    * Fun��o que pega os nomes de todos os campos existentes na tabela perfil
    * @return O n�mero de linhas afetadas pela opera��o
    */
	public function camposPerfil(){
		
		$campos = $this->db->get('perfil')->list_fields();
		
		return $campos;
		
	}
    
    /**
     * Permissaoperfil_model::psqPerfis()
     * 
     * lista os perfis existentes de acordo com os par�metros informados
     * @param $nome do perfil que se deseja encontrar
     * @param $status Status do perfil
     * @param $pagina P�gina da pagina��o
     * @param $mostra_por_pagina P�gina corrente da pagina��o
     * 
     * @return A lista perfis encontrados
     */
    public function psqPerfis($nome = null, $status = null, $pagina = null, $mostra_por_pagina = null){
        
        $this->db->select("
                            cd_perfil,
                            nome_perfil,
                            CASE WHEN status_perfil = 'A'
                                THEN 'Ativo'
                            ELSE 'Inativo' END AS status_perfil
                            ");       
        
        
        if($nome != '0'){
            #$this->db->like('nome_perfil', $nome); 
            $condicao = "nome_perfil LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status != '0'){
            $this->db->where('status_perfil', $status);
        }
        
        #$this->db->join('banco', 'banco.cd_banco = arquivo_retorno.cd_banco');        
        
        return $this->db->get('perfil', $mostra_por_pagina, $pagina)->result();
    }
    
    /**
     * Permissaoperfil_model::psqQtdPerfis()
     * 
     * Consulta a quantidade de perfis da pesquisa
     * 
     * @param $nome Nome do perfil para filtrar a consulta
     * @param $status Status do perfil para filtrar a consulta
     * 
     * @return Retorna a quantidade
     */
    public function psqQtdPerfis($nome = null, $status = null){
        
        if($nome != '0'){
            $condicao = "nome_perfil LIKE '%".strtoupper($nome)."%'";
            $this->db->where($condicao);
        }
        
        if($status <> '0'){
            $this->db->where('status_perfil', $status);
        }
        
        $this->db->select('count(*) as total');
        return $this->db->get('perfil')->result();
    }
    
    /**
     * PermissaoPerfil_model::deletePerfil()
     * 
     * Apaga o perfil
     * 
     * @return O n�mero de linhas afetadas
     */
    public function deletePerfil(){
        
        $sql = "DELETE FROM perfil WHERE cd_perfil = ".$this->input->post('apg_cd_perfil');
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }

}