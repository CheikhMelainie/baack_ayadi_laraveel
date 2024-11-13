<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    private $MERCHANT_ID = "2025014911175760";
    private $MASRVI_URL = "https://22200.tagpay.fr/online/online.php";

    public function getSessionId()
    {
        try {
            // Form the URL with the merchant ID
            $url = "{$this->MASRVI_URL}?merchantid={$this->MERCHANT_ID}";

            // Make the request to the external API
            $response = Http::get($url);
            $responseText = $response->body();

            // Check if the response starts with "OK"
            if (str_starts_with($responseText, "OK")) {
                // Extract sessionID from the response
                $sessionId = substr($responseText, 3);
                return response()->json(["session_id" => $sessionId], 200);
            } else {
                return response()->json(["error" => "Failed to retrieve session ID"], 400);
            }

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function createTransaction(Request $request)
    {
        try {
            // Extract data from request
            $amount = $request->input('amount');
            $currency = $request->input('currency', '929');
            $description = $request->input('description', 'Achat en ligne');
            $brand = $request->input('brand', 'bmci');
            $purchaseRef = $request->input('purchase_ref', '784512456745');
            $phoneNumber = $request->input('phonenumber');
            $acceptUrl = $request->input('accepturl', 'https://165.227.85.96/ayadi/payment/success/');
            $declineUrl = $request->input('declineurl', 'https://165.227.85.96/ayadi/payment/decline/');
            $cancelUrl = $request->input('cancelurl', 'https://165.227.85.96/ayadi/payment/cancel/');
            $text = $request->input('text', 'Thank you for your purchase');

            // Multiply the amount by 100
            if ($amount !== null) {
                $amount = (int)($amount * 100);
            }

            // API call to retrieve the session ID
            $sessionResponse = Http::get('https://165.227.85.96/ayadi/get-session-id/');
            
            if ($sessionResponse->successful()) {
                $sessionId = $sessionResponse->json()['session_id'] ?? null;

                if (!$sessionId) {
                    return response()->json(["error" => "Session ID not found"], 400);
                }

                // Prepare the data to be sent to the external API
                $paymentData = [
                    "merchantid" => $this->MERCHANT_ID,
                    "sessionid" => $sessionId,
                    "amount" => $amount,
                    "currency" => $currency,
                    "brand" => $brand,
                    "purchaseref" => $purchaseRef,
                    "description" => $description,
                    "phonenumber" => $phoneNumber,
                    "accepturl" => $acceptUrl,
                    "declineurl" => $declineUrl,
                    "cancelurl" => $cancelUrl,
                    "text" => $text
                ];

                // Make a POST request to the external API
                $externalResponse = Http::asForm()->post($this->MASRVI_URL, $paymentData);

                if ($externalResponse->successful()) {
                    // Modify the HTML content for proper CSS and JS linking
                    $htmlContent = $this->modifyLinks($externalResponse->body());
                    return response($htmlContent, 200)->header('Content-Type', 'text/html');
                } else {
                    return response()->json(["error" => "Transaction failed", "details" => $externalResponse->body()], 400);
                }

            } else {
                return response()->json(["error" => "Failed to retrieve session ID"], 400);
            }

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    private function modifyLinks($htmlContent)
    {
        $baseUrl = "https://22200.tagpay.fr";
        $htmlContent = preg_replace('/href="([^"]+)"/', 'href="' . $baseUrl . '$1"', $htmlContent);
        $htmlContent = preg_replace('/src="([^"]+)"/', 'src="' . $baseUrl . '$1"', $htmlContent);
        return $htmlContent;
    }
}
