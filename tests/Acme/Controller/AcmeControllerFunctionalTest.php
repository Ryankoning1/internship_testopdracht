<?php declare(strict_types=1);

namespace App\Tests\Acme\Controller;

use App\Tests\Account\Helper\AuthenticationHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AcmeControllerFunctionalTest extends WebTestCase
{

    use AuthenticationHelper;

    protected function setUp(): void
    {
        $this->client = static::createClient();

    }

    public function testIndexRedirectAction()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(301, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Redirecting to', $this->client->getResponse()->getContent());
        $this->assertStringContainsString('/api/v1', $this->client->getResponse()->getContent());
    }

    public function testIndexFollowRedirectAction()
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/');

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('', $this->client->getResponse()->getContent());
    }

    public function testIndexAction()
    {
        $this->client->request('GET', '/api/v1');


        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('', $this->client->getResponse()->getContent());
    }
    public function testIndexActionWithUserAccount()
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/api/v1', [], [], $this->getUserAuthentication());


        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('["Welcome to Acme Api!"]', $this->client->getResponse()->getContent());
    }
    public function testIndexActionWithAdminAccount()
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/api/v1', [], [], $this->getAdminAuthentication());


        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('["Welcome to Acme Api!"]', $this->client->getResponse()->getContent());
    }
}
