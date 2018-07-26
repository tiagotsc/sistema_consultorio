<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once('config.inc.php');
date_default_timezone_set('America/Sao_Paulo');
#setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
/**
* Classe criada para controlar todas as buscas sicronas (Sem refresh)
*/
class Ajax extends CI_Controller
{
    
    /**
	 * Agenda::__construct()
	 * 
	 */
	public function __construct(){
		parent::__construct();
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
    }
    
    /*function index($product_id = 2)
    {
        $this->load->model('Product_model', 'product');
        $data['json'] = $this->product->get($product_id);
        if (!$data['json']) show_404();

        $this->load->view('json_view', $data);
    }*/
	
    /**
    * Função que pesuisa o paciente de acordo com os dados fornecidos
    * @param $pagina Informação utilizada para gerar a paginação da pesquisa
    */
	public function pesquisaPaciente($pagina = null){
	
		$this->load->model('paciente_model','paciente');
		#$this->load->helper('url');
		#$this->load->helper('form');
		$this->load->library('table');
		$this->load->helper('text');
		$this->load->library('pagination');
		
		$dadoPesquisa = $this->input->post('dados_paciente');
		
		$mostra_por_pagina = 4;
		$resPaciente['pacientes'] = $this->paciente->pesquisaPaciente($dadoPesquisa, $pagina, $mostra_por_pagina);
		$resPaciente['qtdPacientes'] = $this->paciente->pesquisaQtdPaciente($dadoPesquisa);
		
		if($resPaciente['qtdPacientes'][0]->total == 0){ # SE A CONSULTA O NÃO RETORNAR NENHUM VALOR
			$resPaciente['pacientes'] = '0'; # VARIÁVEL PACIENTE FICA ZERADA
		}
		
		$resPaciente['pesquisa'] = 'normal'; # INFORMA SE FOI REALIZADA ALGUMA PESQUISA
		
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
		
		$this->load->view('paciente/view_ajax',$resPaciente);
	
	}
    
    /**
    * Função que pesquisa o paciente antigo que deseja marcar consulta
    */
    public function pesquisaPacienteMarcarConsulta(){
	
		$this->load->model('paciente_model','paciente');
		
		$resDados['dados'] = $this->paciente->pesquisaPacienteParaConsulta();
		
		$this->load->view('agenda/view_ajax',$resDados);
	
	}
    
    /**
    * Função que pesquisa as 10 últimas consulta de um determinado paciente
    */
    public function pesquisaPacienteConsulta(){
	
		$this->load->model('agenda_model','agenda');
		
		$resDados['dados'] = $this->agenda->consultasDoPaciente();
		
		$this->load->view('agenda/view_ajax',$resDados);
	
	}
    
    /**
    * Função que realiza a busca de auto completar do paciente
    */
    public function pesquisaPacienteAutoComplete(){
        
        $this->load->model('paciente_model','paciente');
        
        $resPaciente['pesquisa'] = 'autoComplete';
        
        $resPaciente['pacientes'] = $this->paciente->pesquisaAutoComplete();
        
        $this->load->view('paciente/view_ajax',$resPaciente);
        
    }
    
    /**
    * Função que monitora os atendimentos
    */
    public function monitoraAtendimentos(){
	
		$this->load->model('agenda_model','agenda');
		#$this->load->helper('url');
		#$this->load->helper('form');
		$this->load->helper('text');
		
		$resDados['dados'] = $this->agenda->verificaConsultas();
		
		$this->load->view('agenda/view_ajax',$resDados);
	
	}
    
    /**
    * Função que consulta os pacientes agendados
    */
    public function monitoraPacientes(){
	
		$this->load->model('agenda_model','agenda');
		#$this->load->helper('url');
		#$this->load->helper('form');
		$this->load->helper('text');
		
		$resDados['dados'] = $this->agenda->verificaAgenda();
		
		$this->load->view('agenda/view_ajax',$resDados);
	
	}
    
    /**
    * Função que consulta a estatística do dia das consultas
    */
    public function estatisticaDia(){
	
		$this->load->model('agenda_model','agenda');
		#$this->load->helper('url');
		#$this->load->helper('form');
		$this->load->helper('text');
		
		$resDados['dados'] = $this->agenda->estatisticaConsultas();
		
		$this->load->view('agenda/view_ajax',$resDados);
	
	}
    
