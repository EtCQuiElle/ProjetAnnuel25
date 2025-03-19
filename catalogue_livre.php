<?php
include('pdo.php');
// Récupération des livres dans la base de données
// Utilisation de la connexion PDO déjà établie
try {
    $stmt = $pdo->prepare("SELECT * FROM livre ORDER BY `id livre`");
    $stmt->execute();
    $livres = $stmt->fetchAll();
    
    // Vous pouvez maintenant utiliser $livres pour afficher vos données
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des livres : " . $e->getMessage();
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
                <li><a href="#dates-sortie">Dates de sortie</a></li>
                <li><a href="#livres-sponsorises">Livres Sponsorisés</a></li>
                <li><a href="#listes-livres">Les Listes de Livres</a></li>
                <li><a href="#prix-litteraires">Prix littéraires</a></li>
                <li><a href="#meilleures-ventes">Meilleures ventes</a></li>
                <li><a href="#top-auteurs">Top Auteurs</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Dates de sortie -->
<section id="dates-sortie" class="category">
    <h2>Dates de sortie</h2>
    <div class="book-row">
        <?php 
        // Affichage des livres
        foreach ($livres as $livre) {
        ?>
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
        <?php
        }
        ?>
    </div>
</section>

        <!-- Livres Sponsorisés -->
        <section id="livres-sponsorises" class="category">
            <h2>Livres Sponsorisés</h2>
            <div class="book-row">
                <div class="book">Titre : "Le Petit Prince"<br><span>Auteur : Antoine de Saint-Exupéry</span></div>
                <div class="book">Titre : "Harry Potter à l'école des sorciers"<br><span>Auteur : J.K. Rowling</span></div>
                <div class="book">Titre : "Game of Thrones"<br><span>Auteur : George R.R. Martin</span></div>
                <div class="book">Titre : "Le Seigneur des Anneaux"<br><span>Auteur : J.R.R. Tolkien</span></div>
                <div class="book">Titre : "Les Misérables"<br><span>Auteur : Victor Hugo</span></div>
                <div class="book">Titre : "L'Alchimiste"<br><span>Auteur : Paulo Coelho</span></div>
                <div class="book">Titre : "Le Comte de Monte-Cristo"<br><span>Auteur : Alexandre Dumas</span></div>
                <div class="book">Titre : "1984"<br><span>Auteur : George Orwell</span></div>
                <div class="book">Titre : "Animal Farm"<br><span>Auteur : George Orwell</span></div>
                <div class="book">Titre : "Cyrano de Bergerac"<br><span>Auteur : Edmond Rostand</span></div>
            </div>
        </section>

        <!-- Les Listes de Livres -->
        <section id="listes-livres" class="category">
            <h2>Les Listes de Livres</h2>
            <div class="book-row">
                <div class="book">Titre : "Éducation Européenne"<br><span>Auteur : Romain Gary</span></div>
                <div class="book">Titre : "La Peste"<br><span>Auteur : Albert Camus</span></div>
                <div class="book">Titre : "Le Rouge et le Noir"<br><span>Auteur : Stendhal</span></div>
                <div class="book">Titre : "Bel-Ami"<br><span>Auteur : Guy de Maupassant</span></div>
                <div class="book">Titre : "Madame Bovary"<br><span>Auteur : Gustave Flaubert</span></div>
                <div class="book">Titre : "Les Fleurs du Mal"<br><span>Auteur : Charles Baudelaire</span></div>
                <div class="book">Titre : "Germinal"<br><span>Auteur : Émile Zola</span></div>
                <div class="book">Titre : "La Chartreuse de Parme"<br><span>Auteur : Stendhal</span></div>
                <div class="book">Titre : "Les Trois Mousquetaires"<br><span>Auteur : Alexandre Dumas</span></div>
                <div class="book">Titre : "L'Assommoir"<br><span>Auteur : Émile Zola</span></div>
            </div>
        </section>

        <!-- Ajout d'autres catégories -->
        <!-- Exemples : Prix littéraires, Meilleures ventes, Top Auteurs -->
    </main>
    <footer>
        <p>&copy; 2025 Catalogue de Livres. Tous droits réservés.</p>
    </footer>
</body>
</html>
