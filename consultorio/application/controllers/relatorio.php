<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class relatorio extends CI_Controller {
     
    /**
     * relatorio::__construct()
     * 
     * Carrega as classes e modelos necessários
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
        $this->load->library('Util', '', 'util');
		$this->load->model('DadosBanco_model','dadosBanco');
        $this->load->model('agenda_model','agenda');
        $this->load->model('Relatorio_model','relatorio');
        /*$this->load->helper('file');
        $this->load->library('Util', '', 'util');        
		$this->load->library('table');
        $this->load->library('pagination');
		$this->load->model('Financeiro_model','financeiro');
            
        $this->load->model('RegistroTelecom_model','RegistroTelecom');   
        $this->load->model('DadosBanco_model','DadosBanco');
        $this->load->model('ArquivoCobranca_model','arquivoCobranca'); */
           
	} 
     
	/**
     * relatorio::relatorio()
     * 
     * Lista os relatórios existentes
     * 
     */
	public function index()
	{ 
        
        $dados_header = array(
            'titulo' => 'Consultório Online',
            'descricao' => 'Gestão de consultório, prático e eficiente.',
            'palavras_chave' => 'paciente, médico, secretaria');

        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));

        $dataCalendar['dataCalendario'] = date('Y-m-d');
        $dataCalendar['dataFormatada'] = date('d/m/Y');
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();

        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

        //Cria as regiões (views parciais) que serão montadas no arquivo de layout.
        $this->layout->region('html_header', 'view_html_header', $dados_header);
        $this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(3, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'relatorio/view_relatorio');
             
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
        $this->layout->region('agenda', 'view_agenda', $dataCalendar);
        $this->layout->region('rodape', 'view_rodape', $cliente);
        $this->layout->region('html_footer', 'view_html_footer');
        
      	$this->layout->show('layout');
	}
    
    
    /**
     * relatorio::geraRelatorio()
     * 
     * Gera o relatório informado
     * 
     */
    public function geraRelatorio()
    {
        
        switch($this->input->post('tipo_relatorio'))
        {
            case 'smsEnviados':
            
                $dadosRelatorio = $this->relatorio->smsEnviados();
                 
                 # Conteúdo para a planilha
                $dados['valores'] = ($dadosRelatorio)? $dadosRelatorio: '';
                
                # Títulos da planilha
                $dados['campos'] = ($dadosRelatorio)? array_keys($dados['valores'][0]): '';
             
            break;
            default:
                echo 'Relatório não definido';
                exit();
        }
        
        $this->load->view('relatorio/view_baixa_relatorio', $dados);
        
    }
       
}