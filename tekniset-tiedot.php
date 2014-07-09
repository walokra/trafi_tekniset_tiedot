<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ajoneuvojen tekniset tiedot - TraFi Avoin data</title>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
	<style type="text/css">
html,body{margin:0;padding:0;color:#000;background:#fff}
html {
	position: relative;
	min-height: 100%;
}
body {
	padding-top: 50px;
	/* Margin bottom by footer height */
	margin-bottom: 30px;
}
#footer {
	position: absolute;
	bottom: 0;
	width: 100%;
	/* Set the fixed height of the footer here */
	height: 30px;
	background-color: #f5f5f5;
}
.container {
  width: auto;
  padding: 0 15px;
}
.container .text-muted {
  margin: 5px 0;
}

.form-search {
	max-width: 960px;
	padding: 15px;
}
.form-search .form-control {
	position: relative;
	float: left;
	height: auto;
	width: auto;
	-webkit-box-sizing: border-box;
    	-moz-box-sizing: border-box;
		box-sizing: border-box;
	padding: 5px;
	margin: 5px;
}
.form-search .form-control:focus {
	z-index: 2;
}
.form-search .btn {
	padding: 5px 15px;
	margin-top: 5px;
}

table {
	border-spacing:0;
	border-collapse:collapse;
}
thead {background-color: #fff;}

.table-responsive {overflow:auto;}
.table { font-size: 13px; line-height: 1.231;}

.total {
	padding: 10px 10px 0 10px;
}
.pagination {padding: 0 10px 0 10px;}
.current {
	font-weight: bold;
}
	</style>
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="http://dataoksi.fi/lab/trafi/tekniset-tiedot.php">Ajoneuvojen tekniset tiedot - TraFi Avoin data</a>
		</div>
	</div>
</div>

<div class="container">
	<h1>Suomalaisten tieliikennekäytössä olevien ajoneuvojen tiedot</h1>
	<p>
	Suomessa tieliikennekäytössä olevien ajoneuvojen rekisteröinti-, hyväksyntä- ja tekniset tiedot ajoneuvoluokista M1 ja M1G. 
	Omistajaan viittaavia tietoja ei julkaista (rekisterinumero ja valmistenumero).
	</p>
	<div class="row">
	<div class="col-md-9">
	<form name="tekniset-tiedot" class="form-search" method="get" action="" role="form">
			<input type="text" name="merkki" placeholder="Merkki" class="form-control" autofocus>
			<input type="text" name="malli" placeholder="Malli" class="form-control" autofocus >
			<input type="text" name="kunta" placeholder="Kunta" class="form-control" autofocus >
			<input type="text" name="kayttovoima" placeholder="Käyttövoima" class="form-control" autofocus >
			<input type="text" name="ensirekisterointipvm" placeholder="Ensirekisterointipvm" class="form-control" autofocus >

			<input type="submit" value="Hae" class="btn btn-primary">
	</form>
	</div>
	</div>
</div>

<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

// Config
$page = "tekniset-tiedot";
$file = "trafi_ajotekn_koodisto_utf8.sqlite";
$taulu = "tekniset_tiedot_view";
$search_url = $page.'?';
$limit = 100; // how many rows to show

// lasketaan sivun kasittelyyn kulunut aika
$time_alku=explode(" ", microtime());
$time_alku=$time_alku[0] + $time_alku[1];

// Quote variable to make safe
function quote_smart($value) {
	// Stripslashes
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
 
	// Quote if not integer
	if (!is_numeric($value) || $value[0] == '0') {
		$value = SQLite3::escapeString($value);
	}
	return $value;
}

// Paging
$_GET = array_map('strip_tags', $_GET);
if(isset($_GET['show'])) {
	if (strlen($_GET['show']) > 5)
		$show = substr($_GET['show'], 0, 5);
	else $show = htmlspecialchars($_GET['show']);
} else $show = 1;
if(isset($_GET['merkki'])) {
	$merkki = htmlspecialchars($_GET['merkki']);
} else $merkki = "";

if(isset($_GET['malli'])) {
	$malli = htmlspecialchars($_GET['malli']);
} else $malli = "";

if(isset($_GET['kunta'])) {
    $kunta = htmlspecialchars($_GET['kunta']);
} else $kunta = "";

if(isset($_GET['ensirekisterointipvm'])) {
	    $ensirekisterointipvm = htmlspecialchars($_GET['ensirekisterointipvm']);
} else $ensirekisterointipvm = "";

if(isset($_GET['kayttovoima'])) {
	    $kayttovoima = htmlspecialchars($_GET['kayttovoima']);
} else $kayttovoima = "";

if(isset($_GET['index'])) {
    if (strlen($_GET['index']) > 5)
	   	$val = substr($_GET['index'], 0, 5);
	else $val = htmlspecialchars($_GET['index']);
}

#$file = $_SERVER['DOCUMENT_ROOT'] . "/dev/trafi_ajotekn_idx.sqlite";
//$file = "trafi_ajotekn_idx_utf8.sqlite";
$db = new SQLite3($file) or die("Could not open database");

$sql = 'SELECT * FROM ' .$taulu. ' ';
$where = '';
$total_sql = 'SELECT COUNT(*) as count FROM ' .$taulu. ' ';

$start = ($show - 1) * $limit;
$limit_clause = ' LIMIT ' .$start. ',' .$limit;

if (!isset($show)) $show=0;

if ($merkki != "" || $malli != "") {

	if ($merkki != "") {
		$merkki = quote_smart($merkki);
		$where = $where . ' merkkiSelvakielinen LIKE :merkki';
		$search_url = $search_url . 'merkki='.$merkki.'&amp;';
	}

	if ($malli != "") {
		$malli = quote_smart($malli);
		if ($where != "") {
			$where = $where . ' AND ';
		}
		$where = $where . 'kaupallinenNimi LIKE :malli';
		$search_url = $search_url . 'malli='.$malli.'&amp;';
	}

	if ($kunta != "") {
        $kunta = quote_smart($kunta);
        if ($where != "") {
            $where = $where . ' AND ';
        }
        $where = $where . 'kunta LIKE :kunta';
		$search_url = $search_url . 'kunta='.$kunta.'&amp;';
    }

	if ($kayttovoima != "") {
        $kayttovoima = quote_smart($kayttovoima);
        if ($where != "") {
            $where = $where . ' AND ';
        }
        $where = $where . 'kayttovoima LIKE :kayttovoima';
        $search_url = $search_url . 'kayttovoima='.$kayttovoima.'&amp;';
    }

	if ($ensirekisterointipvm != "") {
        $ensirekisterointipvm = quote_smart($ensirekisterointipvm);
        if ($where != "") {
            $where = $where . ' AND ';
        }
        $where = $where . 'ensirekisterointipvm LIKE :ensirekisterointipvm';
        $search_url = $search_url . 'ensirekisterointipvm='.$ensirekisterointipvm.'&amp;';
    }

	$sql = $sql . ' WHERE ' . $where . $limit_clause; 
	$total_sql = $total_sql . ' WHERE ' . $where;
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':merkki', $merkki, SQLITE3_TEXT);
	$stmt->bindValue(':malli', $malli, SQLITE3_TEXT);
	$stmt->bindValue(':kunta', $kunta, SQLITE3_TEXT);
	$stmt->bindValue(':kayttovoima', $kayttovoima, SQLITE3_TEXT);
	$stmt->bindValue(':ensirekisterointipvm', $ensirekisterointipvm, SQLITE3_TEXT);
	$total_stmt = $db->prepare($total_sql);
	$total_stmt->bindValue(':merkki', $merkki, SQLITE3_TEXT);
	$total_stmt->bindValue(':malli', $malli, SQLITE3_TEXT);
	$total_stmt->bindValue(':kunta', $kunta, SQLITE3_TEXT);
	$total_stmt->bindValue(':kayttovoima', $kayttovoima, SQLITE3_TEXT);
	$total_stmt->bindValue(':ensirekisterointipvm', $ensirekisterointipvm, SQLITE3_TEXT);
} else {
	$sql = $sql . $limit_clause;
	$stmt = $db->prepare($sql);
	$total_stmt = $db->prepare($total_sql);
}

