function fetchMatieres() {
    const apiUrl = 'http://localhost:8000/api/matieres';

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                const tableau = document.getElementById('contenu');
                tableau.innerHTML = ''; // Vider le tableau avant d'ajouter les données

                // Ajouter l'en-tête du tableau
                const headerRow = document.createElement('tr');
                const thMatiere = document.createElement('th');
                thMatiere.textContent = 'Matière';

                headerRow.appendChild(thMatiere);
                tableau.appendChild(headerRow);

                // Ajouter les données des matières
                data.forEach(matiere => {
                    const tr = document.createElement('tr');

                    const tdMatiere = document.createElement('td');
                    tdMatiere.textContent = matiere.nom;


                    tr.appendChild(tdMatiere);

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

function createMatiere(event) {
    event.preventDefault();

    const nom = document.getElementById('nom').value;

    const apiUrl = 'http://localhost:8000/api/matieres';

    const newMatiere = {
        nom: nom
    };
    console.log(JSON.stringify(newMatiere));

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(newMatiere),
    })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.id) {
                alert('Matière créée avec succès');
                location.reload();
            } else {
                console.error("Erreur lors de la création de la matière:", data);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi des données:', error);
        });
}

window.onload = function() {
    fetchMatieres();

    const form = document.getElementById('submitButton');
    form.addEventListener('click', createMatiere);
};
