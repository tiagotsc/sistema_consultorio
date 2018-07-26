<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');
include_once('config.inc.php');
/**
 * Sms
 * 
 * @package 
 * @author Boomer
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class Sms extends CI_Controller
{

    /**
     * Sms::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        #$this->load->library('table');
        $this->load->library('Util', '', 'util');
        $this->load->model('funcionario_model', 'funcionario');
        $this->load->model('DadosBanco_model', 'dadosBanco');
        $this->load->model('agenda_model', 'agenda');
        $this->load->model('paciente_model', 'paciente');
        $this->load->model('sms_model', 'sms');
    }

    /**
     * Sms::index()
     * 
     * @return
     */
    public function index()
    {

        //Define os dados do header HTML.
        /*$dados_header = array(
        'titulo' => 'Consultório Online',
        'descricao' => 'Gestão de consultório, prático e eficiente.',
        'palavras_chave' => 'paciente, médico, secretaria'	
        );

        //Cria as regiões (views parciais) que serão montadas no arquivo de layout.
        $this->layout->region('html_header', 'view_html_header', $dados_header);
        $this->layout->region('corpo', 'view_form_login');
        $this->layout->region('html_footer', 'view_html_footer');
        
        // Então chama o layout que irá exibir as views parciais...
        $this->layout->show('layout');*/
    }

    /**
     * Sms::enviarConfirmacao()
     * 
     * Abre a aplicação para envio de SMS confirmação
     * 
     * @return
     */
    public function enviarConfirmacao()
    {
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
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

        $sms['saldoTotal'] = $this->sms->saldoTotal();

        //Cria as regiões (views parciais) que serão montadas no arquivo de layout.
        $this->layout->region('html_header', 'view_html_header', $dados_header);
        $this->layout->region('menu', 'view_menu', $menu);
        
        if(in_array(33, $this->session->userdata('permissoes'))){
        
      	     $this->layout->region('corpo', 'sms/view_envio_confirmacao', $sms);
             
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
     * Sms::execEnvioConfirmacao()
     * 
     * Inicia o processo de envio de sms
     * 
     * @return
     */
    public function execEnvioConfirmacao()
    {
        
        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $sms - true;
        $saldoAtual = 0;
        $contEnviado = 0;
        foreach ($this->input->post('selecionado') as $posicao) {

            $saldo = $this->sms->saldoPacote();

            if ($saldo) {

                if ($saldo[0]->qtd_sms > 0) {

                    $cd_agenda = $this->input->post('cd_agenda')[$posicao];
                    $celular = $this->input->post('celular')[$posicao];
                    $msg = $this->input->post('msg')[$posicao];
                    $saldoAtual = $saldo[0]->qtd_sms - 1;

                    $envio = $this->enviarSMS($cd_agenda, $saldo[0]->cd_sms, $saldo[0]->qtd_sms, $celular, $msg);

                    if ($envio) {
                        $contEnviado++;
                        $this->sms->debitaPacote($saldo[0]->cd_sms, $saldoAtual);
                    }

                    continue;

                } else {
                    
                    $sms = false;
                    break;
                    
                    $this->sms->debitaPacote($saldo[0]->cd_sms, 0);

                    #$this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Você não possui mais SMS para enviar. Efetue uma nova recarga.</strong></div>');

                    #redirect(base_url('sms/enviarConfirmacao'));
                    #exit();

                }

            } else {
                
                $sms = false;
                break;
                
                #$this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Você não possui mais SMS para enviar. Efetue uma nova recarga.</strong></div>');

                #redirect(base_url('sms/enviarConfirmacao'));
                #exit();

            }
            
            if($sms == false){
                break;
            }

        }
        
        if($sms){
            
            $this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Foram enviados ' . $contEnviado . ' sms com sucesso!</strong></div>');
            
        }else{
            
            $this->session->set_flashdata('status', '<div class="alert alert-block textoCentro"><strong>Você não possui mais SMS para enviar. Efetue uma nova recarga.</strong></div>');
            
        }
        
        #$this->session->keep_flashdata('status');
        #var_dump($this->session->flashdata('status'));
        #exit();
        #$this->session->set_flashdata('status', '<div class="alert alert-success textoCentro"><strong>Foram enviados ' . $contEnviado . ' sms com sucesso!</strong></div>');
        redirect(base_url('sms/enviarConfirmacao'));

    }

    /**
     * Sms::enviarSMS()
     * 
     * @param mixed $cdAgenda
     * @param mixed $cdPacoteSMS
     * @param mixed $saldoAtual
     * @param mixed $celular
     * @param mixed $msg
     * @return TRUE ou FALSE
     */
    public function enviarSMS($cdAgenda, $cdPacoteSMS, $saldoAtual, $celular, $msg)
    {

        if(!$this->session->userdata('session_id') || !$this->session->userdata('logado')){
			redirect(base_url('home'));
		}
        
        $celular = preg_replace("/[^0-9]/", "", $celular);


        include_once ("assets/sms/smsempresa-class.php");

        $SmsEmpresa = new SmsEmpresa();
        $SmsEmpresa->chavekey = CHAVE_ENVIO_SMS; // Chave de acesso.
        $SmsEmpresa->message = utf8_encode($msg); // Texto da mensagem a ser enviada.
        $SmsEmpresa->type = "9"; // Tipo da mensagem. 0-Sms ; 1-Voz.
        $SmsEmpresa->to = $celular; // Telefone do destinatário com ddd.
        #$SmsEmpresa->transfer = "21986422683"; 				   // Número para transferência ao fim da chamada (Aplicável somente para type = 1 (Voz))

        $Resultado = $SmsEmpresa->Envia();

        $gravacao = $this->sms->gravaEnvio($cdAgenda, $cdPacoteSMS, $saldoAtual, $celular,
            utf8_decode($msg), $Resultado->retorno['id'], $Resultado->retorno['situacao']);

        if ($gravacao) {

            $this->sms->statusSMSConsulta($cdAgenda);

            return true;

        } else {

            return false;

        }

    }
    
    /**
     * Sms::verificaResposta()
     * 
     * Consulta a resposta do sms
     * 
     * @return TRUE ou FALSE
     */
    public function verificaResposta()
    {
        
        $consultasPendentes = $this->sms->consultasEnviadas();

        foreach ($consultasPendentes as $cP) {

            $dadosSMS = $this->consultaReposta($cP->chave_sms);

            if ($dadosSMS) {
                
                $resposta = strtoupper(preg_replace('/ã|Ã/', 'A', trim($dadosSMS['resposta'])));
                
                if($resposta == 'NAO'){
                    $this->sms->atualizaSituacaoConsulta($dadosSMS['chave_sms']);
                    $this->sms->gravaResposta($resposta, $dadosSMS);
                }elseif($resposta == 'SIM'){
                    $this->sms->gravaResposta($resposta, $dadosSMS);
                }

            }

        }

    }

    /**
     * Sms::consultaReposta()
     * 
     * Pega a resposta quando existir na API de terceiros
     * 
     * @param mixed $chaveSms
     * @return TRUE ou FALSE
     */
    public function consultaReposta($chaveSms)
    {

        $url = 'http://api.smsempresa.com.br/get?key='.CHAVE_ENVIO_SMS.'&action=inbox&id='.$chaveSms;
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_URL, $url);
        $contents = curl_exec($c);

        $simple = $contents;
        $p = xml_parser_create();
        xml_parse_into_struct($p, $simple, $vals, $index);
        xml_parser_free($p);

        curl_close($c);

        $resultado = false;
        foreach ($vals as $v) {

            if (isset($v['attributes']['ID'])) {

                $resultado['chave_sms'] = $v['attributes']['ID'];
                $resultado['situacao'] = $v['attributes']['SITUACAO'];
                $resultado['data_envio'] = $v['attributes']['DATA_READ'];
                $resultado['celular'] = $situacao = $v['attributes']['TELEFONE'];
                $resultado['resposta'] = $v['value'];

            }

        }

        if ($resultado) {
            return $resultado;
        } else {
            return false;
        }

    }
    
    /**
     * Sms::criaPacote()
     * 
     * Cria pacote de sms
     * 
     * @param mixed $qtdSMS Quantidade de sms que serão adicionados no novo pacote
     * @param mixed $token Token de acesso para criar o pacote
     * @return O status no formato JSON
     */
    public function criaPacote($qtdSMS, $token){
        
        if($token == md5('sms=ok '.date('Y-m-d'))){
        
            $criacao = $this->sms->adicionaPacote($qtdSMS);
            
            if($criacao){
                echo json_encode(array('status'=>'PACOTE CRIADO COM SUCESSO!'));
            }else{
                echo json_encode(array('status'=>'ERRO AO CRIAR PACOTE'));
            }
        
        }else{
            
            echo json_encode(array('status'=>'TOKEN ERRADO'));
            
        }
        
    }

}
