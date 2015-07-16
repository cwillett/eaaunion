<?php 
// global defining pro edition
	//define('VPRO', 1);
	
	 if(!pagelines('credtext')){
		global $pagelines;
		$linkno = rand(1,2); 	
		if($linkno == 1){ $pagelines->credtext = "premium wordpress themes";}else{ $pagelines->credtext = "wordpress themes";}
		$pagelines->save_options();
	}
 		
		
?>