<?php
abstract class CandidateAbstract {
	abstract public function run();
	
	abstract protected function calculateDistance($coordsCandidate);
}
	