<?php

/**
 * Relatorio_model
 * 
 * Classe que realiza consultas para geração de relatórios
 * 
 * @package   
 * @author Tiago Silva Costa
 * @version 2014
 * @access public
 */
class Relatorio_model extends CI_Model{
	
	/**
	 * Relatorio_model::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		parent::__construct();
        $this->load->library('Util', '', 'util');
	}
	
	/**
	 * Relatorio_model::smsEnviados()
	 * 
     * Pega os dados dos SMS enviados dentro do intervalo informado
     * 
	 * @return os dados
	 */
	public function smsEnviados(){
       
       if($this->input->post('data_inicio') == ''){
            $dataInicio = '';
       }else{
            $dataInicio = $this->util->formaValorBanco($this->input->post('data_inicio'));
       }    
       
       if($this->input->post('data_fim') == ''){
            $dataFim = '';
       }else{
            $dataFim = $this->util->formaValorBanco($this->input->post('data_fim'));
       }
        
        $sql = "SELECT 
                	sms.cd_sms AS pacote_sms,
                	original_qtd_sms AS qts_sms_comprados,
                	chave_sms,
                	celular,
                	status_envio,
                	msg,
                	resposta AS resposta_sms,
                	DATE_FORMAT(data_hora, '%d/%m/%Y %H:%i:%s') AS data_hora_envio,
                	DATE_FORMAT(data_agenda, '%d/%m/%Y') AS data_consulta,
                	horario_agenda,
                	matricula_paciente,
                	nome_paciente
                FROM sms_agenda
                INNER JOIN sms ON sms_agenda.cd_sms = sms.cd_sms
                INNER JOIN agenda ON agenda.cd_agenda = sms_agenda.cd_agenda
                INNER JOIN paciente ON matricula_paciente = matricula_paciente_agenda
                WHERE DATE(data_hora) BETWEEN DATE(".$dataInicio.") AND DATE(".$dataFim.")";
                
          return $this->db->query($sql)->result_array();
        
        #return $this->db->get()->result_array();
        
	}

}