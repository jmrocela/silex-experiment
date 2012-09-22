<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class DocumentsSessionHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = (string) $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['sess_id'])) {
            $value = $data['sess_id'];
            $return = (string) $value;
            $this->class->reflFields['sess_id']->setValue($document, $return);
            $hydratedData['sess_id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['data'])) {
            $value = $data['data'];
            $return = (string) $value;
            $this->class->reflFields['data']->setValue($document, $return);
            $hydratedData['data'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['time'])) {
            $value = $data['time'];
            $return = (string) $value;
            $this->class->reflFields['time']->setValue($document, $return);
            $hydratedData['time'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['IP'])) {
            $value = $data['IP'];
            $return = (string) $value;
            $this->class->reflFields['IP']->setValue($document, $return);
            $hydratedData['IP'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['useragent'])) {
            $value = $data['useragent'];
            $return = (string) $value;
            $this->class->reflFields['useragent']->setValue($document, $return);
            $hydratedData['useragent'] = $return;
        }
        return $hydratedData;
    }
}