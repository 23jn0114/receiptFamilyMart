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
          <input type="file" name="receipt[]" id="receipt" multiple accept="image/*">
          <br><br>
          <input type="submit" value="アップロード">
      </form>
  </body>
</html>
