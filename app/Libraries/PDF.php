<?php

namespace App\Libraries;

require_once APPPATH . 'ThirdParty' . DIRECTORY_SEPARATOR . 'dompdf' . DIRECTORY_SEPARATOR . 'autoload.inc.php';

use Dompdf\Dompdf;

class PDF
{
	public $dompdf;
	public $css;
	public $html;
	public $header;
	public $footer;

	public function __construct()
	{
		$this->dompdf = new Dompdf();

		$this->dompdf->setPaper('A4', 'landscape');

		$options = $this->dompdf->getOptions();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'Courier');
		$options->set('isPhpEnabled', true);
		$options->set('isRemoteEnabled', true);
		$options->set('isJavascriptEnabled',true);
		$options->set('isFontSubsettingEnabled',true);
	}

	public function setCSS($css)
	{
		$this->css = $css;
	}

	public function appendCSS($css)
	{
		$this->css .= $css;
	}

	public function setHeader($header)
	{
		$this->header = $header;
	}

	public function setFooter($footer)
	{
		$this->footer = $footer;
	}

	public function setBody($html)
	{
		$this->html = $html;
	}

	public function appendHTML($html)
	{
		$this->html .= $html;
	}

	public function generatePDF($file_name)
	{
		$render = '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8" />';
		$render .= '<style>';
		$render .= $this->css;
		$render .= '</style>';
		$render .= '</head>';
		$render .= '<body>';
		$render .= '<header>';
		$render .= $this->header;
		$render .= '</header>';
		$render .= '<footer>';
		$render .= $this->footer;
		$render .= '</footer>';
		$render .= $this->html;
		$render .= '</body>';
		$render .= '</html>';

		//$render = preg_replace('/>\s+</', "><", $render);

		$this->dompdf->loadHtml($render);
		$this->dompdf->render();

		//echo $render;
		
		$this->dompdf->stream($file_name . ".pdf", ['Attachment' => 0 , 'compress' => 0]);

		exit();
	}
}
