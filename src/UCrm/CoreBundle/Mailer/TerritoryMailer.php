<?php
namespace UCrm\CoreBundle\Mailer;


use UCrm\CoreBundle\Entity\Territory;

define('DOMPDF_ENABLE_AUTOLOAD', false);
define("DOMPDF_ENABLE_REMOTE", true);
define("TMP_PATH", ROOT_PATH . '/tmp');

// include DOMPDF's default configuration
require_once ROOT_PATH . '/vendor/dompdf/dompdf/dompdf_config.inc.php';

class TerritoryMailer {
	
	protected $mailer;

	protected $em;

	protected $templating;


	public function __construct(\Swift_Mailer $mailer, $em, $templating)
	{
		$this->mailer = $mailer;
		$this->em = $em;
		$this->templating = $templating;
	}


	public function send(Territory $territory) 
	{
		$people = $this->em->getRepository('UCrmCoreBundle:Client')->findAllInTerritory($territory);

		$tplParams = [
            'people' => $people,
            'entity' => $territory,
            'mapCenter' => $territory->center(),
            'mapPath'   => $territory->pathString()
        ];
        $pdfTemplate = "UCrmCoreBundle:Territory:print.html.twig";

        $dompdf = new \DOMPDF();
        $dompdf->load_html($this->templating->render($pdfTemplate, $tplParams));
        $dompdf->render();

        $outfile = TMP_PATH . '/territory.pdf';
        file_put_contents($outfile, $dompdf->output( array("compress" => 0) ));

        $message = \Swift_Message::newInstance()
            ->setSubject('Territory Assignment')
            ->setFrom('donotreply@' . $_SERVER['HTTP_HOST'])
            ->setTo($territory->getUser()->getEmail())
            ->setBody(
                $this->templating->render(
                    'UCrmCoreBundle:TerritoryMailer:send.txt.twig',
                    array(
                        'entity' => $territory,
                        'http_host' => $_SERVER['HTTP_HOST']
                    )
                )
            )
            ->attach(\Swift_Attachment::fromPath($outfile));

        $this->mailer->send($message);
	}
}