$total_result = $total_stmt->execute() or die("Error in query");
$total_row = $total_result->fetchArray(SQLITE3_ASSOC);
$total = $total_row['count'];
if ($start > $total) {
	$start = $total - $limit;
}

$result = $stmt->execute() or die("Error in query");

// print values
echo '<div class="table-responsive">';
echo '<table class="table table-striped">';
echo "<thead>";
echo "<tr>";
echo "<th>#</th><th>ajoneuvoluokka</th><th>ensirekisterointipvm</th><th>ajoneuvoryhma</th><th>ajoneuvonkaytto</th><th>kayttoonottopvm</th><th>vari</th><th>ovienLukumaara</th><th>korityyppi</th><th>ohjaamotyyppi</th><th>istumapaikkojenLkm</th><th>omamassa</th><th>teknSuurSallKokmassa</th><th>tieliikSuurSallKokmassa</th><th>ajonKokPituus</th><th>ajonLeveys</th><th>ajonKorkeus</th><th>kayttovoima</th><th>iskutilavuus</th><th>suurinNettoteho</th><th>sylintereidenLkm</th><th>ahdin</th><th>merkkiSelvakielinen</th><th>mallimerkinta</th><th>vaihteisto</th><th>vaihteidenLkm</th><th>kaupallinenNimi</th><th>voimanvalJaTehostamistapa</th><th>tyyppihyvaksyntanro</th><th>yksittaisKayttovoima</th><th>kunta</th><th>Co2</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while($obj = $result->fetchArray(SQLITE3_NUM)) {
	echo "<tr>";
	for ($i=0; $i < $result->numColumns(); $i++) {
		echo "<td>" . $obj[$i] . "</td>";
	}
	echo "</tr>";
}
echo "</tbody>";
echo "<tfoot><tr>";
echo "</tr></tfoot>";
echo "</table>";
echo "</div>";
echo '<div class="total">Rivejä: '.$total.'</div>';

