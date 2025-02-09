<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>レシートアップロードフォーム</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <h1>レシートアップロードフォーム</h1>
        <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
            <label for="receipt">レシート画像を選択してください:</label>
            <input type="file" id="fileInput" name="file" accept="*/*" multiple>
            <button type="submit">Upload</button>
        </form>
        <div id="loadingMessage" style="display:none;">Loading now...</div>

        <script>
            $(document).ready(function () {
                const endpoint = "https://receiptfamilymart.cognitiveservices.azure.com";
                const modelId = "prebuilt-receipt";
                const key = "3TTIp6OuzzVMRsuNduo9XPcyM4qD4DcPqVfQTrkUZmoVzB8TxDyfJQQJ99BAACi0881XJ3w3AAALACOGkl43";
                $('#uploadForm').on('submit', function (e) {
                    e.preventDefault();
                    $('#loadingMessage').show();
                    const files = $('#fileInput')[0].files;
                    if (files) {
                        Array.from(files).forEach(file => {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var base64File = e.target.result.split(',')[1];
                                $.ajax({
                                    url: `${endpoint}/documentintelligence/documentModels/${modelId}:analyze?api-version=2024-11-30`,
                                    type: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Ocp-Apim-Subscription-Key': key
                                    },
                                    data: JSON.stringify({
                                        base64Source: base64File
                                    }),
                                    success: function (data, textStatus, jqXHR) {
                                        var operationLocation = jqXHR.getResponseHeader('Operation-Location');
                                        console.log('Operation-Location:', operationLocation);
                                        // Use the operationLocation value to fetch the results
                                        wait(10).done(function () {
                                            fetchResults(operationLocation);
                                        })
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        console.error('Error:', errorThrown);
                                        $('#loadingMessage').hide();
                                    }
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });
                
                function fetchResults(operationLocation) {
                    $.ajax({
                        url: operationLocation,
                        type: 'GET',
                        headers: {
                            'Ocp-Apim-Subscription-Key': key
                        },
                        success: function (data) {
                            console.log('Results:', data);
                            if (data.status == "running") {
                                wait(10).done(function () {
                                    fetchResults(operationLocation);
                                })
                            } else {
                                let results = getData(data);
                                console.log(results);
                                displayResults(results);
                                $('#loadingMessage').hide();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error fetching results:', errorThrown);
                            $('#loadingMessage').hide();
                        }
                    });
                }
                function wait(sec) {
                    var objDef = new $.Deferred;
                    setTimeout(function () {
                        objDef.resolve(sec);
                    }, sec*1000);
                    return objDef.promise();
                };
                function getData(data) {
                    const documents = data.analyzeResult.documents;
                    let results = []
                    documents.forEach(doc => {
                        const fields = doc.fields;
                        const MerchantName = fields.MerchantName.valueString;
                        const MerchantAddress = fields.MerchantAddress.content;
                        const MerchantAddress_Obj = fields.MerchantAddress.valueAddress;
                        const MerchantPhoneNumber = fields.MerchantPhoneNumber.valuePhoneNumber;
                        const TransactionDate = fields.TransactionDate.valueDate;
                        const TransactionTime = fields.TransactionTime.valueTime;
                        const Total = fields.Total.valueCurrency.amount;
                        
                        let Items = [];
                        fields.Items.valueArray.forEach(v => {
                            const body = v.valueObject;
                            const itemName = body.Description.valueString;
                            const quantity = body.Quantity.valueNumber;
                            const price = body.TotalPrice.valueCurrency.amount
                            Items.push({name: itemName, quantity: quantity, price: price});
                        });
                        const res = {
                            MerchantName: MerchantName,
                            MerchantAddress: MerchantAddress,
                            MerchantAddress_Obj: MerchantAddress_Obj,
                            MerchantPhoneNumber: MerchantPhoneNumber,
                            TransactionDate: TransactionDate,
                            TransactionTime: TransactionTime,
                            Items: Items,
                            Total: Total,
                        };
                        results.push(res);
                    });
                    return results;
                }
                function displayResults(results) {
                    let table = $('#resultsTable');
                    if (table.length === 0) {
                        table = $('<table id="resultsTable" border="1"><tr><th>取引先名</th><th>取引先の住所</th><th>取引先電話番号</th><th>取引日</th><th>取引時</th><th>商品</th><th>個数</th><th>価格</th><th>合計</th></tr></table>');
                        $('body').append(table);
                    }
                    results.forEach(data => {
                        const MerchantName = data.MerchantName;
                        const MerchantAddress = data.MerchantAddress;
                        const MerchantPhoneNumber = data.MerchantPhoneNumber;
                        const TransactionDate = data.TransactionDate;
                        const TransactionTime = data.TransactionTime;
                        const Items = data.Items;
                        const rowspan = Items.length;

                        Items.forEach((item, index) => {
                            let row = '<tr>';
                            if (index === 0) {
                                row += `<td rowspan="${rowspan}">${MerchantName}</td>`;
                                row += `<td rowspan="${rowspan}">${MerchantAddress}</td>`;
                                row += `<td rowspan="${rowspan}">${MerchantPhoneNumber}</td>`;
                                row += `<td rowspan="${rowspan}">${TransactionDate}</td>`;
                                row += `<td rowspan="${rowspan}">${TransactionTime}</td>`;
                            }
                            row += `<td>${item.name}</td>`;
                            row += `<td>${item.quantity}</td>`;
                            row += `<td>${item.price}</td>`;
                            if (index === 0) {
                                row += `<td rowspan="${rowspan}">${data.Total}</td>`;
                            }
                            row += '</tr>';
                            table.append(row);
                        });
                    });
                }
            });
        </script>
    </body>
</html>
