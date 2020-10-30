<?php


namespace Boldy\SyliusExportPlugin\Controller;


use Boldy\SyliusExportPlugin\Exporter\ExporterInterface;
use Boldy\SyliusExportPlugin\Form\Type\ExportType;
use Boldy\SyliusExportPlugin\Gabarit\GabaritInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\LazyCriteriaCollection;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportDataController extends AbstractController
{
    /**
     * @var ServiceRegistryInterface
     */
    private $registry;

    /**
     * ExportDataController constructor.
     * @param ServiceRegistryInterface $registry
     */
    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function index()
    {
        $form = $this->createForm(ExportType::class);

        return $this->render('@BoldySyliusExportPlugin/modal.html.twig', ['form' => $form->createView()]);
    }

    public function export(Request $request)
    {
        $export = $request->get('export');
        $type = $export['export_type'];
        $format = $export['export_format'];
        $startDate = $export['date_start'];
        $endDate = $export['date_end'];

        $outputFilename = sprintf('%s-%s.%s', $type, date('Y-m-d'), $format);

        return $this->exportData($request, $type, $format, $outputFilename, $startDate, $endDate);
    }

    private function exportData(Request $request, $type, $format, string $outputFilename, $startDate, $endDate): Response
    {
        /** @var GabaritInterface $gabarit */
        $gabarit = $this->registry->get($type);

        /** @var ExporterInterface $exporter */
        $exporter = $gabarit->getExporter($format);

        /** @var RepositoryInterface $resourceRepository */
        $resourceRepository = $gabarit->getRepository();

        $resourceIds = $this->findResourceBetweenDate($resourceRepository, $startDate, $endDate);

        $exporter->export($resourceIds, $gabarit->getHeaders(), $gabarit->getResourceKeys());

        $response = new Response($exporter->getExportedData());
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $outputFilename
        );

        $response->headers->set('Content-Type', 'application/' . $format);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    private function findResourceBetweenDate(RepositoryInterface $resourceRepository, $startDate, $endDate)
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->gte('createdAt', new \DateTime($startDate)))
            ->andWhere(Criteria::expr()->lte('createdAt', new \DateTime($endDate)));

        /** @var LazyCriteriaCollection $results */
        $results = $resourceRepository->matching($criteria);

        return $results->map(function ($obj) { return $obj->getId(); })->getValues();
    }
}