// all done
// destroy database object
$db->close();
unset($db);
unset($stmt);

// Pagination
$current_page = $show;
$mid_range = 7;
$num_pages = ceil($total / $limit);

$for = $current_page + 1;
$back = $current_page - 1;


echo '<ul class="pagination">';
if ($num_pages > 10) {
	if ($current_page != 1 && $total > 10) echo '<li><a class="paginage" href="'.$search_url.'show='.$back.'">edellinen</a></li>';

	$start_range = $current_page - floor($mid_range/2);
	$end_range = $current_page + floor($mid_range/2);

	if($start_range <= 0){
		$end_range += abs($start_range)+1;
		$start_range = 1;
	}
    if($end_range > $num_pages){
		$start_range -= $end_range-$num_pages;
        $end_range = $num_pages;
    }
    $range = range($start_range,$end_range);

	for($i=1; $i<=$num_pages; $i++) {
		if($range[0] > 2 && $i == $range[0]) echo "<li><a href='#'>...</a></li>";

		// loop through all pages. if first, last, or in range, display
        if($i==0 || $i==$num_pages || in_array($i,$range)){
            print '<li><a href="'.$search_url.'show='.$i.'"';
			if ($i == $current_page) echo ' class="current"';
     		echo '>'.$i.'</a></li>';
		}
		if($range[$mid_range-1] < $num_pages-1 && $i == $range[$mid_range-1]) echo "<li><a href='#'>...</a></li>";
	}
	if ($current_page != $num_pages && $total > 10) echo '<li><a class="paginate" href="'.$search_url.'show='.$for.'">seuraava</a></li>';
}
else {
	for($i=1; $i<=$num_pages; $i++) {
    	if ($i == $current_page) 
			echo '<li><a class="current" href="#">'.$i.'</a></li>';
		else echo '<li><a href="'.$search_url.'show='.$i.'">'.$i.'</a></li>';
	}
}
echo '</ul>';

// how long it took
$time_loppu=explode(" ", microtime());
$time_loppu=$time_loppu[0] + $time_loppu[1];
$time=$time_loppu - $time_alku;
$time=round($time, 3);
// Prints like "It took 0.0003499 s"
?>

	<div id="footer">
		<div class="container">
        	<p class="text-muted" style="float: left;">tehnyt <a href="https://twitter.com/walokra">@walokra</a>, avoin data: <a href="http://www.trafi.fi/palvelut/avoin_data">TraFi</a> (ladattu 17.6.2014), <a href="http://www.trafi.fi/palvelut/avoin_data/avoimen_datan_lisenssi">lisenssi</a></p>
			<p class="text-muted" style="float: right; font-size: 0.7em;">Haku kesti: <?php echo '' .$time.' s'; ?></p>
		</div>
    </div>

	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/floatthead/1.2.8/jquery.floatThead.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		var $table = $('table');
		$table.floatThead();
		//$(window).resize(function() {
			$('.table-responsive').css('height', window.innerHeight - 360);
			$('.table-responsive').css('width', window.innerWidt  -20);
		//});
	});
	</script>
	<?php include_once("analyticstracking.php") ?>
</body>
</html> 
