<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\Recaptcha\RecaptchaApi;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class RecaptchaApiTest extends TestCase
{
    protected RecaptchaApi $recaptchaApi;
    protected MockHandler $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $this->recaptchaApi = new RecaptchaApi(
            projectId: 'test-project',
            apiKey: 'test-key'
        );
        $this->recaptchaApi->setGuzzleClient($client);
    }

    public function testCreateAssessmentSendsCorrectPayload()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'tokenProperties' => ['valid' => true, 'action' => 'login'],
            'riskAnalysis' => ['score' => 0.9]
        ])));

        $result = $this->recaptchaApi->createAssessment('test-token', 'test-site-key', 'login');

        $request = $this->mockHandler->getLastRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertStringContainsString('projects/test-project/assessments', (string) $request->getUri());
        $this->assertStringContainsString('key=test-key', (string) $request->getUri());

        $body = json_decode((string) $request->getBody(), true);
        $this->assertEquals('test-token', $body['event']['token']);
        $this->assertEquals('test-site-key', $body['event']['siteKey']);
        $this->assertEquals('login', $body['event']['expectedAction']);

        $this->assertTrue($result['tokenProperties']['valid']);
    }

    public function testVerifyTokenReturnsTrueOnSuccess()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'tokenProperties' => ['valid' => true, 'action' => 'login'],
            'riskAnalysis' => ['score' => 0.9]
        ])));

        $isValid = $this->recaptchaApi->verifyToken('test-token', 'test-site-key', 'login');

        $this->assertTrue($isValid);
    }

    public function testVerifyTokenReturnsFalseOnInvalidToken()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'tokenProperties' => ['valid' => false],
            'riskAnalysis' => ['score' => 0.1]
        ])));

        $isValid = $this->recaptchaApi->verifyToken('test-token', 'test-site-key', 'login');

        $this->assertFalse($isValid);
    }

    public function testVerifyTokenReturnsFalseOnActionMismatch()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'tokenProperties' => ['valid' => true, 'action' => 'register'],
            'riskAnalysis' => ['score' => 0.9]
        ])));

        $isValid = $this->recaptchaApi->verifyToken('test-token', 'test-site-key', 'login');

        $this->assertFalse($isValid);
    }

    public function testVerifyTokenReturnsFalseOnLowScore()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'tokenProperties' => ['valid' => true, 'action' => 'login'],
            'riskAnalysis' => ['score' => 0.1]
        ])));

        $isValid = $this->recaptchaApi->verifyToken('test-token', 'test-site-key', 'login', 0.3);

        $this->assertFalse($isValid);
    }
}
