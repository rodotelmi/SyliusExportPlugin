<?php


namespace Boldy\SyliusExportPlugin\Plugin;


use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class OrderPlugin implements PluginInterface
{
    protected $fieldNames = [];

    /** @var RepositoryInterface */
    protected $repository;

    /** @var PropertyAccessorInterface */
    protected $propertyAccessor;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var array */
    protected $data;

    /** @var ResourceInterface[] */
    protected $resources;

    public function __construct(
        RepositoryInterface $repository,
        PropertyAccessorInterface $propertyAccessor,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->propertyAccessor = $propertyAccessor;
        $this->entityManager = $entityManager;
    }

    public function getData(string $id, array $keysToExport): array
    {
        if (!isset($this->data[$id])) {
            throw new \InvalidArgumentException(sprintf('Requested ID "%s", but it does not exist', $id));
        }

        $result = [];

        foreach ($keysToExport as $exportKey) {
            if ($this->hasPluginDataForExportKey($id, $exportKey)) {
                $result[$exportKey] = $this->getDataForExportKey($id, $exportKey);
            } else {
                $result[$exportKey] = '';
            }
        }

        return $result;
    }

    public function init(array $idsToExport): void
    {
        $this->resources = $this->findResources($idsToExport);

        foreach ($this->resources as $resource) {
            $this->addDataForId($resource);
        }
    }

    protected function hasPluginDataForExportKey(string $id, string $exportKey): bool
    {
        return isset($this->data[$id][$exportKey]);
    }

    protected function getDataForResourceAndExportKey(ResourceInterface $resource, string $exportKey)
    {
        return $this->getDataForExportKey((string) $resource->getId(), $exportKey);
    }

    protected function getDataForExportKey(string $id, string $exportKey)
    {
        return $this->data[$id][$exportKey];
    }

    private function addDataForId(ResourceInterface $resource): void
    {
        $fields = $this->entityManager->getClassMetadata(\get_class($resource));

        foreach ($fields->getFieldNames() as $index => $field) {
            $this->fieldNames[$index] = ucfirst($field);

            if (!$this->propertyAccessor->isReadable($resource, $field)) {
                continue;
            }

            $this->addDataForResource(
                $resource,
                ucfirst($field),
                $this->propertyAccessor->getValue($resource, $field)
            );
        }
    }

    /**
     * @param int[] $idsToExport
     *
     * @return ResourceInterface[]
     */
    protected function findResources(array $idsToExport): array
    {
        /** @var ResourceInterface[] $items */
        $items = $this->repository->findBy(['id' => $idsToExport]);

        return $items;
    }

    protected function addDataForResource(ResourceInterface $resource, string $field, $value): void
    {
        $this->data[$resource->getId()][$field] = $value;
    }

    public function getFieldNames(): array
    {
        return $this->fieldNames;
    }
}