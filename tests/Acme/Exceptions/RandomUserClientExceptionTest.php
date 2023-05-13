<?php declare(strict_types=1);

namespace App\Tests\Acme\Exceptions;

use App\Acme\Exceptions\RandomUserClientException;
use Exception;
use PHPUnit\Framework\TestCase;

class RandomUserClientExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $statusCode = 404;
        $message = 'Not Found';

        $previous = new Exception('Previous Exception');

        $exception = new RandomUserClientException($statusCode, $message, $previous);

        $expectedMessage = '[404] Error in Random User API Client and looks like error on our side: Not Found';


        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($statusCode, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
