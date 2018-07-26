/*
Navicat MySQL Data Transfer

Source Server         : Local
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : consultorio

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2017-08-15 10:25:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `agenda`
-- ----------------------------
DROP TABLE IF EXISTS `agenda`;
CREATE TABLE `agenda` (
`cd_agenda`  int(11) NOT NULL AUTO_INCREMENT ,
`data_agenda`  date NULL DEFAULT NULL ,
`horario_agenda`  time NULL DEFAULT NULL ,
`sigla_situacao_agenda`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`matricula_paciente_agenda`  int(5) UNSIGNED NOT NULL ,
`sigla_plano_agenda`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`matricula_medico_agenda`  char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`anotacoes_medico_agenda`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`hora_presenca_paciente_agenda`  time NULL DEFAULT NULL ,
`hora_inicio_atendimento_agenda`  time NULL DEFAULT NULL ,
`hora_final_atendimento_agenda`  time NULL DEFAULT NULL ,
`cd_especialidade_agenda`  int(11) NULL DEFAULT NULL ,
`envio_sms_agenda`  enum('N','S') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'N' ,
PRIMARY KEY (`cd_agenda`),
FOREIGN KEY (`matricula_medico_agenda`) REFERENCES `funcionario` (`matricula_funcionario`) ON DELETE CASCADE ON UPDATE RESTRICT,
FOREIGN KEY (`matricula_paciente_agenda`) REFERENCES `paciente` (`matricula_paciente`) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of agenda
-- ----------------------------
BEGIN;
INSERT INTO `agenda` VALUES ('1', '2014-12-06', '07:45:00', 'MAR', '14001', 'PAR', 'F14002', null, null, null, null, '2', 'N');
COMMIT;

-- ----------------------------
-- Table structure for `cliente`
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
`cd_cliente`  int(11) NOT NULL AUTO_INCREMENT ,
`nome_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`endereco_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`numero_endereco_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`bairro_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cidade_cliente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`comp_endereco_cliente`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cep_cliente`  char(9) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cpf_cnpj_cliente`  char(19) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_cliente`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of cliente
-- ----------------------------
BEGIN;
INSERT INTO `cliente` VALUES ('1', 'CONSULTORIO TESTE', 'AVENIDA NOSSA SENHORA DE COPACABANA', '784', 'COPACABANA', 'RIO DE JANEIRO', null, '22050-002', '32.344.324/3242-43');
COMMIT;

-- ----------------------------
-- Table structure for `especialidade`
-- ----------------------------
DROP TABLE IF EXISTS `especialidade`;
CREATE TABLE `especialidade` (
`cd_especialidade`  int(11) NOT NULL AUTO_INCREMENT ,
`nome_especialidade`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_especialidade`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=54

;

-- ----------------------------
-- Records of especialidade
-- ----------------------------
BEGIN;
INSERT INTO `especialidade` VALUES ('1', 'Acupuntura'), ('2', 'Alergia e Imunologia'), ('3', 'Anestesiologia'), ('4', 'Angiologia'), ('5', 'Cancerologia (oncologia)'), ('6', 'Cardiologia'), ('7', 'Cirurgia Cardiovascular'), ('8', 'Cirurgia da Mão'), ('9', 'Cirurgia de cabeça e pescoço'), ('10', 'Cirurgia do Aparelho Digestivo'), ('11', 'Cirurgia Geral'), ('12', 'Cirurgia Pediátrica'), ('13', 'Cirurgia Plástica'), ('14', 'Cirurgia Torácica'), ('15', 'Cirurgia Vascular'), ('16', 'Clínica Médica (Medicina interna) '), ('17', 'Coloproctologia'), ('18', 'Dermatologia'), ('19', 'Endocrinologia e Metabologia'), ('20', 'Endoscopia'), ('21', 'Gastroenterologia'), ('22', 'Genética médica'), ('23', 'Geriatria'), ('24', 'Ginecologia e obstetrícia'), ('25', 'Hematologia e Hemoterapia'), ('26', 'Homeopatia'), ('27', 'Infectologia'), ('28', 'Mastologia'), ('29', 'Medicina de Família e Comunidade'), ('30', 'Medicina do Trabalho'), ('31', 'Medicina do Tráfego'), ('32', 'Medicina Esportiva'), ('33', 'Medicina Física e Reabilitação'), ('34', 'Medicina Intensiva'), ('35', 'Medicina Legal e Perícia Médica (ou medicina forense)'), ('36', 'Medicina Nuclear'), ('37', 'Medicina Preventiva e Social'), ('38', 'Nefrologia'), ('39', 'Neurocirurgia'), ('40', 'Neurologia'), ('41', 'Nutrologia'), ('42', 'Oftalmologia'), ('43', 'Ortopedia e Traumatologia'), ('44', 'Otorrinolaringologia'), ('45', 'Patologia'), ('46', 'Patologia Clínica/Medicina laboratorial'), ('47', 'Pediatria'), ('48', 'Pneumologia'), ('49', 'Psiquiatria'), ('50', 'Radiologia e Diagnóstico por Imagem'), ('51', 'Radioterapia'), ('52', 'Reumatologia'), ('53', 'Urologia');
COMMIT;

-- ----------------------------
-- Table structure for `especialidade_medico`
-- ----------------------------
DROP TABLE IF EXISTS `especialidade_medico`;
CREATE TABLE `especialidade_medico` (
`cd_especialidade_medico`  int(11) NOT NULL AUTO_INCREMENT ,
`cd_especialidade`  int(11) NULL DEFAULT NULL ,
`matricula_funcionario`  char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`cd_especialidade_medico`),
FOREIGN KEY (`matricula_funcionario`) REFERENCES `funcionario` (`matricula_funcionario`) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of especialidade_medico
-- ----------------------------
BEGIN;
INSERT INTO `especialidade_medico` VALUES ('1', '2', 'F14002'), ('2', '18', 'F14002');
COMMIT;

-- ----------------------------
-- Table structure for `estado`
-- ----------------------------
DROP TABLE IF EXISTS `estado`;
CREATE TABLE `estado` (
`cd_estado`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_estado`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sigla_estado`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_estado`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=28

;

-- ----------------------------
-- Records of estado
-- ----------------------------
BEGIN;
INSERT INTO `estado` VALUES ('1', 'ACRE', 'AC'), ('2', 'ALAGOAS', 'AL'), ('3', 'AMAPÁ', 'AP'), ('4', 'AMAZONAS', 'AM'), ('5', 'BAHIA', 'BA'), ('6', 'CEARÁ', 'CE'), ('7', 'DISTRITO FEDERAL', 'DF'), ('8', 'ESPÍRITO SANTO', 'ES'), ('9', 'GOIÁS', 'GO'), ('10', 'MARANHÃO', 'MA'), ('11', 'MATO GROSSO', 'MT'), ('12', 'MATO GROSSO DO SUL', 'MS'), ('13', 'MINAS GERAIS', 'MG'), ('14', 'PARÁ', 'PR'), ('15', 'PARAÍBA', 'PB'), ('16', 'PARANÁ', 'PA'), ('17', 'PERNAMBUCO', 'PE'), ('18', 'PIAUÍ', 'PI'), ('19', 'RIO DE JANEIRO', 'RJ'), ('20', 'RIO GRANDE DO NORTE', 'RN'), ('21', 'RIO GRANDE DO SUL', 'RS'), ('22', 'RONDÔNIA', 'RO'), ('23', 'RORAIMA', 'RR'), ('24', 'SANTA CATARINA', 'SC'), ('25', 'SÃO PAULO', 'SP'), ('26', 'SERGIPE', 'SE'), ('27', 'TOCANTINS', 'TO');
COMMIT;

-- ----------------------------
-- Table structure for `funcionario`
-- ----------------------------
DROP TABLE IF EXISTS `funcionario`;
CREATE TABLE `funcionario` (
`cd_funcionario`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`matricula_funcionario`  char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`nome_funcionario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sigla_sexo_funcionario`  enum('M','F') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`cd_perfil_funcionario`  int(11) UNSIGNED NULL DEFAULT NULL ,
`sigla_status_funcionario`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tel_fix_funcionario`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tel_cel_funcionario`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cpf_funcionario`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`email_funcionario`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`senha_funcionario`  char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`endereco_funcionario`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`numero_funcionario`  char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`bairro_funcionario`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`estado_funcionario`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cep_funcionario`  char(9) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`medico_funcionario`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'NAO' ,
`data_cadastro_funcionario`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`data_nascimento_funcionario`  date NOT NULL ,
PRIMARY KEY (`cd_funcionario`),
FOREIGN KEY (`cd_perfil_funcionario`) REFERENCES `perfil` (`cd_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of funcionario
-- ----------------------------
BEGIN;
INSERT INTO `funcionario` VALUES ('1', 'ADMIN', 'ADMINISTRADOR', 'M', '1', 'A', null, null, '', null, 'f0daa66be635bf3de30289d5f6e10864', null, null, null, null, '', 'NAO', '2014-12-06 19:00:04', '2014-12-06'), ('2', 'F14002', 'MEDICO TESTE', 'M', '1', 'A', null, null, '234.324.234-23', 'TESTE@TESTE.COM.BR', 'f0daa66be635bf3de30289d5f6e10864', 'RUA CONQUISTA', '432', 'CAJU', 'RJ', '20931-785', 'SIM', '2014-12-06 19:02:16', '1989-04-29');
COMMIT;

-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
`cd_menu`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_menu`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pai_menu`  tinyint(11) NULL DEFAULT NULL ,
`link_menu`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ordem_menu`  tinyint(4) NULL DEFAULT NULL ,
`cd_permissao`  int(11) UNSIGNED NOT NULL ,
`status_menu`  enum('A','I') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_menu`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=13

;

-- ----------------------------
-- Records of menu
-- ----------------------------
BEGIN;
INSERT INTO `menu` VALUES ('2', 'Agenda', '0', null, '2', '1', 'A'), ('3', 'Paciente', '0', 'paciente', '3', '4', 'A'), ('4', 'Funcionário', '0', 'funcionario', '4', '5', 'A'), ('5', 'Sair', '0', 'home/logout', '6', '7', 'A'), ('6', 'Médico', '2', 'agenda/medico', '2', '9', 'A'), ('7', 'Secretária', '2', 'agenda/secretaria', '2', '8', 'A'), ('8', 'Dashboard', '0', 'dashboard', '5', '6', 'A'), ('10', 'SMS', '0', null, '2', '2', 'A'), ('11', 'Enviar confirmar consulta', '10', 'sms/enviarConfirmacao', '1', '13', 'A'), ('12', 'Relatórios', '0', 'relatorio', '2', '3', 'A');
COMMIT;

-- ----------------------------
-- Table structure for `nacionalidade`
-- ----------------------------
DROP TABLE IF EXISTS `nacionalidade`;
CREATE TABLE `nacionalidade` (
`cd_nacionalidade`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_nacionalidade`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sigla_nacionalidade`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_nacionalidade`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of nacionalidade
-- ----------------------------
BEGIN;
INSERT INTO `nacionalidade` VALUES ('1', 'BRASILEIRO', 'BRA'), ('2', 'ESTRANGEIRO', 'EST'), ('3', 'NATURALIZADO', 'NAT');
COMMIT;

-- ----------------------------
-- Table structure for `paciente`
-- ----------------------------
DROP TABLE IF EXISTS `paciente`;
CREATE TABLE `paciente` (
`cd_paciente`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`matricula_paciente`  int(5) UNSIGNED NOT NULL ,
`nome_paciente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`rg_paciente`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sigla_sexo_paciente`  enum('M','F') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`nacionalidade_paciente`  enum('BRA','EST','NAC') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`estado_paciente`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`endereco_paciente`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`numero_paciente`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`bairro_paciente`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cep_paciente`  char(9) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tel_fix_paciente`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`tel_cel_paciente`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`email_paciente`  varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_cadastro_paciente`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`novo_paciente`  enum('SIM','NAO') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`cd_paciente`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of paciente
-- ----------------------------
BEGIN;
INSERT INTO `paciente` VALUES ('1', '14001', 'PACIENTE TESTE', null, 'M', null, '', null, null, null, null, '', '', null, '2014-12-06 19:19:00', 'SIM');
COMMIT;

-- ----------------------------
-- Table structure for `perfil`
-- ----------------------------
DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
`cd_perfil`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_perfil`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`status_perfil`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_perfil`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of perfil
-- ----------------------------
BEGIN;
INSERT INTO `perfil` VALUES ('1', 'Médico', 'A'), ('2', 'Secretária', 'A');
COMMIT;

-- ----------------------------
-- Table structure for `permissao`
-- ----------------------------
DROP TABLE IF EXISTS `permissao`;
CREATE TABLE `permissao` (
`cd_permissao`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_permissao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`descricao_permissao`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`pai_permissao`  int(11) NULL DEFAULT NULL ,
`ordem_permissao`  int(11) NULL DEFAULT NULL ,
`status_permissao`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_permissao`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=34

;

-- ----------------------------
-- Records of permissao
-- ----------------------------
BEGIN;
INSERT INTO `permissao` VALUES ('1', 'MENU - Agenda', null, '0', '1', 'A'), ('2', 'MENU - SMS', null, '0', '2', 'A'), ('3', 'MENU - Relatórios', null, '0', '3', 'A'), ('4', 'MENU - Paciente', null, '0', '4', 'A'), ('5', 'MENU - Funcionário', null, '0', '5', 'A'), ('6', 'MENU - Dashboard', null, '0', '6', 'A'), ('7', 'MENU - Sair', null, '0', '7', 'A'), ('8', 'PÁGINA - Secretária', null, '1', '1', 'A'), ('9', 'PÁGINA - Médico', null, '1', '2', 'A'), ('18', 'FUNÇÃO - Cadastrar / Editar funcionário', null, '5', '1', 'A'), ('19', 'FUNÇÃO - Excluir funcionário', null, '5', '2', 'A'), ('20', 'FUNÇÃO - Cadastrar / Editar paciente', null, '4', '1', 'A'), ('21', 'FUNÇÃO - Excluir paciente', null, '4', '2', 'A'), ('22', 'FUNÇÃO - Marcar consulta', null, '8', '1', 'A'), ('23', 'FUNÇÃO - Editar situação consulta', null, '8', '2', 'A'), ('24', 'FUNÇÃO - Encaminhar consulta', null, '8', '3', 'A'), ('25', 'FUNÇÃO - Completar ficha paciente', null, '8', '4', 'A'), ('26', 'FUNÇÃO - Editar situação consulta', null, '9', '1', 'A'), ('27', 'FUNÇÃO - Chama paciente', null, '9', '2', 'A'), ('28', 'FUNÇÃO - Atender paciente', null, '9', '3', 'A'), ('30', 'FUNÇÃO - Emitir receita', null, '8', '5', 'A'), ('33', 'PÁGINA - Enviar confirmação consulta', null, '2', '1', 'A');
COMMIT;

-- ----------------------------
-- Table structure for `permissao_perfil`
-- ----------------------------
DROP TABLE IF EXISTS `permissao_perfil`;
CREATE TABLE `permissao_perfil` (
`cd_permissao_perfil`  bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`cd_perfil`  int(11) UNSIGNED NOT NULL ,
`cd_permissao`  int(11) UNSIGNED NOT NULL ,
PRIMARY KEY (`cd_permissao_perfil`),
FOREIGN KEY (`cd_perfil`) REFERENCES `perfil` (`cd_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`cd_permissao`) REFERENCES `permissao` (`cd_permissao`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=40

;

-- ----------------------------
-- Records of permissao_perfil
-- ----------------------------
BEGIN;
INSERT INTO `permissao_perfil` VALUES ('1', '1', '1'), ('2', '1', '2'), ('3', '1', '3'), ('4', '1', '4'), ('5', '1', '5'), ('6', '1', '6'), ('7', '1', '7'), ('8', '1', '8'), ('9', '1', '9'), ('10', '1', '20'), ('11', '1', '21'), ('12', '1', '22'), ('13', '1', '23'), ('14', '1', '24'), ('15', '1', '25'), ('16', '1', '26'), ('17', '1', '27'), ('18', '1', '28'), ('19', '1', '30'), ('20', '1', '33'), ('21', '1', '18'), ('22', '1', '19'), ('23', '2', '1'), ('24', '2', '2'), ('25', '2', '3'), ('26', '2', '4'), ('27', '2', '5'), ('28', '2', '6'), ('29', '2', '7'), ('30', '2', '8'), ('31', '2', '22'), ('32', '2', '23'), ('33', '2', '24'), ('34', '2', '33'), ('35', '2', '25'), ('36', '2', '20'), ('37', '2', '21'), ('38', '2', '18'), ('39', '2', '19');
COMMIT;

-- ----------------------------
-- Table structure for `plano`
-- ----------------------------
DROP TABLE IF EXISTS `plano`;
CREATE TABLE `plano` (
`cd_plano`  int(11) NOT NULL AUTO_INCREMENT ,
`nome_plano`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sigla_plano`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`cd_plano`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of plano
-- ----------------------------
BEGIN;
INSERT INTO `plano` VALUES ('1', 'PARTICULAR', 'PAR'), ('2', 'CONVÊNIO', 'CON');
COMMIT;

-- ----------------------------
-- Table structure for `receita_medica`
-- ----------------------------
DROP TABLE IF EXISTS `receita_medica`;
CREATE TABLE `receita_medica` (
`cd_receita_medica`  bigint(11) NOT NULL AUTO_INCREMENT ,
`conteudo_receita_medica`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`cd_agenda_receita_medica`  int(11) NOT NULL ,
PRIMARY KEY (`cd_receita_medica`),
FOREIGN KEY (`cd_agenda_receita_medica`) REFERENCES `agenda` (`cd_agenda`) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of receita_medica
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `sexo`
-- ----------------------------
DROP TABLE IF EXISTS `sexo`;
CREATE TABLE `sexo` (
`cd_sexo`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`nome_sexo`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`sigla_sexo`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`cd_sexo`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of sexo
-- ----------------------------
BEGIN;
INSERT INTO `sexo` VALUES ('1', 'MASCULINO', 'M'), ('2', 'FEMININO', 'F');
COMMIT;

-- ----------------------------
-- Table structure for `situacao_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `situacao_agenda`;
CREATE TABLE `situacao_agenda` (
`cd_situacao_agenda`  int(11) NOT NULL AUTO_INCREMENT ,
`sigla_situacao_agenda`  char(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`nome_situacao_agenda`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`situacao_agenda_situacao_agenda`  tinyint(4) NOT NULL ,
PRIMARY KEY (`cd_situacao_agenda`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=8

;

-- ----------------------------
-- Records of situacao_agenda
-- ----------------------------
BEGIN;
INSERT INTO `situacao_agenda` VALUES ('1', 'MAR', 'MARCADO', '1'), ('2', 'DES', 'DESISTIU', '1'), ('4', 'PRE', 'PRESENTE', '1'), ('5', 'CHA', 'CHAMADO', '1'), ('6', 'CON', 'CONSULTANDO', '1'), ('7', 'FIN', 'FINALIZADO', '1');
COMMIT;

-- ----------------------------
-- Table structure for `sms`
-- ----------------------------
DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
`cd_sms`  int(11) NOT NULL AUTO_INCREMENT ,
`original_qtd_sms`  int(11) NULL DEFAULT NULL ,
`qtd_sms`  int(11) NULL DEFAULT NULL ,
`data_sms`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`status_sms`  enum('I','A') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A' ,
PRIMARY KEY (`cd_sms`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of sms
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `sms_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `sms_agenda`;
CREATE TABLE `sms_agenda` (
`cd_sms_agenda`  int(11) NOT NULL AUTO_INCREMENT ,
`cd_agenda`  int(11) NOT NULL ,
`cd_sms`  int(11) NOT NULL ,
`chave_sms`  int(11) NULL DEFAULT NULL ,
`celular`  int(11) NULL DEFAULT NULL ,
`saldo_sms`  int(11) NULL DEFAULT NULL ,
`status_envio`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`msg`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`resposta`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`data_hora`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`cd_sms_agenda`),
FOREIGN KEY (`cd_agenda`) REFERENCES `agenda` (`cd_agenda`) ON DELETE NO ACTION ON UPDATE RESTRICT,
FOREIGN KEY (`cd_sms`) REFERENCES `sms` (`cd_sms`) ON DELETE NO ACTION ON UPDATE RESTRICT
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of sms_agenda
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `status`
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
`cd_status`  int(11) NOT NULL AUTO_INCREMENT ,
`nome_status`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`sigla_status`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`cd_status`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of status
-- ----------------------------
BEGIN;
INSERT INTO `status` VALUES ('1', 'Ativo', 'A'), ('2', 'Inativo', 'I');
COMMIT;

-- ----------------------------
-- Table structure for `telefone_cliente`
-- ----------------------------
DROP TABLE IF EXISTS `telefone_cliente`;
CREATE TABLE `telefone_cliente` (
`cd_telefone_cliente`  int(11) NOT NULL AUTO_INCREMENT ,
`numero_telefone_cliente`  varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`cd_cliente`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`cd_telefone_cliente`),
FOREIGN KEY (`cd_cliente`) REFERENCES `cliente` (`cd_cliente`) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of telefone_cliente
-- ----------------------------
BEGIN;
INSERT INTO `telefone_cliente` VALUES ('1', '(21)43434-3534', '1');
COMMIT;

-- ----------------------------
-- Indexes structure for table agenda
-- ----------------------------
CREATE INDEX `idx_matricula_paciente_agenda` ON `agenda`(`matricula_paciente_agenda`) USING BTREE ;
CREATE INDEX `fk_matricula_medico` ON `agenda`(`matricula_medico_agenda`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `agenda`
-- ----------------------------
ALTER TABLE `agenda` AUTO_INCREMENT=2;

-- ----------------------------
-- Auto increment value for `cliente`
-- ----------------------------
ALTER TABLE `cliente` AUTO_INCREMENT=2;

-- ----------------------------
-- Auto increment value for `especialidade`
-- ----------------------------
ALTER TABLE `especialidade` AUTO_INCREMENT=54;

-- ----------------------------
-- Indexes structure for table especialidade_medico
-- ----------------------------
CREATE INDEX `fk_matricula_funcionario` ON `especialidade_medico`(`matricula_funcionario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `especialidade_medico`
-- ----------------------------
ALTER TABLE `especialidade_medico` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for `estado`
-- ----------------------------
ALTER TABLE `estado` AUTO_INCREMENT=28;

-- ----------------------------
-- Indexes structure for table funcionario
-- ----------------------------
CREATE UNIQUE INDEX `login_funcionario` ON `funcionario`(`matricula_funcionario`) USING BTREE ;
CREATE INDEX `idx_cd_funcionario` ON `funcionario`(`cd_funcionario`) USING BTREE ;
CREATE INDEX `idx_login_funcionario` ON `funcionario`(`matricula_funcionario`) USING BTREE ;
CREATE INDEX `fk_cd_perfil_funcionario` ON `funcionario`(`cd_perfil_funcionario`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `funcionario`
-- ----------------------------
ALTER TABLE `funcionario` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for `menu`
-- ----------------------------
ALTER TABLE `menu` AUTO_INCREMENT=13;

-- ----------------------------
-- Auto increment value for `nacionalidade`
-- ----------------------------
ALTER TABLE `nacionalidade` AUTO_INCREMENT=4;

-- ----------------------------
-- Indexes structure for table paciente
-- ----------------------------
CREATE UNIQUE INDEX `matricula_paciente` ON `paciente`(`matricula_paciente`) USING BTREE ;
CREATE INDEX `idx_cd_paciente` ON `paciente`(`cd_paciente`) USING BTREE ;
CREATE INDEX `idx_login_paciente` ON `paciente`(`matricula_paciente`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `paciente`
-- ----------------------------
ALTER TABLE `paciente` AUTO_INCREMENT=2;

-- ----------------------------
-- Indexes structure for table perfil
-- ----------------------------
CREATE INDEX `idx_cd_perfil` ON `perfil`(`cd_perfil`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `perfil`
-- ----------------------------
ALTER TABLE `perfil` AUTO_INCREMENT=3;

-- ----------------------------
-- Indexes structure for table permissao
-- ----------------------------
CREATE INDEX `idx_cd_permissao` ON `permissao`(`cd_permissao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `permissao`
-- ----------------------------
ALTER TABLE `permissao` AUTO_INCREMENT=34;

-- ----------------------------
-- Indexes structure for table permissao_perfil
-- ----------------------------
CREATE INDEX `fk_cd_perfil` ON `permissao_perfil`(`cd_perfil`) USING BTREE ;
CREATE INDEX `fk_cd_permissao_cd_permissao_perfil` ON `permissao_perfil`(`cd_permissao`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `permissao_perfil`
-- ----------------------------
ALTER TABLE `permissao_perfil` AUTO_INCREMENT=40;

-- ----------------------------
-- Auto increment value for `plano`
-- ----------------------------
ALTER TABLE `plano` AUTO_INCREMENT=3;

-- ----------------------------
-- Indexes structure for table receita_medica
-- ----------------------------
CREATE INDEX `fk_cd_agenda_receita_medica` ON `receita_medica`(`cd_agenda_receita_medica`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `receita_medica`
-- ----------------------------
ALTER TABLE `receita_medica` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `sexo`
-- ----------------------------
ALTER TABLE `sexo` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for `situacao_agenda`
-- ----------------------------
ALTER TABLE `situacao_agenda` AUTO_INCREMENT=8;

-- ----------------------------
-- Auto increment value for `sms`
-- ----------------------------
ALTER TABLE `sms` AUTO_INCREMENT=1;

-- ----------------------------
-- Indexes structure for table sms_agenda
-- ----------------------------
CREATE INDEX `idx_cd_sms` ON `sms_agenda`(`cd_sms`) USING BTREE ;
CREATE INDEX `idx_cd_agenda` ON `sms_agenda`(`cd_agenda`) USING BTREE ;
CREATE INDEX `idx_chave_sms` ON `sms_agenda`(`chave_sms`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `sms_agenda`
-- ----------------------------
ALTER TABLE `sms_agenda` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `status`
-- ----------------------------
ALTER TABLE `status` AUTO_INCREMENT=3;

-- ----------------------------
-- Indexes structure for table telefone_cliente
-- ----------------------------
CREATE INDEX `fk_cd_cliente` ON `telefone_cliente`(`cd_cliente`) USING BTREE ;

-- ----------------------------
-- Auto increment value for `telefone_cliente`
-- ----------------------------
ALTER TABLE `telefone_cliente` AUTO_INCREMENT=2;
