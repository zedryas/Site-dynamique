<?php
  $DB    = 'base1';
  $HOST  = 'localhost';
  $USER  = 'root';
  $PASS  = '';

  $dsn = "mysql:dbname=$DB;host=$HOST";
  $conn = new PDO ($dsn, $USER, $PASS) or die ("Unable to connect to DB");

  $pSelectStudent = $conn->prepare("SELECT * FROM eleve
                    WHERE nom=LOWER(:nom) and prenom=LOWER(:prenom);");
  $pInsertStudent = $conn->prepare("INSERT INTO eleve (nom, prenom) 
                                    VALUES(LOWER(:nom), LOWER(:prenom));");
  $pInsertNote = $conn->prepare("INSERT INTO note (id_eleve, id_matiere, note)
                                    VALUES(:id_eleve, :id_matiere, :note);");
  $pSelectNotes = $conn->prepare("SELECT * from note 
                          WHERE id_eleve=:id_eleve and id_matiere=:id_matiere;");

  function getAllMatiere() {
    global $conn;
    return $conn->query("SELECT sujet FROM matiere ORDER BY id_matiere ASC")->fetchAll();
  }

  function getAllNotesAndMatiersForStudents($offset) {
    global $conn;
    return $conn->query("SELECT e.id_eleve, e.nom, e.prenom, m.sujet, n.note
                           FROM eleve   AS e
                           JOIN note    AS n ON n.id_eleve   = e.id_eleve
                           JOIN matiere AS m ON m.id_matiere = n.id_matiere
                           ORDER BY e.nom ASC LIMIT $offset , 5;")->fetchAll();
  }

  function getStudentWithPost($post) {
    global $pSelectStudent;
    $pSelectStudent->bindParam(":nom",utf8_decode($post["nom"]));
    $pSelectStudent->bindParam(":prenom",utf8_decode($post["prenom"]));
    $pSelectStudent->execute();
    return $pSelectStudent->fetch();
  }

  function insertNote($id, $post) {
    global $pInsertNote;

    $pInsertNote->bindParam(":id_eleve",   $id);
    $pInsertNote->bindParam(":id_matiere", $post["id_matiere"]);
    $pInsertNote->bindParam(":note",       floatval($_POST["note"]));
    $pInsertNote->execute();
  }            

  function insertNewStudent($post) {
    global $conn;
    global $pInsertNote;
    global $pInsertStudent;

    $pInsertStudent->bindParam(":nom",    utf8_decode($post["nom"]   ));
    $pInsertStudent->bindParam(":prenom", utf8_decode($post["prenom"]));
    $pInsertStudent->execute();
    $last_id = $conn->lastInsertId();

    insertNote($last_id, $post);
  }

  function selectNotesForStudent($post, $id) {//$id = $student["id_eleve"]
    global $pSelectNotes;
    
    $pSelectNotes->bindParam(":id_eleve", $id);
    $pSelectNotes->bindParam(":id_matiere", $post["id_matiere"]);
    $pSelectNotes->execute();
    return $pSelectNotes->fetch();
  }

  function getStudentCount() {
    global $conn;
    
    $res =  $conn->query("SELECT count(*) AS nbr FROM eleve")->fetch();
    return $res['nbr'];
  }