<?php

namespace Oro\Bundle\FrontendBundle\Tests\Unit\GuestAccess\Provider;

use Oro\Bundle\FrontendBundle\GuestAccess\Provider\ChainGuestAccessAllowedUrlsProvider;
use Oro\Bundle\FrontendBundle\GuestAccess\Provider\GuestAccessAllowedUrlsProviderInterface;
use PHPUnit\Framework\TestCase;

class ChainGuestAccessAllowedUrlsProviderTest extends TestCase
{
    public function testGetAllowedUrlsPatternsWithoutRegisteredProviders(): void
    {
        $chainProvider = new ChainGuestAccessAllowedUrlsProvider([]);

        $this->assertEmpty($chainProvider->getAllowedUrlsPatterns());
    }

    public function testGetAllowedUrlsPatterns(): void
    {
        $providerAPatterns = ['^/pattern1$', '^/pattern2$', '^/pattern3$'];
        $providerA = $this->mockProvider($providerAPatterns);

        $providerBPatterns = ['^/pattern4$', '^/pattern5$'];
        $providerB = $this->mockProvider($providerBPatterns);

        $chainProvider = new ChainGuestAccessAllowedUrlsProvider([$providerA, $providerB]);

        $this->assertEquals(
            \array_merge($providerAPatterns, $providerBPatterns),
            $chainProvider->getAllowedUrlsPatterns()
        );
    }

    /**
     * @param string[] $patterns
     * @return GuestAccessAllowedUrlsProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockProvider(array $patterns): GuestAccessAllowedUrlsProviderInterface
    {
        $provider = $this->createMock(GuestAccessAllowedUrlsProviderInterface::class);
        $provider->expects($this->once())
            ->method('getAllowedUrlsPatterns')
            ->willReturn($patterns);

        return $provider;
    }
}
