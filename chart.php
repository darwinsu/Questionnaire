<?php
session_start();
include_once ('application/library/jpgraph/jpgraph.php');

class chart
{

    public static function horizbarex($val=0)
    {
        include_once ('application/library/jpgraph/jpgraph_bar.php');
        $datay=array($val);

        $graph = new Graph(100,14);
        $graph->SetImgFormat('jpeg');
        $graph->SetScale('textlin',0,100);
        $graph->xaxis->Hide();
        $graph->yaxis->Hide();

        $graph->Set90AndMargin(1,1,-10,-8);

        $bplot = new BarPlot($datay);

        $bplot->SetFillColor('orange');
        $bplot->SetYMin(0);

        $graph->Add($bplot);

        $graph->Stroke();
    }

    public static function pie($subid)
    {
        $sub = $_SESSION['_rep']['subjects'][$subid];
        include_once ('application/library/jpgraph/jpgraph_pie.php');
        include_once ('application/library/jpgraph/jpgraph_pie3d.php');

        $data = $sub['items']['scale'];

        $graph = new PieGraph(900,300);
        $graph->SetImgFormat('jpeg');
        $graph->SetShadow();
        $graph->legend->SetFont(FF_SIMSUN);

        $graph->title->Set($sub['title']);
        $graph->title->SetFont(FF_SIMSUN,FS_BOLD);

        $p1 = new PiePlot3D($data);
        //$p1->SetSize(0.5);
        //$p1->SetCenter(0.45);
        $p1->SetLegends($sub['items']['s_answer']);

        $graph->Add($p1);
        $graph->Stroke();
    }

    public static function bar($subid)
    {
        $sub = $_SESSION['_rep']['subjects'][$subid];
        include_once ('application/library/jpgraph/jpgraph_bar.php');

        $databary=$sub['items']['itemcount'];

        $graph = new Graph(900,300);
        $graph->SetImgFormat('jpeg');
        $graph->SetShadow();
        $graph->legend->SetFont(FF_SIMSUN);

        $graph->SetScale("textlin",0,$_SESSION['_rep']['djcount']);

        $graph->title->Set($sub['title']);

        $graph->title->SetFont(FF_SIMSUN,FS_BOLD);

        $b1 = new BarPlot($databary);
        //$b1->SetLegend("Temperature");

        $graph->Add($b1);
        $graph->Stroke();
    }

}

$actionArray = array(
    'horizbarex',
    'pie',
    'bar',
    'bar2'
);
$defaultAction = 'horizbarex';

$currentAction = !in_array($_GET['action'],$actionArray) ? $defaultAction : $_GET['action'];

chart::$currentAction((int)$_GET['val']);

?>