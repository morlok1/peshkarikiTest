<?php

require_once 'CandidateAbstract.php';

class Candidate extends CandidateAbstract
{
	
	public function run()
	{
		echo "Hello-run<br>";
	}
	
	public function calculateDistance($coordsCandidate)
	{
		echo "Hello-calculate " . $coordsCandidate;
		
	}
	
}

?>