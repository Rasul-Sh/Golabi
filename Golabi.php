<?php

Class Golabi{
    
    //Compatible with farapayamak API
	/* init var */
	// sms panel username
	public $puser="";  
	// sms panel password
	public $ppass="";  
	// sms panel number
	public $pnum="";   
	// web service url for communicate
	public $WSDL= 'http://87.107.121.54/post/send.asmx?wsdl';  
    // Authorized numbers
    public $aunm=array('','','');

	public function __construct(){
       ini_set("soap.wsdl_cache_enabled", "0");
	   ini_set("default_charset", "UTF-8");
	   ini_set("display_error", "off");
    }

    public function __destruct(){
    	
    }

    function getInboxSmsCount($flag){
        $sms_client = new SoapClient($this->WSDL,array('encoding'=>'UTF-8'));
        $parameters['username'] = $this->puser;
        $parameters['password'] = $this->ppass;
        $parameters['isRead'] = $flag; //Either True or False
        $var  = $sms_client ->GetInboxCount($parameters)->GetInboxCountResult;
        unset($sms_client);
        return $var;
    }

    function getSms(){
        $sms_client = new SoapClient($this->WSDL, array('encoding'=>'UTF-8'));
        $parameters['username'] = $this->puser;
        $parameters['password'] = $this->ppass;
        $parameters['location'] = 1; // 1 = Receive | 2 = Send | -1 = All
        $parameters['from'] = ""; //Here you can choose if specific number should be selected
        $parameters['index'] = 0;
        $parameters['count'] = 10;
        $Data  = $sms_client ->getMessages($parameters)->getMessagesResult->MessagesBL;
        return $Data;
    }

    function exeCmd($command){

        return shell_exec($command);
    }

    function sendSms($no,$txt){
        $sms_client = new SoapClient($this->WSDL, array('encoding'=>'UTF-8'));
        $parameters['username'] =$this->puser;
        $parameters['password'] =$this->ppass;
        $parameters['to'] = "$no";
        $parameters['from'] = $this->pnum;
        $parameters['text'] = "$txt";
        $parameters['isflash'] = false;
        $recId  = $sms_client ->SendSimpleSMS2($parameters)->SendSimpleSMS2Result;

        return $recId;
    }

    function verifyNumber($hostNumber){

        return (int)in_array($hostNumber,$this->aunm);
    }


}

?>