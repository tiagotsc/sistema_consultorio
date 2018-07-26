<?php
#error_reporting(0);
if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe responsável pela perfil
*/
class Perfil extends CI_Controller
{
    
	/**
	 * Perfil::__construct()
	 * 
	 * @return
	 */
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
		$this->load->model('funcionario_model','funcionario');
        $this->load->model('agenda_model','agenda');
        $this->load->model('permissaoPerfil_model','permissaoPerfil');

    }
    
    /**
     * Perfil::index()
     * 
     * Abre a tela de pesquisa de perfil
     * 
     * @return
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
        
        $dataBR = date('d/m/Y');
        $dataUSA = date('Y-m-d');
        
        
        $dataCalendar['dataCalendario'] = $dataUSA;
        $dataCalendar['dataFormatada'] = $dataBR;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();
        
        #$menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
       
	    #$dados['valores'] = $this->relatorio->arquivoRetornoDiario();
        #$dados['campos'] = ($dados['valores'][0]);
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(15, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'perfil/view_pesquisa');
             
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
     * Perfil::fichaPerfil()
     * 
     * Abre a ficha do do perfil
     * 
     * @param bool $cd Quando informado carrega os dados do perfil
     * @return
     */
    public function fichaPerfil($cd = false){
        
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
        
        $dataBR = date('d/m/Y');
        $dataUSA = date('Y-m-d');
        
        
        $dataCalendar['dataCalendario'] = $dataUSA;
        $dataCalendar['dataFormatada'] = $dataBR;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();
        
        if($cd){
            
            $dados = $this->permissaoPerfil->dadosPerfil($cd);
            
            $campos = array_keys($dados);
            
            foreach($campos as $campo){
			 
                #Data
                #if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $dados[$campo])){
                    #$dados[$campo] = $this->util->formataData($dados[$campo],'BR');
                #}
             
				$info[$campo] = $dados[$campo]; # ALIMENTA OS CAMPOS COM OS DADOS
			}
            
            $permissoesPerfil = $this->permissaoPerfil->permissoesDoPerfil($cd);
            
            foreach($permissoesPerfil as $perPer){
                $permissoesDoPerfil[] = $perPer['cd_permissao'];
            }
            
        }else{
            
            $campos = $this->permissaoPerfil->camposPerfil();
            
            foreach($campos as $campo){
                $info[$campo] = '';
            }
            
            $permissoesDoPerfil = false;
        
        }
        
        
        $paiPermissoes = $this->dadosBanco->paiPermissao();
        $permissoes = $this->dadosBanco->permissoes();
   
        $info['permissoes'] = $this->util->montaPermissao($permissoes, $paiPermissoes, $permissoesDoPerfil);

        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(16, $this->session->userdata('permissoes'))){
        
            $this->layout->region('corpo', 'perfil/view_ficha', $info);
        
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();
        
        $this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
        
    }
    
    
    
    /**
     * Perfil::salvarPerfil()
     * 
     * Cadastra ou atualiza o perfil
     * 
     * @return
     */
    public function salvarPerfil(){
        
        if($this->input->post('cd_perfil')){
            $cd = $this->permissaoPerfil->atualizaPerfil($this->input->post('cd_perfil'));
        }else{
            $cd = $this->permissaoPerfil->inserePerfil();
        }
        
        if($cd){
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Perfil salvo com sucesso!</strong></div>');
            redirect(base_url('perfil/fichaPerfil/'.$cd));
        }else{
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao criar perfil, caso o erro persiste comunique o perfil!</div>');
            redirect(base_url('perfil/fichaPerfil/'));
        }
        
        
    }
    
    /**
     * Perfil::pesquisarPerfil()
     * 
     * Pesquisa o perfil
     * 
     * @param mixed $nome Nome para pesquisa
     * @param mixed $status Status para pesquisa
     * @param mixed $pagina Página corrente
     * @return
     */
    public function pesquisarPerfil($nome = null, $status = null, $pagina = null){
        
        $nome = ($nome == null)? '0': $nome;
        $status = ($status == null)? '0': $status;
        
        $this->load->library('pagination');
        
        $dados['pesquisa'] = 'sim';
        $dados['postNome'] = ($this->input->post('nome_perfil') != '')? $this->input->post('nome_perfil') : $nome;
        $dados['postStatus'] = ($this->input->post('status_perfil') != '')? $this->input->post('status_perfil') : $status;
        
        $mostra_por_pagina = 30;
        $dados['perfis'] = $this->permissaoPerfil->psqPerfis($dados['postNome'], $dados['postStatus'], $pagina, $mostra_por_pagina);   
        $dados['qtdPerfis'] = $this->permissaoPerfil->psqQtdPerfis($dados['postNome'], $dados['postStatus']);                     
        
        $config['base_url'] = base_url('perfil/pesquisarPerfil/'.$dados['postNome'].'/'.$dados['postStatus']); 
		$config['total_rows'] = $dados['qtdPerfis'][0]->total;
		$config['per_page'] = $mostra_por_pagina;
		$config['uri_segment'] = 5;
        $config['first_link'] = '&lsaquo; Primeiro';
        $config['last_link'] = '&Uacute;ltimo &rsaquo;';
        $config['full_tag_open'] = '<li>';
        $config['full_tag_close'] = '</li>';
        $config['first_tag_open']	= '';
       	$config['first_tag_close']	= '';
        $config['last_tag_open']		= '';
	    $config['last_tag_close']		= '';
	    $config['first_url']			= ''; // Alternative URL for the First Page.
	    $config['cur_tag_open']		= '<a id="paginacaoAtiva" class="active"><strong>';
	    $config['cur_tag_close']		= '</strong></a>';
	    $config['next_tag_open']		= '';
        $config['next_tag_close']		= '';
	    $config['prev_tag_open']		= '';
	    $config['prev_tag_close']		= '';
	    $config['num_tag_open']		= '';
		$this->pagination->initialize($config);
		$dados['paginacao'] = $this->pagination->create_links();
        
        $dados['postNome'] = ($dados['postNome'] == '0')? '': $dados['postNome'];
        $dados['postStatus'] = ($dados['postStatus'] == '0')? '': $dados['postStatus'];
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
  
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();
  
        $this->layout->region('html_header', 'view_html_header');
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(15, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'perfil/view_pesquisa', $dados);
             
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
     * Perfil::apagaPerfil()
     * 
     * Apaga o perfil
     * 
     * @return
     */
    public function apagaPerfil(){
        
        $status = $this->permissaoPerfil->deletePerfil();  
        
        if($status){
        
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-success"><strong>Perfil apagado com sucesso!</strong></div>');
            redirect(base_url('perfil'));      
        
        }else{
            
            $this->session->set_flashdata('statusOperacao', '<div class="alert alert-danger">Erro ao apagar perfil, o perfil deve estar associado a algum usu&aacute;rio, caso o erro persiste comunique o administrador!</div>');
            redirect(base_url('perfil'));
        
        }
    }
                
}
