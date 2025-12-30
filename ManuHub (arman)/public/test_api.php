<?php
// FILE: public/test_api.php
echo "<h1>Network & API Test</h1>";

// 1. Test Basic Internet
$google = @file_get_contents("http://www.google.com");
if ($google) {
    echo "<p style='color:green'>✅ Basic Internet Connection: OK</p>";
} else {
    echo "<p style='color:red'>❌ Basic Internet Connection: FAILED. Check your WiFi or XAMPP DNS.</p>";
}

// 2. Test OpenAlex API (SSL)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.openalex.org/works?search=medicine");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Allow insecure for local testing
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, "ManuHubTest/1.0");

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200 && $response) {
    $data = json_decode($response, true);
    $count = count($data['results'] ?? []);
    echo "<p style='color:green'>✅ OpenAlex API: OK (Found $count results)</p>";
} else {
    echo "<p style='color:red'>❌ OpenAlex API: FAILED (HTTP Code: $httpCode)</p>";
    echo "CURL Error: " . curl_error($ch);
}
curl_close($ch);
?>