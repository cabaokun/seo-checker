<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>ツール・ド・ナカハラ</title>
<link rel="stylesheet" href="style.css">
</head>	
<body>
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
					<td><input type="url" name="url" value="" size="40" autofocus placeholder="http://url.com/"></td>
				</tr>
				<tr>
					<th>対策ワード（カンマや「、」やスペースなどで区切ってください）</th>
					<td><input type="text" name="word" value="" size="70" placeholder="ワード１、ワード２、ワード３"></td>
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
					<td><input type="url" name="url" value="" size="40" placeholder="https://url.com/"></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="送信">
	</form>
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
	<div class="left-bg"></div>
	<div class="right-bg"></div>
</body>
</html>
