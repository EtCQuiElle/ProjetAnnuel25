<?php
include('pdo.php');
// Récupération des livres dans la base de données
// Utilisation de la connexion PDO déjà établie
try {
    $stmt = $pdo->prepare("SELECT genre, titre, `prenom auteur`, `nom auteur` FROM livre ORDER BY genre, titre");
    $stmt->execute();
    $livres = $stmt->fetchAll();
    
    // Vous pouvez maintenant utiliser $livres pour afficher vos données
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des livres : " . $e->getMessage();
}
// Récupération des genres distincts
$stmtGenres = $pdo->prepare("SELECT DISTINCT genre FROM livre ORDER BY genre");
$stmtGenres->execute();
$genres = $stmtGenres->fetchAll(PDO::FETCH_COLUMN);


// Organiser les livres par genre
$livresParGenre = [];
foreach ($livres as $livre) {
    $livresParGenre[$livre['genre']][] = $livre;
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon_Profil</title>
    <link rel="stylesheet" href="style.css">


</head>
<body>


<!-- header -->
    <div class="top-bar">
        <div class="logo">
            <a href="Accueil.html">
                <img src="C:\Info\Projet annuel\Icon\logo.png" alt="logo">
            </a>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
        </div>
        <div class="icons-header">
            <div class="icons">
                <!-- Les coups de coeurs  -->
                <a href="coup_de_coeur.html">
                    <img src="C:\Info\Projet annuel\Icon\coeur.png" alt="Coup_de_Coeur">
                </a>


                <!-- le panier  -->
                <a href="panier.html">
                    <img src="C:\Info\Projet annuel\Icon\cart.png" alt="Panier">
                </a>


                <!-- compte client -->
                <a href="acces_client.html">
                    <img src="C:\Info\Projet annuel\Icon\profil_pictures.png" alt="Profil_pictures">
                </a>
            </div>


    <!-- Les notifications et déconnexion -->
    <div class="deconnexion">
        <a href="#notificationModal" class="notification-box">
            Notifications
        </a>
        <a href="Admin_ou_lecteur.html" class="deconnecte">Déconnexion</a>
    </div>
</div>


<!-- Modal Notifications -->
<div id="notificationModal" class="modal-notification">
    <div class="modal-notification-content">
        <a href="#" class="close">&times;</a>
        <p>Voici les notifications</p>
    </div>
</div>
        </div>
    <div class="categories">
        <a href="Accueil.html">Accueil</a>
        <a href="blog.html" >Blog</a>
        <a href="livre.html"class="active" >Livre</a>
        <a href="rachats_de_livres.html" >Rachats de livres</a>
        <a href="messagerie.html">Messagerie</a>
    </div>
</body>

<body>
    <header>
        <h1>Catalogue de Livres</h1>
        <nav>
            <ul>
            <?php foreach ($genres as $genre) : ?>
            <li><a href="#<?php echo strtolower(str_replace(' ', '-', $genre)); ?>">
            <?php echo htmlspecialchars($genre); ?>
            </a></li>
            <?php endforeach; ?>
            </ul>
        </nav>
    </header>
    <main>
    <?php foreach ($livresParGenre as $genre => $livres) : ?>
        <section id="<?php echo strtolower(str_replace(' ', '-', $genre)); ?>" class="category">
            <h2><?php echo htmlspecialchars($genre); ?></h2>
            <div class="book-row">
                <?php foreach ($livres as $livre) : ?>
                    <div class="book">
                        <div class="book-content">
                            <div class="book-title">
                                Titre : "<?php echo htmlspecialchars($livre['titre']); ?>"
                            </div>
                            <div class="book-author">
                                Auteur : <?php echo htmlspecialchars($livre['prenom auteur']) . ' ' . htmlspecialchars($livre['nom auteur']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
    </main>
    <footer>
        <p>&copy; 2025 Catalogue de Livres. Tous droits réservés. echo "Bonjour";</p>
    </footer>
</body>
</html>
