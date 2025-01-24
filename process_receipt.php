<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['receipt'])) {
    // Azure AI Visionの設定
    $endpoint = 'https://<your-resource-name>.cognitiveservices.azure.com/';
    $subscription_key = '<your-subscription-key>';
    
    // アップロードされたファイルの処理
    $files = $_FILES['receipt']['tmp_name'];
    $results = [];
    
    foreach ($files as $file) {
        $imageData = file_get_contents($file);
        $ocrResult = performOCR($endpoint, $subscription_key, $imageData);
        $parsedResult = parseOCRResult($ocrResult);
        $results[] = $parsedResult;
        logOCRResult($ocrResult); // OCR結果をログに書き込む
    }
    
    // 結果を表示
    displayResults($results);
    // CSVファイルの生成とダウンロードリンクの表示
    generateCSV($results);
}

// Azure AI Vision OCRを実行する関数
function performOCR($endpoint, $subscription_key, $imageData) {
    $url = $endpoint . 'vision/v3.2/ocr';
    $headers = [
        'Ocp-Apim-Subscription-Key: ' . $subscription_key,
        'Content-Type: application/octet-stream'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $imageData);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// OCR結果をパースする関数
function parseOCRResult($ocrResult) {
    $items = [];
    $total = 0;
    foreach ($ocrResult['regions'] as $region) {
        foreach ($region['lines'] as $line) {
            $text = implode(' ', array_column($line['words'], 'text'));
            if (preg_match('/(合計|TOTAL)\s+¥?(\d+)/i', $text, $matches)) {
                $total = intval($matches[2]);
            } elseif (preg_match('/(.+?)\s+¥?(\d+)/', $text, $matches)) {
                $items[] = [
                    'name' => $matches[1],
                    'price' => intval($matches[2])
                ];
            }
        }
    }
    return ['items' => $items, 'total' => $total];
}

// OCR結果をログに書き込む関数
function logOCRResult($ocrResult) {
    $logFile = 'ocr.log';
    file_put_contents($logFile, print_r($ocrResult, true), FILE_APPEND | LOCK_EX);
}

// 結果を表示する関数
function displayResults($results) {
    echo '<h2>抽出結果</h2>';
    foreach ($results as $result) {
        echo '<ul>';
        foreach ($result['items'] as $item) {
            echo '<li>' . htmlspecialchars($item['name']) . ' ¥' . htmlspecialchars($item['price']) . '</li>';
        }
        echo '<li><strong>合計 ¥' . htmlspecialchars($result['total']) . '</strong></li>';
        echo '</ul>';
    }
}

// CSVファイルを生成する関数
function generateCSV($results) {
    $csvFile = 'results.csv';
    $fp = fopen($csvFile, 'w');
    
    foreach ($results as $result) {
        foreach ($result['items'] as $item) {
            fputcsv($fp, [$item['name'], '¥' . $item['price']]);
        }
        fputcsv($fp, ['合計', '¥' . $result['total']]);
    }
    
    fclose($fp);
    echo '<a href="' . $csvFile . '" download>CSVファイルをダウンロード</a>';
}
?>
