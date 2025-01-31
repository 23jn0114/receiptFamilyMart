<!DOCTYPE html>
<html lang="ja">
  <head>
      <meta charset="UTF-8">
      <title>レシートアップロードフォーム</title>
  </head>
  <body>
      <h1>レシートアップロードフォーム</h1>
      <form action="process_receipt.php" method="post" enctype="multipart/form-data">
          <label for="receipt">レシート画像を選択してください:</label>
          <input type="file" id="fileInput" name="file" accept="*/*">
        <button type="submit">Upload</button>
    </form>

    <script>
        $(document).ready(function () {
            const endpoint = "https://receiptfamilymart.cognitiveservices.azure.com";
            const modelId = "prebuilt-receipt";
            const key = "3TTIp6OuzzVMRsuNduo9XPcyM4qD4DcPqVfQTrkUZmoVzB8TxDyfJQQJ99BAACi0881XJ3w3AAALACOGkl43";
            $('#uploadForm').on('submit', function (e) {
                e.preventDefault();
                var file = $('#fileInput')[0].files[0];

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var base64File = e.target.result.split(',')[1];

                        $.ajax({
                            url: '{endpoint}/documentintelligence/documentModels/{modelId}:analyze?api-version=2024-11-30',
                            type: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Ocp-Apim-Subscription-Key': '{key}'
                            },
                            data: JSON.stringify({
                                base64Source: base64File
                            }),
                            success: function (data, textStatus, jqXHR) {
                                var operationLocation = jqXHR.getResponseHeader('Operation-Location');
                                console.log('Operation-Location:', operationLocation);
                                // Use the operationLocation value to fetch the results
                                fetchResults(operationLocation);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.error('Error:', errorThrown);
                            }
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            function fetchResults(operationLocation) {
                $.ajax({
                    url: operationLocation,
                    type: 'GET',
                    headers: {
                        'Ocp-Apim-Subscription-Key': '{key}'
                    },
                    success: function (data) {
                        console.log('Results:', data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching results:', errorThrown);
                    }
                });
            }
        });
    </script>
</body>
</html>
