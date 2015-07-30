<?php require 'method.php'; ?>

<?php
if (count($alerts) > 0) { ?>

<article class="alert">
<h3>Vous avez effectué les actions suivantes</h3>
  <ul>
    <?php foreach ($alerts as $alert) { ?>
    <li><?php echo $alert; ?></li>
    <?php } ?>
  </ul>
</article>
<?php } ?>
<?php
  $page = getPage($_GET);
  $count = getStudentCount();
  $offset = ($page-1) * 5; 
?>

<a href='./form.php'>Ajouter une note</a>
<table>
  <thead>
    <tr>
      <th>Nom</th>
      <th>Prenom</th>
      <?php foreach (getAllMatiere() as $matiere) { ?>
        <th><?php echo utf8_encode($matiere["sujet"]); ?></th>
      <?php } ?>
    </tr>
  </thead>
    <tbody>
    <?php foreach (getStudentsWithDetails($offset) as $student) { ?>
      <tr>
        <td><?php echo $student["nom"]; ?></td>
        <td><?php echo $student["prenom"]; ?></td>
        <?php foreach ($student["notes"] as $note) { ?>
          <td><?php echo $note; ?></td>          
        <?php } ?>
      </tr>      
    <?php } ?>
    </tbody>
</table>
<ul>
  <?php for ($i=1; $i <= round($count/5)+1; $i++) { ?>
    <?php if ($i == $page) { ?>
    <li><?php echo $i; ?></li>
    <?php } else { ?>
    <li><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php } ?>
  <?php } ?>
</ul>