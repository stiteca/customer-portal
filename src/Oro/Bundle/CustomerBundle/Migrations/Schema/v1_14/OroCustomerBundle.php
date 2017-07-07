<?php

namespace Oro\Bundle\CustomerBundle\Migrations\Schema\v1_14;

use Doctrine\DBAL\Schema\Schema;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\CustomerBundle\Entity\CustomerUserRole;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\LoadEntityConfigStateMigrationQuery;
use Oro\Bundle\MigrationBundle\Migration\Extension\DataStorageExtension;
use Oro\Bundle\MigrationBundle\Migration\Extension\DataStorageExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroCustomerBundle implements
    Migration,
    ContainerAwareInterface,
    DataStorageExtensionAwareInterface
{
    use ContainerAwareTrait;

    /** @var DataStorageExtension */
    private $dataStorageExtension;

    /**
     * {@inheritdoc}
     */
    public function setDataStorageExtension(DataStorageExtension $dataStorageExtension)
    {
        $this->dataStorageExtension = $dataStorageExtension;
    }

    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $configManager = $this->container->get('oro_entity_config.config_manager');
        $provider = $configManager->getProvider('extend');

        $entityConfig = $provider->getConfig(CustomerUser::class);
        $entityConfig->set('is_extend', true);
        $entityConfig->set('state', ExtendScope::STATE_ACTIVE);
        $configManager->persist($entityConfig);

        $entityConfig = $provider->getConfig(CustomerUserRole::class);
        $entityConfig->set('is_extend', true);
        $entityConfig->set('state', ExtendScope::STATE_ACTIVE);
        $configManager->persist($entityConfig);

        $configManager->flush();
        $configManager->clear();

        $queries->addQuery(
            new LoadEntityConfigStateMigrationQuery($this->dataStorageExtension)
        );
    }
}
