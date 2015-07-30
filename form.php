<?php require 'method.php'; ?>

<?php
if (isset($_POST["submit"])){
  addNoteToStudent($_POST);
  if (count($errors) == 0)
    header("Location:index.php");
  else { ?>
  
  <article class="errors">
    <h3>vous avez les erreurs suivantes</h3>
    <ul>
      <?php foreach ($errors as $error) { ?>
      <li><?php echo $error; ?></li>
      <?php } ?>
    </ul>
  </article>

<?php }
}
?>

<form action="form.php" method="post">
  <input type="text" placeholder="Nom de l'éléve" name="nom"><br/>
  <input type="text" placeholder="Prénom de l'éléve" name="prenom"><br/>
  <label> Matière :</label>
  <select name="id_matiere">
  <?php foreach(getAllMatiere() as $matiere) { ?>
    <option value="<?php echo $matiere["id_matiere"]; ?>">
      <?php echo utf8_encode($matiere["sujet"]); ?>
    </option>
  <?php } ?>
  </select><br/>
  <input type="text" placeholder="Note" name="note"><br/>
  <input type="submit" name="submit" value="ok">
</form>

<!-- <ul>
<li><a href="listing1.php">Listing des eleves matieres et notes version de code 1 :</a></li>
<li><a href="listing2.php">Listing des eleves matieres et notes version de code 2 :</a></li>
</ul> -->