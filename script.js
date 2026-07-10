function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this staff record? This action cannot be undone.")) {
        window.location.href = "staff_delete.php?id=" + id;
    }
}

// Simple client-side search filter
function filterTable() {
    let input = document.getElementById("staffSearch");
    let filter = input.value.toUpperCase();
    let table = document.getElementById("staffTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            let textValue = td.textContent || td.innerText;
            tr[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
}
/**
 * Staff Profile Management System - Interactivity
 * Focus: Security & Efficiency
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. DELETE CONFIRMATION ---
    // Targeted specifically at delete buttons for better control
    const deleteButtons = document.querySelectorAll('.delete-btn, a[href*="staff_delete.php"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Professional confirmation dialog
            const confirmation = confirm('CRITICAL ACTION: Are you sure you want to permanently remove this staff record? This cannot be undone.');
            
            if (!confirmation) {
                e.preventDefault(); // Stop the deletion
            }
        });
    });

    // --- 2. LIVE SEARCH FILTERING ---
    // Solves the "Inefficiency" problem in your proposal
    const searchInput = document.getElementById('staffSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#staffTable tbody tr');

            rows.forEach(row => {
                // Search across Name (col 1) and Designation (col 2)
                const name = row.cells[0].textContent.toLowerCase();
                const role = row.cells[1].textContent.toLowerCase();
                
                if (name.includes(filter) || role.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }

    // --- 3. PHOTO PREVIEW (Add/Edit Page) ---
    const photoInput = document.querySelector('input[name="profile_pic"]');
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.photo-preview-box img') || 
                                  document.querySelector('.photo-preview-box i');
                    
                    // Replace icon with actual image preview before upload
                    const previewBox = document.querySelector('.photo-preview-box');
                    previewBox.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">`;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
