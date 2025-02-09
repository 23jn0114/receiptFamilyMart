<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $post_data = json_decode($_POST['json'], true);
        $log = json_decode($_POST['log'], true);

        // Save log data to ocr.log file
        $log_file = fopen('ocr.log', 'w');
        fwrite($log_file, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        fclose($log_file);

        $pdo = new PDO("sqlsrv:server = tcp:receipt-familymart-db.database.windows.net,1433; Database = receiptFamilyMartDB", "jn230114", "Pa\$\$word1234");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $csv_data = [];
        $csv_data[] = ['MerchantName', 'MerchantAddress', 'MerchantPhoneNumber', 'TransactionDate', 'TransactionTime', 'ItemName', 'Quantity', 'Price'];

        foreach ($post_data as $i => $data) {
            $MerchantName = $data['MerchantName'];
            $MerchantAddress = $data['MerchantAddress'];
            $MerchantPhoneNumber = $data['MerchantPhoneNumber'];
            $TransactionDate = $data['TransactionDate'];
            $TransactionTime = $data['TransactionTime'];
            $Items = $data['Items'];

            $stmt = $pdo->prepare("INSERT INTO Receipt (MerchantName, MerchantAddress, MerchantPhoneNumber, TransactionDate, TransactionTime) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$MerchantName, $MerchantAddress, $MerchantPhoneNumber, $TransactionDate, $TransactionTime]);
            $receipt_id = $pdo->lastInsertId();

            foreach ($Items as $index => $item) {
                $name = $item['name'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                $stmt = $pdo->prepare("INSERT INTO ReceiptItems (ReceiptID, ItemName, ItemQuantity, ItemPrice) VALUES (?, ?, ?, ?)");
                $stmt->execute([$receipt_id, $name, $quantity, $price]);

                $csv_data[] = [$MerchantName, $MerchantAddress, $MerchantPhoneNumber, $TransactionDate, $TransactionTime, $name, $quantity, $price];
            }
        }
        $fileName = date('Y-m-d\TH:i:s') . '-receipts.csv';
        $csv_file = fopen($fileName, 'w');
        foreach ($csv_data as $row) {
            fputcsv($csv_file, $row);
        }
        fclose($csv_file);

        echo json_encode(['status' => 'success', 'message' => 'Data inserted and saved to CSV successfully', 'csv' => $fileName]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>