    /**
    * Função que consulta o endereço através do CEP
    * 
    * @param CEP informado para consulta
    */
    public function pegaEndereco(){
        
        $cep = $this->input->post('cep');
        
        $this->load->model('DadosBanco_model','dadosBanco');
        
        $resDados['dados'] = $this->dadosBanco->endereco($cep);
		
		$this->load->view('agenda/view_ajax',$resDados);
        
    }
    
    /**
    * Função que realiza a busca de auto completar do funcionário
    */
    public function pesquisaFuncionarioAutoComplete(){
        
        $this->load->model('funcionario_model','funcionario');
        
        $resFuncionario['pesquisa'] = 'autoComplete';
        
        $resFuncionario['funcionarios'] = $this->funcionario->pesquisaAutoComplete();
        
        $this->load->view('funcionario/view_ajax',$resFuncionario);
        
    }
    
    /**
    * Função que pesuisa o funcionário de acordo com os dados fornecidos
    * @param $pagina Informação utilizada para gerar a paginação da pesquisa
    */
	public function pesquisaFuncionario($pagina = null){
	
		$this->load->model('funcionario_model','funcionario');
		#$this->load->helper('url');
		#$this->load->helper('form');
		$this->load->library('table');
		$this->load->helper('text');
		$this->load->library('pagination');
		
		$dadoPesquisa = $this->input->post('dados_funcionario');
		
		$mostra_por_pagina = 4;
		$resFuncionario['funcionarios'] = $this->funcionario->pesquisaFuncionario($dadoPesquisa, $pagina, $mostra_por_pagina);
		$resFuncionario['qtdFuncionarios'] = $this->funcionario->pesquisaQtdFuncionario($dadoPesquisa);
		
		if($resFuncionario['qtdFuncionarios'][0]->total == 0){ # SE A CONSULTA O NÃO RETORNAR NENHUM VALOR
			$resFuncionario['funcionarios'] = '0'; # VARIÁVEL FUNCIONARIO FICA ZERADA
		}
		
		$resFuncionario['pesquisa'] = 'normal'; # INFORMA SE FOI REALIZADA ALGUMA PESQUISA
		
		$config['base_url'] = base_url('funcionario/pesquisar/'.$dadoPesquisa); 
		$config['total_rows'] = $resFuncionario['qtdFuncionarios'][0]->total;
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
		$resFuncionario['paginacao'] = $this->pagination->create_links();
		
		$this->load->view('funcionario/view_ajax',$resFuncionario);
	
	}
    
    /**
    * Função que realiza a busca de auto completar do funcionário
    */
    public function medicosEspecialidades(){
        
        $this->load->model('funcionario_model','funcionario');
        
        $resDados['dados'] = $this->funcionario->dadosMedicoConsulta();
        
        $this->load->view('agenda/view_ajax',$resDados);
        
    }
    
    /**
    * Função que realiza a busca do gráfico de linhas para o dashboard
    */
    public function graficoLinha($ano){
        
        $this->load->model('Dashboard_model','dashboard');
        
        $qtdPacientes = $this->dashboard->qtdPacientes($ano);
        
        if($qtdPacientes){
            
            foreach($qtdPacientes as $qtdP){
                
                if($qtdP->qtd_finalizados > 0){
                    $qtdFinalidados = $qtdP->qtd_finalizados;
                }else{
                    $qtdFinalidados = 0;
                }
                
                if($qtdP->qtd_ausentes > 0){
                    $qtdAusentes = $qtdP->qtd_ausentes;
                }else{
                    $qtdAusentes = 0;
                }
                
                $resultado[] = array('meses'=>$qtdP->mes, 'Atendidos'=>$qtdFinalidados, 'Ausentes'=>$qtdAusentes);
                
            }
            
        }else{
            
            $resultado[] = array('meses'=>'Nenhum', 'Atendidos'=>0, 'Ausentes'=>0);
            
        }
        
        $resDados['dados'] = $resultado;
        
        $this->load->view('dashboard/view_ajax',$resDados);
        
    }
    
