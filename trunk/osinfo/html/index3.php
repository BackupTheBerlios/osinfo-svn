<?php

$index = new Index();

$index->tulosta_alkukoodi(true);
$index->nayta_sivuframe();
$index->nayta_paaframe($_GET['target']);
$index->tulosta_loppukoodi();

class Index
{
	private $_DivResoluutiot = array("SivuFrame" => array('10px', '10px', '20%', '500'),
					"PääFrame" => array('10px', '10px', '70%', '500'));
	private $_xmlkansio = "hosts";
	private $_xmlparseri = "xml-parseri.php";
	private $_index_view = "index_view.html";

	public function __construct()
    	{

	}

	public function tulosta_alkukoodi($tyylit = true)
	{
		//print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n\n";

		print "<html>\n";	
		print "<head>\n";	
		print "<title>Osinfo</title>\n\n";

		if($tyylit) $this->tulosta_tyyli();

		print "\n\n</head>\n\n";
		print "<body>\n\n";
	}

	public function tulosta_loppukoodi()
	{
		print "</body>\n";
		print "</html>";
	}
	
	private function tulosta_tyyli()
	{
		$varit = array(222, 197, 148);
		$varisyote = "r=" . $varit[0] . "&g=" . $varit[1] . "&b=" . $varit[2];

		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"xmltyyli.css\" />";
		print "<style type=\"text/css\">

		body
		{
			background-color: #eddcbc;
		}
		
		div.SivuFrame, div.PaaFrame
		{ 
			float: left;
		}
		
		div.SivuFrameSisalto, div.PaaFrame
		{
			background-color: rgb(" . $varit[0] . ", " . $varit[1] . ", " . $varit[2] . ");
		}

		div.SivuFrame
		{
			margin-left: " . $this->_DivResoluutiot[SivuFrame][0] . ";
			margin-top: " . $this->_DivResoluutiot[SivuFrame][1] . ";
			margin-bottom: " . $this->_DivResoluutiot[SivuFrame][1] . ";
			width: " . $this->_DivResoluutiot[SivuFrame][2] . ";
			min-height: " . $this->_DivResoluutiot[PääFrame][3] . ";
			min-width: 200;
			height: auto;
		}

		div.SivuFrameSisalto
		{
			padding: 5px;
			padding: 10px;
		}

		div.SivuFrameFade
		{
			background-image: url('fade.php?w=1&h=150&" . $varisyote . "');
			background-repeat: repeat-x;
			background-position: top;
			height: 150px;
		}

		div.PaaFrame
		{
			border: 1px solid black;
			padding: 5px;
			padding: 10px;
			margin-left: " . $this->_DivResoluutiot[PääFrame][0] . ";
			margin-top: " . $this->_DivResoluutiot[PääFrame][1] . ";
			margin-bottom: " . $this->_DivResoluutiot[PääFrame][1] . ";
			width: " . $this->_DivResoluutiot[PääFrame][2] . ";
			min-height: " . $this->_DivResoluutiot[PääFrame][3] . ";
			min-width: 600;
			height: auto;
			clear: none;
		}

		p.valitus
		{
			color: red;
		}

		</style>";
	}
	
	private function listaa_tiedostot()
	{
		if(file_exists($this->_xmlkansio))
		{
			$kansio = opendir($this->_xmlkansio);
		
			for($i=0; $tiedosto = readdir($kansio); $i++)
			{
				$p_array = explode(".", $tiedosto);
				$paate = $p_array[sizeof($p_array)-1];
		
				if($i > 1 && ($paate == "xml"))
				{
					print "<a href=\"?target=". $tiedosto . "\">" .
						$tiedosto . "</a><br />";
				}
			}
		}
		else print "<p class=\"valitus\">Directory \"" . $this->_xmlkansio . "\" does not exist</p>";
	}

	private function hae_viimeksi_paivitetty()
	{
		if(file_exists($this->_xmlkansio))
		{
			$kansio = opendir($this->_xmlkansio);
	
			$suurin = 0;
		
			for($i=0; $tiedosto = readdir($kansio); $i++)
			{
				$p_array = explode(".", $tiedosto);
				$paate = $p_array[sizeof($p_array)-1];
		
				if($i > 1 && ($paate == "xml"))
				{
					$aika = filemtime($this->_xmlkansio . "/" . $tiedosto);
	
					if($aika > $suurin)
						$suurin = $aika;
				}
			}
			
			return $suurin;
		}
	}
	
	public function nayta_sivuframe()
	{
		print "<div class=\"SivuFrame\">";
		print "<div class=\"SivuFrameSisalto\">";

		print "<h3>Osinfo</h3>
			<p><a href=\"" . $_SERVER["SCRIPT_NAME"] . "\">index view</a></p>
			<hr />";

		$this->listaa_tiedostot();
	
		print "<hr />";

		print "<p>Last updated:</p>";

		$viimeksi_paivitetty = $this->hae_viimeksi_paivitetty();

		if($viimeksi_paivitetty > 0)
			print "<p>" . date("F d Y H:i:s", $viimeksi_paivitetty) . "</p>";

		//include("sidebar.html");
	
		print "</div>";

		print "<div class=\"SivuFrameFade\"></div>";

		print "</div>";
	}
	
	public function nayta_paaframe($sivu = "")
	{
		print "<div class=\"PaaFrame\">";
		
		if($sivu != "")
		{
			if(file_exists($this->_xmlparseri))
			{
				include($this->_xmlparseri);
	
				if(file_exists($this->_xmlkansio . "/" . $sivu))
				{					
					$parametrit = $_POST['osinfo'];
				
					print "<p>
						<form action=\"" . $PHP_SELF . "\" method=\"post\">
						Parameters: <input type=\"text\" name=\"osinfo\" value=\"" . $parametrit . "\" />
							<input type=\"submit\" value=\"ok\" />
						</form>
						</p>";

					$parseri = new xmlparseri($this->_xmlkansio . "/" . $sivu);
		
					print "<pre>";

					//$parseri->nayta_tietotaulu();
					$parseri->hae_arvot("osinfo", $parametrit);

					print "</pre>";
				}
				else print "<p class=\"valitus\">File \"" . $sivu . "\" not found</p>";
			}
			else print "<p class=\"valitus\">Cannot find file \"" . $this->_xmlparseri . "\"</p>";
		}
		else
		{
			if(file_exists($this->_index_view))
				include($this->_index_view);
		}

		print "</div>";
	}
}

?> 