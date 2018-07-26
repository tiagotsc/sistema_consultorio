<?php

/**
 * @author TIAGO SILVA COSTA
 * @copyright 2014
 * 
 * CONFIG DO SISTEMA
 * 
 */
error_reporting(0);
define('LOGIN_MASTER', 'admin');
define('SENHA_MASTER', '123'); # SENHA MASTER
define('CHAVE_ENVIO_SMS', 'W9TIKV77ZF86BZVF'); # TOKEN ENVIO SMS
define('INADIMPLENTE', false); # STATUS INADIMPLENTES - SE DEFINIDO COMO "TRUE" LIMITA DIVERSAS FUN��ES DO SISTEMA


# HOR�RIOS DA AGENDA
define('HORA_INICIO', '07:30:00'); # HORA IN�CIO DA AGENDA
define('HORA_FIM', '19:30:00'); # HORA FIM DA AGENDA
define('MINUTO_INTERVALO', 15); # INTERVALO PARA GERA��O DE HORAS (15 MINUTOS)

# CONFIGURA��ES DE BANCO
define('HOST','localhost');
define('DATABASE', 'consultorio');
define('USER', 'root');
define('PASSWORD', '');


/*
CRIA O BANCO DE DADOS CASO ELE N�O EXISTA
CRIA A TABELA DE SESSION CASO N�O EXISTA
*/
function iniciaBanco(){
    
    $mysqli = new mysqli(HOST, USER, PASSWORD);
    
    $bancoExiste = $mysqli->select_db(DATABASE);
    
    if(!$bancoExiste){
    
        $sql = 'CREATE DATABASE '.DATABASE.' CHARACTER SET utf8 COLLATE utf8_general_ci';
        $mysqli->query($sql);
        
        $mysqli->select_db(DATABASE);
        
        $sql = "CREATE TABLE `ci_sessions` (
                              `session_id` varchar(40) NOT NULL DEFAULT '0',
                              `ip_address` varchar(16) NOT NULL DEFAULT '0',
                              `user_agent` varchar(254) NOT NULL,
                              `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
                              `user_data` text,
                              PRIMARY KEY (`session_id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                            
        $mysqli->query($sql);
        
        $mysqli->close();
    
    }

}
?>