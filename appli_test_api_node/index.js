const express = require('express');
const path = require('path');

const app = express();
const port = 3000;

// Servir un fichier HTML
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'index.html'));
});

// Servir la route '/classes'
app.get('/classes', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'classes.html')); // Assurez-vous que ce fichier existe
});

app.get('/matieres', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'matieres.html')); // Assurez-vous que ce fichier existe
});

app.get('/eleves', (req, res) => {
    res.sendFile(path.join(__dirname, 'html', 'eleves.html')); // Assurez-vous que ce fichier existe
});

// Servir les fichiers JS depuis le dossier 'js'
app.use('/js', express.static(path.join(__dirname, 'js')));

app.use('/css', express.static(path.join(__dirname, 'css')));

// Lancer le serveur
app.listen(port, () => {
    console.log(`Serveur en cours d'exécution sur http://localhost:${port}`);
});
