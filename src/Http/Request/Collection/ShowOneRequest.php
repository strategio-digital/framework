<?php
/**
 * Copyright (c) 2023 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.dev, jz@strategio.dev)
 */
declare(strict_types=1);

namespace Saas\Http\Request\Collection;

use Doctrine\ORM\AbstractQuery;
use Nette\Schema\Expect;
use Saas\Database\EntityManager;
use Saas\Database\CrudHelper\CrudHelper;
use Saas\Event\Collection\CollectionEvent;
use Saas\Event\Collection\OnProcessingExceptionEvent;
use Saas\Event\Collection\OnProcessingStartEvent;
use Saas\Event\Collection\OnProcessingFinishEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowOneRequest extends BaseCrudRequest
{
    public function __construct(protected readonly EntityManager $em, protected readonly CrudHelper $helper)
    {
    }
    
    public function schema(): array
    {
        $tables = array_map(fn($meta) => $meta['table'], $this->helper->getAllEntities());
        
        return [
            'table' => Expect::anyOf(...$tables)->required(),
            'schema' => Expect::bool(false),
            'id' => Expect::string()->required(),
        ];
    }
    
    public function process(array $data): Response
    {
        if (!$meta = $this->setUpMetadata($data['table'], $data['schema'], CrudHelper::PROPERTY_SHOW_ONE)) {
            return $this->error([$this->helper->getError()]);
        }
        
        $event = new OnProcessingStartEvent($data, $this->request, $meta);
        $this->dispatcher->dispatch($event, CollectionEvent::ON_PROCESSING_START);
        
        $repo = $this->em->getRepository($meta->className);
        
        $qb = $repo->createQueryBuilder('E')
            ->select($meta->getQuerySelect('E'))
            ->where('E.id = :id')
            ->setParameter('id', $data['id']);
        
        $item = $qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
        
        if (!$item) {
            $e = new NotFoundHttpException("Item '{$data['id']}' not found");
            $response = $this->error([$e->getMessage()], 404);
            $event = new OnProcessingExceptionEvent($data, $this->request, $meta, $e, $response);
            $this->dispatcher->dispatch($event, CollectionEvent::ON_PROCESSING_EXCEPTION);
            return $response;
        }
        
        $result = ['item' => $item];
        
        if ($data['schema']) {
            $result['schema'] = $meta->getSchema();
        }
        
        $response = $this->json($result);
        
        $event = new OnProcessingFinishEvent($data, $this->request, $meta, $result, $response);
        $this->dispatcher->dispatch($event, CollectionEvent::ON_PROCESSING_FINISH);
        
        return $response;
    }
}