    /**
    * Função que realiza a busca do gráfico de barras para o dashboard
    */
    public function graficoBarra($ano = 2014){

        $this->load->model('Dashboard_model','dashboard');
        
        $resultadoTempo = $this->dashboard->mediaTempo();
        
        if($resultadoTempo){
            
            foreach($resultadoTempo as $res){
                
                if($res->media_atraso_pacientes <> null){
                    $atrasosPacientes = str_replace(':','.',$res->media_atraso_pacientes);
                }else{
                    $atrasosPacientes = 0;
                }
                
                if($res->media_tempo_atendimento <> null){
                    $tempoAtendimento = str_replace(':','.',$res->media_tempo_atendimento);
                }else{
                    $tempoAtendimento = 0;
                }
                
                if($res->media_atraso_atendimento <> null){
                    $atrasoAtendimento = str_replace(':','.',$res->media_atraso_atendimento);
                }else{
                    $atrasoAtendimento = 0;
                }
                
                $resultado[] = array('meses'=>$res->mes, 'Atrasos Pacientes'=>$atrasosPacientes, 'Atrasos Atendimentos'=>$atrasoAtendimento, 'Tempo Atendimentos'=>$tempoAtendimento);
            
            }
            
        }else{
            
            $resultado[] = array('meses'=>'Nenhum', 'Atrasos Pacientes'=>0, 'Atrasos Atendimentos'=>0, 'Tempo Atendimentos'=>0);
            
        }

        $resDados['dados'] = $resultado;
        
        $this->load->view('dashboard/view_ajax',$resDados);
        
    }
    
    /**
    * Função que realiza a busca do gráfico de barra colada para o dashboard
    */
    public function graficoBarraColada($ano){
        
        $this->load->model('Dashboard_model','dashboard');
        
        $resultado = $this->dashboard->qtdParticularConvenio($ano);
        
        if($resultado){
            
            foreach($resultado as $res){
                
                if($res->qtd_particular > 0){
                    $qtdParticular = $res->qtd_particular;
                }else{
                    $qtdParticular = 0;
                }
                
                $particular[] = $qtdParticular;
                
                if($res->qtd_convenio){
                    $qtdConvenio = $res->qtd_convenio;
                }else{
                    $qtdConvenio = 0;
                }
                
                $convenio[] = $qtdConvenio;
                
                $meses[] = $res->mes;
                
            }
            
            $resDados['dados'] = array('meses'=>$meses, 'Convênio'=>$convenio, 'Particular'=>$particular);
            
        }else{
            
            $resDados['dados'] = array('meses'=>'Nenhum', 'Convênio'=>0, 'Particular'=>0);
            
        }
        
        $this->load->view('dashboard/view_ajax',$resDados);
        
    }
    
    
    /**
    * Função que verifica se a consulta existe
    */
    public function verificaConsulta(){
        
        $this->load->model('agenda_model','agenda');
        
        $resDados['dados'] = array('situacao'=>$this->agenda->consultaExistente());
        
        $this->load->view('agenda/view_ajax',$resDados);
        
    }
    
    /**
    * Função que pega todas as consulta com status de "MARCADO" de acordo com a data informada para disparar SMS
    */
    public function consultaParaEnvioSMS(){
        
        $this->load->model('sms_model','sms');
        
        $resDados['dados'] = $this->sms->consultasParaEnvio();

        $this->load->view('sms/view_ajax',$resDados);
        
    }
    
    /**
    * Função que retorna os horários que o médico tem disponível
    */
    public function horariosDisponiveis(){
        
        $this->load->library('Util', '', 'util');
        $this->load->model('agenda_model','agenda');
        
        $todosHorarios = $this->util->intervaloHoras(HORA_INICIO, HORA_FIM, MINUTO_INTERVALO);
        
        $agendaMedicoHorarios = $this->agenda->horariosAgendaMedico();
        
        if($agendaMedicoHorarios){
        
            foreach($agendaMedicoHorarios as $aMf){
                
                $horariosMarcados[] = $aMf->horarios_marcados;
                
            }
            
            #$horariosMarcados = array('09:00','09:15','09:30','09:45','10:00','10:15');
            
            
            
            $horariosDisponiveis = array_values(array_diff($todosHorarios, $horariosMarcados));
        
        }else{
            
            $horariosDisponiveis = $todosHorarios;
            
        }
        
        $resDados['dados'] = $horariosDisponiveis;
        $this->load->view('agenda/view_ajax',$resDados);
        
    }
                
}
