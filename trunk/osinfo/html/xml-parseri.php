<?php

class xmlparseri
{
	private $_parsittava_tiedosto;
	private $_tiedoston_sisalto;
	private $_tietotaulu = array();
	private $_attribuutteja_rivilla = 3;

	// "koodit"
	private $_nimi = "--nimi--";
	private $_parametrit = "--parametrit--";
	private $_tyyppi = "--tyyppi--";
	private $_sisalto = "--sisalto--";

	private $_virheet = array();

	public function __construct()
	{

	}

	public function parsi_tiedosto($tiedoston_nimi)
	{
		$this->_virheet = array();

		if(file_exists($tiedoston_nimi))
		{
			$this->_parsittava_tiedosto = $tiedoston_nimi;
			$this->lue_tiedoston_sisalto();
		
			$this->_tietotaulu = $this->etsi_tagi("", 0);
		}
		else
			print "<p class=\"valitus\">File " . $tiedoston_nimi . " not found</p>";
	}

	public function hae_arvot($kanta, $attribuutit = "")
	{
		$this->hae_array($this->_tietotaulu, $kanta, $esitettava_array);
		$this->esita_tiedot($esitettava_array, $attribuutit);
	}

	public function hae_tieto($ryhma, $nimi)
	{
		return $this->hae_array2($this->_tietotaulu, $ryhma, $nimi);
	}

	public function nayta_tietotaulu()
	{
		print "<p>--------- KOKO TIETOTAULU:---------</p>";
		print "<pre>";
		print_r($this->_tietotaulu);
		print "</pre>";
	}

	private function lue_tiedoston_sisalto()
	{
		$fp = fopen($this->_parsittava_tiedosto, "r");

		$this->_tiedoston_sisalto = "";

		while(false !== ($char = fgetc($fp)))
			$this->_tiedoston_sisalto .= $char;
	}

	private function etsi_tagi($etsittava, $indeksi, $taso = -1)
	{
		$taulu = array();
		$taso++;

		for($i=$indeksi; $i < strlen($this->_tiedoston_sisalto); $i++)
		{
			if($this->_tiedoston_sisalto[$i] == "<")
			{
				$tagi = "";
		
				for($j=$i+1; $j < strlen($this->_tiedoston_sisalto); $j++)
				{
					if($this->_tiedoston_sisalto[$j] == "<") break;
					if($this->_tiedoston_sisalto[$j] != ">")
						$tagi .= $this->_tiedoston_sisalto[$j];
					else break;
				}
			
				$tietue = $this->tarkista_tagi($tagi);
		
				switch($tietue[$this->_tyyppi])
				{
				case 1:
					array_push($taulu, $tietue);
					break;
				case 2:
					$paluuarvot = $this->etsi_tagi($tietue[$this->_nimi], $j+1, $taso);
					
					if($paluuarvot['indeksi'] > $i)
						$i = $paluuarvot['indeksi'];

					$this->array_lisaa_samaan($tietue, $paluuarvot['taulu']);
					$sisalto = "";
			
					for($j += 1; $j < strlen($this->_tiedoston_sisalto); $j++)
					{
						$char = ord($this->_tiedoston_sisalto[$j]);
		
						if($char != 10 && $char != 13 && $char != 9 && 				$this->_tiedoston_sisalto[$j] != "<")
							$sisalto .= $this->_tiedoston_sisalto[$j];
						elseif($this->_tiedoston_sisalto[$j] == "<") break;
					}
		
					if(!empty($sisalto))
					{
						$temp_array = array($this->_sisalto => $sisalto);
						$this->array_lisaa_samaan($tietue, $temp_array);
					}
		
					array_push($taulu, $tietue);
					break;
				case 3:
					if($etsittava != "")
					{
						if($tietue[$this->_nimi] == $etsittava)
							return array("taulu" => $taulu, "indeksi" => $i);
						else
						{
							array_push($this->_virheet, "found &lt;/" . $tietue[$this->_nimi] . "&gt;, " . "&lt;/" . $etsittava . "&gt; expected");
		
							$taulu[$this->_nimi] == $etsittava;
							
							return array("taulu" => $taulu, "indeksi" => $i);
						}
					}
		
					break;
				case 4:
					array_push($taulu, $tietue);
					break;
				default:
					break;
				}
			}
		}
		return $taulu;
	}

