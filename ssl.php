<html>
<head>
<meta charset="UTF-8">
<title>結果：ツール・ド・ナカハラ</title>
<link rel="stylesheet" href="style.css">
</head>	
<body>

<?php

//フォームから送信されたURLを取得
$url = $_GET['url'];

?>

<div id="contents-body">
	<h1>ツール・<span class="color-chenge">ド</span>・ナカハラ</h1>
	<p>チェッカーつくりました。←係り受け</p>
	<h2>SEOチェッカー</h2>
	<p>SEOチェッカーにURLを入力すれば、サイトのタイトルなどを取得する可能性がございます。</p>
	<form action="./checker.php" method="get">
		<table class="form-table">
			<tbody>
				<tr>
					<th>URL</th>
					<td><input type="url" name="url" value="" size="40"></td>
				</tr>
				<tr>
					<th>対策ワード（カンマや「、」やスペースなどで区切ってください）</th>
					<td><input type="text" name="word" value="" size="70"></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="チェック">
	</form>
	<h2>「https://」URLのステータスコードチェック用</h2>
	<form action="./ssl.php" method="get">
		<table class="form-table">
			<tbody>
				<tr>
					<th>URL</th>
					<td><input type="url" name="url" value="" size="40"></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="送信">
	</form>

<?php

$pattern = '/https:\/\/(www.)?(.*)/';

//URLが入力されているか
if($url){
	//「www.」なしのURLか
	if(!strpos($url,'www.')!== false){
		$noWwwUrl = $url;
		//「www.」ありのURLを生成
		$string = 'https://www.$2';
		$wwwUrl = preg_replace($pattern, $string, $url);
	}else {
		$wwwUrl = $url;
		//「www.」なしのURLを生成
		$string = 'https://$2';
		$noWwwUrl = preg_replace($pattern, $string, $url);
	}

$slashUnders = array('index.html',
					'index.htm',
					'index.php',
					'index.cgi',
					'');

echo '<ul class="general-list">';

foreach($slashUnders as $slashUnder){
	echo '<li>'.$wwwUrl.$slashUnder.'</li>';
}

foreach($slashUnders as $slashUnder){
	echo '<li>'.$noWwwUrl.$slashUnder.'</li>';
}

echo '</ul>';

?>

<?php

}else{
	echo 'URLが正しく入力されていないようです・・・。';
}

?>
	<h2>ミドルコーディング用</h2>
	<p>「キーワード/URL」をエクセルに貼り付けられるよう&lt;table&gt;形式に変換します。</p>
	<form action="./middle.php" method="get">
		<table>
			<tbody>
				<tr>
					<th>キーワード/URL</th>
					<td><textarea name="middle" id="middle" cols="100" rows="30"></textarea></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="変換">
	</form>
</div>

</body>
</html>