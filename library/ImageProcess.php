<?php
class ImageProcess{
    public static function redimencion($origem,$destino,$maxlargura,$maxaltura,$qualidade){
		    if (file_exists($origem)){
            	$info = getimagesize($origem);
				if (!$info) return;

				$largura = $info[0];
				$altura = $info[1];
				$tipo = $info[2];

            	if($altura>$largura){
            		$diferenca=$altura/$maxaltura;
            		$maxlargura=round($largura/$diferenca, 0, PHP_ROUND_HALF_UP);
            	}
            	else{
            		$diferenca=$largura/$maxlargura;
            		$maxaltura=round($altura/$diferenca, 0, PHP_ROUND_HALF_UP);
            	}
            	
            	$image_p = imagecreatetruecolor($maxlargura,$maxaltura);
				$image_orig = false;

				switch ($tipo) {
					case IMAGETYPE_JPEG:
						$image_orig = imagecreatefromjpeg($origem);
						break;
					case IMAGETYPE_PNG:
						$image_orig = imagecreatefrompng($origem);
						imagealphablending($image_p, false);
						imagesavealpha($image_p, true);
						break;
					case IMAGETYPE_GIF:
						$image_orig = imagecreatefromgif($origem);
						break;
				}

				if (!$image_orig) {
					if($image_p) imagedestroy($image_p);
					return;
				}

            	imagecopyresampled($image_p, $image_orig, 0, 0, 0, 0,  $maxlargura, $maxaltura, $largura, $altura);
            	
				$ext_dest = strtolower(pathinfo($destino, PATHINFO_EXTENSION));
				
				if ($ext_dest == 'png') {
					imagepng($image_p, $destino);
				} elseif ($ext_dest == 'gif') {
					imagegif($image_p, $destino);
				} else {
					imagejpeg($image_p, $destino, $qualidade);
				}

            	imagedestroy($image_p);
            	imagedestroy($image_orig);
            }
        }
}