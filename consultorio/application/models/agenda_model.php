<?php
/**
* Classe que realiza todas as interações com a entidade agenda
*/
class Agenda_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->library('Util', '', 'util');
	}
    
    /**
    * Função que pega os dados dos médicos para montar a combo dinâmica
    * @return Retorna os médicos
    */
    public function dadosMedicoConsulta(){
        
        $this->db->select('cd_funcionario, nome_funcionario');
        $this->db->where('sigla_tipo_funcionario', 'ME');
        $this->db->order_by("nome_funcionario", "asc"); 
		return $this->db->get('funcionario')->result();
	}
	
    /**
    * Função que realiza a inserção dos dados da consulta na base de dados
    * 
    * @param $matricula Matrícula que será associada a marcação de consulta
    * 
    * @return O número de linhas afetadas pela operação
    */
	public function insere($matricula){
        
        /*
        Se já existe consulta marcada na data e horário, retorna false para evitar conflito de horário
        */
        if($this->consultaExistente($matricula)){
            return false;
        }
        
        array_pop($_POST); // Remove o último elemento do array $_POST (CAMPO cd_agenda)
        
        $campo[] = 'matricula_paciente_agenda';
		$valor[] = $matricula;
        $campo[] = 'sigla_situacao_agenda';
		$valor[] = "'MAR'";
		foreach($_POST as $c => $v){
			
            if(preg_match('/.*agenda$/', $c)) {
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
		}
		
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
        
		$sql = "INSERT INTO agenda(".$campos.")\n VALUES(".$valores.");";

		$this->db->query($sql);
		return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
	}
    
    /**
    * Função que verifica se existe consulta marcada no mesmo dia e horário
    * Isso evita conflito de horário
    * 
    * @return Returna 'true' caso exista e 'false' caso não exista
    */
    public function consultaExistente($matricula = null){
        
        $data = $this->util->formaValorBanco($this->input->post('data_agenda'));
        
        $this->db->select('COUNT(*) AS total');
        #$this->db->where('matricula_paciente_agenda <> '.$matricula);
        $this->db->where('data_agenda = '.$data);
        $this->db->where("horario_agenda = '".$this->input->post('horario_agenda').":00'");
        $this->db->where('matricula_medico_agenda', $this->input->post('matricula_medico_agenda'));
        $this->db->where('sigla_situacao_agenda', 'MAR');
        #$this->db->where('cd_especialidade_agenda', $this->input->post('cd_especialidade_agenda'));
       
        $res = $this->db->get('agenda')->result();
        #echo $this->db->last_query(); Exibe a query
       
        if($res[0]->total > 0){
            return true;
        }else{
            return false;
        }
        
    }
    
    /**
    * Função que realiza a atualização dos dados do paciente na base de dados
    * 
    * @param $data Que fitrará a busca de todos os dados
    * 
    * @return O número de linhas afetadas pela operação
    */
    public function todasConsultas($data, $solicitacao = null){
        
        $this->db->select(  'matricula_paciente, 
                            nome_paciente, 
                            tel_fix_paciente, 
                            tel_cel_paciente, 
                            cd_agenda, 
                            horario_agenda, 
                            data_agenda, 
                            nome_situacao_agenda, 
                            sigla_plano_agenda, 
                            matricula_medico_agenda, 
                            nome_funcionario, 
                            agenda.sigla_situacao_agenda, 
                            novo_paciente,
                            cd_especialidade AS especialidade,
                            CASE WHEN data_agenda < CURDATE() THEN \'Visualizar atendimento\' ELSE \'Atender paciente\' END AS title_atendimento');
        $this->db->where('data_agenda', $data);
        $this->db->where('cd_especialidade_agenda = cd_especialidade');

        if($this->session->userdata('tipo') == 'ME' and $solicitacao == 'medico'){
            $this->db->where('matricula_medico_agenda', $this->session->userdata('matricula'));
        }
        
        $this->db->order_by("horario_agenda", "asc"); 
        $this->db->from('agenda');
        $this->db->join('paciente', 'matricula_paciente_agenda = matricula_paciente');
        $this->db->join('situacao_agenda', 'situacao_agenda.sigla_situacao_agenda = agenda.sigla_situacao_agenda');
        $this->db->join('funcionario', 'matricula_medico_agenda = matricula_funcionario');
        $this->db->join('especialidade_medico', 'especialidade_medico.matricula_funcionario = funcionario.matricula_funcionario');
        #$this->db->get()->result();
        #echo '<pre>';
        #print_r($this->db->last_query());
        #exit();
		return $this->db->get()->result();
        
    }
	
    /**
    * Função que realiza a atualização dos dados do paciente na base de dados
    * 
    * @param $matricula Matrícula que esta associada a marcação de consulta
    * 
    * @return O número de linhas afetadas pela operação
    */
	public function atualiza($matricula){
	   
       /*
        Se já existe consulta marcada na data e horário, retorna false para evitar conflito de horário
        */
        if($this->consultaExistente($matricula)){
            return false;
        }
        
        $campoValor[] = 'matricula_paciente_agenda = '.$matricula;
		foreach($_POST as $c => $v){
			
            if(preg_match('/.*agenda$/', $c)) {
                
                if($this->input->post($c) <> 'cd_agenda'){
                    
        			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
        			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
        			
        			$campoValor[] = $c.' = '.$valorFormatado;
                
                }
            
            }
		}
		
		$camposValores = implode(', ', $campoValor);
        
		$sql = "UPDATE agenda SET ".$camposValores." \nWHERE cd_agenda = ".$this->input->post('cd_agenda');
		
		$this->db->query($sql);
        
        return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
		
	}
    
    /**
    * Função que pega todas as situações da agenda para montar a combo dinâmica
    * 
    * @return Retorna as situações da agenda
    */
    public function situacao(){
        
        $this->db->select('sigla_situacao_agenda AS sigla_situacao, nome_situacao_agenda AS nome_situacao');
        $this->db->order_by("nome_situacao_agenda", "asc"); 
		return $this->db->get('situacao_agenda')->result();
	}
    
    /**
    * Função que altera a situação da consulta do paciente na agenda 
    *
    * @return A o número de linha afetadas pela alteração
    */
    public function alteraSituacaoConsulta(){
        
        if($this->input->post('situacao_consulta') == 'PRE'){
            $dataPresenca = ",hora_presenca_paciente_agenda = '".date('H:i:s')."'";
        }else{
            $dataPresenca = ',hora_presenca_paciente_agenda = null';
        }
        
        $sql = "UPDATE agenda SET sigla_situacao_agenda = '".$this->input->post('situacao_consulta')."' ".$dataPresenca." WHERE cd_agenda = ".$this->input->post('cd_agenda');
		#echo '<pre>'; echo $sql; exit();
		$this->db->query($sql);
        
        return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
        
    }
    
    /**
    * Função que altera a situação da consulta do paciente na agenda para 'CONSULTANDO' (EXCLUSIVA)
    *
    * @return A o número de linha afetadas pela alteração
    */
    public function situacaoConsultando($id){
        
        $sql = "UPDATE agenda SET sigla_situacao_agenda = 'CON', hora_inicio_atendimento_agenda = '".date('H:i:s')."' WHERE cd_agenda = ".$id;
		
		$this->db->query($sql);
        
        return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
        
    }
    
    /**
    * Função que altera a situação da consulta do paciente na agenda para 'CHAMADO' (EXCLUSIVA)
    *
    * @return A o número de linha afetadas pela alteração
    */
    public function situacaoChamado($id){
        
        $sql = "UPDATE agenda SET sigla_situacao_agenda = 'CHA' WHERE cd_agenda = ".$id;
		
		$this->db->query($sql);
        
        return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
        
    }
    
    /**
    * Função que busca todas as consultas que estão sendo realizadas hoje em tempo real (AJAX)
    * 
    * @return Os registros encontrados na verificação
    */
    public function verificaConsultas(){
	
		$condicao = "data_agenda = '".date('Y-m-d')."'";
        
        $this->db->select('cd_agenda, horario_agenda, nome_paciente, agenda.sigla_situacao_agenda, nome_situacao_agenda');
        $this->db->from('agenda');
        $this->db->join('paciente', 'matricula_paciente_agenda = matricula_paciente');
        $this->db->join('situacao_agenda', 'situacao_agenda.sigla_situacao_agenda = agenda.sigla_situacao_agenda');
		$this->db->where($condicao);
        
        $this->db->order_by("horario_agenda", "asc"); 
            
		return $this->db->get()->result();
	
	}
    
    /**
    * Função que monitora os agendamentos
    * 
    * @return Os registros encontrados na verificação
    */
    public function verificaAgenda(){
	
		$condicao = "data_agenda = '".date('Y-m-d')."'";
        
        $this->db->select('cd_agenda, horario_agenda, nome_paciente, agenda.sigla_situacao_agenda, nome_situacao_agenda');
        $this->db->from('agenda');
        $this->db->join('paciente', 'matricula_paciente_agenda = matricula_paciente');
        $this->db->join('situacao_agenda', 'situacao_agenda.sigla_situacao_agenda = agenda.sigla_situacao_agenda');
		$this->db->where($condicao);
        $this->db->where('matricula_medico_agenda', $this->session->userdata('matricula'));
        
        $this->db->order_by("horario_agenda", "asc"); 
            
		return $this->db->get()->result();
	
	}
    
    /**
    * Função que busca todos os pacientes marcados no dia de hoje que foram chamados pelo médico em tempo real (AJAX)
    * 
    * @return Os registros encontrados na verificação
    */
    public function consultasDoPaciente(){
	
		$condicao = "nome_paciente LIKE '%".$this->input->post('dados_paciente')."%' OR rg_paciente LIKE '%".$this->input->post('dados_paciente')."%'";
        
        $this->db->select('
                            cd_agenda, 
                            data_agenda, 
                            horario_agenda, 
                            nome_situacao_agenda, 
                            matricula_paciente, 
                            nome_paciente, 
                            tel_fix_paciente, 
                            tel_cel_paciente, 
                            sigla_plano_agenda, 
                            matricula_medico_agenda,
                            nome_funcionario,
                            cd_especialidade_agenda');
        $this->db->from('agenda');
        $this->db->join('paciente', 'matricula_paciente_agenda = matricula_paciente');
        $this->db->join('situacao_agenda', 'situacao_agenda.sigla_situacao_agenda = agenda.sigla_situacao_agenda');
        $this->db->join('funcionario', 'matricula_medico_agenda = matricula_funcionario');
        #$this->db->join('especialidade', 'cd_especialidade = cd_especialidade_agenda');
		$this->db->where($condicao);
        $this->db->order_by("data_agenda", "desc"); 
        $this->db->limit(10);
            
		return $this->db->get()->result();
	
	}
    
    /**
     * Função que verifica a quantidade de consultas marcadas para serem informados no dia do caledário
     * 
     * @return Os registros de quantidade
     */
    public function qtdConsultasMarcadas(){
        
        $condicao = "data_agenda BETWEEN DATE_ADD(CURRENT_DATE(),INTERVAL -90 DAY) AND DATE_ADD(CURRENT_DATE(),INTERVAL 120 DAY)";
        
        $this->db->select('data_agenda, COUNT(*) AS qtd_consulta');
        $this->db->from('agenda');
        $this->db->where($condicao);
        
        if($this->session->userdata('tipo') == 'ME'){
            $this->db->where('matricula_medico_agenda', $this->session->userdata('matricula'));
        }
        
        $this->db->group_by("data_agenda"); 
        
        return $this->db->get()->result();
    }
    
    /**
     * Função que informa a estatística diária das consultas
     * 
     * @return Os dados da estatística
     */
    public function estatisticaConsultas(){
        
        if($this->input->post('dataCalendario')){
            $data = $this->input->post('dataCalendario');
        }else{
            $data = date('Y-m-d');
        }
        
        $sql = "
                            total,
                            ROUND(((atendidos / total) * 100)) AS porc_atendidos,
                            ROUND(((ausentes / total) * 100)) AS porc_ausentes,
                            ROUND(((desistiu / total) * 100)) AS porc_desistiu
                            FROM (
                            	SELECT 
                            		(SELECT
                            		    COUNT(*) AS total
                            		    FROM agenda
                            		    WHERE 
                            			data_agenda = '".$data."') AS total,
                            		 (SELECT
                            		     COUNT(*) AS atendidos
                            		     FROM agenda
                            		     WHERE 
                            			 sigla_situacao_agenda = 'FIN'
                            			AND data_agenda = '".$data."') AS atendidos,
                            		 (SELECT
                            			  COUNT(*) AS ausentes
                            			  FROM agenda
                            			  WHERE 
                            			      sigla_situacao_agenda = 'MAR' AND data_agenda < CURRENT_DATE AND horario_agenda < CURRENT_TIME
                            			AND data_agenda = '".$data."') AS ausentes,
                            		  (SELECT
                            		       COUNT(*) AS desistiu
                            		       FROM agenda
                            		       WHERE 
                            			   sigla_situacao_agenda = 'DES'
                            			AND data_agenda = '".$data."') AS desistiu
                            	FROM agenda
                            	LIMIT 1
                            ) AS res";
        $this->db->select($sql);
        
        return $this->db->get()->result();
    }
    
    /**
    * Função que busca os dados do atendimento da consulta
    * 
    * @return Os dados da consulta atendimento
    */
    public function atendimento($cd_agenda){
        
        $this->db->select('cd_agenda, 
                        data_agenda, 
                        horario_agenda, 
                        matricula_paciente, 
                        nome_paciente, 
                        tel_fix_paciente, 
                        tel_cel_paciente, 
                        sigla_plano_agenda, 
                        matricula_medico_agenda, 
                        nome_funcionario, 
                        anotacoes_medico_agenda, 
                        cd_especialidade_agenda, 
                        nome_especialidade,
                        hora_final_atendimento_agenda');
        $this->db->from('agenda');
        $this->db->join('paciente', 'matricula_paciente_agenda = matricula_paciente');
        $this->db->join('funcionario', 'matricula_funcionario = matricula_medico_agenda');
        $this->db->join('especialidade', 'cd_especialidade = cd_especialidade_agenda');
		$this->db->where('cd_agenda', $cd_agenda);
            
		return $this->db->get()->result();
	
	}
    
    /**
    * Função que retorna todas as receitas médica de alguma consulta
    * 
    * @return as receitas encontradas
    */
    public function receitasMedica($cd_agenda){
        
        $this->db->where('cd_agenda_receita_medica', $cd_agenda);
        return $this->db->get('receita_medica')->result();
        
    }
    
    
    /**
    * Função que retorna os dados de alguma receita
    * 
    * @return as receitas encontradas
    */
    public function dadosReceita($cdReceita){
        
        $this->db->where('cd_receita_medica', $cdReceita);
        return $this->db->get('receita_medica')->result();
        
    }
    
    /**
    * Função as receitas de acordo com o histórico de atendimentos
    * 
    * @return as receitas do atendimentos
    */
    public function receitas_medicas($cdAgenda){
        
        $this->db->where('cd_agenda_receita_medica', $cdAgenda);
        return $this->db->get('receita_medica')->result();
        
    }
    
    /**
    * Função que busca todo o histórico de atendimento associados ao médico
    * 
    * @return Os dados encontrados
    */
    public function atendimentoHistoricoPaciente($cdAgenda, $matricula, $especialidade, $todos = 'nao'){
        
        $this->db->select('
                            cd_agenda, 
                            data_agenda, 
                            horario_agenda, 
                            matricula_paciente, 
                            nome_paciente, 
                            tel_fix_paciente, 
                            tel_cel_paciente, 
                            sigla_plano_agenda, 
                            matricula_medico_agenda, 
                            nome_funcionario, 
                            anotacoes_medico_agenda, 
                            cd_especialidade, 
                            nome_especialidade');
        $this->db->from('agenda');
        $this->db->join('paciente', 'matricula_paciente_agenda = matricula_paciente');
        $this->db->join('funcionario', 'matricula_funcionario = matricula_medico_agenda');
        $this->db->join('especialidade', 'cd_especialidade = cd_especialidade_agenda');
		$this->db->where('matricula_paciente', $matricula);
        
        if($todos == 'nao'){
            $this->db->where('matricula_medico_agenda', $this->session->userdata('matricula'));
        }
        
        $this->db->where('sigla_situacao_agenda', 'FIN');
        $this->db->where('cd_especialidade_agenda', $especialidade);
        $this->db->where('cd_agenda <> '.$cdAgenda);
        $this->db->order_by("data_agenda", "desc");
        $this->db->order_by("horario_agenda", "desc");  
            
		return $this->db->get()->result();
	
	}
    
    /**
    * Função que salva o atendimento
    * 
    * @return return TRUE ou FALSE dependendo do status da operação
    */
    public function salvaAtendimentoConsulta(){
        
        $campoValor[] = "hora_final_atendimento_agenda = '".date('H:i:s')."'";

        foreach($_POST as $c => $v){
			
            if(preg_match('/.*agenda$/', $c)) {
                
                if($c <> 'cd_agenda'){
                    
        			$valorFormatado = $this->util->formaValorBanco(preg_replace("/['\"]/", "\\\"", $v));
        			
        			$campoValor[] = $c.' = '.$valorFormatado;
                
                }
            
            }
		}
        
		$camposValores = implode(', ', $campoValor);
        
        $this->db->trans_begin();
        
		$sql = "UPDATE agenda SET ".$camposValores." \nWHERE cd_agenda = ".$this->input->post('cd_agenda');
		
		$this->db->query($sql);
        
        $sqlApagaReceita = "DELETE FROM receita_medica WHERE cd_agenda_receita_medica = ".$this->input->post('cd_agenda');
        $this->db->query($sqlApagaReceita);
        
        foreach($this->input->post('receita_medica') as $receita){
            
            if(trim($receita) <> ''){
            
                $sqlReceita = "INSERT INTO receita_medica (conteudo_receita_medica,cd_agenda_receita_medica) VALUES('".$receita."',".$this->input->post('cd_agenda').")"; 
                $this->db->query($sqlReceita);
            
            }
        }
        
        #return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
        
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
    
    public function horariosAgendaMedico(){
        
        $data = $this->util->formaValorBanco($this->input->post('data_agenda'));
        
        $this->db->select('SUBSTR(horario_agenda,1,5) AS horarios_marcados');
        #$this->db->where('matricula_paciente_agenda <> '.$matricula);
        $this->db->where('data_agenda = '.$data);
        #$this->db->where("horario_agenda = '".$this->input->post('horario_agenda').":00'");
        #$this->db->where('matricula_medico_agenda', $this->input->post('matricula_medico_agenda'));
        $this->db->where('sigla_situacao_agenda', 'MAR');
        #$this->db->where('cd_especialidade_agenda', $this->input->post('cd_especialidade_agenda'));
       
        return $this->db->get('agenda')->result();
        
    }

}