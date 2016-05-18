<?php

namespace Doctrine\Tests\ORM\Mapping;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Events;

class ClassMetadataLoadEventTest extends \Doctrine\Tests\OrmTestCase
{
    /**
     * @group DDC-1610
     */
    public function testEvent()
    {
        $em = $this->_getTestEntityManager();
        $metadataFactory = $em->getMetadataFactory();
        $evm = $em->getEventManager();
        $evm->addEventListener(Events::loadClassMetadata, $this);
        $classMetadata = $metadataFactory->getMetadataFor('Doctrine\Tests\ORM\Mapping\LoadEventTestEntity');
        self::assertTrue($classMetadata->hasField('about'));
        self::assertArrayHasKey('about', $classMetadata->reflFields);
        self::assertInstanceOf('ReflectionProperty', $classMetadata->reflFields['about']);
    }

    public function loadClassMetadata(\Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        $classMetadata->addProperty('about', Type::getType('string'), ['length' => 255]);
    }
}

/**
 * @Entity
 * @Table(name="load_event_test_entity")
 */
class LoadEventTestEntity
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Column(type="string", length=255)
     */
    private $name;

    private $about;
}
