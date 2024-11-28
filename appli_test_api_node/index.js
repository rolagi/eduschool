const express = require('express');
const path = require('path');

const app = express();
const port = 3000;

// Route pour la page d'accueil
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'index.html'));
});

// Route pour afficher les classes
app.get('/classes', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'classes.html'));
});

// Route pour afficher les matières
app.get('/matieres', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'matieres.html'));
});

// Route pour afficher les élèves
app.get('/eleves', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'eleves.html'));
});

app.get('/eleve/:id/notes', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'notes.html'));
});

// Servir les fichiers statiques JS et CSS
app.use('/js', express.static(path.join(__dirname, 'js')));
app.use('/css', express.static(path.join(__dirname, 'css')));

// Lancer le serveur
app.listen(port, () => {
    console.log(`Serveur en cours d'exécution sur http://localhost:${port}`);
});
