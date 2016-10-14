<?php
	namespace AcreditacionBundle;

	class MyTCPDF extends \TCPDF{
		private $companyLogo1='/images/escudoElSalvador.png';
		private $companyLogo2='/images/logoMined.png';
		private $companyName='Ministerio de Educación';
		private $companyLongName='Ministerio de Educación, Gobierno de El Salvador';
		private $companyShortName='MINED';
		private $companyLocation='San Salvador';
		private $companyDepartment='San Salvador';
		private $companyAdminGroupName='';
		private $headerType;
		private $footerType;
		private $headerTypeChange=true;
		private $headerTitle;
		private $workAreaWidth;
		private $lineHeight=6;
		private $colorFondoEncabezado=array(255, 255, 153);
		private $filasZebra=false;
		private $colorFilasZebra=array(null,array(168, 184, 217));
		private $colorearTotales=false;
		private $colorTotales=array(0, 0, 255);


		public function getCompanyLogo(){
			return $this->companyLogo2;
		}

		public function getCompanyName(){
			return $this->companyName;
		}

		public function getCompanyLongName(){
			return $this->companyLongName;
		}

		public function getCompanyShortName(){
			return $this->companyShortName;
		}

		public function getCompanyLocation(){
			return $this->companyLocation;
		}

		public function getCompanyDepartment(){
			return $this->companyDepartment;
		}

		public function getCompanyAdminGroupName(){
			return $this->companyAdminGroupName;
		}

		public function setFilasZebra($filasZebra){
	    	$this->filasZebra = $filasZebra;
		}

		public function getFilasZebra(){
	    	return $this->filasZebra;
		}

		public function setColorearTotales($colorearTotales){
	    	$this->colorearTotales = $colorearTotales;
		}

		public function getColorearTotales(){
	    	return $this->colorearTotales;
		}

		public function __construct($orientation='P',$units='mm',$pageSize='Letter'){
			parent::__construct($orientation,$units,$pageSize);
			$this->SetHeaderMargin();
		}

		public function setHeaderType($headerType){
			$this->headerType=$headerType;
			switch ($this->headerType) {
				case 'logoHeader':
					$this->SetMargins(25,40,20);
					break;
				case 'newLogoHeader':
					$this->SetMargins(25,40,20);
					break;
				case 'simpleHeader':
					$this->SetMargins(25,25,20);
					break;
				case 'depositHeader':
					$this->SetMargins(8,8,8);
					break;
				default:
					$this->SetMargins(25,10,20);
					break;
			}
			$this->headerTypeChange=true;
		}

		public function setHeaderTitle($headerTitle){
			$this->headerTitle=$headerTitle;
		}

		public function Header(){
			switch ($this->headerType) {
				case 'logoHeader':
					$this->Image($this->companyLogo1,$this->GetX(),$this->GetY(),42);
					$this->SetX($this->GetX() + 47);
					$x=$this->GetX();
					$y=$this->GetY();
					$this->SetLineStyle(array('color' => array(152, 203, 255)));
					$this->SetFillColor(152, 203, 255);
					$this->RoundedRect($x, $y, 120, 15, 3, '1111', 'DF');
					$this->SetLineStyle(array('color' => array(0, 0, 0)));
					$this->SetFillColor(255, 255, 255);
					$this->RoundedRect($x - 2, $y + 2, 120, 15, 3, '1111', 'DF');
					$this->SetY($y + 5);
					$this->SetX($x + 8);
					$this->SetFontSize(11);
					$this->MultiCell(100, 20, $this->companyName, null, 'C');
					$this->SetY($y + 19);
					$this->SetX($x);
					$this->SetFontSize(10);
					$this->SetFont(null,'B');
					$this->MultiCell(120, $this->lineHeight, $this->headerTitle, 0, 'C');
					break;
				case 'newLogoHeader':
					$this->Image($this->companyLogo1,$this->GetX(),$this->GetY(),20);
					$this->Image($this->companyLogo2,$this->GetX()+132,$this->GetY(),37);
					$this->SetX($this->GetX() + 20);
					$x=$this->GetX();
					$y=$this->GetY();
					$this->SetY($y + 2);
					$this->SetX($x + 8);
					$this->SetFontSize(10);
					$this->SetFont(null,'B');
					$this->MultiCell(100, 20, 'MINISTERIO DE EDUCACIÓN
República de El Salvador. C. A.', null, 'C');
					$this->SetY($y + 10);
					$this->SetX($x + 8);
					$this->SetFontSize(7);
					$this->SetFont(null,'');
					$this->MultiCell(100, 20, 'Dirección  Nacional de Gestión Educativa
Gerencia de Acreditación y Presupuesto Escolar
Departamento de Acreditación Institucional', null, 'C');
					$this->SetY($y + 19);
					$this->SetX($x);
					break;
				case 'simpleHeader':
					$this->SetX($this->GetX() + 14);
					$this->SetFontSize(11);
					$this->SetFont(null,'B');
					$this->MultiCell(140, $this->lineHeight, $this->companyName . " ($this->companyShortName)", 0, 'C');
					$this->SetY($this->GetY() + 2);
					$this->line($this->GetX(),$this->GetY(),$this->GetX() + $this->workAreaWidth,$this->GetY());
					break;
				case 'diplomaHeader':
					$margins=$this->getMargins();
					$this->Image('/images/diplomaFondoSuperior.png',$this->GetX()-$margins['left'],$this->GetY()-$margins['top'],$this->GetPageWidth()+$margins['left']+$margins['right'],150);
					$this->Image('/images/escudoElSalvadorSombra.png',$this->GetX(),$this->GetY(),30);
					$this->Image('/images/logoMinedSombra.png',$this->GetX()+202,$this->GetY(),47);
					$this->SetFontSize(24);
					$this->SetX(($this->GetPageWidth()/2)-125);
					$this->MultiCell(250, $this->lineHeight, 'El MINISTERIO DE EDUCACIÓN', 0, 'C');
					$this->SetFontSize(18);
					$this->SetX(($this->GetPageWidth()/2)-125);
					$this->MultiCell(250, $this->lineHeight, 'DE LA REPÚBLICA DE EL SALVADOR', 0, 'C');
					break;
				default:
					break;
			}
		}

		public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false){
			parent::AddPage($orientation, $format, $keepmargins, $tocpage);
			if($this->headerTypeChange || !$this->workAreaWidth){
		        $margins=$this->getMargins();
		        $this->workAreaWidth=$this->GetPageWidth() - $margins['left'] - $margins['right'];
		        $this->headerTypeChange=false;
			}
		}

		public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false){
			if($align=='J')
				$txt.=sprintf('%100s','');
			parent::MultiCell($w, $h, $txt, $border, $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell);
		}

		public function setLineHeight($lineHeight){
			$this->lineHeight=$lineHeight;
		}

		public function getLineHeight(){
			return $this->lineHeight;
		}

		public function getWorkAreaWidth(){
			return $this->workAreaWidth;
		}

		public function newLine($num=1){
			$this->SetX(0);
			$this->SetY($this->GetY() + $num*$this->lineHeight);
		}

		public function setFooterType($footerType){
			$this->footerType=$footerType;
		}

		public function Footer(){
			$this->setFont(null,'',8);
			$this->SetY(-15);
			switch($this->footerType){
				case 'simpleFooter':
					$this->MultiCell($this->getWorkAreaWidth(),$this->getLineHeight(),'Pág. '.$this->getAliasNumPage() . '/' . $this->getPageGroupAlias(),0,'C');
					break;
				case 'infoFooter':
					$this->MultiCell($this->getWorkAreaWidth(),$this->getLineHeight(),"Edificios A, Plan Maestro, Centro de Gobierno, Alameda Juan Pablo II y calle Guadalupe, " . $this->getCompanyLocation() . ".\nTeléfonos: +(503) 2592-2000, +(503) 2592-2122, +(503) 2592-3117 - Correo electrónico: educacion@mined.gob.sv",0,'C');
					break;
				case 'imageFooter':
					$x=$this->GetX();
					$y=$this->GetY();
					$margins=$this->getMargins();
					$this->Image('/images/footer.png',$this->GetX()-$margins['left'],$this->GetY(),$this->getWorkAreaWidth()+$margins['left']+$margins['right'],20);
					$this->SetXY($x,$y);
					$this->MultiCell($this->getWorkAreaWidth(),$this->getLineHeight(),'Pág. '.$this->getAliasNumPage() . '/' . $this->getPageGroupAlias(),0,'C');
					break;
				case 'diplomaFooter':
					$margins=$this->getMargins();
					$this->Image('/images/footer.png',$this->GetX()-$margins['left'],$this->GetY()-60,$this->getWorkAreaWidth()+$margins['left']+$margins['right'],60+20);
					break;
				default:
					break;
			}
		}

		/* funciones auxiliares */

		public function dateLongFormat($dateString,$dayName=true,$justMonthAndYear=false){
			if($dateString)
				list($dia,$mes,$anio)=preg_split("/[\/.-]/",$dateString);
			else
				return null;
			$dd=new \DateTime("$anio-$mes-$dia");
			$diasSemana=explode(' ',"domingo lunes martes miércoles jueves viernes sábado");				
			$meses=explode(' '," enero febrero marzo abril mayo junio julio agosto septiembre octubre noviembre diciembre");
			if($justMonthAndYear)
				return $meses[$mes+0] . " de $anio";
			else
				return ($dayName?$diasSemana[$dd->format('w')] . ' ':'') . "$dia de " . $meses[$mes+0] . " de $anio";
		}

		public function dataTable($columns,$rows,$differentHeader=array('type' => 'N','text' => ''),$showHeaders=true){
			$workAreaWidth=$this->getWorkAreaWidth();
			$margins=$this->getMargins();
			$totalWidth=$columnsWithoutWidth=0;
			foreach($columns as $key => $column){
				if(!isset($column['title'])){
					trigger_error(__FUNCTION__ . ": La columna $key debe tener un atributo 'title'.");
					die();
				}
				if(!isset($column['width']) || !is_numeric($column['width'])){
					$columnsWithoutWidth++;
				}
				elseif($column['width']>100){
					trigger_error(__FUNCTION__ . ": La columna $column[title] debe tener un ancho menor que 100.");
					die();
				}
				else{
					$totalWidth+=$column['width'];
					$columns[$key]['realWidth']=round($workAreaWidth*$column['width']/100,1);
				}
				if(!isset($column['align'])){
					$columns[$key]['align']='L';
				}
			}
			reset($columns);
			$missingWidth=100 - $totalWidth;
			$extraHeaderHeight=$maxHeightHeader=0;
			if(isset($differentHeader['type']) && $differentHeader['type']=='E'){
				$extraHeaderHeight=$this->getStringHeight($this->getWorkAreaWidth(),$differentHeader['text']);
				$extraHeaderHeight*=1.05;
			}
			foreach($columns as $key => $column){
				if(!isset($column['width']) || !is_numeric($column['width'])){
					$columns[$key]['width']=$column['width']=$missingWidth/$columnsWithoutWidth;
					$columns[$key]['realWidth']=$column['realWidth']=round($workAreaWidth*$column['width']/100,1);
				}
				$currentHeight=$this->getStringHeight($column['realWidth'],$column['title']);
				if($currentHeight>$maxHeightHeader)
					$maxHeightHeader=$currentHeight;
			}
			$maxHeightHeader*=1.05;
			if($this->GetY()+$extraHeaderHeight+$maxHeightHeader>$this->getPageHeight()-$margins['bottom'])
				$this->AddPage();
			$idxRow=0;
			$firstRow=true;
			$totalRows=count($rows);
			foreach($rows as $row){
				$maxHeightRow=(isset($columns[$key]['height'])?$columns[$key]['height']:0);
				foreach($row as $key => $value){
					if(is_array($value))
						$currentHeight=$this->getStringHeight($columns[$key]['realWidth'],$value['text']);
					else
						$currentHeight=$this->getStringHeight($columns[$key]['realWidth'],$value);
					if($currentHeight>$maxHeightRow)
						$maxHeightRow=$currentHeight;
				}
				$maxHeightRow*=1.05;
				if($this->GetY()+($firstRow?$extraHeaderHeight+$maxHeightHeader:0)+$maxHeightRow>$this->getPageHeight()-$margins['bottom']){
					$this->AddPage();
					$firstRow=true;
				}
				if($firstRow && $showHeaders){
					$this->SetFont(null,'B');
					if(isset($differentHeader['type']) && $differentHeader['type']=='S'){
						$this->SetFillColor(230, 230, 230);
						$this->MultiCell($this->getWorkAreaWidth(),$this->getLineHeight(),$differentHeader['text'],0,'L',1,1);
					}
					elseif(isset($differentHeader['type']) && $differentHeader['type']=='SU'){
						$this->SetFont(null,'BU');
						$this->MultiCell($this->getWorkAreaWidth(),$this->getLineHeight(),$differentHeader['text'],0,'L');
						$this->SetFont(null,'');
					}
					else{
						if(isset($differentHeader['type']) && $differentHeader['type']=='E'){
							if(!isset($differentHeader['background']) || $differentHeader['background']===true)
								$this->SetFillColor(125, 166, 71);
							else
								$this->SetFillColorArray($this->colorFondoEncabezado);
							$this->MultiCell($this->getWorkAreaWidth(),$this->getLineHeight(),$differentHeader['text'],1,'C',1,1);
						}
						$this->SetFillColorArray($this->colorFondoEncabezado);
						reset($columns);
						foreach($columns as $key => $column){
							$this->MultiCell($column['realWidth'],$maxHeightHeader,$column['title'],(isset($column['headerBorder'])?$column['headerBorder']:1),'C',(isset($column['headerFill'])?$column['headerFill']:1),0);
						}
						$this->SetX(0);
						$this->SetY($this->GetY() + $maxHeightHeader);
					}
					$this->SetFont(null,'');
					$firstRow=false;
				}
				reset($row);
				foreach($row as $key => $value){
					if(isset($columns[$key]['style']) || (is_array($value) && isset($value['style'])))
						$this->SetFont(null,(isset($value['style'])?$value['style']:$columns[$key]['style']));
					$fill=0;
					if($this->getColorearTotales() && ($idxRow+1)==$totalRows){
						$this->SetFillColorArray($this->colorTotales);
						$fill=1;
					}
					elseif($this->getFilasZebra() && $this->colorFilasZebra[$idxRow%2]){
						$this->SetFillColorArray($this->colorFilasZebra[$idxRow%2]);
						$fill=1;
					}
					if(is_array($value)){
						if(!$this->getColorearTotales() && !$this->getFilasZebra()){
							if(isset($value['fill'])){
								$this->SetFillColor($value['fill'][0],$value['fill'][1],$value['fill'][2]);
								$fill=1;
							}
						}
						$this->MultiCell($columns[$key]['realWidth'],$maxHeightRow,$value['text'],(isset($value['border'])?$value['border']:(isset($columns[$key]['border'])?$columns[$key]['border']:1)),(isset($value['align'])?$value['align']:$columns[$key]['align']),$fill,0);
					}
					else{
						if(!$this->getColorearTotales() && !$this->getFilasZebra()){
							if(isset($columns[$key]['fill'])){
								$this->SetFillColor($columns[$key]['fill'][0],$columns[$key]['fill'][1],$columns[$key]['fill'][2]);
								$fill=1;
							}
						}
						$this->MultiCell($columns[$key]['realWidth'],$maxHeightRow,$value,(isset($columns[$key]['border'])?$columns[$key]['border']:1),$columns[$key]['align'],$fill,0);
					}
					if(isset($columns[$key]['style']) || (is_array($value) && isset($value['style'])))
						$this->SetFont(null,'');
				}
				$this->SetY($this->GetY() + $maxHeightRow);
				$idxRow++;
			}
		}

        public function dobleLineaSeparacion($newLine=true){
	        $lineWidth=$this->getLineWidth();
	        $this->SetLineWidth(0.5);
	        $this->line($this->GetX(),$this->GetY(),$this->GetX() + $this->getWorkAreaWidth(),$this->GetY());
	        $this->SetLineWidth($lineWidth);
	        $this->SetY($this->GetY()+1);
	        $this->line($this->GetX(),$this->GetY(),$this->GetX() + $this->getWorkAreaWidth(),$this->GetY());
	        if($newLine)
	        	$this->newLine();
        }

		/* funciones para convertir números a letras */

		//función que hace el trabajo en la conversión de entero a letras
		private function entero_a_letras_aux($e,$mil){

			$num_exactos=array(
				0 => '',
				1 => 'uno ', 2 => 'dos ', 3 => 'tres ', 4 => 'cuatro ', 5 => 'cinco ', 6 => 'seis ',
				7 => 'siete ', 8 => 'ocho ', 9 => 'nueve ', 10 => 'diez ', 11 => 'once ', 12 => 'doce ',
				13 => 'trece ', 14 => 'catorce ', 15 => 'quince ', 16 => 'dieciséis ', 17 => 'diecisiete ',
				18 => 'dieciocho ', 19 => 'diecinueve ', 20 => 'veinte ', 30 => 'treinta ', 40 => 'cuarenta ',
				50 => 'cincuenta ', 60 => 'sesenta ', 70 => 'setenta ', 80 => 'ochenta ', 90 => 'noventa ',
				100 => 'ciento ', 200 => 'doscientos ', 300 => 'trescientos ', 400 => 'cuatrocientos ',
				500 => 'quinientos ', 600 => 'seiscientos ', 700 => 'setecientos ', 800 => 'ochocientos ', 
				900 => 'novecientos '
			);
			
			if(array_key_exists($e,$num_exactos) && $e!=100) //tomarlo del array
				return $num_exactos[$e];
				
			$decenas_centenas=array(
				20 => 'veinti', 30 => 'treinta y ', 40 => 'cuarenta y ', 50 => 'cincuenta y ', 60 => 'sesenta y ',
				70 => 'setenta y ', 80 => 'ochenta y ', 90 => 'noventa y '
			);
				
			if($e<100) //máximo 99
				return $decenas_centenas[$e - $e%10] . $num_exactos[$e%10];
			if($e==100) //máximo 99
				return 'cien ';
			if($e<1000) //de 101 a 999
				return $num_exactos[$e - $e%100] . $this->entero_a_letras_aux($e%100,0);
			
			$tres_cifras=array(
				'', 'mil ', 'millones ', 'mil ', 'billones ', 'mil ', 'trillones ' //por gusto si topa en 2147483647
			);
			
			//caso por default...
			if((($e - $e%1000) / 1000)==1) //para que retorne 'un millón' en lugar de 'uno millones'
				return 'un ' . str_replace('es','',$tres_cifras[$mil + 1]) . $this->entero_a_letras_aux($e%1000,0);
			else
				//si los siguientes tres dígitos NO son cero o el contador de miles es impar
				if(((($e - $e%1000)/1000)%1000)!=0 || $mil%2!=0)
					return $this->entero_a_letras_aux(($e - $e%1000) / 1000,$mil + 1) . $tres_cifras[$mil + 1] . $this->entero_a_letras_aux($e%1000,0);
				else
					return $this->entero_a_letras_aux(($e - $e%1000) / 1000,$mil + 1) . $this->entero_a_letras_aux($e%1000,0);
		}
		
		//dada una cantidad la retorna así: 1003.25 -> un mil tres 25/100 d&oacute;lares
		//$may_min indica si la cantidad se escribe en mayúsculas o minúsculas
		public function cantidad_a_letras($c,$may_min='m',$texto=false){
			$c=sprintf('%0.2f',$c);
			$ctvs=round(($c - floor($c)) * 100);
			$dlrs=floor($c);
			settype($dlrs,'integer');
			$ctvs.='';
			while(strlen($ctvs)<2)
				$ctvs='0' . $ctvs;
			if($may_min=='m') //minúsculas
				//return $this->entero_a_letras_aux($dlrs,0) . $ctvs . '/100 d' . ($texto?'ó':'&oacute;') . 'lares';
				return $this->entero_a_letras_aux($dlrs,0) . $ctvs . '/100 USDÓLARES';
			elseif($may_min=='M') //mayúsculas
				return str_replace('ACUTE','acute',strtoupper($this->entero_a_letras_aux($dlrs,0) . $ctvs . '/100 d' . ($texto?'O':'&oacute;') . 'lares'));
		}
	}
?>