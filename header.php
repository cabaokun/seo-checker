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

//入力された対策ワードを取得
$word = $_GET['word'];

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
					<td><textarea name="middle" id="middle" cols="80" rows="30"></textarea></td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="チェック">
	</form>
<a href="./source.php" target="_blank">ソースはこちら（アドバイスございましたらお願いいたします。）</a>

<?php

//URLが入力されているか
if($url){
	//URLをもとにサイトのソースを取得
	$context = stream_context_create(array('http'=>array('user_agent'=>'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)')));
	$html = file_get_contents($url,false,$context);
}else{
	echo 'URLが正しく入力されていないようです・・・。';
}

//いろいろな書式のh1タグ整形
$pattern = '/<[hH]1.*?>/';
$html = preg_replace($pattern,'<h1>',$html);

//いろいろな書式のdescriptionを整形する正規表現を変数へ
$pattern1 = '/<[mM][eE][tT][aA].*?[cC][oO][nN][tT][eE][nN][tT](.*?)[dD][eE][sS][cC][rR][iI][pP][tT][iI][oO][nN].*?>/';
$pattern2 = '/<[mM][eE][tT][aA].*?[dD][eE][sS][cC][rR][iI][pP][tT][iI][oO][nN].*?[cC][oO][nN][tT][eE][nN][tT](.*?)>/';
$string = '<meta name="description" content$1>';

//正規表現をもとにdescriptionのcontentが前か後かパターンごとに置換
if(preg_match($pattern1,$html)){
	$html = preg_replace($pattern1,$string,$html);
}elseif(preg_match($pattern2,$html)){
	$html = preg_replace($pattern2,$string,$html);
}

//取得したhtmlのタグを無効化し文字列に変換
$html = mb_convert_encoding($html, "UTF-8", "UTF-8, SJIS, Shift_JIS, sjis-win, eucjp-win, JIS, euc-jp ");
$html = htmlspecialchars($html);
$html = mb_ereg_replace('\r\n', '<br />', $html);
$html = mb_ereg_replace('\n', '<br />', $html);
$html = mb_ereg_replace('\r', '<br />', $html);

echo '<h1>調査URL「'.$url.'」</h1>';

//半角スペース、全角スペースをカンマに変換
$search = array(' ','　','、');
$word = str_replace($search,',',$word);

//カンマで区切り配列にする
$words = explode(',',$word);

//配列から重複を削除
$words = array_unique($words);

//対策ワードを分けてブラウザに表示
echo '<ul>';

$i = 1;
foreach($words as $word){
	echo '<li>対策ワード'.$i.'【'.$word.'】</li>';
	$i++;
}

echo '</ul>';


echo '<h2>タイトル・デスクリプション</h2><table><tbody><tr><th>title</th>';

$pattern = '/<[tT][iI][tT][lL][eE]>/';
$html = preg_replace($pattern,'<title>',$html);

//titleタグがあるか
if(strpos($html,'title') !== false){
	//titleを抜き出す
	$title = tagExt($html,'title');
	echo '<td>'.$title[0].'</td>';

	//文字数が32文字を超えていたら色を変えて表示するためクラス名を付ける
	if(mb_strlen($title[0],"UTF-8") > 32){
		echo '<td class="len len-over">';
	}else{
		echo '<td class="len">';
	}
	echo mb_strlen($title[0],"UTF-8").'文字</td>';
}else{
	echo '<td>なし</td>';
}

echo '</tr>';


//descriptionが存在するか
if(strpos($html,'description') !== false){
	//descriptionの後の=""の中身を取り出す
	$description = tagExt($html,'description');
	echo '<tr><th>description</th><td>'.$description[0].'</td>';

	//文字数が120文字を超えていたら色を変えて表示するためクラス名を付ける
	if(mb_strlen($description[0],"UTF-8") > 120){
		echo '<td class="len len-over">';
	}else{
		echo '<td class="len">';
	}
	echo mb_strlen($description[0],"UTF-8").'文字</td>';

}else{
	echo '<tr><th>description</th><td>なし</td>';
}

echo '</tr></tbody></table>';

echo '<h2>見出しタグ</h2><table><tbody>';

//h1タグを文字列に変換して変数へ
$headOpen1 = htmlspecialchars('<h1>');
$headClose1 = htmlspecialchars('</h1>');
$countH1 = mb_substr_count($html,$headOpen1);

//<h1>タグが存在するか
if(strpos($html,$headOpen1) !== false){
	//<h1>の開始タグでソースを区切る
	$sourceHalf = explode($headOpen1,$html);
	for($i=1; $i<=$countH1; $i++){
		$headContents1 = explode($headClose1,$sourceHalf[$i]);
		if($countH1 > 1){
			echo '<tr><th>h1-'.$i.'個目</th><td>';
		}else{
			echo '<tr><th>h1</th><td>';
		}
		echo $headContents1[0].'</th><td class="len">'.mb_strlen($headContents1[0],"UTF-8").'文字</td>';
		if(strpos($headContents1[0],'img')){
			echo '<td>&lt;h1&gt;タグはテキストを推奨いたします。</td>';
		}
		echo '</tr>';
	}
}else{
	echo '<tr><th>h1</th><td>なし</td></tr>';
}

echo '</tbody></table>';

echo '<h2>対策ワード</h2><table><thead><tr><th>場所</th>';

foreach($words as $word){
    echo '<th>'.$word.'</th>';
}

echo '</tr></thead><tbody><tr><th>title</th>';

foreach($words as $word){
	if(strpos($title[0],$word) !== false){
		echo '<td class="t-center">○</td>';
	}else{
		echo '<td class="t-center">×</td>';
	}
}

echo '</tr>';

$sourceHalf = explode($headOpen1,$html);
for($i=1; $i<=$countH1; $i++){
	if($countH1 > 1){
		echo '<tr><th>h1-'.$i.'個目</th>';
	}else{
		echo '<tr><th>h1</th>';
	}
		$headContents1 = explode($headClose1,$sourceHalf[$i]);
	foreach($words as $word){
		if(strpos($headContents1[0],$word) !== false){
			echo '<td class="t-center">○</td>';
		}else{
			echo '<td class="t-center">×</td>';
		}
	}
	echo '</tr>';
}

echo '</tr></tbody></table>';


/***
ソースとタグからタグ内の文言を抜き出す
***/
//$html=ソース,$ele=取得要素
function tagExt($html,$ele){
	switch($ele){
	case 'title';
		$expSta = '<title>';
		$expEnd = '</title>';
		break;
	case 'description';
		$expSta = '<meta name="description" content="';
		$expEnd = '"';
		break;
	case '<h1>';
		$expSta = '<h1>';
		$expEnd = '</h1>';
		break;
	default:
		$expSta = '';
		$expEnd = '';
		break;
	}

	$expSta = htmlspecialchars($expSta);
	$expEnd = htmlspecialchars($expEnd);

	if($ele == '<h1>'){
		for($i=1; $i<substr_count($html,$ele); $i++){
			$sourceHalf = explode($expSta,$html);
			$ele = explode($expEnd,$sourceHalf[$i]);
		}
	}else{
		$sourceHalf = explode($expSta,$html);
		$ele = explode($expEnd,$sourceHalf[1]);
	}
	return $ele;
}

?>

<a href="./source.php" target="_blank">ソースはこちら（アドバイスございましたらお願いいたします。）</a>
</div>

</body>
</html>