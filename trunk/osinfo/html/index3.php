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
	private $_parseri;
	private $_clientteja_rivilla = 3;

	public function __construct()
    	{
		if(file_exists($this->_xmlparseri))
		{
			include($this->_xmlparseri);

			$this->_parseri = new xmlparseri();
		}
		else print "<p class=\"valitus\">Cannot find file \"" . $this->_xmlparseri . "\"</p>";
	}

	public function tulosta_alkukoodi($tyylit = true)
	{
		print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n\n";

		print "<html>\n";	
		print "<head>\n";	
		print "<title>OS info</title>\n\n";

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
			padding: 0px 10px 10px 10px;
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

		ul.hostit
		{
			padding: 0px;
			padding-left: 10px;
		}

		li.kuvaus
		{
			font-size: 12px;
		}

		a:link
		{
			color: rgb(0,0,255);
		}

		a:visited
		{
			color: rgb(0,0,200);
		}

		</style>";
	}
	
	private function listaa_tiedostot($valikko = false)
	{
		if(file_exists($this->_xmlkansio))
		{
			$kansio = opendir($this->_xmlkansio);

			$ul_avattu = false;

			$xml_array = array();
		
			for($i=0; $tiedosto = readdir($kansio); $i++)
			{
				$p_array = explode(".", $tiedosto);
				$paate = $p_array[sizeof($p_array)-1];
		
				if($i > 1 && ($paate == "xml"))
				{
					$this->_parseri->parsi_tiedosto($this->_xmlkansio . "/" . $tiedosto);

					if($valikko)
					{
					$temp_array = array("tiedosto" => $tiedosto,
						"hostname" => $this->_parseri->hae_tieto("computer", "hostname"),
						"domain" => $this->_parseri->hae_tieto("computer", "domain"),
						"profile" => $this->_parseri->hae_tieto("computer", "profile"),
						"os" => $this->_parseri->hae_tieto("computer", "os"),
						"cpu" => $this->_parseri->hae_tieto("computer", "cpu"));
					}
					else
					{
					$temp_array = array("tiedosto" => $tiedosto,
						"hostname" => $this->_parseri->hae_tieto("computer", "hostname"),
						"domain" => $this->_parseri->hae_tieto("computer", "domain"),
						"profile" => $this->_parseri->hae_tieto("computer", "profile"),
						"os" => $this->_parseri->hae_tieto("computer", "os"),
						"cpu" => $this->_parseri->hae_tieto("computer", "cpu"),
						"script_version" => $this->_parseri->hae_tieto("script", "version"),
						"ram" => $this->_parseri->hae_tieto("ram", "Physical RAM"),
						"drive" => $this->_parseri->hae_tieto("drive", "SATA/SCSI drive"),
						"device" => $this->_parseri->hae_tieto("iface", "Device"),
						"ip" => $this->_parseri->hae_tieto("iface", "IPv4")
						);
					}

					array_push($xml_array, $temp_array);

				}
			}

			$domainit = $this->lajittele_array($xml_array, "domain");
			$profilet = array();

			foreach($domainit as $domain => $arvo)
			{
				$temp_array = array($domain => $this->lajittele_array($arvo, "profile"));
				$this->array_lisaa_samaan($profilet, $temp_array);
			}

			ksort($profilet);

			//print "<pre>";
			//print_r($domainit);
			//print_r($profilet);
			//print "</pre>";
			
			if($valikko)
			{
				foreach($profilet as $domain => $profiles)
				{
					ksort($profiles);
					print "<ul class=\"hostit\"><li>Domain: <i>" . $domain . "</i>";
	
					foreach($profiles as $profile => $hostnames)
					{
						print "<ul class=\"hostit\"><li>Profile: <i>" . $profile . 
							"</i><ul class=\"hostit\">";
	
						foreach($hostnames as $hostname => $arvot)
						{
							print "<li><b><a href=\"?target=". $arvot["tiedosto"] . "\">" .
								$arvot["hostname"] . "</a></b><ul class=\"hostit\">
								<li class=\"kuvaus\">" . $arvot["os"] . "</li>
								<li class=\"kuvaus\">" . $arvot["cpu"] . "</li>
								</ul></li>";
	
						}
	
						print "</ul></li></ul>";
					}
	
					print "</li></ul>";
				}
			}
			else
			{
				foreach($profilet as $domain => $profiles)
				{
					ksort($profiles);
					print "<div class=\"kentta\"><h3>Domain: <i>" . $domain . "</i>";
	
					foreach($profiles as $profile => $hostnames)
					{
						print "<div class=\"kentta\"><h3>Profile: <i>" . $profile . 
							"</i><table class=\"attribuutti\" cellspacing=\"10\">";

						$laskuri = 0;
	
						foreach($hostnames as $hostname => $arvot)
						{
							if($laskuri == 0) 
								print "<tr>";
							elseif($laskuri % $this->_clientteja_rivilla == 0)
 								print "</tr><tr>";

							print "<td valign=\"top\" class=\"attribuutti_td\" style=\"width: " . round(100 / $this->_clientteja_rivilla, 1) . "%\">";

							print "<b><a href=\"?target=". $arvot["tiedosto"] . "\">" .
								$arvot["hostname"] . "</a></b><ul class=\"hostit\">";

							foreach($arvot as $tyyppi => $arvo)
							{
								if($tyyppi != "hostname" && $tyyppi != "domain" && $tyyppi != "profile" && $tyyppi != "tiedosto" && $arvo != "")
								{
									print "<li class=\"kuvaus\">" . $arvo . "</li>";
								}
							}

							print "</ul></td>";

							$laskuri++;
						}

						while($laskuri % $this->_clientteja_rivilla != 0)
						{
							print "<td>&nbsp;</td>";
							$laskuri++;
						}
	
						print "</table></h3></div>";
					}
	
					print "</h3></div>";
				}
			}
		}
		else print "<p class=\"valitus\">Directory \"" . $this->_xmlkansio . "\" does not exist</p>";
	}

	private function lajittele_array($array, $nimi)
	{
		$nimet = array(" ");
		$nimet_lajiteltuna = array();

		foreach($array as $avain => $arvo)
		{
			if(is_array($arvo))
			{
				$loytyi = false;

				for($i=0; $i < sizeof($nimet); $i++)
					if($arvo[$nimi] == $nimet[$i]) $loytyi = true;

				if(!$loytyi)
				{
					$temp_array = array($arvo[$nimi] => array());
					$this->array_lisaa_samaan($nimet_lajiteltuna, $temp_array);
					array_push($nimet, $arvo[$nimi]);
				}
				
				array_push($nimet_lajiteltuna[$arvo[$nimi]], $arvo);
			}
		}

		return $nimet_lajiteltuna;
	}

	private function array_lisaa_samaan(&$array) 
	{
		$argumentit = func_get_args();
	
		foreach($argumentit as $argumentti) 
		{
			if(is_array($argumentti)) 
			{
				foreach($argumentti as $avain => $arvo) 
				{
					$array[$avain] = $arvo;
					$paluuarvo++;
				}
			}
			else $array[$argumentti] = "";
		}
	
		return $paluuarvo;
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

		print "<h3>OS info</h3>
			<p><a href=\"" . $_SERVER["SCRIPT_NAME"] . "\">index view</a></p>
			<hr />";

		$this->listaa_tiedostot(true);
	
		print "<hr />";

		print "<p>Last update:</p>";

		$viimeksi_paivitetty = $this->hae_viimeksi_paivitetty();

		if($viimeksi_paivitetty > 0)
			print "<p><i>" . date("F d Y H:i:s", $viimeksi_paivitetty) . "</i></p>";

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
			if(file_exists($this->_xmlkansio . "/" . $sivu))
			{
				/*
				$parametrit = $_POST['osinfo'];
			
				print "<p>
					<form action=\"" . $PHP_SELF . "\" method=\"post\">
					Parameters: <input type=\"text\" name=\"osinfo\" value=\"" . $parametrit . "\" />
						<input type=\"submit\" value=\"ok\" />
					</form>
					</p>";
				*/
	
				//print "<pre>";

				$this->_parseri->parsi_tiedosto($this->_xmlkansio . "/" . $sivu);
				
				//$this->_parseri->nayta_tietotaulu();
				$this->_parseri->hae_arvot("osinfo");

				//print "</pre>";
			}
			else print "<p class=\"valitus\">File \"" . $sivu . "\" not found</p>";

		}
		else
		{
			$this->nayta_index_view();
		}

		print "</div>";
	}

	private function nayta_index_view()
	{
		print "<h3>index view</h3>";

		$this->listaa_tiedostot(false);
	}
}

?> 