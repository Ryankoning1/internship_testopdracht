<?php declare(strict_types=1);

namespace App\Tests\Acme\Controller;

use App\Acme\Controller\AcmeController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AcmeControllerTest extends TestCase
{
    private AcmeController $acmeController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->acmeController = new AcmeController();
    }

    /**
     * @dataProvider provideIndexActionData
     */
    public function testIndexAction($requestBody, $expectedResponseCode, $expectedResponseBody)
    {
        $request = new Request([], [], [], [], [], [], $requestBody);

        $response = $this->acmeController->indexAction($request);

        $this->assertEquals($expectedResponseCode, $response->getStatusCode());
        $this->assertEquals($expectedResponseBody, json_decode($response->getContent(), true));
    }

    public static function provideIndexActionData()
    {
        return [
            // Happy flow
            [
                [],
                Response::HTTP_OK,
                ['Welcome to Acme Api!']
            ]
        ];
    }
}
