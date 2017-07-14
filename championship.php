<?php 
require_once "crowley.php";
require_once "team.php";
/**
* 				
*/
class Championship
{
	
	private $link;
	private $teams;
	private $crowley;

	public function __construct() {
		$this->link = "http://globoesporte.globo.com/futebol/brasileirao-serie-a/";
		$this->crowley = new Crowley($this->link);
	}

	public function getClassification() {
		
		$this->setTeams();
		$this->setPoints();

		return $this->teams;
	}

	private function setTeams() {
		$teams_list = $this->crowley->getGenericAttribute("table.tabela-times > tr.tabela-body-linha", $this->crowley->xpath, '', false);
		
		$teams = [];

		foreach ($teams_list as $key => $team) {
			$team_dom = new DOMDocument();
			$team_dom->appendChild($team_dom->importNode($team, true));

			$team_xpath = new DOMXPath($team_dom);

			$tmp_team = new Team();

			$tmp_team->position = $this->crowley->getGenericAttribute('td.tabela-times-posicao', $team_xpath);
			$tmp_team->name 	= $this->crowley->getGenericAttribute('strong.tabela-times-time-nome', $team_xpath);
			$tmp_team->initials = $this->crowley->getGenericAttribute('span.tabela-times-time-sigla', $team_xpath);
			
			$teams[] = $tmp_team;
		}

		$this->teams = $teams;
	}

	private function setPoints() {

		$points_list = $this->crowley->getGenericAttribute("table.tabela-pontos > tr.tabela-body-linha", $this->crowley->xpath, '', false);
		
		$teams = [];
		$my_teams = $this->teams;

		foreach ($points_list as $key => $point) {

			$point_dom = new DOMDocument();
			$point_dom->appendChild($point_dom->importNode($point, true));

			$point_xpath = new DOMXPath($point_dom);

			$tmp_team = $my_teams[$key];

			$points = $this->crowley->getGenericAttribute('td', $point_xpath, '', false);

			$tmp_team->points 		= $points[0]->nodeValue;
			$tmp_team->games 		= $points[1]->nodeValue;
			$tmp_team->victories 	= $points[2]->nodeValue;
			$tmp_team->draws 		= $points[3]->nodeValue;
			$tmp_team->defeats 		= $points[4]->nodeValue;
			$tmp_team->goalFor 		= $points[5]->nodeValue;
			$tmp_team->goal_against = $points[6]->nodeValue;
			$tmp_team->goal_balance = $points[7]->nodeValue;
			$tmp_team->improvement 	= $points[8]->nodeValue;


		}

	}

}

	