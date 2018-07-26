<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as execuções de processos dos pacientes
*/
class Setup extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		#$this->load->helper('url');
		$this->load->helper('form');
		#$this->load->library('table');
        #$this->load->library('Util', '', 'util');
		#$this->load->model('DadosBanco_model','dadosBanco');
		#$this->load->model('paciente_model','paciente');
        $this->load->model('setup_model','setup');
	}
	
    /**
    * Função monta a tela pesquisa do paciente
    */
	public function index(){	
		
        #$resultado = $this->setup->executaInstalacao();
        #print_r($resultado);
		#echo 'aqui';
        
	}
    
    public function iniciar(){
        
        //Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'funcionario, médico, secretaria'	
		);
	
		
		#$this->load->view('calendario',$data);
        
        /*
        Responsável por redirecionar o processo para a agenda novamente
        Esse redirecionamento é executado quando a ficha do funcionario esta incompleta
        */

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('corpo', 'view_setup');
		#$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	#$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
    }
    
    public function processaInstacao(){
        
        array_pop($_POST); // Remove o último elemento do array $_POST (CAMPO submit)
        
        #echo '<pre>';
        #print_r($_POST);
        
        
        $resultado = $this->setup->executaInstalacao();
        
        if($resultado){
            redirect(base_url('home'));
        }else{
            redirect(base_url('setup/iniciar'));
        }
        
    }
	
}