	private function tarkista_tagi($tagi)
	{
		$tyyppi = 0;
		$nimi = "";
		$parametrit = array();
		$parametritagi = false;
		
		// tyypit: 1 = tiedoston tyyppitagi
		//	   2 = aloitustagi
		//	   3 = lopetustagi
		//	   4 = yksinäinen tagi
	
		for($i=strlen($tagi)-1; $i>0; $i--)
		{
			if($tagi[$i] == "/" && $i > 0)
				$parametritagi = true;
		}
	
		if($tagi[0] == "!" || $tagi[0] == "?")
			$tyyppi = 1;
		else
		{
			if($tagi[0] == "/")
			{
				$tyyppi = 3;
		
				for($i=0; $i < strlen($tagi); $i++)
					if($tagi[$i] != "/") $nimi .= $tagi[$i];
			}
			else
			{
				if($parametritagi) $tyyppi = 4;
				else $tyyppi = 2;
		
				for($i=0; $i < strlen($tagi); $i++)
				{
					if($i == 0)
					{
						for($j=$i; $j < strlen($tagi); $j++)
						{
							if($tagi[$j] != " " && ord($tagi[$j]) != 9 && ord($tagi[$j]) != 10 && ord($tagi[$j]) != 13)
								$nimi .= $tagi[$j];
							else break;
		
							$i = $j;
						}
					}
		
					$temp_atrb_nimi = "";
					$temp_atrb_arvo = "";
					
					for($j=$i+2; $j < strlen($tagi); $j++)
					{	
						if($j >= strlen($tagi)-1) break;
						
						if($tagi[$j] != "=" && $tagi[$j] != " " && ord($tagi[$j]) != 9 && ord($tagi[$j]) != 10 && ord($tagi[$j]) != 13)
							$temp_atrb_nimi .= $tagi[$j];

						elseif($tagi[$j] == "=")
						{
							if($tagi[$j+1] == "\"") $indeksi = $j+2;
							else $indeksi = $j+1;
			
							for($k=$indeksi; $k < strlen($tagi); $k++)
							{
								if($tagi[$k] != "\"")
									$temp_atrb_arvo .= $tagi[$k];
								else
								{
									$j = $k - 1;
									break;
								}
							}
			
							$i = $j;
							break;
						}
						$i = $j;
					}
		
					if($temp_atrb_nimi != "")
					{
						$temp_array = array($temp_atrb_nimi => $temp_atrb_arvo);
						$this->array_lisaa_samaan($parametrit, $temp_array);
					}
				}
			}
		}
		
		if(empty($parametrit))
			return array($this->_tyyppi => $tyyppi, $this->_nimi => $nimi);
		else
			return array($this->_tyyppi => $tyyppi, $this->_nimi => $nimi, $this->_parametrit => $parametrit);
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

	private function esita_tiedot($array, $tarkenne = "")
	{
		print "<table style=\"width: 100%\">
			<tr>
			<td><h2>" . $this->parsi_nimi($this->hae_tieto("computer", "hostname")) . "</h2></td>
			<td>";
	
		$this->nayta_quick_jump_valikko();
	
		print "</td><td style=\"text-align: right\"><p>Script version: <i>" . $this->hae_tieto("script", "version") . "</i><br />" .
			"Scanning date: <i>" . $this->hae_tieto("scanning", "date") . "</i></p></td>";
	
		print "</td></tr></table>";
	
		if(sizeof($this->_virheet) > 0)
		{
			print "<p class=\"valitus\">";
	
			foreach($this->_virheet as $avain => $virhe)
				print "Parse error: " . $virhe . "<br />";
	
			print "</p>";
		}
	
		$this->_attribuutti_avattu = false;
		$this->_attribuutti_laskuri = 0;
	
		if(is_array($array))
		{
			$listatyyppi = false;
	
			// käy läpi päätaulun kannasta lähtien
			foreach($array as $avain => $arvo)
			{
				if(is_array($arvo)) 
				{
					if($tarkenne != "") $this->esita_alitiedot($arvo, $listatyyppi, false, $tarkenne);
					else $this->esita_alitiedot($arvo, $listatyyppi, false);
				}
			}
		}
	}

	private function esita_alitiedot($array, $listatyyppi = false, $attribuutti_avattu = false, $tarkenne = "", $paasy_alitauluihin = false, $attribuuttilaskuri = 0)
	{
		$div_avattu = false;
		$attribuutti_suljettu = false;
		$atrb = false;
		$listatyyppi = true;
	
		foreach($array as $avain => $arvo)
		{	
			if($tarkenne != "" && $avain != $this->_tyyppi && $arvo == $tarkenne)
				$paasy_alitauluihin = true;
			elseif($tarkenne != "" && is_array($arvo)) 
				$div = $this->esita_alitiedot($arvo, $listatyyppi, $attribuutti_avattu, $tarkenne);
	
			if($paasy_alitauluihin || $tarkenne == "")
			{	
				if($arvo == "attribute")
				{
					if(is_array($array))
					{
						if(!$listatyyppi)
						{
							if(!$attribuutti_avattu) 
							{
								print "<table class=\"attribuutti\" cellspacing=\"10\"><tr>";
								$attribuutti_avattu = true;
							}
					
							if($attribuuttilaskuri > 0 && $attribuuttilaskuri % $this->_attribuutteja_rivilla == 0)
								print "</tr><tr>";
							
							print "<td valign=\"top\" class=\"attribuutti_td\" style=\"width: " . round(100 / $this->_attribuutteja_rivilla, 1) . "%\">";
			
							$this->esita_attribuutit($array, $array);
	
							$attribuuttilaskuri++;
			
							print "</td>";
			
							break;
						}
						else
						{
							if(!$attribuutti_avattu)
							{
								print "<table class=\"lista_attribuutti\">";
								$attribuutti_avattu = true;
							}
	
							print "<tr>";
			
							$this->esita_attribuutit($array, $array, $listatyyppi);
			
							print "</tr>";
			
							break;
						}
					}
				}
				else
				{
					if($avain != $this->_tyyppi && !is_array($avain) && !is_array($arvo) && $arvo != "scanning" && $arvo != "script")
					{
						if($avain == $this->_nimi)
						{
							if($attribuutti_avattu) 
							{
								$attribuutti_avattu = false;
								$attribuutti_suljettu = true;
								$this->sulje_attribuutti($listatyyppi, $attribuuttilaskuri);
								$attribuuttilaskuri = 0;
							}
	
							print "<div class=\"kentta\" id=\"" . $arvo . "\">";
							print "<h2>" . $this->parsi_nimi($arvo) . "</h2>";
	
							$div_avattu = true;
						}
						else
							print "<b>" . $avain . ":</b> " . $arvo . "<br />";
					}
					elseif($arvo == "scanning" || $arvo == "script") break;
	
					if($arvo[$this->_nimi] == "value")
						$this->esita_sisatiedot($listatyyppi, false, $arvo[$this->_sisalto]);
					
					if(is_array($arvo) && $arvo[$this->_nimi] != "value")
					{
						if($tarkenne != "") 
							$atrb = $this->esita_alitiedot($arvo, $listatyyppi, $attribuutti_avattu, $tarkenne, true, $attribuuttilaskuri);
						else 
							$atrb = $this->esita_alitiedot($arvo, $listatyyppi, $attribuutti_avattu, "", false, $attribuuttilaskuri);
								
						if($atrb) $attribuuttilaskuri++;
					}
				}
				if($atrb) { $attribuutti_avattu = true; $attribuutti_suljettu = true; }
			}
		}
	
		if($atrb && $attribuutti_suljettu) 
		{ 
			$this->sulje_attribuutti($listatyyppi, $attribuuttilaskuri);
			$attribuutti_avattu = false;
		}
	
		if($div_avattu) print "</div>";
	
		if($attribuutti_avattu && !$attribuutti_suljettu) return true;
		else return false;
	}

	private function esita_attribuutit(&$palautus_array, $array, $listatyyppi = false)
	{
		$description_str = "";
		$code_str = "";
		$name_str = "";
		$values = array();
	
		foreach($array as $avain => $arvo)
		{
			if($avain === $this->_parametrit)
			{
				if($arvo['code'] != "") $code_str = $arvo['code'];
				if($arvo['name'] != "") $name_str = $arvo['name'];
			}
	
			$paluutaulu = $this->hae_sisatiedot($arvo, $avain);

			if($paluutaulu['description'] != "") 
				$description_str = $paluutaulu['description'];
	
			if($paluutaulu['value'] != "")
				array_push($values, $paluutaulu['value']);
		}
	
		if($code_str != "")
			$this->esita_sisatiedot($listatyyppi, $code_str, $values);
		else
		{
			if($description_str != "")
				$this->esita_sisatiedot($listatyyppi, $description_str, $values);
			else
			{
				if($name_str != "")
					$this->esita_sisatiedot($listatyyppi, $name_str, $values);
			}
		}
	}

	private function esita_sisatiedot($listatyyppi, $eka_kentta = false, $arvot)
	{
		if($listatyyppi)
		{
			if(is_array($arvot))
			{
				if($eka_kentta)
					print "<td valign=\"top\" class=\"lista_description\"><b>" . $eka_kentta . "</b></td>";
		
				print "<td class=\"lista_value\">";
	
				if(sizeof($arvot) > 0)
				{
					print "<table class=\"value\">";
		
					foreach($arvot as $avain => $arvo)
						print "<tr><td>" . $arvo . "</td></tr>";
		
					print "</table>";
				}
				else print "&nbsp;";
			}
			else print "<table><tr><td>" . $arvot . "</td></tr></table>";
	
			if($eka_kentta) print "</td>";
		}
		else
		{
			if(is_array($arvot))
			{
				if($eka_kentta) print "<h3>" . $eka_kentta . "</h3>";
		
				foreach($arvot as $avain => $arvo)
					print "<p>" . $arvo . "</p>";
		
			}
			else print "<p>" . $arvot . "</p>";
	
			if($eka_kentta) print "</td>";
		}
	}

	private function hae_sisatiedot($array, $avain)
	{
		$description = "";
		$value = "";
	
		if(is_array($array) && $avain !== $this->_parametrit)
		{
			$edellinen_arvo = "";
	
			foreach($array as $avain2 => $arvo2)
			{
				if(!is_array($arvo2))
				{
					if($edellinen_arvo == "description")
						$description = $arvo2;
					elseif($edellinen_arvo == "value")
					{
						if($avain2 == $this->_sisalto)
							$value = $arvo2;
					}
	
					$edellinen_arvo = $arvo2;
				}
			}
		}
	
		$palautus = array("value" => $value, "description" => $description);
	
		return $palautus;
	}

	private function sulje_attribuutti($listatyyppi = false, $attribuuttilaskuri = 0)
	{
		if(!$listatyyppi)
		{
			for(; $attribuuttilaskuri % $this->_attribuutteja_rivilla != 0; $attribuuttilaskuri++)
				print "<td>&nbsp;</td>";
	
			print "</tr></table>";
		}
		else
			print "</table>";
	}

	private function hae_array($array, $etsittava, &$pal_array)
	{
		foreach($array as $avain => $arvo)
		{
			if($avain === $this->_nimi && ($avain == $etsittava || $arvo == $etsittava))
			{
				$pal_array = $array;
				break;
			}
			else
				if(is_array($arvo)) $this->hae_array($arvo, $etsittava, $pal_array);
		}
	}

	private function parsi_nimi($nimi)
	{
		$palautus = "";

		for($i=0; $i < strlen($nimi); $i++)
		{
			if($nimi[$i] == "_") $palautus .= " ";
			else $palautus .= $nimi[$i];
		}
		
		return ucfirst($palautus);
	}

	private function hae_array2($array, $etsittava_nimi, $etsittava_arvo)
	{
		$haettava_paikallistettu = false;

		foreach($array as $avain => $arvo)
		{
			if($haettava_paikallistettu)
			{
				$loytyi = 0;
	
				if(is_array($arvo))
					foreach($arvo as $avain2 => $arvo2)
					{
						$edellinen = "";
	
						if(is_array($arvo2))
						{
							foreach($arvo2 as $avain3 => $arvo3)
							{
								if($edellinen == "value" && $avain3 == $this->_sisalto)
									if($loytyi) return $arvo3;
	
								if($avain3 == $this->_sisalto && $arvo3 === $etsittava_arvo)
									$loytyi = 1;
	
								$edellinen = $arvo3;
							}
						}
					}
			}
	
			if($avain === $this->_nimi && $arvo == $etsittava_nimi)
			{
				$haettava_paikallistettu = true;
	
				if(is_array($array[$this->_parametrit]))
					foreach($array[$this->_parametrit] as $avain2 => $arvo2)
						if(strtolower($avain2)  == strtolower($etsittava_arvo))
							return $arvo2;
			}
			else
			{
				if(is_array($arvo))
				{
					$palautus = $this->hae_array2($arvo, $etsittava_nimi, $etsittava_arvo);

					if($palautus != "") return $palautus;
				}
			}
		}
	}

	private function hae_paataulut()
	{
		$taulut = array();
	
		foreach($this->_tietotaulu as $avain => $arvo)
			if(is_array($arvo))
				foreach($arvo as $avain2 => $arvo2)
					if(is_array($arvo2))
						foreach($arvo2 as $avain3 => $arvo3)
							if($avain3 == $this->_nimi && !is_array($arvo3))
								array_push($taulut, $arvo3);
	
		return $taulut;
	}

	private function nayta_quick_jump_valikko()
	{
		$taulut = $this->hae_paataulut();
			
		print "<script type=\"text/javascript\">
			<!--
	
			document.write(\"Quick jump:\")
	
			function loadPage(pageURL)
			{
				if(pageURL.selectedIndex > 0)
				{
					sivu = \"" . $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'] . "#\";
					location.href = sivu + pageURL.options[pageURL.selectedIndex].value;
				}
			}
	
			//-->
			</script>
			<noscript>
			This feature requires JavaScript.
			</noscript>";
	
		print "<form name=\"qj_form\">
			<select name=\"quick_jump\">";
	
		foreach($taulut as $avain => $arvo)
			print "<option name=\"quick_jump_header\" onclick=\"loadPage(document.qj_form.quick_jump); \" value=\"" . $arvo . "\">" . $this->parsi_nimi($arvo) . "</option>";
	
		print "</select>
			</form>";
	}
}

?>
