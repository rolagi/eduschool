function fetchClasses() {
    const apiUrl = 'http://localhost:8000/api/classes';

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                const tableau = document.getElementById('contenu');
                tableau.innerHTML = ''; // Vider le tableau avant d'ajouter les données

                // Ajouter l'en-tête du tableau
                const headerRow = document.createElement('tr');
                const thClasse = document.createElement('th');
                thClasse.textContent = 'Classe';
                const thNiveau = document.createElement('th');
                thNiveau.textContent = 'Niveaux';
                const thEleves = document.createElement('th');
                thEleves.textContent = 'Nombre d\'élèves';

                headerRow.appendChild(thClasse);
                headerRow.appendChild(thNiveau);
                headerRow.appendChild(thEleves);
                tableau.appendChild(headerRow);

                // Ajouter les données des classes
                data.forEach(classe => {
                    const tr = document.createElement('tr');

                    const tdClasse = document.createElement('td');
                    tdClasse.textContent = classe.nom;

                    const tdNiveau = document.createElement('td');
                    tdNiveau.textContent = classe.niveau.nom;

                    const tdEleves = document.createElement('td');
                    tdEleves.textContent = classe.eleves.length;

                    tr.appendChild(tdClasse);
                    tr.appendChild(tdNiveau);
                    tr.appendChild(tdEleves);

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

function fetchNiveaux() {
    const apiUrl = 'http://localhost:8000/api/niveaux';

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const niveauSelect = document.getElementById('niveau');
            if (Array.isArray(data)) {
                niveauSelect.innerHTML = ''; // Vider les anciennes options
                data.forEach(niveau => {
                    const option = document.createElement('option');
                    option.value = '/api/niveaux/'+niveau.id;
                    option.textContent = niveau.nom;
                    niveauSelect.appendChild(option);
                });
            } else {
                console.error("Les données des niveaux ne sont pas sous forme de tableau.");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des niveaux:', error);
        });
}

function createClasse(event) {
    event.preventDefault(); // Empêcher l'envoi du formulaire

    const nom = document.getElementById('nom').value;
    const niveauId = document.getElementById('niveau').value;

    const apiUrl = 'http://localhost:8000/api/classes';

    const newClasse = {
        nom: nom,
        niveau: niveauId
    };
    console.log(JSON.stringify(newClasse));

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(newClasse),
    })
        .then(response => {
            response.json();
            location.reload(true);
        })
        .then(data => {
            if (data.id) {
                alert('Classe créée avec succès');
                location.reload();
            } else {
                console.error("Erreur lors de la création de la classe:", data);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi des données:', error);
        });
}

window.onload = function() {
    fetchNiveaux();
    fetchClasses();

    const form = document.getElementById('submitButton');
    form.addEventListener('click', createClasse);
};
