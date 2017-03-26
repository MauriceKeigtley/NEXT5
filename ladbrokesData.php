<?php



	function getData(){
		$homepage = file_get_contents('https://www.ladbrokes.com.au/racing/');

		// a new dom object
		$dom = new domDocument; 

		// load the html into the object
		$dom->loadHTML($homepage);

		//get element by id
		$mango_div = $dom->getElementById('sideNavRacing');
		$li_rows = $mango_div->getElementsByTagName('li');
		$top5races = array();
		$stack = array();
		$count = count($li_rows);
	
		$counter = 5;
		$pos_counter = 0;
		for( $i=0; $i<$counter;$i++){
			$race = array();
			//get a href tag element
			$a = $li_rows[$i]->getElementsByTagName('a');

			//get meeting
			$meeting = $a->item(0)->nodeValue."   ";
			$meeting = trim(preg_replace('/\s+/', ' ', $meeting));
			//get link ref
			$href= $a->item(0)->getAttribute('href')."   ";

			$abbr = $li_rows[$i]->getElementsByTagName('abbr');  

			$time= $abbr->item(0)->getAttribute('time')."   ";
			$suspend= $abbr->item(0)->getAttribute('suspend')."   ";
			
			$nowtime = time();
			
			if($suspend>$nowtime){
				$race=[$pos_counter, $meeting,$href,$time,$suspend];
				array_push($stack, $race);
				$pos_counter++;
				
			}else{
				$counter++;
			}
			
		}
		
		return $stack;
		
	}
	
	$data = getData();
	echo json_encode($data);
	
?>
