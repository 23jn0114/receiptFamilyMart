<?php
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // 受信したJSON文字列
    $post_data = json_decode($_POST['json'], true);
    
} else {
    echo "表示できました";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レシートの出力結果</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
        <table border="1">
            <tr>
                <th>取引先名</th>
                <th>取引先の住所</th>
                <th>取引先電話番号</th>
                <th>取引日</th>
                <th>取引時</th>
                <th>商品</th>
                <th>価格</th>
            </tr>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $post_data = json_decode($_POST['json'], true);
                foreach ($post_data as $i => $json_data) {
                    $data = $json_data;
                    $MerchantName = $data['MerchantName'];
                    $MerchantAddress = $data['MerchantAddress'];
                    $MerchantPhoneNumber = $data['MerchantPhoneNumber'];
                    $TransactionDate = $data['TransactionDate'];
                    $TransactionTime = $data['TransactionTime'];
                    $Items = $data['Items'];
                    $rowspan = count($Items);

                    foreach ($Items as $index => $item) {
                        echo "<tr>";
                        if ($index == 0) {
                            echo "<td rowspan='$rowspan'>" . $MerchantName . "</td>";
                            echo "<td rowspan='$rowspan'>" . $MerchantAddress . "</td>";
                            echo "<td rowspan='$rowspan'>" . $MerchantPhoneNumber . "</td>";
                            echo "<td rowspan='$rowspan'>" . $TransactionDate . "</td>";
                            echo "<td rowspan='$rowspan'>" . $TransactionTime . "</td>";
                        }
                        echo "<td>" . $item['name'] . "</td>";
                        echo "<td>" . $item['price'] . "</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </table>
    <?php } ?>
    
    
</body>
</html>