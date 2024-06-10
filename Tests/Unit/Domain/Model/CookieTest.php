<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class CookieTest extends UnitTestCase
{
    /**
     * @var \GdprExtensionsCom\GdprExtensionsComCm\Domain\Model\Cookie|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \GdprExtensionsCom\GdprExtensionsComCm\Domain\Model\Cookie::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForStringSetsCategory(): void
    {
        $this->subject->setCategory('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('category'));
    }

    /**
     * @test
     */
    public function getDomainReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDomain()
        );
    }

    /**
     * @test
     */
    public function setDomainForStringSetsDomain(): void
    {
        $this->subject->setDomain('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('domain'));
    }

    /**
     * @test
     */
    public function getPlatformReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPlatform()
        );
    }

    /**
     * @test
     */
    public function setPlatformForStringSetsPlatform(): void
    {
        $this->subject->setPlatform('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('platform'));
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName(): void
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('name'));
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription(): void
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('description'));
    }

    /**
     * @test
     */
    public function getSequenceReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getSequence()
        );
    }

    /**
     * @test
     */
    public function setSequenceForStringSetsSequence(): void
    {
        $this->subject->setSequence('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('sequence'));
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function setTypeForStringSetsType(): void
    {
        $this->subject->setType('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('type'));
    }
}
