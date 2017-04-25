<?php //Connexion à la base de données
            try
            {
            $bdd = new PDO('mysql:host=localhost;dbname=seriesdom;charset=utf8', 'root', '',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e)
            {
             die('Erreur : '.$e->getMessage());
            }

$id_series = $_POST(['id_series']);
$season = $_POST(['season']);

		$req = $this->bdd->prepare('SELECT *, DATE_FORMAT(airdate, "%d/%m/%Y") AS airdate FROM episodes WHERE id_series = :id_series AND season = :season');

		$req->bindValue(':id_series', $id_series, PDO::PARAM_INT);
		$req->bindValue(':season', $season, PDO::PARAM_INT);
		$req->execute();

		$all = $req->fetchAll();
		return $all;

		echo 'go';