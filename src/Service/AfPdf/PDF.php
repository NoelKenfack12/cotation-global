<?php
/**
*
* @ Copyright 2016 Africexplorer
*/
namespace App\Service\AfPdf;
use Fpdf\Fpdf;

class PDF extends Fpdf
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
    $this->Image(__DIR__.'/logoglobal.png',10,5,35);
    // Police Arial gras 15
    $this->SetFont('Arial','',9);
    $this->SetY(16);
	$this->SetX(10);
    $this->Cell(30,10,'30020 Yaounde, Nouvelle route, Omnisport',0,1);
	$this->SetY(21);
	$this->SetX(10);
    $this->Cell(60,10,'Commissionnaire en douane agree',0,1);
	$this->SetY(26);
	$this->SetX(10);
    $this->Cell(50,10,'RC/YAE/2020/A/1536 | N. Contribuable : P128888546643F',0,1);
	
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
	$this->Cell(60,10,'DOIT: '.$nom_client,0,1);
	
    // Saut de ligne
    $this->Ln(1);
}

function headerdescription()
{
	$y = $this->GetY() + 5;
	$this->SetFont('Times','B',12);
	$this->SetY($y);
	$this->SetX(10);
	$this->Cell(90,10,'ELEMENTS',1,1);
	
	$this->SetY($y);
	$this->SetX(100);
	$this->Cell(30,10,'P.U (XAF)',1,1);
	
	$this->SetY($y);
	$this->SetX(130);
	$this->Cell(20,10,'Qte',1,1);
	
	$this->SetY($y);
	$this->SetX(150);
	$this->Cell(20,10,'TVA',1,1);
	
	$this->SetY($y);
	$this->SetX(170);
	$this->Cell(30,10,'M. TTC (XAF)',1,1);
	
	$this->SetFont('Times','',12);
}

function addProduct($reference, $designation, $quantite, $puht,$mht, $bold = false)
{
	//statistique de la commande
	if($bold == true)
	{
		$this->SetFont('Times','B',12);
	}else{
		$this->SetFont('Times','',12);
	}

	$y = $this->GetY();
	$this->SetY($y);
	$this->SetX(10);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(90,6,$reference,1,'L',0);
	
	$this->SetY($y);
	$this->SetX(100);
	$this->SetFillColor(0, 198, 215);
	$this->Cell(30,6,$designation,1,0);
	
	$this->SetY($y);
	$this->SetX(130);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(20,6,$quantite,1,'L',0);
	
	$this->SetY($y);
	$this->SetX(150);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(20,6,$puht,1,'L',0);
	
	$this->SetY($y);
	$this->SetX(170);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(30,6,$mht,1,'L',0);
}

function addLeftHeding($reference, $value)
{
	//en-tete de la commande
	$this->SetFont('Arial','B',8);
	$y = $this->GetY();
	$this->SetY($y);
	$this->SetX(10);
	$this->MultiCell(25,6,$reference.' : ',0,1);
	
	$this->SetFont('Arial','',8);
	$this->SetY($y);
	$this->SetX(35);
	$this->MultiCell(50,6,$value,0,1);
}

function addRightHeding($reference, $value)
{
	//en-tete de la commande
	$this->SetFont('Arial','B',8);
	$y = $this->GetY();
	$this->SetY($y);
	$this->SetX(120);
	$this->MultiCell(32,6,$reference.' : ',0,1);
	
	$this->SetFont('Arial','',8);
	$this->SetY($y);
	$this->SetX(155);
	$this->MultiCell(50,6,$value,0,1);
}

function addProductLivreurMobile($reference, $designation, $quantite, $puht, $nbp, $detailp, $mht)
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
	$this->MultiCell(10,6,$quantite,1,'L',0);
	
	$this->SetY($y);
	$this->SetX(120);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(20,6,$puht,1,'L',0);

	$this->SetY($y);
	$this->SetX(140);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(10,6,$nbp,1,'L',0);

	$this->SetY($y);
	$this->SetX(150);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(20,6,$detailp,1,'L',0);

	$this->SetY($y);
	$this->SetX(170);
	$this->SetFillColor(0, 198, 215);
	$this->MultiCell(30,6,$mht,1,'L',0);
	
	$this->SetFont('Times','',12);
}

function statistique($totalqte, $totalremise,$prix, $ville, $quartier, $tel, $netapayer,$page,$client,$admin,$caisse)
{
	//statistique de la commande
	$this->SetFont('Times','B',12);
	$y = $this->getY();
	/*$this->SetY($y);
	$this->SetX(10);
	$this->Cell(60,10,'Arrête Le montant TTC à la sommes de',0,1);
	
	$this->SetY($y+5);
	$this->SetX(10);
	$this->Cell(60,10,'..............',0,1);
	
	$this->SetY($y);
	$this->SetX(90);
	$this->Cell(60,10,'Total Qté',0,1);
	
	$this->SetY($y+5);
	$this->SetX(90);
	$this->Cell(60,10,$totalqte,0,1);
	
	$this->SetY($y);
	$this->SetX(130);
	$this->Cell(60,10,'Total remise',0,1);
	
	$this->SetY($y+5);
	$this->SetX(130);
	$this->Cell(60,10,$totalremise,0,1);
	
	$this->SetY($y);
	$this->SetX(170);
	$this->Cell(60,10,'Net à payer',0,1);
	
	$this->SetFont('Times','I',12);
	$this->SetY($y+5);
	$this->SetX(170);
	$this->Cell(60,10,$prix,0,1);
	
	
	
	$this->SetFont('Times','I',12);
	$this->SetY($y+10);
	$this->SetX(10);
	$this->Cell(60,10,$ville.', '.$quartier,0,1);
	
	$this->SetY($y+10);
	$this->SetX(130);
	$this->Cell(60,10,$tel,0,1);
	
	$this->SetFont('Times','B',12);
	$this->SetY($y+10);
	$this->SetX(170);
	$this->Cell(60,10,$netapayer,0,1);*/
	
	$this->SetFont('Arial','',8);
	$this->SetY($y+10);
	$this->SetX(50);
	$this->Cell(60,10,'SAUF ERREUR OU OMMISSION LES DEBOURS SONT FACTURES A L\'IDENTIQUE',0,1);

	$this->SetFont('Arial','',8);
	$this->SetY($y+15);
	$this->SetX(10);
	$this->SetTextColor(209,71,71);
	$this->Cell(60,10,'Global Investissement n\'est pas responsable des  frais dus a  l\'enlevement tardif au dela de la franchise de 11jours.',0,1);
	
	$this->SetFont('Arial','B',8);
	$this->SetY($y+30);
	$this->SetX(10);
	$this->SetTextColor(31, 30, 30);
	$this->Cell(60,10,'Le client',0,1);
	
	$this->SetFont('Arial','',8);
	$this->SetY($y+35);
	$this->SetX(10);
	$this->Cell(60,10,$client,0,1);
	
	$this->SetFont('Arial','B',8);
	$this->SetY($y+30);
	$this->SetX(150);
	$this->Cell(60,10,$caisse,0,1);
	
	$this->SetFont('Arial','',8);
	$this->SetY($y+35);
	$this->SetX(150);
	$this->Cell(60,10,$admin,0,1);
	
	$this->SetFont('Arial','i',8);
	$this->SetY(265);
	$this->SetX(10);
	$this->Cell(80,10,'Page '.$page.'/'.$page,0,1);
	
	$this->SetY(265);//$this->SetY($y+50);
	$this->SetX(130);
	$this->Cell(80,10,'Genere sur: www.global-investissement.com',0,1,'C');
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