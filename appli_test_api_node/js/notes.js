// Fonction pour récupérer l'ID de l'élève à partir de l'URL
function getEleveIdFromUrl() {
    const path = window.location.pathname;
    const pathParts = path.split('/');
    const eleveId = pathParts[2];
    return eleveId;
}

// Fonction pour récupérer les matières
function fetchMatieres() {
    const apiUrl = 'http://localhost:8000/api/matieres';  // Changez l'URL selon votre API pour les matières

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const matiereSelect = document.getElementById('matiere');
            if (Array.isArray(data)) {
                data.forEach(matiere => {
                    const option = document.createElement('option');
                    option.value = matiere.id;
                    option.textContent = matiere.nom;
                    matiereSelect.appendChild(option);
                });
            } else {
                console.error("Erreur dans la récupération des matières");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des matières:', error);
        });
}

// Fonction pour récupérer les évaluateurs (professeurs)
function fetchEvaluateurs() {
    const apiUrl = 'http://localhost:8000/api/professeurs';  // Changez l'URL selon votre API pour les évaluateurs

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const evaluateurSelect = document.getElementById('evaluateur');
            if (Array.isArray(data)) {
                data.forEach(evaluateur => {
                    const option = document.createElement('option');
                    option.value = evaluateur.id;
                    option.textContent = `${evaluateur.nom} ${evaluateur.prenom}`;
                    evaluateurSelect.appendChild(option);
                });
            } else {
                console.error("Erreur dans la récupération des évaluateurs");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des évaluateurs:', error);
        });
}

// Fonction pour récupérer les notes de l'élève
function fetchNotes() {
    const eleveId = getEleveIdFromUrl();
    const apiUrl = `http://localhost:8000/api/eleves/${eleveId}/notes`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.notes && Array.isArray(data.notes)) {
                const tableau = document.getElementById('contenu');
                tableau.innerHTML = '';  // Vider le tableau avant d'ajouter les données

                const eleveElement = document.getElementById('eleve');
                const eleveNom = data.nom;
                const elevePrenom = data.prenom;
                eleveElement.innerHTML = `<strong>${eleveNom}</strong> ${elevePrenom}`;  // Met le nom en gras

                // Ajouter l'en-tête du tableau
                const headerRow = document.createElement('tr');
                const thNote = document.createElement('th');
                thNote.textContent = 'Note';
                const thEvaluateur = document.createElement('th');
                thEvaluateur.textContent = 'Évaluateur';
                const thMatiere = document.createElement('th');
                thMatiere.textContent = 'Matière';
                const thCommentaire = document.createElement('th');
                thCommentaire.textContent = 'Commentaire';  // Nouvelle colonne pour les commentaires

                headerRow.appendChild(thNote);
                headerRow.appendChild(thEvaluateur);
                headerRow.appendChild(thMatiere);
                headerRow.appendChild(thCommentaire);  // Ajouter la colonne commentaire
                tableau.appendChild(headerRow);

                // Ajouter les notes de l'élève
                data.notes.forEach(note => {
                    const tr = document.createElement('tr');

                    const tdNote = document.createElement('td');
                    tdNote.textContent = note.note;

                    const tdEvaluateur = document.createElement('td');
                    tdEvaluateur.textContent = `${note.evaluateur.nom} ${note.evaluateur.prenom}`;

                    const tdMatiere = document.createElement('td');
                    tdMatiere.textContent = note.matiere.nom;

                    const tdCommentaire = document.createElement('td');
                    tdCommentaire.textContent = note.commentaire || 'Aucun commentaire';

                    tr.appendChild(tdNote);
                    tr.appendChild(tdEvaluateur);
                    tr.appendChild(tdMatiere);
                    tr.appendChild(tdCommentaire);  // Ajouter la cellule "Commentaire"
                    tableau.appendChild(tr);
                });
            } else {
                console.error("Aucune note trouvée ou les données ne sont pas au format attendu.");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des notes:', error);
        });
}

// Fonction pour ajouter une note
function ajouterNote(event) {
    event.preventDefault();

    const eleveId = getEleveIdFromUrl();  // Récupérer l'ID de l'élève
    const evaluateurId = document.getElementById('evaluateur').value;
    const matiereId = document.getElementById('matiere').value;
    const note = document.getElementById('note').value;
    const commentaire = document.getElementById('commentaire').value;

    const newNote = {
        evaluateur: `/api/professeurs/${evaluateurId}`,
        matiere: `/api/matieres/${matiereId}`,
        eleve: `/api/eleves/${eleveId}`,
        note: parseFloat(note),
        commentaire: commentaire
    };
console.log(newNote);
    const apiUrl = `http://localhost:8000/api/notes`;

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(newNote),
    })
        .then(response => response.json())
        .then(data => {
            if (data) {
                alert('Note ajoutée avec succès');
                fetchNotes();  // Récupérer les notes après ajout
            } else {
                console.error("Erreur lors de l'ajout de la note.");
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi des données:', error);
        });
}

// Écouter la soumission du formulaire pour ajouter une note
document.getElementById('ajouterNoteForm').addEventListener('submit', ajouterNote);

// Charger les matières, les évaluateurs et les notes au chargement de la page
window.onload = function() {
    fetchMatieres();  // Récupérer les matières
    fetchEvaluateurs();  // Récupérer les évaluateurs
    fetchNotes();  // Appel de la fonction pour récupérer et afficher les notes de l'élève

    // Assigner l'ID de l'élève au champ caché
    document.getElementById('eleveId').value = getEleveIdFromUrl();
};
