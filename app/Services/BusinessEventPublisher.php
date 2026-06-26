<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class BusinessEventPublisher
{
    public function __construct(private CentralDosenAuthService $centralDosenAuthService)
    {
    }

    public function publishVehicleDispatched(array $payload): string
    {
        if (config('iae_integrations.rabbitmq.driver') === 'mock' || ! config('iae_integrations.rabbitmq.enabled')) {
            return 'mock_published';
        }

        if (config('iae_integrations.rabbitmq.driver') === 'http') {
            $response = Http::withToken($this->centralDosenAuthService->bearerToken())
                ->acceptJson()
                ->post(config('iae_integrations.rabbitmq.http_publish_url'), [
                    'exchange' => config('iae_integrations.rabbitmq.exchange'),
                    'routing_key' => config('iae_integrations.rabbitmq.routing_key'),
                    'payload' => $payload,
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('Failed to publish event notification to RabbitMQ Dosen');
            }

            return 'published_to_central_http';
        }

        $connection = new AMQPStreamConnection(
            config('iae_integrations.rabbitmq.host'),
            config('iae_integrations.rabbitmq.port'),
            config('iae_integrations.rabbitmq.username'),
            config('iae_integrations.rabbitmq.password'),
            config('iae_integrations.rabbitmq.vhost')
        );

        $channel = $connection->channel();
        $exchange = config('iae_integrations.rabbitmq.exchange');
        $routingKey = config('iae_integrations.rabbitmq.routing_key');

        $channel->exchange_declare($exchange, 'topic', false, true, false);

        $message = new AMQPMessage(json_encode($payload, JSON_THROW_ON_ERROR), [
            'content_type' => 'application/json',
            'delivery_mode' => 2,
        ]);

        $channel->basic_publish($message, $exchange, $routingKey);
        $channel->close();
        $connection->close();

        return 'published';
    }
}
