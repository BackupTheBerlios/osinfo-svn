<?php

// luodaan Index-olio. Input-parametrit:

// $xmlkansio, kansio jossa kaikki xml-tiedostot sijaitsevat
// $r, divien taustavärin punaisen arvo
// $g, divien taustavärin vihreän arvo
// $b, divien taustavärin sinisen arvo
// $fade_korkeus, sivuframen alla olevan fade-kuvan korkeus
// $clienteja_rivilla, index viewin clienttien lukumäärä rivillä

$index = new Index("hosts", 222, 197, 148, 150, 3);

$index->tulosta_alkukoodi(true);
$index->nayta_sivuframe();
$index->nayta_paaframe($_GET['target']);
$index->tulosta_loppukoodi();

class Index
{
	private $_xmlkansio;
	private $_xmlparseri = "xml-parseri.php";
	private $_parseri;
	private $_clientteja_rivilla;
	private $_r;
	private $_g;
	private $_b;
	private $_fade_korkeus;

	public function __construct($xmlkansio, $r, $g, $b, $fade_korkeus, $clientteja_rivilla)
	{
		// sijoitetaan input-parametrit luokan jäsenmuuttujiin

		$this->_xmlkansio = $xmlkansio;
		$this->_r = $r;
		$this->_g = $g;
		$this->_b = $b;
		$this->_fade_korkeus = $fade_korkeus;
		$this->_clientteja_rivilla = $clientteja_rivilla;

		// tarkistetaan, xmlparseri on olemassa ja luodaan siitä olio

		if(file_exists($this->_xmlparseri))
		{
			include($this->_xmlparseri);

			$this->_parseri = new xmlparseri();
		}
		else print "<p class=\"valitus\">Cannot find file \"" . $this->_xmlparseri . "\"</p>";
	}

	public function tulosta_alkukoodi($tyylit = true)
	{
		// tekee sivulle alkukoodit
	
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
		// tekee sivulle loppukoodit

		print "</body>\n";
		print "</html>";
	}
	
	private function tulosta_tyyli()
	{
		// määritetään ylimääräiset tyyliasetukset divien värille

		$varisyote = "r=" . $this->_r . "&g=" . $this->_g . "&b=" . $this->_b;

		print "<link rel=\"stylesheet\" type=\"text/css\" href=\"xmltyyli.css\" />";
		print "<style type=\"text/css\">

		
		div.SivuFrameSisalto, div.PaaFrame
		{
			background-color: rgb(" . $this->_r . ", " . $this->_g . ", " . $this->_b . ");
		}

		div.SivuFrameFade
		{
			background-image: url('fade.php?w=1&h=". $this->_fade_korkeus . "&" . $varisyote . "');
			background-repeat: repeat-x;
			background-position: top;
			height: ". $this->_fade_korkeus . "px;
		}

		</style>";
	}

	// Listaa xml-tiedostokansion sisällön sivu- ja pääframelle.
	// Ottaa input-parametrina tiedon, onko kyse valikosta vai index viewistä
	// $valikko = true --> valikko
	// $valikko = false --> index view
	
