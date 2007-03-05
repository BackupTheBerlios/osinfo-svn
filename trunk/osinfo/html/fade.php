<?php

$uusi_kuva = new Haivytyskuva();

class Haivytyskuva
{
	public function __construct()
    	{
		$this->nayta_haivytyskuva($_GET['w'], $_GET['h'], $_GET['r'], $_GET['g'], $_GET['b']);
	}

	private function nayta_haivytyskuva($leveys, $korkeus, $r, $g, $b)
	{
		if($leveys > 10) $leveys = 10;
		if($korkeus > 300) $korkeus = 300;

		//header("Content-type: image/png");
		$kuva = @imagecreate($leveys, $korkeus);
		
		for ($i = 0; $i < 255; $i++) 
			$vari[$i] = @imagecolorallocatealpha($kuva, $r, $g, $b, $i);
		
		for ($i = 0; $i < $leveys; $i++)
			for ($j = 0; $j < $korkeus; $j++)
				@imagesetpixel($kuva, $i, $j, $vari[(255/$korkeus) * $j/2]);
		
		@imagepng($kuva);
		@imagedestroy($kuva);
	}
}

?>