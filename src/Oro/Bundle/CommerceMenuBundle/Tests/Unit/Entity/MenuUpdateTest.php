<?php

namespace Oro\Bundle\CommerceMenuBundle\Tests\Unit\Entity;

use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\CommerceMenuBundle\Entity\MenuUpdate;
use Oro\Bundle\CommerceMenuBundle\Entity\MenuUserAgentCondition;
use Oro\Bundle\CommerceMenuBundle\Tests\Unit\Entity\Stub\MenuUpdateStub;
use Oro\Bundle\WebCatalogBundle\Entity\ContentNode;
use Oro\Component\Testing\Unit\EntityTestCaseTrait;

class MenuUpdateTest extends \PHPUnit\Framework\TestCase
{
    use EntityTestCaseTrait;

    public function testProperties()
    {
        $properties = [
            ['condition', 'condition'],
            ['screens', ['mobile' => ['class' => 'test']]],
            ['contentNode', new ContentNode(), false],
            ['systemPageRoute', 'sampleRoute', false],
        ];

        static::assertPropertyAccessors(new MenuUpdate(), $properties);
    }

    public function testPropertiesCollections()
    {
        $properties = [
            ['menuUserAgentConditions', new MenuUserAgentCondition()],
        ];

        static::assertPropertyCollections(new MenuUpdate(), $properties);
    }

    public function testGetExtras()
    {
        $image = new File();
        $priority = 10;

        $update = new MenuUpdateStub();
        $screens = ['sample-screen'];
        $update
            ->setImage($image)
            ->setScreens($screens)
            ->setCondition('test condition')
            ->setIcon('test-icon')
            ->setPriority($priority)
            ->setDivider(true);

        $expected = [
            'image' => $image,
            'screens' => $screens,
            'condition' => 'test condition',
            'divider' => true,
            'userAgentConditions' => $update->getMenuUserAgentConditions(),
            'translate_disabled' => false,
            'content_node' => null,
            'system_page_route' => null,
            'position' => $priority,
            'icon' => 'test-icon',
        ];

        $this->assertSame($expected, $update->getExtras());
    }

    /**
     * @dataProvider getTargetTypeDataProvider
     *
     * @param MenuUpdate $menuUpdate
     * @param string|null $expectedTargetType
     */
    public function testGetTargetType(MenuUpdate $menuUpdate, ?string $expectedTargetType): void
    {
        $this->assertSame($expectedTargetType, $menuUpdate->getTargetType());
    }

    /**
     * @return array
     */
    public function getTargetTypeDataProvider(): array
    {
        return [
            'uri target type' => [
                'menuUpdate' => (new MenuUpdate())->setUri('uri'),
                'expectedTargetType' => MenuUpdate::TARGET_URI,
            ],
            'system_page target type' => [
                'menuUpdate' => (new MenuUpdate())->setSystemPageRoute('sample_route'),
                'expectedTargetType' => MenuUpdate::TARGET_SYSTEM_PAGE,
            ],
            'content_node target type' => [
                'menuUpdate' => (new MenuUpdate())->setContentNode($this->createMock(ContentNode::class)),
                'expectedTargetType' => MenuUpdate::TARGET_CONTENT_NODE,
            ],
            'no target type' => [
                'menuUpdate' => (new MenuUpdate()),
                'expectedTargetType' => null,
            ],
            'all target fields are filled' => [
                'menuUpdate' => (new MenuUpdate())
                    ->setUri('uri')
                    ->setSystemPageRoute('sample_route')
                    ->setContentNode($this->createMock(ContentNode::class)),
                'expectedTargetType' => 'content_node',
            ],
        ];
    }
}
