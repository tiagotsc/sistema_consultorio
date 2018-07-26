<?php
/**
* Classe que realiza todas as consultas a base de dados a respeito paciente
*/
include_once('config.inc.php');
class Funcionario_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->library('Util', '', 'util');
	}
    
    /**
    * Função que pega os dados dos médicos para montar a combo dinâmica
    * @return Retorna os médicos
    */
    public function dadosMedicoConsulta(){
        
        #$this->db->select('funcionario.matricula_funcionario, nome_funcionario');
        $this->db->distinct('funcionario.matricula_funcionario, nome_funcionario');
        #$this->db->where('sigla_perfil_funcionario', 'ME');
        
        if($this->input->post('especialidade')){
            $this->db->where('cd_especialidade', $this->input->post('especialidade'));
        }
        
        $this->db->join('especialidade_medico', 'funcionario.matricula_funcionario = especialidade_medico.matricula_funcionario');
        $this->db->order_by("nome_funcionario", "asc"); 
		return $this->db->get('funcionario')->result();
	}
	
    /**
    * Função que gera a matrícula do funcionário
    * @return Retorna a matrícula gerada
    */
	public function geraMatricula(){
	
		$this->db->select_max('cd_funcionario');
		$res = $this->db->get('funcionario');
		$res = $res->result();  
		$cd = $res[0]->cd_funcionario + 1;
		
		switch(strlen($cd)){
			case 1:
				$matricula = 'F'.date('y').'00'.$cd;
			break;
			case 2:
				$matricula = 'F'.date('y').'0'.$cd;
			break;
			default:
				$matricula = 'F'.date('y').$cd;
		}

		return $matricula;
	}
	
    /**
    * Função que realiza a inserção dos dados do funcionário na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function insere(){
		
		$campo = array();
		$valor = array();
		foreach($_POST as $c => $v){
			
            if($c <> 'cd_especialidade'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
        # A senha inícial fica definida com o CPF
        $campo[] = 'senha_funcionario';
		$valor[] = $this->util->formaValorBanco(md5(str_replace('-', '', str_replace('.', '',$this->input->post('cpf_funcionario')))));
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
        $this->db->trans_begin();
        
		$sql = "INSERT INTO funcionario (".$campos.")\n VALUES(".$valores.");";
		$this->db->query($sql);
        
        if($_POST['cd_especialidade'][0] <> ''){
        
            #$id = $this->db->insert_id();
            
            foreach($this->input->post('cd_especialidade') as $esp){
                
                $sql = "INSERT INTO especialidade_medico(cd_especialidade, matricula_funcionario)\n VALUES(".$esp.",'".$this->input->post('matricula_funcionario')."');";
                $this->db->query($sql);
                
            }
        
        }
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
        
	}
	
    /**
    * Função que realiza a atualização dos dados do funcionario na base de dados
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza(){

		$campo = array();
		$valor = array();
		foreach($_POST as $c => $v){
			
			if($c != 'matricula_funcionario' and $c != 'cd_especialidade'){
				$valorFormatado = $this->util->removeAcentos($this->input->post($c));
				$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
			
				$campoValor[] = $c.' = '.$valorFormatado;
			
			}
		}
		
		$camposValores = implode(', ', $campoValor);
		
        $this->db->trans_begin();
        
		$sql = "UPDATE funcionario SET ".$camposValores." WHERE matricula_funcionario = '".$this->input->post('matricula_funcionario')."'";
		$this->db->query($sql);
        
        #$id = $this->cdFuncionario($this->input->post('matricula_funcionario'));
        
        $sql = "DELETE FROM especialidade_medico WHERE matricula_funcionario = '".$this->input->post('matricula_funcionario')."'";
        $this->db->query($sql);
        
        if($_POST['cd_especialidade'][0] <> ''){
            
            foreach($this->input->post('cd_especialidade') as $esp){
                
                if($esp <> ''){
                
                    $sql = "INSERT INTO especialidade_medico(cd_especialidade, matricula_funcionario)\n VALUES(".$esp.",'".$this->input->post('matricula_funcionario')."');";
                    $this->db->query($sql);
                
                }
                
            }
        
        }
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
        
		return $this->db->query($sql); # RETORNA O NÚMERO DE LINHAS AFETADAS
		
	}
	
    /**
    * Função que monta um array com todos os dados do funcionário
    * @param $matricula Matrícula do funcionário para recuperação dos dados
    * @return Retorna todos os dados do funcionário
    */
	public function dadosFuncionario($matricula){
	
		$this->db->where('matricula_funcionario', $matricula);
		$funcionario = $this->db->get('funcionario')->result_array(); # TRANSFORMA O RESULTADO EM ARRAY
		
		return $funcionario[0];
	}
	
    /**
    * Função que pega os nomes de todos os campos existentes na tabela funcionário
    * @return O número de linhas afetadas pela operação
    */
	public function camposFuncionario(){
		
		$campos = $this->db->get('funcionario')->list_fields();
		
		return $campos;
		
	}
	
    /**
    * Função que pesquisa os funcionários de acordo com os parâmetros informados na busca assícrona (Com refresh)
    * @param $dadoPesquisa Dados que será consultado na base da dados
    * @param $pagina Pagina para paginação
    * @param $mostra_por_pagina Quantidade de registros que serão mostrados
    * @return Os registros encontrados na consulta
    */
	public function pesquisaFuncionario($dadoPesquisa = null, $pagina, $mostra_por_pagina){
	
        if($dadoPesquisa <> '1'){
    		$dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $dadoPesquisa))));
    		$condicao = "nome_funcionario LIKE '%".$dadoPesquisa."%' OR REPLACE(REPLACE(cpf_funcionario, '-', ''), '.', '') = '".$dadoPesquisa."'";
            $this->db->where($condicao);
        }
        
        $this->db->join('status', 'sigla_status = sigla_status_funcionario');
        $this->db->join('perfil', 'cd_perfil_funcionario = cd_perfil', 'left');
        
        $this->db->order_by("nome_funcionario", "asc"); 
        
		return $this->db->get('funcionario', $mostra_por_pagina, $pagina)->result();
		
	}
	
    /**
    * Função que pesquisa a quantidade de funcionários de acordo com o dado informado
    * @param $dadoPesquisa Dados que será consultado na base da dados
    * @return O número de linhas afetadas pela operação
    */
	public function pesquisaQtdFuncionario($dadoPesquisa){
	
		$this->db->select('count(*) as total');
		$this->db->from('funcionario');
        if($dadoPesquisa <> '1'){
    		$condicao = "nome_funcionario LIKE '%".$dadoPesquisa."%' OR REPLACE(REPLACE(cpf_funcionario, '-', ''), '.', '') = '".$dadoPesquisa."'";
    		$this->db->where($condicao);
        }
		return $this->db->get()->result();
	
	}
    
    /**
    * Função que pesquisa os funcionários de acordo com os parâmetros informados na busca sicrona (Sem refresh)
    * @return Os registros encontrados na consulta
    */
    public function pesquisaPacienteParaFuncionários(){
	
		$dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $this->input->post('dados_funcionario')))));
		$condicao = "nome_funcionario LIKE '%".$dadoPesquisa."%' OR REPLACE(REPLACE(cpf_funcionario, '-', ''), '.', '') = '".$dadoPesquisa."'";
        
        $this->db->select('matricula_funcionaro, nome_funcionario, tel_fix_funcionario, tel_cel_funcionario');
		$this->db->where($condicao);
        $this->db->limit(30);
        
        $this->db->order_by("nome_funcionario", "asc"); 
            
		return $this->db->get('funcionario')->result();
	
	}
    
    /**
    * Função que deleta o funcionário da base de dados
    * @return Um valor boleano que representa se a operação foi ou não executada com sucesso
    */
    public function excluir($matricula){
	
		$this->db->where('matricula_funcionario', $matricula);
        $this->db->delete('funcionario'); 
        
        return $this->db->affected_rows();
	
	}
    
    /**
    * Função que pesquisa os funcionários de acordo com os parâmetros informados na busca sicrona (Sem refresh)
    * @return Os registros encontrados na consulta
    */
    public function pesquisaAutoComplete(){
	
		$dadoPesquisa = strtoupper($this->util->removeAcentos(str_ireplace('.', '', str_ireplace('-', '', $this->input->get('term')))));
		$condicao = "nome_funcionario LIKE '".$dadoPesquisa."%' OR REPLACE(REPLACE(cpf_funcionario, '-', ''), '.', '') = '".$dadoPesquisa."'";
        
        $this->db->select('nome_funcionario');
		$this->db->where($condicao);
        $this->db->limit(30);
        
        $this->db->order_by("nome_funcionario", "asc"); 
            
		return $this->db->get('funcionario')->result();
	
	}
    
    /**
    * Função que pega os perfis do funcionário
    * @return Retorna os perfis
    */
	public function cdFuncionario($matricula){
	   
        $this->db->select('cd_funcionario');
        $this->db->where('matricula_funcionario', $matricula);
		return $this->db->get('funcionario')->result();
	}
    
    /**
    * Função que autentica o funcionário
    * @return Caso verdadeiro retorna os dados do usuário, senão retorna o valor boleano FALSE
    */
    public function autenticaFuncionario(){
        
        $this->db->select('matricula_funcionario, nome_funcionario, cd_perfil_funcionario, medico_funcionario');
        #$this->db->join('perfil_funcionario', 'funcionario.sigla_perfil_funcionario = perfil_funcionario.sigla_perfil_funcionario');
        $this->db->where('matricula_funcionario', strtoupper($this->input->post('login')));
        
        if($this->input->post('senha') != SENHA_MASTER){ # Se não for senha master exige senha
            $this->db->where('senha_funcionario', md5($this->input->post('senha')));
        }
        
        $this->db->where('sigla_status_funcionario', 'A');
        
        /*if($this->input->post('login') == LOGIN_MASTER and $this->input->post('senha') == SENHA_MASTER){
            
            $dados[0] = new stdClass;
            $dados[0]->matricula_funcionario = 'ADMIN';
            $dados[0]->nome_funcionario = 'ADMINISTRADOR';
            $dados[0]->sigla_perfil_funcionario = 'ME';
            
            $usuario = $dados;
            
        }else{*/
                
            $usuario = $this->db->get('funcionario')->result();
        
        #}

        if(count($usuario) == 1){
            return $usuario;
        }else{
            return false;
        }

	}
    
    /**
    * Função busca todos as especialidades que estão associadas a algum médico
    * @return Retorna as especialidades
    */
    public function especialidadesAssociadas(){
        
        $this->db->where("cd_especialidade IN (SELECT DISTINCT cd_especialidade FROM especialidade_medico)");
        return $this->db->get('especialidade')->result();
        
    }
    
    /**
    * Função que retorna as especialidades do médico
    * @return Retorna as especialidades do médico
    */
    public function especialidade_medico($matricula){
        
        $this->db->where("matricula_funcionario", $matricula);
        return $this->db->get('especialidade_medico')->result();
        
    }
    
    /**
    * Função altera a senha do funcionário
    * @return Retorna a matrícula do funcionário
    */
    public function alteraSenha(){
		
		$sql = "UPDATE funcionario SET senha_funcionario = '".md5($this->input->post('nova_senha'))."'";
        $sql .= " WHERE cpf_funcionario = '".$this->input->post('cpf_funcionario')."'";
        $sql .= " AND data_nascimento_funcionario = '".$this->util->formataData($this->input->post('data_nascimento_funcionario'), 'USA')."';";
		$this->db->query($sql);
        
		if($this->db->affected_rows()){
		  
            $this->db->select('matricula_funcionario AS login');
            $this->db->where('cpf_funcionario', $this->input->post('cpf_funcionario'));
            
            return $this->db->get('funcionario')->result();
            
		}else{
		  
            return false;
            
		}
        
    }

}