	private function listaa_tiedostot($valikko = false)
	{
		if(file_exists($this->_xmlkansio))
		{
			$kansio = opendir($this->_xmlkansio);

			$xml_array = array();

			for($i=0; $tiedosto = readdir($kansio); $i++)
			{
				// selvittää tiedostopäätteen

				$p_array = explode(".", $tiedosto);
				$paate = $p_array[sizeof($p_array)-1];

				if($i > 1 && ($paate == "xml"))
				{
					// parsii käsiteltävän xml-tiedoston

					$this->_parseri->parsi_tiedosto($this->_xmlkansio . "/" . $tiedosto);

					// hakee parserista halutut tiedot taulukkoon

					// hakualgoritmi etsii tiedon sen yläotsikon kautta, joten funktiolle
					// pitää antaa kaksi parametria, sen lähin yläotsikko ja haettava tieto

					if($valikko)
					{
						// nämä arvot haetaan valikkoon

						$temp_array = array("tiedosto" => $tiedosto,
						"hostname" => $this->_parseri->hae_tieto("computer", "hostname"),
						"domain" => $this->_parseri->hae_tieto("computer", "domain"),
						"profile" => $this->_parseri->hae_tieto("computer", "profile"),
						"os" => $this->_parseri->hae_tieto("computer", "os"),
						"cpu" => $this->_parseri->hae_tieto("computer", "cpu"));
					}
					else
					{
						// nämä arvot haetaan index viewiin

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

					// lisää tiedoston tiedot päätaulukkoon ja jatkaa tiedostojen läpikäymistä
					array_push($xml_array, $temp_array);
				}
			}

			// lajittelee koneet domaineittain

			$domainit = $this->lajittele_array($xml_array, "domain");
			$profilet = array();

			// lajittelee koneet (alustavasti) profiileittain

			foreach($domainit as $domain => $arvo)
			{
				$temp_array = array($domain => $this->lajittele_array($arvo, "profile"));
				$this->array_lisaa_samaan($profilet, $temp_array);
			}

			ksort($profilet);

			if($valikko)
			{
				foreach($profilet as $domain => $profiles)
				{
					// lajittelee koneet profiileittain
					ksort($profiles);

					// näyttää tiedot valikossa domaineittain

					print "<ul class=\"hostit\"><li>Domain: <i>" . $domain . "</i>";

					foreach($profiles as $profile => $hostnames)
					{
						// näyttää tiedot valikossa profiileittain

						print "<ul class=\"hostit\"><li>Profile: <i>" . $profile . 
							"</i><ul class=\"hostit\">";

						// näyttää halutut yksityiskohtaiset konetiedot valikossa

						foreach($hostnames as $hostname => $arvot)
						{
							print "<li><b><a href=\"?target=". $arvot["tiedosto"] . "\">" .
								$arvot["hostname"] . "</a></b><ul class=\"hostit\">
								<li class=\"kuvaus\">" . $arvot["os"] . "</li>
								<li class=\"kuvaus\">" . $arvot["cpu"] . "</li>
								</ul></li>";

						}

						// sulkee profiilien tagit
						print "</ul></li></ul>";
					}

					// sulkee domainien tagit
					print "</li></ul>";
				}
			}
			else
			{
				foreach($profilet as $domain => $profiles)
				{
					// lajittelee koneet profiileittain
					ksort($profiles);

					// näyttää tiedot valikossa domaineittain
					print "<div class=\"kentta2\"><h3>Domain: <i>" . $domain . "</i>";

					foreach($profiles as $profile => $hostnames)
					{
						// näyttää tiedot valikossa profiileittain

						print "<div class=\"kentta2\"><h3>Profile: <i>" . $profile . 
							"</i><table class=\"attribuutti\" cellspacing=\"10\">";

						$laskuri = 0;

						// näyttää kaikki yksityiskohtaiset konetiedot valikossa

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

	// lajittelee taulukon halutun arvon mukaan, joka annetaan input-parametriin $nimi

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

	// yleinen taulukon käsittelyfunktio, joka php:stä itsestään puuttuu

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

	// käy läpi tiedostokansion ja palauttaa viimeksi muokatun tiedoston päivämäärän unixin aika-arvona

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

	// näyttää sivuframen sisällön
	
	public function nayta_sivuframe()
	{
		print "<div class=\"SivuFrame\">";
		print "<div class=\"SivuFrameSisalto\">";

		print "<h3>OS info</h3>
			<p><a href=\"" . $_SERVER["SCRIPT_NAME"] . "\">index view</a></p>
			<hr />";

		// listaa xml-tiedostokansion sisällön sivuframelle
		$this->listaa_tiedostot(true);
	
		print "<hr />";

		$this->paivitys();

		print "<hr />";

		print "<p>Last update:</p>";

		// hakee viimeksi päivitetyn tiedoston aika-arvon
		$viimeksi_paivitetty = $this->hae_viimeksi_paivitetty();

		if($viimeksi_paivitetty > 0)
			// näyttää viimeksi päivitetyn tiedoston päiväyksen ja ajan
			print "<p><i>" . date("F d Y H:i:s", $viimeksi_paivitetty) . "</i></p>";
	
		print "</div>";

		print "<div class=\"SivuFrameFade\"></div>";

		print "</div>";
	}
	
	// näyttää pääframen sisällön

	public function nayta_paaframe($sivu = "")
	{
		print "<div class=\"PaaFrame\">";
		
		// jos osoiterivin target-arvo on määritelty, kokeilee näyttää sivun sisällön pääframeen

		if($sivu != "")
		{
			// tarkistaa ensin, että tiedosto on olemassa
			if(file_exists($this->_xmlkansio . "/" . $sivu))
			{
				// parsii xml-tiedoston läpi
				$this->_parseri->parsi_tiedosto($this->_xmlkansio . "/" . $sivu);

				// näyttää xml:stä "osinfo" tagien sisällä olevan tiedon
				$this->_parseri->hae_arvot("osinfo");
			}
			else print "<p class=\"valitus\">File \"" . $sivu . "\" not found</p>";
		}
		else
			// jos osoiterivin target-arvoa ei ole määritelty, näyttää index viewin
			$this->nayta_index_view();

		print "</div>";
	}

	private function nayta_index_view()
	{
		print "<h3>index view</h3>";

		// listaa xml-kansion tiedostot pääframelle
		$this->listaa_tiedostot(false);
	}

	private function paivitys()
	{
		if($_POST['paivitettava_client'])
			print "<p>-- update " . $_POST['paivitettava_client'] . " --</p>";

		print "<form name=\"paivitysformi\" action=\"" . $PHP_SELF . "\" method=\"post\">
			<p>Client: 
				<input type=\"text\" name=\"paivitettava_client\" /><br /><br />
				<input type=\"submit\" value=\"Update\" />
			</p>
			</form>";
	}
}

?> 