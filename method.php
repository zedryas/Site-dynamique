<?php
require 'db.php';

$alerts = array();
$errors = array();

function getStudentsWithDetails($offset) {
  $eleves = array();
  $matieres = array();
  foreach (getAllMatiere() as $matiere) { $matieres[$matiere['sujet']] = ""; }
  
  foreach(getAllNotesAndMatiersForStudents($offset) as $line) {
    if (!array_key_exists($line["id_eleve"], $eleves)) {
      $eleves[$line["id_eleve"]] = array(
        "notes" => array_merge(array(), $matieres),
        "nom" => utf8_encode($line["nom"]),
        "prenom" => utf8_encode($line["prenom"])
      );
    }
    $eleves[$line["id_eleve"]]["notes"][$line["sujet"]] = $line["note"];
  }
  return $eleves;
}

function validateNote($post) {
  global $errors;

  if (empty($post["nom"]))    array_push($errors, "Vous devez spécifier le nom");
  if (empty($post["prenom"])) array_push($errors, "Vous devez spécifier le prenom");
  if (empty($post["note"]))   array_push($errors, "Vous devez spécifier la note");
  if (!is_numeric($post["note"])) array_push($errors, "La note doit être un chiffre");
  if ($post["note"] > 20 || $post["note"] < 0) array_push($errors, "La note doit être comprise entre 0 et 20");
  return true;
}

function addNoteToStudent($post) {
  global $alerts;
  global $errors;

  if (validateNote($post)) {
    $student = getStudentWithPost($post);
    
    if (empty($student)) {
      insertNewStudent($post);
      array_push($alerts, "L'éléve ".$post["nom"].",".$post["prenom"]." a été ajouté et noté");
    } else {
      $note = selectNotesForStudent($post, $student["id_eleve"]);

      if(empty($note)) {
        insertNote($student["id_eleve"], $post);
        array_push($alerts, "L'éléve ".$post["nom"].",".$post["prenom"]." a été noté");        
      } else {
        array_push($errors, "L'éléve ".$post["nom"].",".$post["prenom"]." a déjà été noté");        
      }
    }
  }
}


function getPage($get) {
   return isset($get["page"]) ? $get["page"] : 1;
}