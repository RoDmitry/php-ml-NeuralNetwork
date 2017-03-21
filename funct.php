<?php
function genimg($network, $networksettings, $input, $imgxshift, $imgyshift): array{
	$imgx = 20;
	$imgy = 20;
	$imgwidth = (count($networksettings)-1)*($imgxshift+40)+42;
	$imgheight = max($networksettings)*($imgy+$imgyshift+20)+42;
	$size[] = $imgwidth;
	$size[] = $imgheight;
	$img = imagecreatetruecolor($imgwidth, $imgheight);
	$color = imageColorAllocate($img, 255, 255, 255);
	imagefill($img, 0, 0, $color);
	imageSetThickness($img, 3);
	$network->setInput($input);
	$lay=0;
	while($lay < count($network->getLayers())){
		$nod=0;
		$imgy = 20;
		while($nod < count($network->getLayers()[$lay]->getNodes())){
			$out = $network->getLayers()[$lay]->getNodes()[$nod]->getOutput();
			if ($lay>0 && $out<1){ //strange out=1 err
				$wgt=0;
				$imgy1 = 20;
				$imgy2 = $imgy;
				$imgx2 = $imgx-20;
				$imgx1 = $imgx2-$imgxshift;
				while($wgt < count($network->getLayers()[$lay]->getNodes()[$nod]->getSynapses())){
					$synweight = $network->getLayers()[$lay]->getNodes()[$nod]->getSynapses()[$wgt]->getWeight();
					$f = tanh($synweight/10);
					if ($f<0){
						$linecolor = 255-round(-$f*255);
						$linecolor = imageColorAllocate($img, $linecolor, $linecolor, 255);
					}else if ($f>0){
						$linecolor = 255-round($f*255);
						$linecolor = imageColorAllocate($img, 255, $linecolor, $linecolor);
					}else $linecolor = imageColorAllocate($img, 255, 255, 255);
					//отрисовка линий
					imageLine($img, $imgx1, $imgy1, $imgx2, $imgy2, $linecolor);
					$imgy1 += $imgyshift+40;
					$wgt++;
				}
			}
			if ($out>0){
				$color = 255-round($out*255);
				$color = imageColorAllocate($img, $color, $color, $color);
			}else{
				$color = 255+round($out*255);
				$color = imageColorAllocate($img, $color, $color, 255);
			}
			imageFilledEllipse($img, $imgx, $imgy, 40, 40, $color);
			$color = imageColorAllocate($img, 0, 200, 0);
			if (round($out, 4) == 1 || round($out, 4) == 0) imagefttext($img, 10, 0, $imgx-3, $imgy+4, $color, './arial.ttf', round($out, 4));
			else imagefttext($img, 10, 0, $imgx-20, $imgy+4, $color, './arial.ttf', round($out, 4));
			$imgy += $imgyshift+40;
			$nod++;
		}
		$imgx += $imgxshift+40;
		$lay++;
	}
	imagePng($img, './sh'.implode('',$input).'.png');
	imageDestroy($img);
	return $size;
}

function genimglay($network, $networksettings, $input, $xmax): array{
	$imgx = 0;
	$imgy = 0;
	$imgwidth = $xmax;
	$imgheight = 0;
	for($i=0; $i < count($network->getLayers()); $i++){
		$imgheight += sqrt($networksettings[$i]) + 1;
	}
	$size[] = $imgwidth;
	$size[] = $imgheight;
	$img = imagecreatetruecolor($imgwidth, $imgheight);
	$color = imageColorAllocate($img, 255, 0, 0);
	imagefill($img, 0, 0, $color);
	imageSetThickness($img, 1);
	$lay=0;
	while($lay < count($network->getLayers())){
		$nod=0;
		$imgx = 0;
		while($nod < count($network->getLayers()[$lay]->getNodes())){
			$out = $network->getLayers()[$lay]->getNodes()[$nod]->getOutput();
			$color = 255-round($out*255);
			$color = imageColorAllocate($img, $color, $color, $color);
			imagesetpixel($img, $imgx, $imgy, $color);
			$imgx += 1;
			if ($imgx%(sqrt($networksettings[$lay]))==0){
				$imgy += 1;
				$imgx = 0;
			}
			$nod++;
		}
		$color = imageColorAllocate($img, 0, 0, 255);
		imageLine($img, 0, $imgy, $imgwidth-1, $imgy, $color);
		$imgy += 1;
		$lay++;
	}
	imagePng($img, './lay'.implode('',$input).'.png');
	imageDestroy($img);
	return $size;
}

function gentable($network){
	$lay=0;
	echo "<div class=\"network\">";
	while($lay < count($network->getLayers())){
		echo "<div class=\"layer\"><div class=\"layer-name\">Layer ".$lay."</div>";
		$nod=0;
		while($nod < count($network->getLayers()[$lay]->getNodes())){
			echo "Node ".$nod."<br>";
			$out = $network->getLayers()[$lay]->getNodes()[$nod]->getOutput();
			if ($lay>0 && $out<1){ //strange out=1 err
				$wgt=0;
				while($wgt < count($network->getLayers()[$lay]->getNodes()[$nod]->getSynapses())){
					$synweight = $network->getLayers()[$lay]->getNodes()[$nod]->getSynapses()[$wgt]->getWeight();
					echo "<i>Weight to Node ".$wgt."</i> = ".$synweight."<br>";
					$wgt++;
				}
			}
			echo "Output Node ".$nod." = <b>".$out."</b><br><br>";
			$nod++;
		}
		echo "</div>";
		$lay++;
	}
	echo "</div>";
}
?>