<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mailer\Bridge\AhaSend\Tests\Webhook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Bridge\AhaSend\RemoteEvent\AhaSendPayloadConverter;
use Symfony\Component\Mailer\Bridge\AhaSend\Webhook\AhaSendRequestParser;
use Symfony\Component\Webhook\Client\RequestParserInterface;
use Symfony\Component\Webhook\Exception\RejectWebhookException;
use Symfony\Component\Webhook\Test\AbstractRequestParserTestCase;

class AhaSendMissingSignatureRequestParserTest extends AbstractRequestParserTestCase
{
    protected function createRequestParser(): RequestParserInterface
    {
        $this->expectException(RejectWebhookException::class);
        $this->expectExceptionMessage('Signature is required.');

        return new AhaSendRequestParser(new AhaSendPayloadConverter());
    }

    public static function getPayloads(): iterable
    {
        yield 'unsigned' => ['{"type":"message.delivered","timestamp":"2024-01-01T00:00:00Z","data":{}}', []];
    }

    protected function getSecret(): string
    {
        return 'nxLe:L:fZLb7J_Wb3uFeWX/&z4Ed#9&DxPL%Ud&:jhpAW1gLaR%AEFwfKnwp60cC';
    }

    protected function createRequest(string $payload): Request
    {
        return Request::create('/', 'POST', [], [], [], [
            'Content-Type' => 'application/json',
        ], $payload);
    }
}
