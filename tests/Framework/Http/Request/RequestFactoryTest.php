<?php

declare(strict_types=1);

namespace Tests\Framework\Http\Request;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Framework\Http\Request\RequestFactory;
use Framework\Http\Interface\RequestInterface;
use Framework\Http\Interface\RequestFactoryInterface;

class RequestFactoryTest extends TestCase
{
    private RequestFactory $requestFactory;

    protected function setUp(): void
    {
        $this->requestFactory = new RequestFactory();
    }

    /**
     * testInvokeReturnRequestInterface
     *
     * @return void
     */
    public function testInvokeReturnRequestInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $requestFactoryInterface = $this->createMock(RequestFactoryInterface::class);
        $equest = $this->requestFactory->__invoke($container, $requestFactoryInterface);

        $this->assertInstanceOf(RequestInterface::class, $equest);
    }
}
