<?php

namespace App\Services;

use App\Models\SsoUser;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class LegacyAuditSoapClient
{
    public function __construct(private CentralDosenAuthService $centralDosenAuthService)
    {
    }

    public function validateVehicleDispatch(Vehicle $vehicle, array $dispatchData, SsoUser $approvedBy): array
    {
        $xmlRequest = $this->buildEnvelope($vehicle, $dispatchData, $approvedBy);

        if (config('iae_integrations.legacy_audit.mode') === 'mock') {
            $receiptNumber = 'MOCK-SOAP-'.now()->format('YmdHis').'-'.$vehicle->id;
            $xmlResponse = $this->mockResponse($receiptNumber);

            return [
                'receipt_number' => $receiptNumber,
                'xml_request' => $xmlRequest,
                'xml_response' => $xmlResponse,
            ];
        }

        $response = Http::withToken($this->centralDosenAuthService->bearerToken())->withHeaders([
            'Content-Type' => 'text/xml; charset=UTF-8',
            'SOAPAction' => 'AuditRequest',
        ])->withBody($xmlRequest, 'text/xml')->post(config('iae_integrations.legacy_audit.endpoint'));

        $xmlResponse = $response->body();

        if (! $response->successful()) {
            throw new \RuntimeException('Legacy SOAP audit rejected the dispatch transaction');
        }

        return [
            'receipt_number' => $this->extractReceiptNumber($xmlResponse),
            'xml_request' => $xmlRequest,
            'xml_response' => $xmlResponse,
        ];
    }

    private function buildEnvelope(Vehicle $vehicle, array $dispatchData, SsoUser $approvedBy): string
    {
        $teamId = $this->escape((string) config('iae_integrations.legacy_audit.team_id'));
        $activityName = $this->escape((string) config('iae_integrations.legacy_audit.activity_name'));
        $logContent = json_encode([
            'service_name' => 'Vehicle-Service',
            'transaction_type' => 'VEHICLE_DISPATCH',
            'vehicle_code' => $vehicle->vehicle_code,
            'plate_number' => $vehicle->plate_number,
            'trip_reference' => $dispatchData['trip_reference'],
            'requester_name' => $dispatchData['requester_name'],
            'destination' => $dispatchData['destination'],
            'start_date' => $dispatchData['start_date'],
            'end_date' => $dispatchData['end_date'] ?? null,
            'approved_by' => $approvedBy->sso_subject,
            'approved_roles' => $approvedBy->roles->pluck('name')->values(),
            'requested_at' => now()->toIso8601String(),
        ], JSON_THROW_ON_ERROR);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit">
  <soap:Body>
    <iae:AuditRequest>
      <iae:TeamID>{$teamId}</iae:TeamID>
      <iae:ActivityName>{$activityName}</iae:ActivityName>
      <iae:LogContent><![CDATA[{$logContent}]]></iae:LogContent>
    </iae:AuditRequest>
  </soap:Body>
</soap:Envelope>
XML;
    }

    private function mockResponse(string $receiptNumber): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit">
  <soap:Body>
    <iae:AuditResponse>
      <iae:Status>SUCCESS</iae:Status>
      <iae:ReceiptNumber>{$receiptNumber}</iae:ReceiptNumber>
    </iae:AuditResponse>
  </soap:Body>
</soap:Envelope>
XML;
    }

    private function extractReceiptNumber(string $xmlResponse): string
    {
        $xml = new SimpleXMLElement($xmlResponse);
        $receiptNode = $xml->xpath('//*[local-name()="ReceiptNumber"]');

        return (string) ($receiptNode[0] ?? '');
    }

    private function escape(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_XML1);
    }
}
