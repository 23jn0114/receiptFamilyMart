<?php

try {
    $conn = new PDO("sqlsrv:server = tcp:receipt-familymart-db.database.windows.net,1433; Database = receiptFamilyMartDB", "jn230114", "Pa\$\$word1234");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT * FROM Receipt");
    $receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->query("SELECT * FROM ReceiptItems");
    $receiptItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipts and Receipt Items</title>
</head>
<body>
    <a href="index.php">戻る</a>
    <h1>Receipts</h1>
    <table border='1'>
        <?php foreach ($receipts as $i => $row): ?>
            <?php if($i ===0): ?>
                <tr>
                    <?php foreach (array_keys($row) as $header): ?>
                        <th><?php echo $header; ?></th>
                    <?php endforeach; ?>
                </tr>
            <?php endif; ?>
            <tr>
                <?php foreach (array_values($row) as $column): ?>
                    <td><?php echo $column; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <h1>Receipt Items</h1>
    <table border='1'>
        <?php foreach ($receiptItems as $i => $row): ?>
            <?php if($i ===0): ?>
                <tr>
                    <?php foreach (array_keys($row) as $header): ?>
                        <th><?php echo $header; ?></th>
                    <?php endforeach; ?>
                </tr>
            <?php endif; ?>
            <tr>
                <?php foreach (array_values($row) as $column): ?>
                    <td><?php echo $column; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>
</body>
</html>
