<?php
$mydb = setup();
$cookiedurationlock = 40;
$mystmt = $mydb->prepare("
    SELECT  `ad_id` 
        FROM `tmp_tbl_actions`
        WHERE 
            (`actiondate` > :nowcookietime )
");
$mystmt->execute(array(
    ':nowcookietime'=>date("Y-m-d H:i:s", time()-$cookiedurationlock),
));
$testnotinsql = $mystmt->fetch(PDO::FETCH_ASSOC);
var_dump($testnotinsql);


function setup() {
    $pdo = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'acg', 'cYSuf8rU8j8RMU9j');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('
        CREATE TEMPORARY TABLE tmp_tbl_actions (
            ad_id int auto_increment,
            actiondate DATETIME,
            primary key(ad_id),
            key(actiondate)
        )
    ');

    $stmt = $pdo->prepare('INSERT INTO tmp_tbl_actions (actiondate) VALUES(?)');
    $t = time();
    for($i=-80; $i<10; $i++) {
        $stmt->execute(array(date('Y-m-d H:i:s', $t+$i)));
    }
    return $pdo;
}
?>