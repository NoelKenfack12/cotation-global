<?php
/**
*
* @ Copyright 2016 Africexplorer
*/
namespace App\Service\AfPdf;
use Fpdf\Fpdf;

class PDFProduit extends Fpdf
{
	
// En-tête
function Header()
{
    
}

// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    // Police Arial italique 8
	// $this->SetFont('Arial','I',8);
	// $this->SetY(-10);
	// $this->SetX(10);
    // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0);
}

public function myheader($num_date,$nom_client,$titrecommande)
{
	// Logo
    $this->Image(__DIR__.'/../../../../web/template/images/facture/logom.png',6,2,40);
    // Police Arial gras 15
    $this->SetFont('Arial','',9);
    $this->SetY(11);
	$this->SetX(10);
    $this->Cell(30,10,'30020 Yaound�, Carrefour Kameni, Biyem-assi',0,1);
	$this->SetY(16);
	$this->SetX(10);
    $this->Cell(60,10,'Commerce G�n�ral & Prestation de service',0,1);
	$this->SetY(21);
	$this->SetX(10);
    $this->Cell(50,10,'T�l: (00237) 693839823 . 680288416 / Internet: www.market.afhunt.com/',0,1);
	
	$this->SetFont('Arial','B',12);
	$this->SetY(11);
	$this->SetX(130);
	$this->Cell(60,10,$titrecommande,0,1);
	
	$this->SetFont('Arial','',9);
	$this->SetY(16);
	$this->SetX(130);
	$this->Cell(60,10,$num_date,0,1);
	
	$this->SetY(21);
	$this->SetX(130);
	$this->Cell(60,10,'Par: '.$nom_client,0,1);
	
    // Saut de ligne
    $this->Ln(1);
}


function headerdescriptionLivreurMobile()
{
	$this->SetFont('Times','B',12);
	$this->SetY(33);
	$this->SetX(10);
	$this->Cell(20,10,'R�f�rence',1,1);
	
	$this->SetY(33);
	$this->SetX(30);
	$this->Cell(80,10,'D�signation',1,1);
	
	$this->SetY(33);
	$this->SetX(110);
	$this->Cell(40,10,'Prix D\'achat (XAF)',1,1);
	
	$this->SetY(33);
	$this->SetX(150);
	$this->Cell(50,10,'Prix de Vente (XAF)',1,1);
	
	$this->SetFont('Times','',12);
}


function addProductLivreurMobile($reference, $designation, $achat, $vente)
{
	//statistique de la commande
	
	$this->SetFont('Times','B',12);
	
	$y = $this->GetY();
	$this->SetY($y);
	$this->SetX(10);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(20,6,$reference,1,'L',0);
	
	$this->SetY($y);
	$this->SetX(30);
	$this->SetFillColor(0, 198, 215);
	$this->Cell(80,6,$designation,1,0);
	
	$this->SetY($y);
	$this->SetX(110);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(40,6,$achat,1,'L',0);

	$this->SetY($y);
	$this->SetX(150);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(50,6,$vente,1,'L',0);
	
	$this->SetFont('Times','',12);
}

function statistique($pluscouteux, $moinscouteux,$pluscommander, $ville, $quartier, $tel, $tel2,$page,$client,$admin,$caisse)
{
	//statistique de la commande
	$this->SetFont('Times','B',12);
	$y = $this->getY();
	$this->SetY($y);
	$this->SetX(10);
	$this->Cell(60,10,'Indicateurs',0,1);
	
	$this->SetY($y+5);
	$this->SetX(10);
	$this->Cell(60,10,'..............',0,1);
	
	$this->SetY($y);
	$this->SetX(90);
	$this->Cell(60,10,'Plus couteux',0,1);
	
	$this->SetY($y+5);
	$this->SetX(90);
	$this->Cell(60,10,$pluscouteux,0,1);
	
	$this->SetY($y);
	$this->SetX(130);
	$this->Cell(60,10,'Moins co�teux',0,1);
	
	$this->SetY($y+5);
	$this->SetX(130);
	$this->Cell(60,10,$moinscouteux,0,1);
	
	$this->SetY($y);
	$this->SetX(170);
	$this->Cell(60,10,'Plus Commande',0,1);
	
	$this->SetFont('Times','I',12);
	$this->SetY($y+5);
	$this->SetX(170);
	$this->Cell(60,10,$pluscommander,0,1);
	
	
	
	$this->SetFont('Times','I',12);
	$this->SetY($y+10);
	$this->SetX(10);
	$this->Cell(60,10,$ville.', '.$quartier,0,1);
	
	$this->SetY($y+10);
	$this->SetX(130);
	$this->Cell(60,10,$tel,0,1);
	
	$this->SetY($y+10);
	$this->SetX(170);
	$this->Cell(60,10,$tel2,0,1);
	
	$this->SetFont('Arial','I',8);
	$this->SetY($y+20);
	$this->SetX(60);
	$this->Cell(60,10,'Grille des prix officiels dans les espaces AFH Market',0,1);
	
	$this->SetFont('Arial','I',8);
	$this->SetY($y+25);
	$this->SetX(10);
	$this->Cell(60,10,'Administration',0,1);
	
	$this->SetFont('Arial','B',8);
	$this->SetY($y+30);
	$this->SetX(10);
	$this->Cell(60,10,$client,0,1);
	
	$this->SetFont('Arial','I',8);
	$this->SetY($y+25);
	$this->SetX(170);
	$this->Cell(60,10,$caisse,0,1);
	
	$this->SetFont('Arial','B',8);
	$this->SetY($y+30);
	$this->SetX(170);
	$this->Cell(60,10,$admin,0,1);
	
	$this->SetFont('Arial','i',8);
	$this->SetY($y+40);
	$this->SetX(10);
	$this->Cell(80,10,'Page '.$page.'/'.$page,0,1);
	
	$this->SetY($y+40);
	$this->SetX(140);
	$this->Cell(80,10,'Propuls�e par: www.afhunt.com',0,1,'C');
}

public function getPDFHeight($nbline)
{
	$hauteur = 121;
	if($nbline == 1)
	{
		$hauteur = 121;
	}else if($nbline == 2)
	{
		$hauteur = 126;
	}else if($nbline == 3)
	{
		$hauteur = 132;
	}else if($nbline == 4)
	{
		$hauteur = 138;
	}else if($nbline == 5)
	{
		$hauteur = 145;
	}else if($nbline == 6)
	{
		$hauteur = 152;
	}else if($nbline == 7)
	{
		$hauteur = 158;
	}else if($nbline == 8)
	{
		$hauteur = 168;
	}else if($nbline == 9)
	{
		$hauteur = 169;
	}else if($nbline == 10)
	{
		$hauteur = 175;
	}else{
		$hauteur = 175;
	}
}

}

?>