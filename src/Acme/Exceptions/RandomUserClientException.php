<?php declare(strict_types=1);

namespace App\Acme\Exceptions;

use Exception;
use Throwable;

class RandomUserClientException extends Exception
{
    public function __construct(int $statusCode = 500, string $message = "", ?Throwable $previous = null)
    {
        $message = 'Error in Random User API Client '.($statusCode >= 500 ? 'and cannot recover' : 'and looks like error on our side').': '. $message;
        $message = '['.$statusCode.'] '.$message;

        parent::__construct($message, $statusCode, $previous);
    }

}
