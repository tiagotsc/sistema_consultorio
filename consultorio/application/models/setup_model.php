<?php
/**
* Classe responsável por instalar todo o banco de dados do sistema
*/
include_once('config.inc.php');
class Setup_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->library('Util', '', 'util');
	}
    
    /**
    * Função que pega os dados dos médicos para montar a combo dinâmica
    * @return Retorna os médicos
    */
    public function executaInstalacao(){
        
        $this->db->trans_begin();
        
        $this->apagaTabelas();
        
        $this->perfil();
        $this->permissao();
        $this->permissao_perfil();
        $this->status();
        
        $this->estado();
        $this->nacionalidade();
        $this->plano();
        $this->sexo();
        $this->situacao_agenda();
        $this->especialidade();
        
        $this->funcionario();
        $this->paciente();
        $this->agenda();
        $this->receita_medica();
        $this->sms();
        $this->sms_agenda();
        
        
        #$this->session();
        
        
        $this->menu();
        
        $this->cliente();
        $this->telefone_cliente();
        $this->especialidade_medico();
        
        $this->dadosEstado();
        $this->dadosMenu();
        $this->dadosNacionalidade();
        $this->dadosPlano();
        $this->dadosSexo();
        $this->dadosSituacaoAgenda();
        $this->dadosStatus();
        $this->dadosTipoMedico();
        $this->dadosCliente();
        $this->dadosPermissao();
        $this->dadosPerfil();
        $this->dadosPermissaoPerfil();
        $this->dadosAdministrador();
        
        if ($this->db->trans_status() === TRUE)
        {
            $this->db->trans_commit();            
            return true;
        }else
        {
            $this->db->trans_rollback();            
            return false;
        }
        
	}
    
    private function apagaTabelas(){
        
        $sqlDrop = "DROP TABLE IF EXISTS `sms_agenda`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `sms`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `receita_medica`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `status`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `agenda`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `ci_sessions`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `estado`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `especialidade_medico`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `funcionario`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `menu`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `nacionalidade`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `paciente`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `plano`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `sexo`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `situacao_agenda`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `especialidade`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `telefone_cliente`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `cliente`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `sms_agenda`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `sms`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `permissao_perfil`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `perfil`;";
        $this->db->query($sqlDrop);
        
        $sqlDrop = "DROP TABLE IF EXISTS `permissao`;";
        $this->db->query($sqlDrop);
        
    }
    
    /**
    * Função que cria a tabela status
    * @return Retorna o boleano da operação
    */
    private function status(){
        
        $sql = "
                CREATE TABLE `status` (
                  `cd_status` int(11) NOT NULL AUTO_INCREMENT,
                  `nome_status` varchar(255) DEFAULT NULL,
                  `sigla_status` char(1) DEFAULT NULL,
                  PRIMARY KEY (`cd_status`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela agenda
    * @return Retorna o boleano da operação
    */
    private function agenda(){
        
        $sql = "
                CREATE TABLE `agenda` (
                  `cd_agenda` int(11) NOT NULL AUTO_INCREMENT,
                  `data_agenda` date DEFAULT NULL,
                  `horario_agenda` time DEFAULT NULL,
                  `sigla_situacao_agenda` char(3) NOT NULL,
                  `matricula_paciente_agenda` int(5) unsigned NOT NULL,
                  `sigla_plano_agenda` char(3) DEFAULT NULL,
                  `matricula_medico_agenda` char(6) NOT NULL,
                  `anotacoes_medico_agenda` text,
                  `hora_presenca_paciente_agenda` time DEFAULT NULL,
                  `hora_inicio_atendimento_agenda` time DEFAULT NULL,
                  `hora_final_atendimento_agenda` time DEFAULT NULL,
                  `cd_especialidade_agenda` int(11),
                  `envio_sms_agenda` enum('N','S') DEFAULT 'N',
                  PRIMARY KEY (`cd_agenda`),
                  KEY `idx_matricula_paciente_agenda` (`matricula_paciente_agenda`) USING BTREE,
                  KEY `fk_matricula_medico` (`matricula_medico_agenda`),
                  CONSTRAINT `fk_matricula_medico` FOREIGN KEY (`matricula_medico_agenda`) REFERENCES `funcionario` (`matricula_funcionario`) ON DELETE CASCADE,
                  CONSTRAINT `fk_matricula_paciente` FOREIGN KEY (`matricula_paciente_agenda`) REFERENCES `paciente` (`matricula_paciente`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
         return $this->db->query($sql);       
        
    }
    
    
    /**
    * Função que cria a tabela ci_sessions
    * @return Retorna o boleano da operação
    */
    private function session(){
        
        $sql = "
                CREATE TABLE `ci_sessions` (
                  `session_id` varchar(40) NOT NULL DEFAULT '0',
                  `ip_address` varchar(16) NOT NULL DEFAULT '0',
                  `user_agent` varchar(254) NOT NULL,
                  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
                  `user_data` text,
                  PRIMARY KEY (`session_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);       
        
    }
    
    /**
    * Função que cria a tabela endereco
    * @return Retorna o boleano da operação
    */
    /*private function endereco(){
        
        $sql = "
                CREATE TABLE `endereco` (
                  `cd_cep` int(11) NOT NULL AUTO_INCREMENT,
                  `tipo_logradouro` varchar(255) DEFAULT NULL,
                  `logradouro` varchar(255) DEFAULT NULL,
                  `bairro` varchar(255) DEFAULT NULL,
                  `cidade` varchar(255) DEFAULT NULL,
                  `uf` char(2) DEFAULT NULL,
                  `cep` int(11) DEFAULT NULL,
                  PRIMARY KEY (`cd_cep`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }*/
    
    /**
    * Função que cria a tabela estado
    * @return Retorna o boleano da operação
    */
    private function estado(){
        
        $sql = "
                CREATE TABLE `estado` (
                  `cd_estado` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `nome_estado` varchar(50) CHARACTER SET utf8 NOT NULL,
                  `sigla_estado` char(2) CHARACTER SET utf8 DEFAULT NULL,
                  PRIMARY KEY (`cd_estado`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela funcionario
    * @return Retorna o boleano da operação
    */
    private function funcionario(){
        
        $sql = "
                CREATE TABLE `funcionario` (
                  `cd_funcionario` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `matricula_funcionario` char(6) NOT NULL,
                  `nome_funcionario` varchar(255) NOT NULL,
                  `sigla_sexo_funcionario` enum('M','F') NOT NULL,
                  `cd_perfil_funcionario` int(11) unsigned,
                  `sigla_status_funcionario` char(1) DEFAULT NULL,
                  `tel_fix_funcionario` varchar(15) DEFAULT NULL,
                  `tel_cel_funcionario` varchar(15) DEFAULT NULL,
                  `cpf_funcionario` char(15) NOT NULL,
                  `email_funcionario` varchar(200) DEFAULT NULL,
                  `senha_funcionario` char(32) NOT NULL,
                  `endereco_funcionario` varchar(255) DEFAULT NULL,
                  `numero_funcionario` char(20) DEFAULT NULL,
                  `bairro_funcionario` char(100) DEFAULT NULL,
                  `estado_funcionario` char(2) DEFAULT NULL,
                  `cep_funcionario` char(9) NOT NULL,
                  `medico_funcionario` char(3) DEFAULT 'NAO',
                  `data_cadastro_funcionario` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `data_nascimento_funcionario` date NOT NULL,
                  PRIMARY KEY (`cd_funcionario`),
                  UNIQUE KEY `login_funcionario` (`matricula_funcionario`),
                  KEY `idx_cd_funcionario` (`cd_funcionario`),
                  KEY `idx_login_funcionario` (`matricula_funcionario`),
                  KEY `fk_cd_perfil_funcionario` (`cd_perfil_funcionario`),
                  CONSTRAINT `fk_cd_perfil_funcionario` FOREIGN KEY (`cd_perfil_funcionario`) REFERENCES `perfil` (`cd_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela menu
    * @return Retorna o boleano da operação
    */
    private function menu(){
        
        $sql = "
                CREATE TABLE `menu` (
                  `cd_menu` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `nome_menu` varchar(255) DEFAULT NULL,
                  `pai_menu` tinyint(11) DEFAULT NULL,
                  `link_menu` varchar(255) DEFAULT NULL,
                  `ordem_menu` tinyint(4) DEFAULT NULL,
                  `cd_permissao` int(11) unsigned NOT NULL,
                  `status_menu` enum('A','I') DEFAULT NULL,
                  PRIMARY KEY (`cd_menu`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela nacionalidade
    * @return Retorna o boleano da operação
    */
    private function nacionalidade(){
        
        $sql = "
                CREATE TABLE `nacionalidade` (
                  `cd_nacionalidade` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `nome_nacionalidade` varchar(50) CHARACTER SET utf8 NOT NULL,
                  `sigla_nacionalidade` char(3) CHARACTER SET utf8 DEFAULT NULL,
                  PRIMARY KEY (`cd_nacionalidade`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela paciente
    * @return Retorna o boleano da operação
    */
    private function paciente(){
        
        $sql = "
                CREATE TABLE `paciente` (
                  `cd_paciente` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `matricula_paciente` int(5) unsigned NOT NULL,
                  `nome_paciente` varchar(255) CHARACTER SET utf8 NOT NULL,
                  `rg_paciente` char(15) CHARACTER SET utf8 DEFAULT NULL,
                  `sigla_sexo_paciente` enum('M','F') CHARACTER SET utf8 NOT NULL,
                  `nacionalidade_paciente` enum('BRA','EST','NAC') CHARACTER SET utf8 DEFAULT NULL,
                  `estado_paciente` char(2) CHARACTER SET utf8 NOT NULL,
                  `endereco_paciente` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
                  `numero_paciente` char(15) CHARACTER SET utf8 DEFAULT NULL,
                  `bairro_paciente` char(100) CHARACTER SET utf8 DEFAULT NULL,
                  `cep_paciente` char(9) CHARACTER SET utf8 DEFAULT NULL,
                  `tel_fix_paciente` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
                  `tel_cel_paciente` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
                  `email_paciente` varchar(200) DEFAULT NULL,
                  `data_cadastro_paciente` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `novo_paciente` enum('SIM','NAO') NOT NULL,
                  PRIMARY KEY (`cd_paciente`),
                  UNIQUE KEY `matricula_paciente` (`matricula_paciente`),
                  KEY `idx_cd_paciente` (`cd_paciente`),
                  KEY `idx_login_paciente` (`matricula_paciente`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela plano
    * @return Retorna o boleano da operação
    */
    private function plano(){
        
        $sql = "
                CREATE TABLE `plano` (
                  `cd_plano` int(11) NOT NULL AUTO_INCREMENT,
                  `nome_plano` varchar(50) NOT NULL,
                  `sigla_plano` char(3) NOT NULL,
                  PRIMARY KEY (`cd_plano`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela sexo
    * @return Retorna o boleano da operação
    */
    private function sexo(){
        
        $sql = "
                CREATE TABLE `sexo` (
                  `cd_sexo` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `nome_sexo` varchar(15) CHARACTER SET utf8 NOT NULL,
                  `sigla_sexo` char(1) CHARACTER SET utf8 NOT NULL,
                  PRIMARY KEY (`cd_sexo`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela situacao_agenda
    * @return Retorna o boleano da operação
    */
    private function situacao_agenda(){
        
        $sql = "CREATE TABLE `situacao_agenda` (
                  `cd_situacao_agenda` int(11) NOT NULL AUTO_INCREMENT,
                  `sigla_situacao_agenda` char(3) NOT NULL,
                  `nome_situacao_agenda` varchar(30) NOT NULL,
                  `situacao_agenda_situacao_agenda` tinyint(4) NOT NULL,
                  PRIMARY KEY (`cd_situacao_agenda`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela especialidade
    * @return Retorna o boleano da operação
    */
    private function especialidade(){
        
        $sql = "
                CREATE TABLE `especialidade` (
                  `cd_especialidade` int(11) NOT NULL AUTO_INCREMENT,
                  `nome_especialidade` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`cd_especialidade`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela cliente
    * @return Retorna o boleano da operação
    */
    private function cliente(){
        
        $sql = "
                CREATE TABLE `cliente` (
                  `cd_cliente` int(11) NOT NULL AUTO_INCREMENT,
                  `nome_cliente` varchar(255) DEFAULT NULL,
                  `endereco_cliente` varchar(255) DEFAULT NULL,
                  `numero_endereco_cliente` varchar(255) DEFAULT NULL,
                  `bairro_cliente` varchar(255) DEFAULT NULL,
                  `cidade_cliente` varchar(255) DEFAULT NULL,
                  `comp_endereco_cliente` varchar(200) DEFAULT NULL,
                  `cep_cliente` char(9) CHARACTER SET utf8 DEFAULT NULL,
                  `cpf_cnpj_cliente` char(19) CHARACTER SET utf8 DEFAULT NULL,
                  PRIMARY KEY (`cd_cliente`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela telefone cliente
    * @return Retorna o boleano da operação
    */
    private function telefone_cliente(){
        
        $sql = "
                CREATE TABLE `telefone_cliente` (
                  `cd_telefone_cliente` int(11) NOT NULL AUTO_INCREMENT,
                  `numero_telefone_cliente` varchar(15) DEFAULT NULL,
                  `cd_cliente` int(11) DEFAULT NULL,
                  PRIMARY KEY (`cd_telefone_cliente`),
                  KEY `fk_cd_cliente` (`cd_cliente`),
                  CONSTRAINT `fk_cd_cliente` FOREIGN KEY (`cd_cliente`) REFERENCES `cliente` (`cd_cliente`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela especialidade_medico
    * @return Retorna o boleano da operação
    */
    private function especialidade_medico(){
        
        $sql = "
                CREATE TABLE `especialidade_medico` (
                `cd_especialidade_medico` int(11) NOT NULL AUTO_INCREMENT,
                `cd_especialidade` int(11) DEFAULT NULL,
                `matricula_funcionario` char(6) NOT NULL,
                PRIMARY KEY (`cd_especialidade_medico`),
                KEY `fk_matricula_funcionario` (`matricula_funcionario`),
                CONSTRAINT `fk_matricula_funcionario` FOREIGN KEY (`matricula_funcionario`) REFERENCES `funcionario` (`matricula_funcionario`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
            
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela receita_medica
    * @return Retorna o boleano da operação
    */
    private function receita_medica(){
        
        $sql = "
                CREATE TABLE `receita_medica` (
                  `cd_receita_medica` bigint(11) NOT NULL AUTO_INCREMENT,
                  `conteudo_receita_medica` text,
                  `cd_agenda_receita_medica` int(11) NOT NULL,
                  PRIMARY KEY (`cd_receita_medica`),
                  KEY `fk_cd_agenda_receita_medica` (`cd_agenda_receita_medica`),
                  CONSTRAINT `fk_cd_agenda_receita_medica` FOREIGN KEY (`cd_agenda_receita_medica`) REFERENCES `agenda` (`cd_agenda`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
            
        return $this->db->query($sql);    
        
    }
    
    /**
    * Função que cria a tabela sms
    * @return Retorna o boleano da operação
    */
    private function sms(){
        
        $sql = "
                CREATE TABLE `sms` (
                  `cd_sms` int(11) NOT NULL AUTO_INCREMENT,
                  `original_qtd_sms` int(11) DEFAULT NULL,
                  `qtd_sms` int(11) DEFAULT NULL,
                  `data_sms` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  `status_sms` enum('I','A') DEFAULT 'A',
                  PRIMARY KEY (`cd_sms`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela sms_agenda
    * @return Retorna o boleano da operação
    */
    private function sms_agenda(){
        
        $sql = "
        CREATE TABLE `sms_agenda` (
          `cd_sms_agenda` int(11) NOT NULL AUTO_INCREMENT,
          `cd_agenda` int(11) NOT NULL,
          `cd_sms` int(11) NOT NULL,
          `chave_sms` int(11) DEFAULT NULL,
          `celular` int(11) DEFAULT NULL,
          `saldo_sms` int(11) DEFAULT NULL,
          `status_envio` varchar(10) DEFAULT NULL,
          `msg` varchar(255) DEFAULT NULL,
          `resposta` varchar(255) DEFAULT NULL,
          `data_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`cd_sms_agenda`),
          KEY `idx_cd_sms` (`cd_sms`) USING BTREE,
          KEY `idx_cd_agenda` (`cd_agenda`) USING BTREE,
          KEY `idx_chave_sms` (`chave_sms`) USING BTREE,
          CONSTRAINT `fk_cd_agenda` FOREIGN KEY (`cd_agenda`) REFERENCES `agenda` (`cd_agenda`) ON DELETE NO ACTION,
          CONSTRAINT `fk_cd_sms` FOREIGN KEY (`cd_sms`) REFERENCES `sms` (`cd_sms`) ON DELETE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela perfil
    * @return Retorna o boleano da operação
    */
    private function perfil(){
        
        $sql = "CREATE TABLE `perfil` (
                  `cd_perfil` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `nome_perfil` varchar(255) DEFAULT NULL,
                  `status_perfil` enum('I','A') DEFAULT 'A',
                  PRIMARY KEY (`cd_perfil`),
                  KEY `idx_cd_perfil` (`cd_perfil`) USING BTREE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela permissao
    * @return Retorna o boleano da operação
    */
    private function permissao(){
        
        $sql = "CREATE TABLE `permissao` (
                  `cd_permissao` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `nome_permissao` varchar(255) DEFAULT NULL,
                  `descricao_permissao` varchar(255) DEFAULT NULL,
                  `pai_permissao` int(11) DEFAULT NULL,
                  `ordem_permissao` int(11) DEFAULT NULL,
                  `status_permissao` enum('I','A') DEFAULT 'A',
                  PRIMARY KEY (`cd_permissao`),
                  KEY `idx_cd_permissao` (`cd_permissao`) USING BTREE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que cria a tabela permissao_perfil
    * @return Retorna o boleano da operação
    */
    private function permissao_perfil(){
        
        $sql = "CREATE TABLE `permissao_perfil` (
                  `cd_permissao_perfil` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
                  `cd_perfil` int(11) unsigned NOT NULL,
                  `cd_permissao` int(11) unsigned NOT NULL,
                  PRIMARY KEY (`cd_permissao_perfil`),
                  KEY `fk_cd_perfil` (`cd_perfil`),
                  KEY `fk_cd_permissao_cd_permissao_perfil` (`cd_permissao`),
                  CONSTRAINT `fk_cd_perfil` FOREIGN KEY (`cd_perfil`) REFERENCES `perfil` (`cd_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
                  CONSTRAINT `fk_cd_permissao_cd_permissao_perfil` FOREIGN KEY (`cd_permissao`) REFERENCES `permissao` (`cd_permissao`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                
        return $this->db->query($sql);
        
    }
    
    /**
    * Função que inseri os dados da tabela Estado
    * @return Retorna o boleano da operação
    */
    private function dadosEstado(){
        
        $sql[] = "INSERT INTO `estado` VALUES ('1', 'ACRE', 'AC');";
        $sql[] = "INSERT INTO `estado` VALUES ('2', 'ALAGOAS', 'AL');";
        $sql[] = "INSERT INTO `estado` VALUES ('3', 'AMAPÁ', 'AP');";
        $sql[] = "INSERT INTO `estado` VALUES ('4', 'AMAZONAS', 'AM');";
        $sql[] = "INSERT INTO `estado` VALUES ('5', 'BAHIA', 'BA');";
        $sql[] = "INSERT INTO `estado` VALUES ('6', 'CEARÁ', 'CE');";
        $sql[] = "INSERT INTO `estado` VALUES ('7', 'DISTRITO FEDERAL', 'DF');";
        $sql[] = "INSERT INTO `estado` VALUES ('8', 'ESPÍRITO SANTO', 'ES');";
        $sql[] = "INSERT INTO `estado` VALUES ('9', 'GOIÁS', 'GO');";
        $sql[] = "INSERT INTO `estado` VALUES ('10', 'MARANHÃO', 'MA');";
        $sql[] = "INSERT INTO `estado` VALUES ('11', 'MATO GROSSO', 'MT');";
        $sql[] = "INSERT INTO `estado` VALUES ('12', 'MATO GROSSO DO SUL', 'MS');";
        $sql[] = "INSERT INTO `estado` VALUES ('13', 'MINAS GERAIS', 'MG');";
        $sql[] = "INSERT INTO `estado` VALUES ('14', 'PARÁ', 'PR');";
        $sql[] = "INSERT INTO `estado` VALUES ('15', 'PARAÍBA', 'PB');";
        $sql[] = "INSERT INTO `estado` VALUES ('16', 'PARANÁ', 'PA');";
        $sql[] = "INSERT INTO `estado` VALUES ('17', 'PERNAMBUCO', 'PE');";
        $sql[] = "INSERT INTO `estado` VALUES ('18', 'PIAUÍ', 'PI');";
        $sql[] = "INSERT INTO `estado` VALUES ('19', 'RIO DE JANEIRO', 'RJ');";
        $sql[] = "INSERT INTO `estado` VALUES ('20', 'RIO GRANDE DO NORTE', 'RN');";
        $sql[] = "INSERT INTO `estado` VALUES ('21', 'RIO GRANDE DO SUL', 'RS');";
        $sql[] = "INSERT INTO `estado` VALUES ('22', 'RONDÔNIA', 'RO');";
        $sql[] = "INSERT INTO `estado` VALUES ('23', 'RORAIMA', 'RR');";
        $sql[] = "INSERT INTO `estado` VALUES ('24', 'SANTA CATARINA', 'SC');";
        $sql[] = "INSERT INTO `estado` VALUES ('25', 'SÃO PAULO', 'SP');";
        $sql[] = "INSERT INTO `estado` VALUES ('26', 'SERGIPE', 'SE');";
        $sql[] = "INSERT INTO `estado` VALUES ('27', 'TOCANTINS', 'TO');";
        
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Menu
    * @return Retorna o boleano da operação
    */
    private function dadosMenu(){
        
        #$sql[] = "INSERT INTO `menu` VALUES ('1', 'Início', '0', null, '1', NULL, 'I');";
        $sql[] = "INSERT INTO `menu` VALUES ('2', 'Agenda', '0', null, '2', 1, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('3', 'Paciente', '0', 'paciente', '3', 4, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('4', 'Funcionário', '0', 'funcionario', '4', 5, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('5', 'Sair', '0', 'home/logout', '6', 7, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('6', 'Médico', '2', 'agenda/medico', '2', 9, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('7', 'Secretária', '2', 'agenda/secretaria', '2', 8, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('8', 'Dashboard', '0', 'dashboard', '5', 6, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('10', 'SMS', '0', null, '2', 2, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('11', 'Enviar confirmar consulta', '10', 'sms/enviarConfirmacao', '1', 13, 'A');";
        $sql[] = "INSERT INTO `menu` VALUES ('12', 'Relatórios', '0', 'relatorio', '2', 3, 'A');";
        
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Nacionalidade
    * @return Retorna o boleano da operação
    */
    private function dadosNacionalidade(){
        
        $sql[] = "INSERT INTO `nacionalidade` VALUES ('1', 'BRASILEIRO', 'BRA');";
        $sql[] = "INSERT INTO `nacionalidade` VALUES ('2', 'ESTRANGEIRO', 'EST');";
        $sql[] = "INSERT INTO `nacionalidade` VALUES ('3', 'NATURALIZADO', 'NAT');";
        
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Plano
    * @return Retorna o boleano da operação
    */
    private function dadosPlano(){
        
        $sql[] = "INSERT INTO `plano` VALUES ('1', 'PARTICULAR', 'PAR');";
        $sql[] = "INSERT INTO `plano` VALUES ('2', 'CONVÊNIO', 'CON');";
        
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Sexo
    * @return Retorna o boleano da operação
    */
    private function dadosSexo(){
        
        $sql[] = "INSERT INTO `sexo` VALUES ('1', 'MASCULINO', 'M');";
        $sql[] = "INSERT INTO `sexo` VALUES ('2', 'FEMININO', 'F');";
         
        foreach($sql as $sq){
            $this->db->query($sq);
        }       
        
    }
    
    /**
    * Função que inseri os dados da tabela Agenda
    * @return Retorna o boleano da operação
    */
    private function dadosSituacaoAgenda(){
        
        $sql[] = "INSERT INTO `situacao_agenda` VALUES ('1', 'MAR', 'MARCADO', '1');";
        $sql[] = "INSERT INTO `situacao_agenda` VALUES ('2', 'DES', 'DESISTIU', '1');";
        $sql[] = "INSERT INTO `situacao_agenda` VALUES ('4', 'PRE', 'PRESENTE', '1');";
        $sql[] = "INSERT INTO `situacao_agenda` VALUES ('5', 'CHA', 'CHAMADO', '1');";
        $sql[] = "INSERT INTO `situacao_agenda` VALUES ('6', 'CON', 'CONSULTANDO', '1');";
        $sql[] = "INSERT INTO `situacao_agenda` VALUES ('7', 'FIN', 'FINALIZADO', '1');";
                
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Status
    * @return Retorna o boleano da operação
    */
    private function dadosStatus(){
        
        $sql[] = "INSERT INTO `status` VALUES ('1', 'Ativo', 'A');";
        $sql[] = "INSERT INTO `status` VALUES ('2', 'Inativo', 'I');";
                
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Permissão
    * @return Retorna o boleano da operação
    */
    private function dadosPermissao(){
        
        $sql[] = "INSERT INTO `permissao` VALUES ('1', 'MENU - Agenda', null, '0', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('2', 'MENU - SMS', null, '0', 2, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('3', 'MENU - Relatórios', null, '0', 3, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('4', 'MENU - Paciente', null, '0', 4, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('5', 'MENU - Funcionário', null, '0', 5, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('6', 'MENU - Dashboard', null, '0', 6, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('7', 'MENU - Sair', null, '0', 7, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('8', 'PÁGINA - Secretária', null, '1', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('9', 'PÁGINA - Médico', null, '1', 2, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('22', 'FUNÇÃO - Marcar consulta', null, '8', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('23', 'FUNÇÃO - Editar situação consulta', null, '8', 2, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('24', 'FUNÇÃO - Encaminhar consulta', null, '8', 3, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('25', 'FUNÇÃO - Completar ficha paciente', null, '8', 4, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('30', 'FUNÇÃO - Emitir receita', null, '8', 5, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('26', 'FUNÇÃO - Editar situação consulta', null, '9', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('27', 'FUNÇÃO - Chama paciente', null, '9', 2, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('28', 'FUNÇÃO - Atender paciente', null, '9', 3, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('33', 'PÁGINA - Enviar confirmação consulta', null, '2', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('20', 'FUNÇÃO - Cadastrar / Editar paciente', null, '4', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('21', 'FUNÇÃO - Excluir paciente', null, '4', 2, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('18', 'FUNÇÃO - Cadastrar / Editar funcionário', null, '5', 1, 'A');";
        $sql[] = "INSERT INTO `permissao` VALUES ('19', 'FUNÇÃO - Excluir funcionário', null, '5', 2, 'A');";
        
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    /**
    * Função que inseri os dados da tabela Medico
    * @return Retorna o boleano da operação
    */
    private function dadosTipoMedico(){
        
        $sql[] = "INSERT INTO `especialidade` VALUES ('1', 'Acupuntura');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('2', 'Alergia e Imunologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('3', 'Anestesiologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('4', 'Angiologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('5', 'Cancerologia (oncologia)');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('6', 'Cardiologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('7', 'Cirurgia Cardiovascular');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('8', 'Cirurgia da Mão');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('9', 'Cirurgia de cabeça e pescoço');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('10', 'Cirurgia do Aparelho Digestivo');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('11', 'Cirurgia Geral');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('12', 'Cirurgia Pediátrica');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('13', 'Cirurgia Plástica');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('14', 'Cirurgia Torácica');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('15', 'Cirurgia Vascular');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('16', 'Clínica Médica (Medicina interna) ');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('17', 'Coloproctologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('18', 'Dermatologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('19', 'Endocrinologia e Metabologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('20', 'Endoscopia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('21', 'Gastroenterologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('22', 'Genética médica');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('23', 'Geriatria');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('24', 'Ginecologia e obstetrícia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('25', 'Hematologia e Hemoterapia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('26', 'Homeopatia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('27', 'Infectologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('28', 'Mastologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('29', 'Medicina de Família e Comunidade');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('30', 'Medicina do Trabalho');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('31', 'Medicina do Tráfego');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('32', 'Medicina Esportiva');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('33', 'Medicina Física e Reabilitação');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('34', 'Medicina Intensiva');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('35', 'Medicina Legal e Perícia Médica (ou medicina forense)');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('36', 'Medicina Nuclear');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('37', 'Medicina Preventiva e Social');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('38', 'Nefrologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('39', 'Neurocirurgia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('40', 'Neurologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('41', 'Nutrologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('42', 'Oftalmologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('43', 'Ortopedia e Traumatologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('44', 'Otorrinolaringologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('45', 'Patologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('46', 'Patologia Clínica/Medicina laboratorial');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('47', 'Pediatria');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('48', 'Pneumologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('49', 'Psiquiatria');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('50', 'Radiologia e Diagnóstico por Imagem');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('51', 'Radioterapia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('52', 'Reumatologia');";
        $sql[] = "INSERT INTO `especialidade` VALUES ('53', 'Urologia');";
                
        foreach($sql as $sq){
            $this->db->query($sq);
        }
        
    }
    
    private function dadosAdministrador(){
        
        $sql = "INSERT INTO funcionario (
                	matricula_funcionario, 
                	nome_funcionario, 
                	sigla_sexo_funcionario,
                	sigla_status_funcionario,
                    data_nascimento_funcionario,
                    cd_perfil_funcionario,
                	senha_funcionario)
                VALUES ('ADMIN', 'ADMINISTRADOR', 'M', 'A', '".date('Y-m-d')."', 1, '".md5(SENHA_MASTER)."');";
        $this->db->query($sql);
        
    }
    
    /**
    * Função que inseri os dados da tabela perfil
    * @return Retorna o boleano da operação
    */
    private function dadosPerfil(){
        
        $sql[] = "INSERT INTO `perfil` VALUES ('1', 'Médico', 'A');";
        $sql[] = "INSERT INTO `perfil` VALUES ('2', 'Secretária', 'A');";
         
        foreach($sql as $sq){
            $this->db->query($sq);
        }       
        
    }
    
    /**
    * Função que inseri os dados da tabela permissão perfil
    * @return Retorna o boleano da operação
    */
    private function dadosPermissaoPerfil(){
        
        # Perfil Médico
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 1);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 2);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 3);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 4);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 5);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 6);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 7);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 8);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 9);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 20);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 21);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 22);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 23);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 24);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 25);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 26);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 27);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 28);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 30);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 33);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 18);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 1, 19);";
        
        # Perfil Secretária
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 1);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 2);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 3);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 4);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 5);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 6);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 7);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 8);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 22);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 23);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 24);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 33);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 25);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 20);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 21);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 18);";
        $sql[] = "INSERT INTO `permissao_perfil` VALUES (null, 2, 19);";
         
        foreach($sql as $sq){
            $this->db->query($sq);
        }       
        
    }
    
    private function dadosCliente(){
        
        $campo = array();
		$valor = array();
		foreach($_POST as $c => $v){
			
            if($c <> 'telefone_cliente' and $c <> 'tipo_documento_cliente'){
            
    			$valorFormatado = $this->util->removeAcentos($this->input->post($c));
    			$valorFormatado = strtoupper($this->util->formaValorBanco($valorFormatado));
    			
    			$campo[] = $c;
    			$valor[] = $valorFormatado;
            
            }
            
		}
        
		$campos = implode(', ', $campo);
		$valores = implode(', ', $valor);
		
		$sql = "INSERT INTO cliente (".$campos.")\n VALUES(".$valores.");";
		$this->db->query($sql);
        
        $id = $this->db->insert_id();
        
        foreach($_POST['telefone_cliente'] AS $tel){
            $sql = "INSERT INTO telefone_cliente(numero_telefone_cliente, cd_cliente) VALUES('".$tel."', ".$id.");";
            $this->db->query($sql);
        }
        
		#return $this->db->affected_rows(); # RETORNA O NÚMERO DE LINHAS AFETADAS
        
    }

}