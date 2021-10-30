<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\CreateCart;

use Exception;
use PHPUnit\Framework\TestCase;

final class CreateCartServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToCreateCart(): void
    {
        // given
        $request = new CreateCartRequest();
        $service = new CreateCartService();

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->cartId());
    }
}
