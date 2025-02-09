<?php
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // 受信したJSON文字列
    $post_data = json_decode($_POST['json'], true)
    foreach($json_data as $i => $json_data) {
        // JSON文字列をPHPの配列に変換
        $data = $json_data[0];
        
        // 各キーに対応する変数にデータを格納
        $MerchantName = $data['MerchantName'];
        $MerchantAddress = $data['MerchantAddress'];
        $MerchantAddress_Obj = $data['MerchantAddress_Obj'];
        $MerchantPhoneNumber = $data['MerchantPhoneNumber'];
        $TransactionDate = $data['TransactionDate'];
        $TransactionTime = $data['TransactionTime'];
        $Items = $data['Items'];
        
        // $item_name = $Items[$i]['name'];
        // $item_price = $Items[$i]['price'];
        
        // 確認のために各変数を出力
        echo "MerchantName: " . $MerchantName . "\n";
        echo "MerchantAddress: " . $MerchantAddress . "\n";
        echo "MerchantPhoneNumber: " . $MerchantPhoneNumber . "\n";
        echo "TransactionDate: " . $TransactionDate . "\n";
        echo "TransactionTime: " . $TransactionTime . "\n";
        echo "Items: " . print_r($Items, true) . "\n";
        echo "houseNumber: " . $houseNumber . "\n";
        echo "city: " . $city . "\n";
        echo "state: " . $state . "\n";
        echo "streetAddress: " . $streetAddress . "\n";
        echo "cityDistrict: " . $cityDistrict . "\n";
        echo "item_name: " . $item_name . "\n";
        echo "item_price: " . $item_price . "\n";
    }
}
?>