function fetchEleves() {
    const apiUrl = 'http://localhost:8000/api/eleves';

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                const tableau = document.getElementById('contenu');
                tableau.innerHTML = ''; // Vider le tableau avant d'ajouter les données

                // Ajouter l'en-tête du tableau
                const headerRow = document.createElement('tr');
                const thNomComplet = document.createElement('th');
                thNomComplet.textContent = 'Élève';
                const thClasse = document.createElement('th');
                thClasse.textContent = 'Classe';
                const thMoyenne = document.createElement('th');
                thMoyenne.textContent = 'Moyenne';

                headerRow.appendChild(thNomComplet);
                headerRow.appendChild(thClasse);
                headerRow.appendChild(thMoyenne); // Ajouter la colonne Moyenne
                tableau.appendChild(headerRow);

                // Ajouter les données des élèves
                data.forEach(eleve => {
                    const tr = document.createElement('tr');

                    const tdNomComplet = document.createElement('td');
                    tdNomComplet.innerHTML = `<strong>${eleve.nom}</strong> ${eleve.prenom}`;

                    const tdClasse = document.createElement('td');
                    if (eleve.classe !== null) {
                        tdClasse.textContent = eleve.classe.nom;
                    } else {
                        tdClasse.textContent = "";
                    }

                    // Calculer la moyenne des notes de l'élève
                    const tdMoyenne = document.createElement('td');
                    if (eleve.notes && eleve.notes.length > 0) {
                        const totalNotes = eleve.notes.reduce((acc, note) => acc + note.note, 0);
                        const moyenne = totalNotes / eleve.notes.length;
                        tdMoyenne.textContent = moyenne.toFixed(2);
                    } else {
                        tdMoyenne.textContent = "Aucune note";
                    }

                    tr.appendChild(tdNomComplet);
                    tr.appendChild(tdClasse);
                    tr.appendChild(tdMoyenne);

                    tableau.appendChild(tr);
                });
            } else {
                console.error("Les données récupérées ne sont pas un tableau.");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
        });
}

function fetchClasses() {
    const apiUrl = 'http://localhost:8000/api/classes';

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const classeSelect = document.getElementById('classe');
            if (Array.isArray(data)) {
                classeSelect.innerHTML = '';
                data.forEach(classe => {
                    const option = document.createElement('option');
                    option.value = classe.id;
                    option.textContent = classe.nom;
                    classeSelect.appendChild(option);
                });
            } else {
                console.error("Les données des classes ne sont pas sous forme de tableau.");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des classes:', error);
        });
}

function createEleve(event) {
    event.preventDefault();

    const nom = document.getElementById('nom').value;
    const prenom = document.getElementById('prenom').value;
    const classeId = document.getElementById('classe').value;

    const apiUrl = 'http://localhost:8000/api/eleves';

    const newEleve = {
        nom: nom,
        prenom: prenom,
        classe: `/api/classes/${classeId}`
    };

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(newEleve),
    })
        .then(response => {
            response.json();
            location.reload(true);
        })
        .then(data => {
            if (data) {
                alert('Élève créé avec succès');
                location.reload();
            } else {
                console.error("Erreur lors de la création de l'élève:", data);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi des données:', error);
        });
}

window.onload = function () {
    fetchClasses(); // Récupérer les classes
    fetchEleves(); // Récupérer les élèves

    const form = document.getElementById('submitButton');
    form.addEventListener('click', createEleve); // Associer la création d'élève
};