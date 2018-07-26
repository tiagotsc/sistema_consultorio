<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as execuções de processos dos pacientes
*/
class Paciente extends CI_Controller {
	
	public function __construct(){
	   
        include_once('config.inc.php');
       
		parent::__construct();
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('table');
        $this->load->library('Util', '', 'util');
		$this->load->model('DadosBanco_model','dadosBanco');
		$this->load->model('paciente_model','paciente');
        $this->load->model('agenda_model','agenda');
	}
	
    /**
    * Função monta a tela pesquisa do paciente
    */
	public function index(){	
		
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);	
	
		$resPaciente['pesquisa'] = 'nao'; # INFORMA SE FOI REALIZADA ALGUMA PESQUISA
		
		#$this->load->view('calendario',$data);
        
        /*
        Responsável por redirecionar o processo para a agenda novamente
        Esse redirecionamento é executado quando a ficha do paciente esta incompleta
        */
       
        if(isset($_SERVER['HTTP_REFERER'])){
            $url = explode('/', $_SERVER['HTTP_REFERER']);
            $direct = (preg_match('/\/agenda\//', $_SERVER['HTTP_REFERER']))? '/'.implode('/',preg_grep('/^[0-9]{2,4}$/', $url)) : '';
        }else{
            $direct = '';
        }
        $paciente['direct'] = $direct;
        
        if(!empty($direct)){
            $dataBR = substr($direct, 1, 10);
            $dataUSA = implode('-',array_reverse(explode('/', $dataBR)));
        }else{
            $dataBR = date('d/m/Y');
            $dataUSA = date('Y-m-d');
        }
        
