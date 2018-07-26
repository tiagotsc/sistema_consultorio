<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as execuções de processos da agenda
*/
class Agenda extends CI_Controller {
	
	/**
	 * Agenda::__construct()
	 * 
	 */
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
        $this->load->model('funcionario_model','funcionario');
        $this->load->model('DadosBanco_model','dadosBanco');
        $this->load->model('agenda_model','agenda');
        $this->load->model('paciente_model','paciente');
	} # Fecha __construct()
	/*
	public function index($ano = null,$mes=null){	
		
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);
		
		//Define os dados do cabeçalho.
		$dados_cabecalho = array(
			'titulo_h2' => 'Agenda'
      	);	
		
		#$this->load->view('calendario',$data);

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $dados_cabecalho);
      	$this->layout->region('corpo', 'view_conteudo', $dataCompleta);
		$this->layout->region('sub_menu', 'view_sub_menu', $data);
      	$this->layout->region('rodape', 'view_rodape');
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	}
    */
    /**
    * Função que abri a agenda da secretária
    * @param $dia, $mes, $ano - Data que será aberta na agenda
    */
    public function secretaria($dia = null,$mes=null, $ano = null){	
        
        if(empty($dia)){
            $dia = date('d');
        }
        
        if(strlen($dia) == 1){
            $dia = '0'.$dia;
        }
        
        if(empty($mes)){
            $mes = date('m');
        }
        
        if(strlen($mes) == 1){
            $mes = '0'.$mes;
        }
        
        if(empty($ano)){
            $ano = date('Y');
        }
        
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);
        
		$dados['dataCompleta']	= $dia.'/'.$mes.'/'.$ano;
        $dados['medico'] = $this->funcionario->dadosMedicoConsulta();
        $dados['plano'] = $this->dadosBanco->plano();
        $dados['todasConsultas'] = $this->agenda->todasConsultas($ano.'-'.$mes.'-'.$dia, 'secretaria');
        $dados['situacao'] = $this->agenda->situacao();
        $dados['especialidade'] = $this->funcionario->especialidadesAssociadas();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $dataCalendar['dataCalendario'] = $ano.'-'.$mes.'-'.$dia;
        $dataCalendar['dataFormatada'] = $dia.'/'.$mes.'/'.$ano;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(8, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'agenda/view_secretaria', $dados);
             
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	
    } # Fecha secretaria()
    
    /**
    * Função que abri a agenda do médico
    * @param $dia, $mes, $ano - Data que será aberta na agenda
    */
    public function medico($dia = null,$mes=null, $ano = null){	
        
        if(empty($dia)){
            $dia = date('d');
        }
        
        if(strlen($dia) == 1){
            $dia = '0'.$dia;
        }
        
        if(empty($mes)){
            $mes = date('m');
        }
        
        if(strlen($mes) == 1){
            $mes = '0'.$mes;
        }
        
        if(empty($ano)){
            $ano = date('Y');
        }
        
		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);	
        
		$dados['dataCompleta']	= $dia.'/'.$mes.'/'.$ano;
        #$dados['medico'] = $this->funcionario->dadosMedicoConsulta();
        #$dados['plano'] = $this->dadosBanco->plano();
        $dados['todasConsultas'] = $this->agenda->todasConsultas($ano.'-'.$mes.'-'.$dia, 'medico');
        $dados['situacao'] = $this->agenda->situacao();
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
        
        $dataCalendar['dataCalendario'] = $ano.'-'.$mes.'-'.$dia;
        $dataCalendar['dataFormatada'] = $dia.'/'.$mes.'/'.$ano;
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(9, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'agenda/view_medico', $dados);
             
        }else{
            
            $this->layout->region('corpo', 'view_permissao');
            
        }
        
		$this->layout->region('agenda', 'view_agenda', $dataCalendar);
      	$this->layout->region('rodape', 'view_rodape', $cliente);
      	$this->layout->region('html_footer', 'view_html_footer');
      	
		// Então chama o layout que irá exibir as views parciais...
      	$this->layout->show('layout');
	
    } # Fecha secretaria()
    
    /**
     * Agenda::salvar()
     * 
     * Insere ou Atualiza a consulta de acordo com os parâmetros informados
     * 
     */
    public function salvar(){
        
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('agenda/secretaria'));
            exit();
        }
        
        array_pop($_POST); // Remove o último elemento do array $_POST (CAMPO submit)

        if($this->input->post('cd_agenda')){ // Altera consulta

            $contatos = $this->paciente->atualizaContatos(); // Atualiza os telefones do paciente
            $status = $this->agenda->atualiza($this->input->post('matricula_paciente'));
            
            if($status or $contatos){
            
                $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Consulta alterada com sucesso!</strong></div>');
                
            }else{
                
                
                $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Altere alguma informa&ccedil;&atilde;o atual da consulta!</strong></div>');
                
            }
            
            redirect(base_url('agenda/secretaria/'.$this->input->post('data_agenda')));
            
        }else{ // Cadastra consulta
            
            if($this->input->post('matricula_paciente')){ // Paciente antigo
            
                $matricula_paciente = $this->input->post('matricula_paciente');
                $this->paciente->atualizaContatos(); // Atualiza os telefones do paciente
                
            }else{ // Paciente novo
            
                $matricula_paciente = $this->paciente->preCadastro(); // Pré cadastro paciente
                
            }
            
            if($matricula_paciente){ // Se matrícula ok
            
                $status = $this->agenda->insere($matricula_paciente); // Cadastra consulta
                
            }
            
            if($status){
                
                
                $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Consulta cadastrada com sucesso!</strong></div>');
                
            }else{
                
                
                $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Já existe paciente marcado no mesmo dia e hor&aacute;rio!</strong></div>');
                
            }
            
            redirect(base_url('agenda/secretaria/'.$this->input->post('data_agenda')));
            
        }
        
    } # Fecha salvar()
    
    /**
     * Agenda::situacaoConsulta()
     * 
     * Atualiza a situacao da consulta do paciente na agenda
     * 
     */
    public function situacaoConsulta(){
        
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('agenda/'.$this->session->userdata('direct_usuario').'/'.$this->input->post('data')));
            exit();
        }
        
        $status = $this->agenda->alteraSituacaoConsulta();
        
        if($status){
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Situa&ccedil;&atilde;o da consulta alterada com sucesso!</strong></div>');
            
            #redirect(base_url('agenda/'.$this->session->userdata('direct_usuario').'/'.$this->input->post('data')));
            
            $direcionamento = (strpos($_SERVER['HTTP_REFERER'], 'medico') === false)? 'secretaria': 'medico';
            redirect(base_url('agenda/'.$direcionamento.'/'.$this->input->post('data')));
        }else{
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Informe uma situa&ccedil;&atilde;o diferente da atual.</strong></div>');
            
            #redirect(base_url('agenda/'.$this->session->userdata('direct_usuario').'/'.$this->input->post('data')));
            
            $direcionamento = (strpos($_SERVER['HTTP_REFERER'], 'medico') === false)? 'secretaria': 'medico';
            redirect(base_url('agenda/'.$direcionamento.'/'.$this->input->post('data')));
        }
        
    } # Fecha situacaoConsulta())
    
    /**
     * Agenda::encaminharConsulta()
     * 
     * Muda a situação da consulta da agenda para 'CONSULTANDO'
     * 
     * @param $cdAgenda Id da agenda que terá sua situação alterada
     * 
     */
    public function encaminharConsulta($cdAgenda){
        
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('agenda/secretaria/'.date('d/m/Y')));
            exit();
        }
        
        $status = $this->agenda->situacaoConsultando($cdAgenda);
        
        if($status){
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Paciente encaminhado para consulta com sucesso!</strong></div>');
            
            redirect(base_url('agenda/secretaria/'.date('d/m/Y')));
        }else{
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Erro ao encaminhar paciente para consulta!</strong></div>');
            
            redirect(base_url('agenda/secretaria/'.date('d/m/Y')));
        }
    }
    
    /**
     * Agenda::chamaPaciente()
     * 
     * Muda a situação da consulta da agenda para 'CHAMADO'
     * 
     * @param $cdAgenda Id da agenda que terá sua situação alterada
     * 
     */
    public function chamaPaciente($cdAgenda){
        
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('agenda/medico/'.date('d/m/Y')));
            exit();
        }
        
        $status = $this->agenda->situacaoChamado($cdAgenda);
        
        if($status){
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Paciente chamado para consulta com sucesso!</strong></div>');
            
            redirect(base_url('agenda/medico/'.date('d/m/Y')));
        }else{
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Erro ao chamar paciente!</strong></div>');
            
            redirect(base_url('agenda/medico/'.date('d/m/Y')));
        }
    }
    
    /**
     * Agenda::atenderPaciente()
     * 
     * Abre a consulta para atendimento do paciente
     * 
     * @param $cdAgenda Id da agenda para identificar o atendimento
     * 
     */
    public function atenderPaciente($cdAgenda){

		//Define os dados do header HTML.
		$dados_header = array(
			'titulo' => 'Consultório Online',
			'descricao' => 'Gestão de consultório, prático e eficiente.',
			'palavras_chave' => 'paciente, médico, secretaria'	
		);	
        
        #$dados['medico'] = $this->funcionario->dadosMedicoConsulta();
        #$dados['plano'] = $this->dadosBanco->plano();
        #$dados['todasConsultas'] = $this->agenda->todasConsultas($ano.'-'.$mes.'-'.$dia);
        $dados['cdAtendimento'] = $cdAgenda;
        $dados['atendimento'] = $this->agenda->atendimento($cdAgenda);
        $dados['atendimentoHistorico'] = $this->agenda->atendimentoHistoricoPaciente($cdAgenda, $dados['atendimento'][0]->matricula_paciente, $dados['atendimento'][0]->cd_especialidade_agenda, 'sim');
        
        foreach($dados['atendimentoHistorico'] as $receitaHistório){
            
            $todasReceitasAtendimento = $this->agenda->receitas_medicas($receitaHistório->cd_agenda);
            
            $receitasAtendimento[$receitaHistório->cd_agenda] = $todasReceitasAtendimento;
            
        }
        
        $dados['dataCompleta']	= $this->util->formataData($dados['atendimento'][0]->data_agenda, 'BR');
        
        $receitas = $this->agenda->receitasMedica($cdAgenda);
        
        $dados['receitasMedica'] = ($receitas)? $receitas: false;
        
        $dados['receitasHistorico'] = (isset($receitasAtendimento))? $receitasAtendimento: false;
        
        $menu['menu'] = $this->util->montaMenu($this->dadosBanco->menu($this->session->userdata('permissoes')), $this->dadosBanco->paisMenu($this->session->userdata('permissoes')));
   
        $dataCalendar['dataCalendario'] = date('Y-m-d');
        $dataCalendar['dataFormatada'] = date('d/m/Y');
        $dataCalendar['qtdConsultas'] = $this->agenda->qtdConsultasMarcadas();
        
        $cliente['cliente'] = $this->dadosBanco->cliente();
        $cliente['telefoneCliente'] = $this->dadosBanco->telefoneCliente();

		//Cria as regiões (views parciais) que serão montadas no arquivo de layout.
      	$this->layout->region('html_header', 'view_html_header', $dados_header);
      	$this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(28, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'agenda/view_atende_paciente', $dados);
             
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
     * Agenda::salvaAtendimento()
     * 
     * Salva a atendimento do paciente
     * 
     * @param $cdAgenda Id da agenda para identificar o atendimento
     * 
     */
    public function salvaAtendimento(){
        
        if(INADIMPLENTE){
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Regularize a sua situação para continuar usando o sistema!</strong></div>');
            redirect(base_url('agenda/'.$this->session->userdata('direct_usuario').'/'.$this->input->post('data')));
            exit();
        }
        
        $salva = $this->agenda->salvaAtendimentoConsulta();
        
        if($salva){
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Atendimento salvo com sucesso!</strong></div>');
            
        }else{
            
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Altere algum conteúdo atual do atendimento para salvar!</strong></div>');
            
        }
        
        redirect(base_url('agenda/atenderPaciente/'.$this->input->post('cd_agenda')));
        
    }
    
    /**
     * Agenda::receita()
     * 
     * Abre o receita
     * 
     * @param $cdAgenda Id da agenda para identificar a receita
     * 
     */
    public function receita($cdReceita, $cdAgenda){
        
        if(!in_array(30, $this->session->userdata('permissoes'))){
            echo '<strong>Não possui permissão</strong>';
            exit();
        }
        
        $dados['atendimento'] = $this->agenda->atendimento($cdAgenda);
        $dados['dadosReceita'] = $this->agenda->dadosReceita($cdReceita);
        
        $dados['nomeMes'] = $this->util->mesExtenso(substr($dados['atendimento'][0]->data_agenda, 5, 2));
        
        $dados['cliente'] = $this->dadosBanco->cliente();
        $dados['telefoneCliente'] = $this->dadosBanco->telefoneCliente();
        $html = $this->load->view('agenda/view_receita', $dados, true);
        
        error_reporting(0);
        
        require_once('assets/mpdf/mpdf.php');
        
        $pdf = new mPDF();
        
        $pdf->WriteHTML(utf8_encode($html)); // write the HTML into the PDF
        #$pdf->SetFooter('{DATE j/m/Y&nbsp; H:i}|{PAGENO}/{nb}|SEDUC / SIGETI');
        $pdf->SetFooter('Emitida no dia '.substr($dados['atendimento'][0]->data_agenda, 8, 2).' de '.$dados['nomeMes'].' de '.substr($dados['atendimento'][0]->data_agenda, 0, 4).' às '.$dados['atendimento'][0]->hora_final_atendimento_agenda.'.');
        $pdf->Output();
        
    }       
    
} # Fecha classe Agenda