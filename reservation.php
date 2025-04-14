<?php
require 'config.php';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Génération automatique d'un numéro de vol
  $numerodevol = 'FLIGHT' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

  // Récupération des données du formulaire
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $phone = htmlspecialchars($_POST['phone']);
  $departure = htmlspecialchars($_POST['departure']);
  $arrival = htmlspecialchars($_POST['arrival']);
  $date = htmlspecialchars($_POST['date']);
  $tarif = htmlspecialchars($_POST['tarif']);

  // Validation des champs
  if (empty($name) || empty($email) || empty($phone) || empty($departure) || empty($arrival) || empty($date) || empty($tarif)) {
    $errors[] = 'Tous les champs sont obligatoires.';
  }

  // Vérification des numero de vols
  if (empty($errors)) {

    // Insérer dans la table correspondant au rôle
    $sql = "INSERT INTO reservation (numerodevol, name, email, phone, depart,arrive,date,tarif) VALUES (:numerodevol,:name, :email, :phone, :departure, :arrival, :date, :tarif)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':numerodevol', $numerodevol);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':departure', $departure);
    $stmt->bindParam(':arrival', $arrival);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':tarif', $tarif);
    try {
      if ($stmt->execute()) {
        $success = " Reservation reussie !";
      }
    } catch (PDOException $e) {
      $errors[] = "Erreur lors de la reservation: " . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réservation</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <header>
    <div class="logo">
      <a href="#">airline<span>TRAVEL</span></a>
    </div>
    <nav>
      <ul>
        <li><a href="./index.html">Accueil</a></li>
        <li><a href="./pages/Service.html">Services</a></li>
        <li><a href="reservation.php">Réservation</a></li>
        <li><a href="./pages/Contact.html">Contact</a></li>
      </ul>
    </nav>
  </header>

  <section>
    <form class="reservation" action="" method="POST">
      <h1 style="color: midnightblue;">Réservation de Vol</h1>
      <div id="form-messages" style="margin-bottom: 15px;"></div>

      <?php if (!empty($errors)): ?>
        <p style="color:red"><?= implode('<br>', $errors) ?></p>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <p style="color:green"><?= $success ?></p>
      <?php endif; ?>

      <!-- Champ masqué pour numéro de vol -->
      <input type="hidden" name="numerodevol" value="<?= $numerodevol ?? '' ?>">

      <label for="name">Nom<span>*</span></label>
      <input type="text" id="name" name="name" placeholder="Entrez votre nom" required>

      <label for="email">Email<span>*</span></label>
      <input type="email" id="email" name="email"  placeholder="Entrez votre email" required>

      <label for="phone">Téléphone<span>*</span></label>
      <input type="tel" id="phone" name="phone" placeholder="Entrez votre numero de telephone" required>

      <label for="departure">lieu de départ<span>*</span></label>
      <input type="text" id="departure" name="departure"  placeholder="Entrez votre lieu depart" required>

      <label for="arrival">Lieu d'arrivée<span>*</span></label>
      <input type="text" id="arrival" name="arrival"  placeholder="Entrez votre lieu d'arrivee" required>

      <label for="date">Date de départ<span>*</span></label>
      <input type="date" id="date" name="date"  placeholder="Entrez votre date de depart"required>

      <label for="tarif">Montant du voyage (€)<span>*</span></label>
      <input type="text" id="tarif" name="tarif"  placeholder="Entrez le tarif selon le tableau de bord" required>

      <button type="submit">Réserver</button>
    </form>
  </section>

  <script src="scripts.js"></script>

</body>

</html>