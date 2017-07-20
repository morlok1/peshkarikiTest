<?php

require_once 'CandidateAbstract.php';

class Candidate extends CandidateAbstract
{
	
	public function run()
	{
		echo $_GET["address"];
	}
	
	public function calculateDistance($coordsCandidate)
	{
		echo "Hello-calculate " . $coordsCandidate;
		
	}
	
}

?>