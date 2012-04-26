<?php

require(ROOT . '/php/libs/fpdf16/fpdf.php');

date_default_timezone_set('Europe/Stockholm');
define('LOGO_MOTIOMERA', PDF_TEMPLATE_DIR . 'images/Logo_Motiomera_colour.jpg');
define('LOGO_MABRA', PDF_TEMPLATE_DIR . 'images/mabra_logo.jpg');
define('LOGO_ALLERS', PDF_TEMPLATE_DIR . 'images/aller_media_logo.png');
define('ALLER_ADDRESS', '');

/**
 * PDF, create PDF-documents for Motiomera
 *
 * @version 1.0
 * @uses fpdf 1.6
 * @package pdfmera
 * @author Jonas Björk, Aller Internet
 */
class PDF extends FPDF {

  var $B;
  var $I;
  var $U;
  var $HREF;

  function PDF($orientation = 'P', $unit = 'mm', $format = 'A4') {
    $this->FPDF($orientation, $unit, $format);
    $this->B = 0;
    $this->I = 0;
    $this->U = 0;
    $this->HREF = '';
  }

  function WriteHTML($html) {
    $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    foreach ($a as $i => $e) {
      if ($i % 2 == 0) {
        //Text 
        if ($this->HREF)
          $this->PutLink($this->HREF, $e);
        else
          $this->Write(5, $e);
      }
      else {
        //Tag 
        if ($e{0} == '/')
          $this->CloseTag(strtoupper(substr($e, 1)));
        else {
          //Extract attributes 
          $a2 = explode(' ', $e);
          $tag = strtoupper(array_shift($a2));
          $attr = array();
          foreach ($a2 as $v)
            if (ereg('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3))
              $attr[strtoupper($a3[1])] = $a3[2];
          $this->OpenTag($tag, $attr);
        }
      }
    }
  }

  function OpenTag($tag, $attr) {
    //Opening tag 
    if ($tag == 'B' or $tag == 'I' or $tag == 'U')
      $this->SetStyle($tag, true);
    if ($tag == 'A')
      $this->HREF = $attr['HREF'];
    if ($tag == 'BR')
      $this->Ln(5);
  }

  function CloseTag($tag) {
    //Closing tag 
    if ($tag == 'B' or $tag == 'I' or $tag == 'U')
      $this->SetStyle($tag, false);
    if ($tag == 'A')
      $this->HREF = '';
  }

  function SetStyle($tag, $enable) {
    //Modify style and select corresponding font 
    $this->$tag+=($enable ? 1 : -1);
    $style = '';
    foreach (array('B', 'I', 'U') as $s)
      if ($this->$s > 0)
        $style.=$s;
    $this->SetFont('', $style);
  }

  function PutLink($URL, $txt) {
    //Put a hyperlink 
    $this->SetTextColor(0, 0, 255);
    $this->SetStyle('U', true);
    $this->Write(5, $txt, $URL);
    $this->SetStyle('U', false);
    $this->SetTextColor(0);
  }

  /**
   * Get page content
   *
   * @param string $fileName The file to read.
   * @return string
   */
  function GetPageContent($fileName = '') {
    $page = array();
    // TODO: error handling
    $fdata = file_get_contents($fileName) or die('Could not open file: ' . $fileName);
    $lineEnd = 0;
    $page['head'] = '';

    if (substr($fdata, 0, 3) == '=h1') {
      $lineEnd = (strpos($fdata, "\n"));
      $page['head'] = substr($fdata, 4, $lineEnd - 4);
    }
    $page['body'] = substr($fdata, $lineEnd, strlen($fdata));

    return $page;
  }

  /**
   * Page Header
   *
   * @param int $incNumber If you want to have an increment number on the page.
   * @return void
   */
  function PageHeader($incNumber = null) {
    $this->Image(LOGO_MOTIOMERA, 13, 5, 100, 46);
    $this->SetXY(180, 20);
    $this->SetFont('Arial', '', 10);
    if ($incNumber) {
      $this->Cell(45, 10, str_pad($incNumber, 5, '0', STR_PAD_LEFT));
    }
  }

  /**
   * Page Footer
   *
   * @param string $info Info text in footer
   * @param string $address The contact address in footer
   * @return void
   */
  function PageFooter($info = null, $address = null) {
    $info = utf8_decode($info);
    $address = utf8_decode($address);
    $this->SetFont('Arial', '', 8);
    $this->SetXY(15, -65);
    $this->MultiCell(120, 3, $info);
    $this->SetXY(130, -65);
    $this->MultiCell(120, 3, $address);
    //$this->Image(LOGO_MABRA, 13, 255, 32, 8);
    //$this->Image(LOGO_ALLERS, 130,255,53,8);
  }

  /**
   * Takes an key-value array as filter on data.
   *
   * @param array $filterset The filter to use on the data.
   * @param string $data The data to be filtered.
   * @return string
   * @author Jonas Björk
   * */
  function TemplateFilter($filterset, $data) {
    $from = array();
    $to = array();
    foreach ($filterset as $k => $v) {
      $from[] = $k;
      $to[] = $v;
    }
    return str_replace($from, $to, $data);
  }

  /**
   * Print a page. Used by the Page* methods.
   *
   * @param array $page The page to print.
   * @param array $filter The filter to use on the data.	
   * @return void
   * @author Jonas Björk
   * */
  function PrintPage($page, $filter) {
    $head = $page['head'];
    $body = $this->TemplateFilter($filter, $page['body']);
    $this->SetXY(15, 50);
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(170, 5, utf8_decode($head));
    $this->SetXY(15, 60);
    $this->SetFont('Times', '', 10);
    $this->WriteHTML(utf8_decode($body));
  }

  /**
   * Adds user code to a page.
   *
   * @param string $userCode The user code to be displayed.
   * @return void
   * @author Jonas Björk
   * */
  function AddUserCode($userCode) {
    $this->SetXY(115, 30);
    $this->SetFont('Arial', 'B', 20);
    $this->SetFillColor(255, 255, 0);
    $this->Cell(80, 10, utf8_decode($userCode), 0, 0, 'C', TRUE);
  }

// AddUserCode()

  /**
   * Adds the "Till tävlingsansvarig" text on page.
   *
   * @return void
   * @author Jonas Björk
   */
  function HeaderPrintContest() {
    $this->SetXY(115, 35);
    $this->SetFont('Arial', 'B', 20);
    $this->SetFillColor(255, 255, 0);
    $this->Cell(80, 10, utf8_decode("Till tävlingsansvarig"), 0, 0, 'C', TRUE);
  }

// HeaderPrintContest()

  /**
   * Creates a new page. Common for all Page*
   *
   * @return void
   * @author Jonas Björk
   */
  function _newpage() {
    $this->AddPage();
    $this->SetLeftMargin(15);
  }

// _newpage()

  /**
   * Create info page for the contest coach.
   * 
   * <code>
   * $filter = array(
   * 	'[STARTDATE]' 	=> date("Y-m-d"),
   * 	'[USERNAME]'	=>	'userName',
   * 	'[PASSWORD]'	=> 'passWord',
   * 	);
   *
   * $customerInfo =	array(
   * 	'COMPANY' 		=> 'Naturbruksgymnasiet Uddetorp - Sparresäter',
   * 	'CUSTOMERNO'	=> '257344630',
   * 	'CONTENDERS'	=> 40,
   * 	);
   * $pdf->PageCoach($filter, $customerInfo);
   * </code>
   * 
   * @param array $filter Filter data for the template engine.
   * @return void
   * @author Jonas Björk
   */
  function PageCoach($filter, $customerInfo) {
    $headerInfo = $customerInfo['COMPANY'] . "\n" .
            'Order id: ' . $customerInfo['ORDERID'] . "\n" .
            'Deltagare: ' . $customerInfo['CONTENDERS'] . ', Stegräknare: ' . $customerInfo['PEDOMETERS'];
    $this->_newpage();
    $this->PageHeader();
    $this->HeaderPrintContest();
    $this->SetXY(115, 14);
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(224, 224, 255);
    $this->MultiCell(80, 5, utf8_decode($headerInfo), 0, 'L', 'C', TRUE);
    $this->PrintPage($this->GetPageContent(PDF_TEMPLATE_DIR . 'templates/letter_corp.tpl'), $filter);
    $this->PageFooter(file_get_contents(PDF_TEMPLATE_DIR . 'templates/footer.tpl'), ALLER_ADDRESS);
  }

// PageCoach()

  /**
   * Create the participant page
   * Submit the incramental page number for each additional page
   *
   * <code>
   * $filter = array(
   * 	'[USERCODE]' 			=> 'BHZS4KXR',
   * 	'[STARTDATE]'			=> date("Y-m-d"),		
   * 	);
   * $pdf->PageParticipant($filter, 1);
   * </code>
   *
   * @param array $filter Filter data for the template engine.
   * @param string $count How many participants is there? One page per participant.
   * @return void
   * @author Jonas Björk
   */
  function PageParticipant($filter, $incNbr = 0) {
    $file = $this->GetPageContent(PDF_TEMPLATE_DIR . 'templates/letter_user.tpl');
    $this->_newpage();
    $this->PageHeader($incNbr);
    $this->AddUserCode($filter['[USERCODE]']);
    $this->PrintPage($file, $filter);
    $this->PageFooter(file_get_contents(PDF_TEMPLATE_DIR . 'templates/footer_user.tpl'), ALLER_ADDRESS);
  }

// PageParticipant()

  /**
   * Create page for reclamations.
   *
   * <code>
   * $filter = array(
   * 	'[CUSTOMER]' 		=> 'Firman AB',
   * 	'[CUSTOMERNO]' 		=> '901212312',
   * 	'[STEPCOUNTERS]' 	=> 32,
   * 	);
   * $pdf->PageDoa($filter);
   * </code>
   *
   * array $filter Filter data for template engine.
   * @return void
   * @author Jonas Björk
   * */
  function PageDoa($filter) {
    $this->_newpage();
    $this->PageHeader();
    $this->PrintPage($this->GetPageContent(PDF_TEMPLATE_DIR . 'templates/letter_doa.tpl'), $filter);
    $this->PageFooter(file_get_contents(PDF_TEMPLATE_DIR . 'templates/footer.tpl'), ALLER_ADDRESS);
  }

// PageDoa()

  /**
   * Create page for additional orders.
   *
   * <code>
   * $filter = array(
   * 	'[CUSTOMER]' 			=> 'Firman AB',
   * 	'[CUSTOMERNO]' 			=> '901212312',
   * 	'[STEPCOUNTERS]'		=> 32,
   * 	'[STARTDATE]' 			=> date("Y-m-d"),
   * 	);
   * $pdf->PageAddition($filter);
   * </code>
   *
   * @param array $filter Filter data for the template engine.
   * @return void
   * @author Jonas Björk
   * */
  function PageAddition($filter) {
    $this->_newpage();
    $this->PageHeader();
    $this->PrintPage($this->GetPageContent(PDF_TEMPLATE_DIR . 'templates/letter_addition.tpl'), $filter);
    $this->PageFooter(file_get_contents(PDF_TEMPLATE_DIR . 'templates/footer.tpl'), ALLER_ADDRESS);
  }

// PageAddition()

  /**
   * Create a preface for delivery package
   *
   * <code>
   * $a = array(
   * 	'FULLNAME'		=> 'ÅSE PERNESTEN',
   * 	'COMPANY'		=> 'ICOPAL AB',
   * 	'ADDRESS'		=> 'Box 848',
   * 	'ZIPCODE'		=> '201 80',
   * 	'CITY' 			=> 'Malmö',
   * 	'COUNTRY'		=> 'Sverige',
   * 	'STARTDATE'		=> date("Y-m-d"),
   * 	'CONTESTERS'	=> 10,
   * 	'COUNT'			=> 30,
   * 	);
   * $pdf->PagePreface($a);
   * </code>
   *
   * @param array $content The content to be written on the page.
   * @return void
   */
  function PagePreFace($content) {
    $address = utf8_decode(
            $content['COMPANY'] . "\r\n" .
            'ATT: ' . $content['FULLNAME'] . "\r\n" .
            $content['ADDRESS'] . "\r\n" .
            $content['ZIPCODE'] . " " .
            $content['CITY'] . "\r\n" .
            $content['COUNTRY'] . "\r\n\n" .
            $content['EMAIL'] . "\r\n" .
            $content['PHONE']
    );
    $this->_newpage();

    $this->SetFont('Arial', '', 12);
    $this->SetXY(70, 10);
    $this->MultiCell(100, 6, $content['FILENAME'] . "\r\nOrder id: " . $content['ORDERID']);

    $this->SetFont('Arial', 'B', 16);
    $this->SetXY(20, 30);
    $this->MultiCell(600, 8, $address);

    $this->SetFont('Arial', 'B', 24);
    $this->SetXY(120, 30);
    $this->Cell(80, 15, utf8_decode($content['CONTESTERS'] . ' deltagare'), 0, 0, 'C', FALSE);

    $this->SetXY(120, 45);
    $this->SetFont('Arial', 'B', 24);
    $this->SetFillColor(255, 255, 0);
    $this->Cell(80, 15, utf8_decode($content['COUNT'] . ' stegräknare'), 0, 0, 'C', TRUE);
    $this->SetFont('Arial', 'B', 12);
    $this->SetXY(120, 50);
    $this->Cell(10, 30, utf8_decode('Startdatum: ' . $content['STARTDATE']));


    $payerAddress = utf8_decode(
            'Fakturaadress' . "\r\n" .
            $content['fak-companyname'] . "\r\n" .
            'ATT: ' . $content['fak-name'] . "\r\n" .
            $content['fak-adress'] . "\r\n" .
            $content['fak-zip'] . " " .
            $content['fak-city'] . "\r\n" .
            $content['fak-country'] . "\r\n" .
            $content['fak-email'] . "\r\n" .
            $content['fak-phone'] . "\r\n" .
            $content['articlesNSum']
    );

    $this->SetFont('Arial', '', 12);
    $this->SetXY(20, 120);
    $this->MultiCell(600, 6, $payerAddress);
  }

// PagePreFace()
}

?>