        $dataCalendar['dataCalendario'] = $dataUSA;
        $dataCalendar['dataFormatada'] = $dataBR;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
      	$this->layout->region('corpo', 'paciente/view_pesquisa', $resPaciente);
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	}
	
    /**
    * Função que abre a ficha do paciente
    * @param $matricula Matrícula do paciente para consulta e montagem dos dados
    * @param $statusOper Informa se a operação de ALTERAÇÃO ou ATUALIZAÇÃO foi realizado e se sim informa seu status
    */
    public function ficha($matricula = null){	
		
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);
		
		$paciente['sexo'] = $this->dadosBanco->sexo();
		$paciente['nacionalidade'] = $this->dadosBanco->nacionalidade();
		$paciente['estado'] = $this->dadosBanco->estado();
		
		if(!empty($matricula)){ # SE DESEJA ALTERAR
		
			$dados = $this->paciente->dadosPaciente($matricula);
			
			#echo $dados['nome_paciente'];
			$campos = array_keys($dados);
			#print_r($dados[0]->nome_paciente); exit();
			foreach($campos as $campo){
				$paciente[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
			
			$paciente['botao'] = 'Alterar';
			
		}else{ # SE DESEJA INSERIR
		
			$campos = $this->paciente->camposPaciente();
			#print_r($campos); exit();
			foreach($campos as $camp){
				$paciente[$camp] = ''; # DEIXAS OS CAMPOS SEU CONTEÚDO
			}
			
			$paciente['botao'] = 'Cadastrar';
			
		}
		
		#$this->load->view('calendario',$data);
        
        /*
        Responsável por redirecionar o processo para a agenda novamente
        Esse redirecionamento é executado quando a ficha do paciente esta incompleta. Quando a secretária completar o cadastro processo
        volta para a agenda
        */
        if(isset($_SERVER['HTTP_REFERER'])){
            $url = explode('/', $_SERVER['HTTP_REFERER']);
            $direct = (preg_match('/\/agenda\//', $_SERVER['HTTP_REFERER']))? '/'.implode('/',preg_grep('/^[0-9]{2,4}$/', $url)) : '';
        }else{
            $direct = '';
        }
        $paciente['direct'] = $direct;
        
        if(!empty($direct)){
            $dataBR = substr($direct, 1, 10);
            $dataUSA = implode('-',array_reverse(explode('/', $dataBR)));
        }else{
            $dataBR = date('d/m/Y');
            $dataUSA = date('Y-m-d');
        }
        
        $dataCalendar['dataCalendario'] = $dataUSA;
        $dataCalendar['dataFormatada'] = $dataBR;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(20, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'paciente/view_ficha', $paciente);
             
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	}
	
    /**
    * Função que define qual processo será executado que pode ser INSERÇÃO ou ATUALIZAÇÃO
    */
	public function salvar($dia = null, $mes = null, $ano = null){
		
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('paciente'));
            exit();
        }
        
		array_pop($_POST); // Remove o último elemento do array $_POST
		
		if($this->input->post('matricula_paciente')){
		  
			$executa = $this->paciente->atualiza();
            
		}else{
			$_POST['matricula_paciente'] = $this->paciente->geraMatricula(); # ATRIBUI A MATRÍCULA GERADA AO POST
			$executa = $this->paciente->insere();
		}
		
		if($executa){
		  
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Os dados foram salvos sucesso!</strong></div>');
          
            if(!empty($dia) and !empty($mes) and !empty($ano)){ # Se a edição é oriunda da agenda
                
                redirect(base_url('agenda/secretaria/'.$dia.'/'.$mes.'/'.$ano));
                
            }else{
          
                redirect(base_url('paciente/ficha/'.$this->input->post('matricula_paciente')));
            
            }
            
		}else{
		  
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Altere algum dado atual antes de salvar.</strong></div>');
          
			redirect(base_url('paciente/ficha/'.$this->input->post('matricula_paciente')));
		}
	}
	
    /**
    * Função que tem a finalidade de recirecionar a solicitação de pesquisa para a real função de 
    * pesquisa de paciente de fato (Esse redirecionamento é realizado para ocultar o index.php a aplicação)
    */
	public function iniPesquisa($dadoGet = null){ # REDIRECINAMENTO PARA OCULTAR INDEX DA URL
        
        if(!empty($dadoGet)){
            redirect(base_url('paciente/pesquisar/'.$dadoGet));
        }else{
            redirect(base_url('paciente/pesquisar/'.$this->input->post('dados_paciente')));
        }
	}
	
    /**
    * Função que realiza a pesquisa de paciente
    * @param $dado Informação que será pesquisada
    * @param $pagina Informação utilizada para gerar a paginação da pesquisa
    */
	public function pesquisar($dado = null,$pagina = null){
        
        $dado = str_ireplace('%20', ' ',$dado);
        
		$this->load->helper('text');
		$this->load->library('pagination');
        
        if(empty($dado)){
            $dado = '1';
        }
		
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);
		
		$dadoPesquisa = $dado;
		
		$mostra_por_pagina = 15;
		$resPaciente['pacientes'] = $this->paciente->pesquisaPaciente($dadoPesquisa, $pagina, $mostra_por_pagina);
		$resPaciente['qtdPacientes'] = $this->paciente->pesquisaQtdPaciente($dadoPesquisa);
		
		if($resPaciente['qtdPacientes'][0]->total == 0){ # SE A CONSULTA O NÃO RETORNAR NENHUM VALOR
			$resPaciente['pacientes'] = '0'; # VARIÁVEL PACIENTE FICA ZERADA
		}
		
		$resPaciente['pesquisa'] = 'sim'; # INFORMA SE FOI REALIZADA ALGUMA PESQUISA
		
		$config['base_url'] = base_url('paciente/pesquisar/'.$dadoPesquisa); 
		$config['total_rows'] = $resPaciente['qtdPacientes'][0]->total;
		$config['per_page'] = $mostra_por_pagina;
		$config['uri_segment'] = 4;
        $config['full_tag_open'] = '<li>';
        $config['full_tag_close'] = '</li>';
        $config['first_tag_open']	= '';
       	$config['first_tag_close']	= '';
        $config['last_tag_open']		= '';
	    $config['last_tag_close']		= '';
	    $config['first_url']			= ''; // Alternative URL for the First Page.
	    $config['cur_tag_open']		= '<a class="active"><strong>';
	    $config['cur_tag_close']		= '</strong></a>';
	    $config['next_tag_open']		= '';
        $config['next_tag_close']		= '';
	    $config['prev_tag_open']		= '';
	    $config['prev_tag_close']		= '';
	    $config['num_tag_open']		= '';
		$this->pagination->initialize($config);
		$resPaciente['paginacao'] = $this->pagination->create_links();

		
		#$this->load->view('calendario',$data);
        
        /*
        Responsável por redirecionar o processo para a agenda novamente
        Esse redirecionamento é executado quando a ficha do paciente esta incompleta
        */
        $url = explode('/', $_SERVER['HTTP_REFERER']);
        $direct = (preg_match('/\/agenda\//', $_SERVER['HTTP_REFERER']))? '/'.implode('/',preg_grep('/^[0-9]{2,4}$/', $url)) : '';
        $paciente['direct'] = $direct;
        
        if(!empty($direct)){
            $dataBR = substr($direct, 1, 10);
            $dataUSA = implode('-',array_reverse(explode('/', $dataBR)));
        }else{
            $dataBR = date('d/m/Y');
            $dataUSA = date('Y-m-d');
        }
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $dataCalendar['dataCalendario'] = $dataUSA;
        $dataCalendar['dataFormatada'] = $dataBR;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
      	$this->layout->region('corpo', 'paciente/view_pesquisa', $resPaciente);
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	
	}
    
    /**
    * Função que solicita a remoção do paciente
    * @param $matricula Matrícula do paciente para remoção
    */
    public function remover(){	
		
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('paciente'));
            exit();
        }
        
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);
		
		//Define os dados do cabeçalho.
		$dados_cabecalho = array(
			'titulo_h2' => 'Pacientes'
      	);	
        
        $resPaciente['pesquisa'] = 'nao';

        $res = $this->paciente->excluir($this->input->post('excluirPaciente'));
        
        if($res){
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Paciente removido com sucesso!</strong></div>');
            
        }else{
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Erro ao remover o paciente!</strong></div>');
            
        }
        
        redirect(base_url('paciente'));

	}
}