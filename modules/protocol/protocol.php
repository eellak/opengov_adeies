<?php

/*	
	When adding attachments to the Protocol they MUST be in byte sequence and have specific details in array format.
	
	PHP Sample Code (from post form submit):
	//-----------------------------------------------------
	if(isset($_FILES) and !empty($_FILES)){ 
		$attachments = array();
		foreach ($_FILES as $name => $file) {
			$fh = fopen ($file["tmp_name"], "rb");
			$data = fread($fh, filesize($file["tmp_name"]));
			$attachments[] = array (
				'file' 			=>  $data,
				'name' 			=> 	$file['name'],
				'type' 			=>  $file['type'],
				'description'	=> 	$name,
			);
		}
		if(count($attachments) > 0){
			$result = create_protocol($subject, $sender, $usage_code, $attachments);
		}
	} 
	//-----------------------------------------------------
*/

function create_protocol($subject, $sender, $usage_code, $files = null, $return_full_result = false){

	$client = new SoapClient(PROTOCOL_URL, array('trace' => 1, 'exceptions' => 1, 'cache_wsdl' => 0)); 	// set trace = 1 for debugging
	$client->__setLocation(PROTOCOL_URL);	

	try {	
		
		$protocol_args	= array(
					'userName'	=> PROTOCOL_USER,
					'password'	=> PROTOCOL_PASS,
					'document' 	=> array(
								'createdBy' 		=> PROTOCOL_USER,
								'docReceiveType' 	=> '',
								'documentPK' => array(
								  'protNo' 	=> 0,
								  'usage' 	=> $usage_code,
								  'year' 	=> 0,
								),
								'inDescription' => $subject,		// Θέμα εισερχομένου - μέχρι 1000 χαρακτήρες
								'inPerson' 		=> $sender,			// Αποστολέας του εγγράφου -  μέχρι 500 χαρακτήρες
							),
					'sessionId' => 1,
			);
		
		$result = $client->createProtocol($protocol_args);
		
		if( $files != null and count($files)>0){
			attach_file_protocol($subject, $sender, $usage_code, $files, $result, $return_full_result);
		}
		
		if($return_full_result)
			return $result->return;
		else{
			$documentPK = $result->return->documentPK;
			return array('protNo' => $documentPK->protNo);
		}
		
	} catch(SoapFault $fault) {
		if(DEBUG)
			return array( 'lastest_request' => $client->__getLastRequest(), 'fault' => $fault->getMessage());
	}

}

function attach_file_protocol($subject, $sender, $usage_code, $files, $document, $print_full_result = false){
	
	$client = new SoapClient(PROTOCOL_URL, array('trace' => 1, 'exceptions' => 1, 'cache_wsdl' => 0)); 	// set trace = 1 for debugging
	$client->__setLocation(PROTOCOL_URL);	
	
	$return = array();

	foreach($files as $file){
		try {	
			
			$protocol_args	= array(
						'userName'			=> PROTOCOL_USER,
						'password'			=> PROTOCOL_PASS,
						'document' 			=> $document->return,
						'fileData' 			=> $file['file'],
						'fileName' 			=> $file['name'],
						'fileDescription' 	=> $file['description'],
						'inOutBoth'			=> 1,
				);
			
			$result = $client->attachFileToDocument ($protocol_args);
			
			$return[] = array( 
				'name'				=> $file['name'],
				'description'		=> $file['description'],
				'protocol_attach'	=> $result->return
			);
				
			
		} catch(SoapFault $fault) {
			if(DEBUG)
				$return[] = array( 'lastest_request' => $client->__getLastRequest(), 'fault' => $fault->getMessage());
		}
	}
	//TEMP -------------------
	echo '<br /><hr /> attachFileToDocument call results: <br /><br />';
	print_r($return);
	//------------------------
	
	return $return;
}

?>