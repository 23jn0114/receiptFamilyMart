# receiptFamilyMart
ファミリーマートのレシートを読み込み抽出結果を分かりやすい形で画面に表示し、同じ内容のCSVファイルをダウンロードできるようにするプログラム。


【要件定義】
・レシートを読み込む（アップロード）ためのフォームをindex.phpとして用意してください
・添付のレシートを読み込み、商品名と値段、および合計金額を抽出してください。
※今回はテストデータとして添付の3枚の画像を使用してください。ファミリーマートのレシートだけ対応します。

・抽出結果を分かりやすい形で画面に表示し、同じ内容のCSVファイルをダウンロードできるようにしてください。
例：ファミリーマートレシート２の場合
「ザバスプロテインフルー　¥247, ◎天然水新潟県津南６０　¥108, 合計　¥355」が抽出できれば正解です。
※「軽」やその他の余計な文字は一切入れてはいけません

・写真をアップロードするフォーム、処理するPHP、読み取り内容を書き込むためのデータベースを作成する必要があります。

データベースはAzureの中で無料版または一番安価なものを使用してください。なお、写真は複数枚同時にアップロードします。
・OCRした結果の検証のため、OCRした内容をocr.logに書き込んで下さい。また、抽出した内容を画面表示し、ocr.log及びCSVファイルのダウンロードリンクも表示してください。
・無料の範囲で行うため、クラウド技術で勉強したAzure App Service、Azure AI Visionを使用してください。
（無料枠がなくなった人はご相談ください）

・提出するものは、Azure App ServiceのURL、ocr.logの中身とします。
