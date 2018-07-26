<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
class Home extends CI_Controller {
		
	public function __construct(){
		parent::__construct();
		#$this->load->helper('url');
		$this->load->helper('form');
		#$this->load->library('table');
		$this->load->model('funcionario_model','funcionario');
        $this->load->model('permissaoPerfil_model','permissaoPerfil');
        $this->load->model('DadosBanco_model','dadosBanco');
        $this->load->model('agenda_model','agenda');
        $this->load->library('Util', '', 'util');
	}
	
	public function index(){		
       
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('corpo', 'view_form_login');
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	}
    
    public function autentica(){
        
        $usuario = $this->funcionario->autenticaFuncionario();
        
        if($usuario){
            
            $permissoesPerfil = $this->permissaoPerfil->permissoesDoPerfil($usuario[0]->cd_perfil_funcionario);
            
            foreach($permissoesPerfil as $perPer){
                $permissoesDoPerfil[] = $perPer['cd_permissao'];
            }
            
            $tipo_usuario = ($usuario[0]->medico_funcionario == 'NAO')? 'secretaria': 'medico';
            
            $dados = array(
                            'matricula' => $usuario[0]->matricula_funcionario,
                            'nome' => $usuario[0]->nome_funcionario,  
                            'permissoes' => $permissoesDoPerfil,
                            'direct_usuario' => $tipo_usuario,
                            'logado' => true
                            );
                           
            $this->session->set_userdata($dados);  
                
            redirect(base_url('home/inicio'));                        
                            
        }else{ # Se login ou senha errados ou usuário inativo
            
            $this->session->set_flashdata('class', 'erroOpera');
            $this->session->set_flashdata('status', 'Login ou senha inválida!');
            redirect(base_url('home'));
        }
        
    }
    
    public function inicio(){
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        //Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'funcionario, médico, secretaria'	
		);
        
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
      	$this->layout->region('corpo', 'view_inicio');
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('home'));
	}
    
    public function alteraSenha(){
        
        $altereSenha = $this->funcionario->alteraSenha();
        
        if($altereSenha){
            $this->session->set_flashdata('class', 'okOpera');
            $this->session->set_flashdata('status', 'Senha alterada com sucesso!<br><br>Login: '.$altereSenha[0]->login.'<br>Senha: '.$this->input->post('nova_senha'));
        }else{
            $this->session->set_flashdata('class', 'erroOpera');
            $this->session->set_flashdata('status', 'Essa é a mesma senha registrada no sistema!');
        }
        
        redirect(base_url('home'));
        
    }

	/*public function erro_404(){
		$this->load->view('erro_404');
	}*/
	
}