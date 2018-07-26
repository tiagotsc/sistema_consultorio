<?php

/**
 * Sms_model
 * 
 * Classe que realiza as operações de sms
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Sms_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
        $this->load->library('Util', '', 'util');
	}
    
    public function saldoPacote(){
        
        $this->db->select("cd_sms, qtd_sms");
        $this->db->where("status_sms", "A");
        $this->db->order_by("data_sms", "asc"); 
        $this->db->limit(1);
        return $this->db->get('sms')->result();
        
    }
    
    public function saldoTotal(){
        
        $this->db->select_sum('qtd_sms');
        $this->db->where("status_sms", "A");
        return $this->db->get('sms')->result();
        
    }
    
    /**
    * Função que pega todas as consultas que estão com a situação de "MARCADO" na data informada
    * @return Retorna os dados da consulta
    */
	public function consultasParaEnvio(){
        
        $data = $this->util->formataData($this->input->post("data_consulta"), 'USA');
        
        $this->db->select("
                            cd_agenda,
                            matricula_paciente,
                            nome_paciente,
                            SUBSTRING_INDEX(SUBSTRING_INDEX(nome_funcionario, ' ', 1), ' ', -1)  AS nome_medico,
                        	DATE_FORMAT(data_agenda, '%d/%m/%Y') AS data_agenda,
                        	SUBSTR(horario_agenda, 1, 5) AS horario_agenda,
                        	tel_cel_paciente");
        $this->db->join("funcionario","matricula_funcionario = matricula_medico_agenda");
        $this->db->join("paciente","matricula_paciente = matricula_paciente_agenda");
        
        $this->db->where("sigla_situacao_agenda", "MAR");
        $this->db->where("envio_sms_agenda", "N");
        $this->db->where("LENGTH(REPLACE(REPLACE(REPLACE(tel_cel_paciente,'(',''),')',''),'-','')) IN (10,11)");
        $this->db->where("data_agenda", $data);
        $this->db->order_by("data_agenda, horario_agenda", "asc"); 
       
		return $this->db->get('agenda')->result();
	}
    
    public function debitaPacote($cd, $qtd){
        
        $status = ($qtd == 0)? 'I': 'A';
        
        $sql = "UPDATE sms SET qtd_sms = ".$qtd.", status_sms = '".$status."' WHERE cd_sms = ".$cd;
        $this->db->query($sql);
        
    }
    
    public function gravaEnvio($cdAgenda, $cdPacoteSMS, $saldoAtual, $celular, $msg, $chaveMsg, $statusEnvio){
        
        $sql = "INSERT INTO sms_agenda(cd_agenda, cd_sms, chave_sms, celular, saldo_sms, msg, status_envio) ";
        $sql .= "VALUES(".$cdAgenda.", ".$cdPacoteSMS.", ".$chaveMsg.", ".$celular.", ".$saldoAtual.", '".$msg."', '".$statusEnvio."')";
        
        $this->db->query($sql);
        return $this->db->affected_rows();
        
    }
    
    public function statusSMSConsulta($cdAgenda){
        
        $sql = "UPDATE agenda SET envio_sms_agenda = 'S' WHERE cd_agenda = ".$cdAgenda;
        $this->db->query($sql);
        
    }
    
    public function consultasEnviadas(){
        
        $this->db->select("cd_sms_agenda,chave_sms");
        $this->db->where("resposta IS NULL");
        $this->db->where("DATE(data_hora) BETWEEN DATE_ADD(CURDATE(), INTERVAL - 1 DAY) AND CURDATE()");
        return $this->db->get('sms_agenda')->result();
        
    }
    
    public function gravaResposta($reposta, $dados)
    {   
        $sql = "UPDATE sms_agenda SET resposta = '".$reposta."' WHERE chave_sms = ".$dados['chave_sms'];
        $this->db->query($sql);
    }
    
    public function atualizaSituacaoConsulta($chave)
    {
        
        $sql = "UPDATE agenda
                SET sigla_situacao_agenda = 'DES'
                WHERE
                	cd_agenda =(
                		SELECT
                			sms_agenda.cd_agenda
                		FROM
                			sms_agenda
                		WHERE
                			chave_sms = ".$chave."
                	)";
                    
        $this->db->query($sql);
        
    }
    
    public function adicionaPacote($qtd){
        
        $sql = "INSERT INTO sms (original_qtd_sms, qtd_sms, status_sms) VALUES (".$qtd.", ".$qtd.", 'A');";
            
        return $this->db->query($sql);
        
    }

}