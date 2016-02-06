<?

if (!defined('IN_MYTOOLS')) {
	header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
	echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL {$_SERVER['PHP_SELF']} was not found on this server.</p>
</body></html>
EOF;
	die();
}

require_once 'include_libs/myqrcode.php';

$api = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&show=[firstid|all|通知ID]";

if (isset($_GET['show'])) {
	require_once 'include_libs/simple_html_dom.php';
	$html = new simple_html_dom();
	if ($_GET['show'] == 'all' || $_GET['show'] == 'firstid') {
		$html->load_file('http://ded.nuaa.edu.cn/HomePage/articles/?type=list');
		$as = $html->find('.tit1 a');
		$spans = $html->find('.time1 span');
		$result = [];
		for ($i = 0; $i < count($as); $i++) {
			$id = intval(str_replace('?type=detail&amp;id=', '', $as[$i]->href));
			if ($_GET['show'] == 'firstid') {
				$result = ['id' => $id];
				break;
			}
			$result []= [
				'id' => $id,
				'title' => $as[$i]->innertext,
				'date' => $spans[$i]->innertext
			];
		}
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	else {
		$id = intval($_GET['show']);
		$html->load_file('http://ded.nuaa.edu.cn/HomePage/articles/?type=detail&id=' . $id);
		$content = $html->find('.content');
		$result = trim(strip_tags($content[0], '<a>'));
		$result = preg_replace('/<\/a>\ +<a/', '</a><a', $result);
		$result = preg_replace('/通\ \ 知/', '通知', $result);
		$result = preg_replace('/发布日期：\ /', '发布日期：', $result);
		$result = preg_replace('/点击数：\ \ /', '点击数：', $result);
		$result = preg_replace('/(\t|\s{2,}|\　)+/', "\n", $result);
		$result = preg_replace('/(&nbsp;)+/', ' ', $result);
		$result = preg_replace('/\n\s+/s', "\n", $result);
		header('Content-type: application/json');
		echo json_encode(['content' => $result], JSON_UNESCAPED_UNICODE);
	}
}
else {

?>
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<title>纸飞机教务处通知列表获取工具</title>
</head>
<body>
	<h3>使用指南</h3>
	<p>
		<span>API地址：GET</span>
		<a href="<?=$api?>"><?=$api?></a>
	</p>
	<p>
		可以获取教务处通知并转换为JSON串的接口。支持获取第一条信息的ID、第一页的全部信息、或者某一具体通知的内容。
	</p>
</body>
</html>
<?

}
