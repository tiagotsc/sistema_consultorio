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
class Dashboard_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
    
    /**
    * Função que pega os dados do cliente
    * @return Retorna os dados do cliente
    */
    public function anosAgenda(){
        
        $this->db->distinct();
        $this->db->select('SUBSTR(data_agenda, 1 , 4) AS anos');
        $this->db->order_by('SUBSTR(data_agenda, 1 , 4)', 'desc'); 
        return $this->db->get('agenda')->result();
        
    }
    
    public function qtdPacientes($ano){
        
        $sql = "SELECT
                    DISTINCT
                	CASE 
                		WHEN SUBSTR(agePri.data_agenda,6,2) = 1 
                			THEN 'Jan'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 2) 
                			THEN 'Fev'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 3)
                			THEN 'Mar'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 4)
                			THEN 'Abr'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 5)
                			THEN 'Mai'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 6)
                			THEN 'Jun'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 7)
                			THEN 'Jul'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 8)
                			THEN 'Ago'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 9)
                			THEN 'Set'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 10)
                			THEN 'Out'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 11)
                			THEN 'Nov'
                		WHEN (SUBSTR(agePri.data_agenda,6,2) = 12)
                			THEN 'Dez'
                	ELSE null END AS mes,
                	(
                		SELECT
                			COUNT(*)
                		FROM agenda AS ageSec
                		WHERE ageSec.sigla_situacao_agenda = 'FIN'
                		AND SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
                		AND ageSec.data_agenda LIKE '".$ano."%'
                	) AS qtd_finalizados,
                	(
                		SELECT
                			COUNT(*)
                		FROM agenda AS ageSec
                		WHERE ageSec.sigla_situacao_agenda = 'MAR'
                		AND ageSec.hora_presenca_paciente_agenda IS NULL
                		AND SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
                		AND ageSec.data_agenda LIKE '".$ano."%'
                	) AS qtd_ausentes
                FROM agenda AS agePri
                WHERE data_agenda LIKE '".$ano."%'";
        
        return $this->db->query($sql)->result();  
        
    }
    
    /**
    * Função que pega a quantidade de pacientes atendindos por mês no ano
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    /*public function qtdAtendidosMesAno($mes, $ano){
            
        #$sql = "(SELECT COUNT(*) FROM agenda WHERE data_agenda LIKE '".$ano."-".$mes."%' AND sigla_situacao_agenda = 'FIN') AS res";

        #$this->db->select($sql);
        #return $this->db->get('agenda')->result();
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('sigla_situacao_agenda', 'FIN');
        $this->db->from('agenda');
        return $this->db->count_all_results();
        
    }*/
    
    /**
    * Função que pega a quantidade de pacientes ausentes por mês no ano
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    /*public function qtdAusentesMesAno($mes, $ano){
            
        #$sql = "(SELECT COUNT(*) FROM agenda WHERE data_agenda LIKE '".$ano."-".$mes."%' AND sigla_situacao_agenda = 'FIN') AS res";

        #$this->db->select($sql);
        #return $this->db->get('agenda')->result();
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('sigla_situacao_agenda', 'MAR');
        $this->db->where('hora_presenca_paciente_agenda IS NULL');
        $this->db->from('agenda');
        return $this->db->count_all_results();
        
    }*/
    
    /**
    * Função que pega a média de atrasos dos pacientes no mês por ano
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    public function mediaAtrasosPacientesMesAno($mes, $ano){
        
        $this->db->select('	SUBSTR(
                        		CAST(
                        			SUM(
                        				TIMEDIFF(
                        					hora_presenca_paciente_agenda,
                        					horario_agenda
                        				)
                        			) / COUNT(*) AS time
                        		), 1, 5
                        	) AS media_atraso_pacientes');
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('hora_presenca_paciente_agenda > horario_agenda');
        $this->db->where('hora_presenca_paciente_agenda IS NOT NULL');
        $resultado = $this->db->get('agenda')->result();
        return $resultado[0]->media_atraso_pacientes;
        
    }
    
    /**
    * Função que pega a média de atrasos dos pacientes no mês por ano
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    public function mediaTempoAtendimentoMesAno($mes, $ano){
        
        $this->db->select('SUBSTR(
                        		CAST(
                        			SUM(
                        				TIMEDIFF(
                        					hora_final_atendimento_agenda,
                        					hora_inicio_atendimento_agenda
                        				)
                        			) / COUNT(*) AS time
                        		), 1, 5
                        	) AS media_tempo_atendimento');
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('sigla_situacao_agenda', 'FIN');
        $resultado = $this->db->get('agenda')->result();
        return $resultado[0]->media_tempo_atendimento;
        
    }
    
    /**
    * Função que pega a média dos atrasos dos atendimentos no mês por ano
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    public function mediaAtrasosAtendimentoMesAno($mes, $ano){
        
        $this->db->select('SUBSTR(
                        		CAST(
                        			SUM(
                        				TIMEDIFF(
                        					hora_inicio_atendimento_agenda,
                        					horario_agenda
                        				)
                        			) / COUNT(*) AS time
                        		), 1, 5
                        	) AS media_atraso_atendimento');
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('hora_presenca_paciente_agenda <= horario_agenda');
        $this->db->where('hora_inicio_atendimento_agenda > horario_agenda');
        $this->db->where('sigla_situacao_agenda', 'FIN');
        $resultado = $this->db->get('agenda')->result();
        return $resultado[0]->media_atraso_atendimento;
        
    }
    
    public function mediaTempo(){
        
        $sql = "SELECT
            	DISTINCT
            	CASE 
            		WHEN SUBSTR(agePri.data_agenda,6,2) = 1 
            			THEN 'Jan'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 2) 
            			THEN 'Fev'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 3)
            			THEN 'Mar'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 4)
            			THEN 'Abr'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 5)
            			THEN 'Mai'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 6)
            			THEN 'Jun'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 7)
            			THEN 'Jul'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 8)
            			THEN 'Ago'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 9)
            			THEN 'Set'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 10)
            			THEN 'Out'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 11)
            			THEN 'Nov'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 12)
            			THEN 'Dez'
            	ELSE null END AS mes,
            	(
            	SELECT
            		SUBSTR(
            			SEC_TO_TIME( 
            				SUM( 
            							TIME_TO_SEC(
            								TIMEDIFF(
            										ageSec.hora_presenca_paciente_agenda,
            										ageSec.horario_agenda
            									)
            							) 
            				) / COUNT(*)
            			)
            		, 1, 5)
            	FROM agenda AS ageSec
            	WHERE SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
            	AND ageSec.hora_presenca_paciente_agenda > ageSec.horario_agenda
            	AND ageSec.hora_presenca_paciente_agenda IS NOT NULL
            	AND ageSec.data_agenda LIKE '2014%'
            	) AS media_atraso_pacientes,
            	(
            	SELECT
            		SUBSTR(
            			SEC_TO_TIME( 
            				SUM( 
            							TIME_TO_SEC(
            								TIMEDIFF(
            										ageSec.hora_final_atendimento_agenda,
            										ageSec.hora_inicio_atendimento_agenda
            									)
            							) 
            				) / COUNT(*)
            			)
            		, 1, 5)
            	FROM agenda AS ageSec
            	WHERE SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
            	AND ageSec.sigla_situacao_agenda = 'FIN'
            	AND ageSec.data_agenda LIKE '2014%'
            	) AS media_tempo_atendimento,
            	(
            	SELECT
            		SUBSTR(
            			SEC_TO_TIME( 
            				SUM( 
            							TIME_TO_SEC(
            								TIMEDIFF(
            										ageSec.hora_inicio_atendimento_agenda,
            										ageSec.horario_agenda
            									)
            							) 
            				) / COUNT(*)
            			)
            		, 1, 5)
            	FROM agenda AS ageSec
            	WHERE SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
            	AND ageSec.hora_presenca_paciente_agenda <= ageSec.horario_agenda
            	AND ageSec.hora_inicio_atendimento_agenda > ageSec.horario_agenda
            	AND ageSec.data_agenda LIKE '2014%'
            	) AS media_atraso_atendimento
            FROM agenda AS agePri
            WHERE agePri.data_agenda LIKE '2014%'";
            
            return $this->db->query($sql)->result();
        
    }
    
    /**
    * Função que pega a quantidade de pacientes com plano de saúde
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    /*public function qtdComPlanoMesAno($mes, $ano){
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('sigla_plano_agenda', 'CON');
        $this->db->from('agenda');
        return $this->db->count_all_results();
        
    }*/
    
    /**
    * Função que pega a quantidade de pacientes sem plano de saúde
    * 
    * @param $mes Mês para filtrar a consulta
    * @param $ano Ano para filtrar a consulta
    * 
    * @return Retorna as quantidades
    */
    /*public function qtdSemPlanoMesAno($mes, $ano){
        
        $this->db->like('data_agenda', $ano."-".$mes, 'after'); 
        $this->db->where('sigla_plano_agenda', 'PAR');
        $this->db->from('agenda');
        return $this->db->count_all_results();
        
    }*/
    
    /**
    * Função que pega a quantidade de pacientes com plano e sem plano de saúde
    * 
    * @return Retorna as quantidades
    */
    public function qtdParticularConvenio($ano){
        
        $sql = "SELECT
            	DISTINCT
            	CASE 
            		WHEN SUBSTR(agePri.data_agenda,6,2) = 1 
            			THEN 'Jan'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 2) 
            			THEN 'Fev'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 3)
            			THEN 'Mar'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 4)
            			THEN 'Abr'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 5)
            			THEN 'Mai'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 6)
            			THEN 'Jun'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 7)
            			THEN 'Jul'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 8)
            			THEN 'Ago'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 9)
            			THEN 'Set'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 10)
            			THEN 'Out'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 11)
            			THEN 'Nov'
            		WHEN (SUBSTR(agePri.data_agenda,6,2) = 12)
            			THEN 'Dez'
                ELSE null END AS mes,
                (
                	SELECT
                		COUNT(*)
                	FROM agenda AS ageSec
                	WHERE SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
                	AND ageSec.sigla_plano_agenda = 'PAR'
                	AND ageSec.data_agenda LIKE '".$ano."%'
                ) AS qtd_particular,
                (
                	SELECT
                		COUNT(*)
                	FROM agenda AS ageSec
                	WHERE SUBSTR(ageSec.data_agenda,6,2) = SUBSTR(agePri.data_agenda,6,2)
                	AND ageSec.sigla_plano_agenda = 'CON'
                	AND ageSec.data_agenda LIKE '".$ano."%'
                ) AS qtd_convenio
                FROM agenda AS agePri
                WHERE agePri.data_agenda LIKE '".$ano."%'";
            
            return $this->db->query($sql)->result();            
        
    }

}