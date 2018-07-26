<?php
/**
* Classe que realiza todas as consultas a base de dados a respeito paciente
*/
class Paciente_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->library('Util', '', 'util');
	}
	
    /**
    * Função que gera a matrícula do paciente
    * @return Retorna a matrícula gerada
    */
	public function geraMatricula(){
	
		$this->db->select_max('cd_paciente');
		$res = $this->db->get('paciente');
		$res = $res->result();  
		$cd = $res[0]->cd_paciente + 1;
		
		switch(strlen($cd)){
			case 1:
				$matricula = date('y').'00'.$cd;
			break;
			case 2:
				$matricula = date('y').'0'.$cd;
			break;
			default:
				$matricula = date('y').$cd;
		}

		return $matricula;
	}
	
    /**
    * Função que realiza a inserção dos dados do paciente na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
		foreach($_POST as $c => $v){
			
			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
			$campo[] = $c;
			$valor[] = $valorFormatado;
		}
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
		$sql = "INSERT INTO paciente (".$campos.")\n VALUES(".$valores.");";
		$this->db->query($sql);
		return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
	}
	
    /**
    * Função que realiza a atualização dos dados do paciente na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){
		
		foreach($_POST as $c => $v){
			
			if($c != 'matricula_paciente'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
		$sql = "UPDATE paciente SET ".$camposValores." WHERE matricula_paciente = ".$this->input->post('matricula_paciente');
		
		return $this->db->query($sql); # RETORNA O NÚMERO DE LINHAS AFETADAS
		
	}
	
    /**
    * Função que monta um array com todos os dados do paciente
    * @param $matricula Matrícula do paciente para recuperação dos dados
    * @return Retorna todos os dados do paciente
    */
	public function dadosPaciente($matricula){
	
		$this->db->where('matricula_paciente', $matricula);
		$paciente = $this->db->get('paciente')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $paciente[0];
	}
	
    /**
    * Função que pega os nomes de todos os campos existentes na tabela paciente
    * @return O número de linhas afetadas pela operação
    */
	public function camposPaciente(){
		
		$campos = $this->db->get('paciente')->list_fields();
		
		return $campos;
		
	}
	
    /**
    * Função que pesquisa os pacientes de acordo com os parâmetros informados na busca assícrona (Com refresh)
    * @param $dadoPesquisa Dados que será consultado na base da dados
    * @param $pagina Pagina para paginação
    * @param $mostra_por_pagina Quantidade de registros que serão mostrados
    * @return Os registros encontrados na consulta
    */
	public function pesquisaPaciente($dadoPesquisa = null, $pagina, $mostra_por_pagina){
	
        if($dadoPesquisa <> '1'){
    		$dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $dadoPesquisa))));
    		$condicao = "nome_paciente LIKE '%".$dadoPesquisa."%' OR REPLACE(REPLACE(rg_paciente, '-', ''), '.', '') = '".$dadoPesquisa."'";
    		$this->db->where($condicao);
        }
        
        $this->db->order_by("nome_paciente", "asc"); 
        
		return $this->db->get('paciente', $mostra_por_pagina, $pagina)->result();
		
	}
	
    /**
    * Função que pesquisa a quantidade de pacientes de acordo com o dado informado
    * @param $dadoPesquisa Dados que será consultado na base da dados
    * @return O número de linhas afetadas pela operação
    */
	public function pesquisaQtdPaciente($dadoPesquisa){
	
		$this->db->select('count(*) as total');
		$this->db->from('paciente');
        if($dadoPesquisa <> '1'){
    		$condicao = "nome_paciente LIKE '%".$dadoPesquisa."%' OR REPLACE(REPLACE(rg_paciente, '-', ''), '.', '') = '".$dadoPesquisa."'";
    		$this->db->where($condicao);
        }
		return $this->db->get()->result();
	
	}
    
    /**
    * Função que pesquisa os pacientes de acordo com os parâmetros informados na busca sicrona (Sem refresh)
    * @return Os registros encontrados na consulta
    */
    public function pesquisaPacienteParaConsulta(){
	
		$dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $this->input->post('dados_paciente')))));
		$condicao = "nome_paciente LIKE '%".$dadoPesquisa."%' OR REPLACE(REPLACE(rg_paciente, '-', ''), '.', '') = '".$dadoPesquisa."'";
        
        $this->db->select('matricula_paciente, nome_paciente, tel_fix_paciente, tel_cel_paciente');
		$this->db->where($condicao);
        $this->db->limit(30);
        
        $this->db->order_by("nome_paciente", "asc"); 
            
		return $this->db->get('paciente')->result();
	
	}
    
    /**
    * Função que deleta o paciente da base de dados
    * @return Um valor boleano que representa se a operação foi ou não executada com sucesso
    */
    public function excluir($matricula){
	
		$this->db->where('matricula_paciente', $matricula);
        $this->db->delete('paciente'); 
        
        return $this->db->affected_rows();
	
	}
    
    /**
    * Função que atualiza os contatos do paciente
    * @return O status da operação
    */
	public function atualizaContatos(){
		
		$sql = "UPDATE paciente SET "; 
        $sql .= "tel_fix_paciente = '".$this->input->post('tel_fix_paciente')."',";
        $sql .= "tel_cel_paciente = '".$this->input->post('tel_cel_paciente')."'";
        $sql .= "WHERE matricula_paciente = ".$this->input->post('matricula_paciente');
		
		return $this->db->query($sql); # RETORNA O NÚMERO DE LINHAS AFETADAS
		
	}
    
    /**
    * Função que realiza o pré cadastro do paciente
    * @return A matrícula em caso de sucesso ou o valor boleano falso
    */
    public function preCadastro(){
        
        $matricula = $this->geraMatricula();
        $nome = strtoupper($this->util->removeAcentos($this->input->post('nome_paciente')));
        $fixo = $this->input->post('tel_fix_paciente');
        $celular = $this->input->post('tel_cel_paciente');
        
        $sql = "INSERT INTO paciente(matricula_paciente, nome_paciente, tel_fix_paciente, tel_cel_paciente, novo_paciente) ";
        $sql .= "VALUES(".$matricula.", '".$nome."', '".$fixo."', '".$celular."', 'SIM');";
		$this->db->query($sql);
        
		if($this->db->affected_rows()){
            return $matricula;
		}else{
            return false;
		}
        
    }
    
    /**
    * Função que pesquisa os pacientes de acordo com os parâmetros informados na busca sicrona (Sem refresh)
    * @return Os registros encontrados na consulta
    */
    public function pesquisaAutoComplete(){
	
		$dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $this->input->get('term')))));
		$condicao = "nome_paciente LIKE '".$dadoPesquisa."%' OR REPLACE(REPLACE(rg_paciente, '-', ''), '.', '') = '".$dadoPesquisa."'";
        
        $this->db->select('nome_paciente');
		$this->db->where($condicao);
        $this->db->limit(30);
        
        $this->db->order_by("nome_paciente", "asc"); 
            
		return $this->db->get('paciente')->result();
	
	}

}