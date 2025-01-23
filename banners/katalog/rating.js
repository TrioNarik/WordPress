// ====================================================
// ================ VISITES ===========================
// ==================================================== 
document.addEventListener('DOMContentLoaded', () => {

    // localStorage.setItem("HEisFormSent", false);

    if (!localStorage.getItem("HEisWatched")) {

        localStorage.setItem("HEisWatched", true);
    
        fetch('include/rating.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ HEisWatched: true })
        })
        .then(response => response.text())
        .catch(error => console.error("Błąd podczas wizyty:", error));
    }
})