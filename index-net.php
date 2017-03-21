<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>NeuralNetwork</title>
<style type="text/css">
	.network{
		border: 1px solid black;
		width: 300px;
		display: inline-block;
		margin: 2px;
	}
	.layer{
		border: 1px dotted black;
	}
	.layer-name{
		text-align: center;
		font-weight: bold;
	}
	.img{
		border: 1px solid black; 
		display: inline-block;
		margin: 1px;
	}
</style>
</head>
<body>
<?php
require "./vendor/autoload.php";
use Phpml\NeuralNetwork\Network\MultilayerPerceptron;
use Phpml\NeuralNetwork\Training\Backpropagation;

require "./funct.php";

//Creating network
$networksettings = [3,3,2];
$network = new MultilayerPerceptron($networksettings);

//Training network
$training = new Backpropagation($network);
$samples = [[0,0,0],
			[0,0,1],
			[0,1,0],
			[0,1,1],
			[1,0,0],
			[1,0,1],
			[1,1,0],
			[1,1,1]];
$targets = [[0,0],[0,0],[0,1],[0,1],[1,0],[1,0],[1,1],[1,1]];

$training->train(
	$samples,
	$targets,
	$desiredError = 0.01,
	$maxIteraions = 50000
);

//Select input
$input = [0,1,0];
$network->setInput($input);

//IMAGE outut
$imgxshift = 300;
$imgyshift = 80;
$imgsize = genimg($network, $networksettings, $input, $imgxshift, $imgyshift);
	
//TABLE output
gentable($network);

//IMAGELAYERS (picture of layers)
//$imglaysize = genimglay($network, $networksettings, $input, 5);

?>
<div class="img" style="width:<?php echo $imgsize[0];?>px; height:<?php echo $imgsize[1];?>px; background:#eee url(sсh<?php echo implode('',$input);?>.png) no-repeat;"></div>

<!--<img class="img" style="width:<?php echo $imglaysize[0]*10;?>px; height:<?php echo $imglaysize[1]*10;?>px; image-rendering:pixelated;" src="lay<?php echo implode('',$input);?>.png">-->

</body>
</html>