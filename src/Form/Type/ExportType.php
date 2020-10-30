<?php


namespace Boldy\SyliusExportPlugin\Form\Type;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class ExportType extends AbstractType
{
    const FORMATS = [
        'csv' => 'CSV',
        'xls' => 'EXCEL'
    ];
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ExportType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gabarits = $this->container->getParameter('boldy.gabarits');

        $gabarits = array_keys($gabarits);

        $gabarits = array_combine($gabarits, $gabarits);

        $builder->add('export_type', ChoiceType::class, [
            'mapped' => false,
            'choices' => $gabarits,
            'choice_translation_domain' => 'messages',
            'translation_domain' => 'BoldySyliusExportPlugin'
        ])
        ->add('export_format', ChoiceType::class, [
            'mapped' => false,
            'choices' => array_flip(self::FORMATS)
        ])
        ->add('date_start', DateType::class, [
            'mapped' => false,
            'widget' => 'single_text'
        ])
        ->add('date_end', DateType::class, [
            'mapped' => false,
            'widget' => 'single_text'
        ])
        ;
    }
}
