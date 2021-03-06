<?php


namespace Boldy\SyliusExportPlugin\Transformer;

final class Pool implements TransformerPoolInterface
{
    /** @var HandlerInterface */
    private $handler;

    /** @var \IteratorAggregate */
    private $generator;

    public function __construct(\IteratorAggregate $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($key, $value)
    {
        if (null === $this->handler) {
            $this->registerHandlers();
        }

        return $this->handler->handle($key, $value);
    }

    private function registerHandlers(): void
    {
        foreach ($this->generator->getIterator() as $key => $handler) {
            if ($key === 0) {
                $this->handler = $handler;

                continue;
            }

            $this->handler->setSuccessor($handler);
        }
    }
}
