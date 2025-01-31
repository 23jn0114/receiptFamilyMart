<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>curl to jQuery AJAX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>API Response</h1>
    <pre id="response"></pre>

    <script>
        $(document).ready(function() {
            const endpoint = "https://receiptfamilymart.cognitiveservices.azure.com/";
            const modelId = "prebuilt-receipt";
            const apiKey = "3TTIp6OuzzVMRsuNduo9XPcyM4qD4DcPqVfQTrkUZmoVzB8TxDyfJQQJ99BAACi0881XJ3w3AAALACOGkl43";
            const documentUrl = "https://raw.githubusercontent.com/Azure/azure-sdk-for-python/main/sdk/formrecognizer/azure-ai-formrecognizer/tests/sample_forms/receipt/contoso-receipt.png";

            $.ajax({
                url: `${endpoint}/documentintelligence/documentModels/${modelId}:analyze?api-version=2024-11-30`,
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Ocp-Apim-Subscription-Key': apiKey
                },
                data: JSON.stringify({ 'urlSource': documentUrl }),
                success: function(response) {
                    $('#response').text(response));
                },
                error: function(xhr, status, error) {
                    $('#response').text(`Error: ${xhr.status} ${xhr.statusText}`);
                }
            });
        });
    </script>
</body>
</html>
