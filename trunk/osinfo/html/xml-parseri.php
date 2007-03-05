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

    // listan tunnistin
    private $_lista = "lista";

    public function __construct($tiedoston_nimi)
    {
	if(file_exists($tiedoston_nimi))
	{
		$this->_parsittava_tiedosto = $tiedoston_nimi;
		$this->lue_tiedoston_sisalto();
	
		$this->_tietotaulu = $this->etsi_tagi("", 0);
	}
	else
	{
		print "<p>File " . $tiedoston_nimi . " not found</p>";
	}
    }

    private function lue_tiedoston_sisalto()
    {
	$fp = fopen($this->_parsittava_tiedosto, "r");
	$this->_tiedoston_sisalto = "";

	while(false !== ($char = fgetc($fp))) 
		$this->_tiedoston_sisalto .= $char;
    }

    public function nayta_tietotaulu()
    {
	print "<p>--------- KOKO TIETOTAULU:---------</p>";
	print "<pre>";
	print_r($this->_tietotaulu);
	print "</pre>";
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
				     if($tagi[$j] != " " &&
				   	ord($tagi[$j]) != 9 && ord($tagi[$j]) != 10 &&
				   	ord($tagi[$j]) != 13) $nimi .= $tagi[$j];
				     else break;

				     $i = $j;
				}
			  }

			  $temp_atrb_nimi = "";
			  $temp_atrb_arvo = "";
			
			  for($j=$i+2; $j < strlen($tagi); $j++)
			  {	
				if($j >= strlen($tagi)-1) break;
				
			        if($tagi[$j] != "=" && $tagi[$j] != " " &&
				   	ord($tagi[$j]) != 9 && ord($tagi[$j]) != 10 &&
				   	ord($tagi[$j]) != 13)
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
		return array($this->_tyyppi => $tyyppi, $this->_nimi => $nimi, 
				$this->_parametrit => $parametrit);
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

    public function hae_arvot($kanta, $attribuutit)
    {
	  $this->hae_array($this->_tietotaulu, $kanta, $esitettava_array);
	  $this->esita_tiedot($esitettava_array, $attribuutit);
    }

    private function esita_tiedot($array, $tarkenne = "")
    {
	print "<h1>" . $array[$this->_nimi] . "</h1>";

	$this->_attribuutti_avattu = false;
	$this->_attribuutti_laskuri = 0;

	if(is_array($array))
	{
		if($array[$this->_lista] == "true") $listatyyppi = true;
		else $listatyyppi = false;

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

	if(is_array($array[$this->_parametrit]))
	{
		$temp_array = $array[$this->_parametrit];
		if($temp_array[$this->_lista] == "true")
			$listatyyppi = true;
	}

	//$listatyyppi = true;

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
				
						if($attribuuttilaskuri > 0 && 
						$attribuuttilaskuri % $this->_attribuutteja_rivilla == 0) 
							print "</tr><tr>";
						
						print "<td valign=\"top\" class=\"attribuutti_td\" style=\"width: " . 100 / $this->_attribuutteja_rivilla . "%\">";
		
						$this->esita_attribuutit($array, $array);

						$attribuuttilaskuri++;
		
						print "</td>";
		
						break;
					}
					else
					{
						if(!$attribuutti_avattu) 
						{
							print "<table style=\"width: 100%\" border=\"1\">";
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
				if($avain != $this->_tyyppi && !is_array($avain) && !is_array($arvo) && $avain!= $this->_lista)
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

						print "<div class=\"kentta\">";
						print "<h2>" . $arvo . "</h2>";

						$div_avattu = true;
					}
					else
					{
						print "<b>" . $avain . ":</b> " . $arvo . "<br />";
					}
				}

				$this->esita_sisatiedot($arvo, $avain);	
				
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
	foreach($array as $avain => $arvo)
	{
		if($avain === $this->_parametrit)
			if(!$listatyyppi)
				print "<h3>" . $arvo['name'] . "</h3>";
			else 
				print "<td><b>" . $arvo['name'] . "</b></td>";

		$this->esita_sisatiedot($arvo, $avain, $listatyyppi);		
	}
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
	{		
		print "</table>";
	}
    }

    private function esita_sisatiedot($array, $avain, $listatyyppi = false)
    {
	$sisalto = 0;
	$desc = false;

	if(is_array($array) && $avain !== $this->_parametrit)
	{
		$edellinen_arvo = "";

		foreach($array as $avain2 => $arvo2)
		{
			if(!is_array($arvo2))
			{
				if($sisalto == 0) $sisalto = 1;

				if(!$listatyyppi)
				{
					if($edellinen_arvo == "description")
					{
						$desc = true;
						print "<p><i>" . $arvo2 . "</i></p>";
					}
					else
						if($avain2 == $this->_sisalto)
						{
							print "<p>" . $arvo2 . "</p>";
							$sisalto = 2;
						}
				}
				else
				{
					if($edellinen_arvo == "description")
					{
						$desc = true;
						print "<td><i>" . $arvo2 . "</i></td>";
					}
					else
						if($avain2 == $this->_sisalto)
						{
							$sisalto = 2;
							print "<td>" . $arvo2 . "</td>";
						}
				}
				if($listatyyppi) print $sisalto;
				$edellinen_arvo = $arvo2;
			}
		}
	}
	//if($listatyyppi) print "loppu";
	if($desc && $sisalto == 1 && $listatyyppi)
		print "jep";		
//print "<td>tyhjä</td>";
    }

    private function hae_array($array, $etsittava, &$pal_array)
    {
   	foreach($array as $avain => $arvo)
	{
		if($avain === $this->_nimi && ($avain == $etsittava || $arvo == $etsittava))
			$pal_array = $array;
		else
			if(is_array($arvo)) $this->hae_array($arvo, $etsittava, $pal_array);
	}
    }
}

?>
