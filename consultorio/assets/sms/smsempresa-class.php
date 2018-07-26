<?php

/* Version 2.0 */

class SmsEmpresa {
  
    public $chavekey;
    public $message;
    public $type;
    public $to;	
    public $transfer;
	public $action;
	public $refer;	
	public $jobdate;	
	public $jobtime;		
	
	
    public function Envia(){
      $ch = curl_init();
	  
	  $data = array('key'           => $this->chavekey, 
				    'msg' 		    => $this->message,
				    'type' 		    => $this->type,
				    'number' 	    => $this->to,
				    'jobdate' 		=> $this->jobdate,
				    'jobtime' 		=> $this->jobtime,
				    'encode'        => '0',
				    );
	
	  curl_setopt($ch, CURLOPT_URL, 'http://api.smsempresa.com.br/send');
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
	  $res    = curl_exec ($ch);
	  $err    = curl_errno($ch);
	  $errmsg = curl_error($ch);
	  $header = curl_getinfo($ch);
		
	  curl_close($ch);
	 
      if ($res){
	    return $sms = new SimpleXmlElement($res, LIBXML_NOCDATA);
	  }
    }  
	
	
	
	public function Query(){
      $ch = curl_init();
	  
	  $data = array('key'           => $this->chavekey, 
				    'action' 		=> $this->action,
				    'id' 		    => $this->refer,
				    );
	
	  curl_setopt($ch, CURLOPT_URL, 'http://api.smsempresa.com.br/get');
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
	  $res    = curl_exec ($ch);
	  $err    = curl_errno($ch);
	  $errmsg = curl_error($ch);
	  $header = curl_getinfo($ch);
		
	  curl_close($ch);
      if ($res){
	    return $sms = new SimpleXmlElement($res, LIBXML_NOCDATA);
	  }
    }  
}  

?>