<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as informações de extrações de dados
*/

class Dashboard extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('table');
        $this->load->library('Util', '', 'util');
		$this->load->model('DadosBanco_model','dadosBanco');
        $this->load->model('Dashboard_model','dashboard');
		$this->load->model('funcionario_model','funcionario');
        $this->load->model('agenda_model','agenda');
	}
	
    /**
    * Função monta a tela pesquisa do funcionario
    */
	public function index(){	
		
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
        
        $dadosGrafico['anos'] = $this->dashboard->anosAgenda();
        
        $dataBR = date('d/m/Y');
        $dataUSA = date('Y-m-d');
        
        
        $dataCalendar['dataCalendario'] = $dataUSA;
        $dataCalendar['dataFormatada'] = $dataBR;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(6, $this->session->userdata('permissoes'))){
            $this->layout->region('corpo', 'dashboard/view_dashboard', $dadosGrafico);
        }else{
            $this->layout->region('corpo', 'view_permissao');
        }
        
      	#$this->layout->region('corpo', 'informacao/view_grafico', $dadosGrafico);
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	}

}