<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>結果：ツール・ド・ナカハラ</title>
<link rel="stylesheet" href="style.css">
</head>	
<body>

<?php
//フォームから送信されたキーワード/URLを取得
$middle = $_GET['middle'];
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
	<h2>ミドルコーディング用</h2>
	<p>「キーワード/URL」をエクセルに貼り付けられるよう&lt;table&gt;形式に変換します。</p>
	<form action="./middle.php" method="get">
		<table>
			<tbody>
				<tr>
					<th>キーワード/URL</th>
					<td><textarea name="middle" id="middle" cols="100" rows="30" onclick="this.select();"><?php echo $middle?></textarea></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="変換">
	</form>
<a href="./middleSource.php" target="_blank">ソースはこちら（アドバイスございましたらお願いいたします。）</a>

<?php

function formatCon($con) {
	//ワードとワードの間にカンマを入れる
	$pattern = '」「';
	$con = str_replace($pattern,',',$con);
	//「」を削除する
	$pattern = array('「','」');
	$con = str_replace($pattern,'',$con);

	$pattern = '(\s(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)\s)';
	$con = preg_replace($pattern,"URL-$1-URL",$con);
	$cons = explode("-URL",$con);
	$cons = array_filter($cons,"strlen");
	//空要素が出てしまうため削除
	echo '<textarea name="" cols="100" rows="30" onclick="this.select();">';
	foreach($cons as $con){
		$wordUrls = explode("URL-",$con);
		//$wordUrls = array_filter($wordUrls,"strlen");
		//var_dump($wordUrls);
		$words = explode(",",$wordUrls[0]);
		foreach($words as $word){
			echo $word." ",$wordUrls[1]."\n";
		}
	}
	echo '</textarea>';
	return $cons;
}

function excelformat($wordUrls){
	//配列を定義
	$words = array();
	$urls = array();
	//URLとキーワードの順番を入れ替えて表示したいため、URLとキーワードに分類
	foreach($wordUrls as $wordUrl){
		if(strpos($wordUrl,'http')===false){
			array_push($words,$wordUrl);
		}else{
			array_push($urls,$wordUrl);
		}
	}
	echo '<table class="middle-table">';
	//対策ワードを分けてブラウザに表示
	for($i=0; $i<count($urls); $i++){
		echo '<tr>';
			echo '<th>'.$urls[$i].'</th>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>'.$words[$i].'</td>';
		echo '</tr>';
	}
	echo '</table>';
	return $urls;
}

//キーワード/URLが入力されているか
if($middle){

if(strpos($middle,'「') !== false){
	echo '<h3>「」書きバラし用</h3>';
	$right = formatCon($middle);
}

//URLとキーワードの間の半角スペース、URLの後ろの半角スペースをカンマに変更
$pattern = '(\s(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)\s)';
$middle = preg_replace($pattern,',$1,',$middle);

//カンマで区切り配列にする
$middles = explode(',',$middle);

//Excelに貼り付ける形式にする
$urls = excelformat($middles);

//配列から重複を削除
$urls = array_unique($urls);

echo '<h3>ステータスコードチェック用</h3>';
echo '<a href="https://httpstatus.io/" target="_blank">Bulk URL HTTP Status Code</a>';

echo '<ul>';
foreach($urls as $url){
		echo '<li>'.$url.'</li>';
}
echo '</ul>';

echo '<h3>サイトインデックスチェック用</h3>';
echo '<ul>';
foreach($urls as $url){
		echo '<li><a href="http://www.google.co.jp/search?ie=UTF-8&q=site:'.$url.'" target="_blank">site:'.$url.'</a></li>';
}
echo '</ul>';

}else{
	echo 'キーワード/URLが正しく入力されていないようです・・・。';
}

?>

</div>
</body></html>