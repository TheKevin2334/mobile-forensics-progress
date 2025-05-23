document.addEventListener('DOMContentLoaded', function() {
    // Update progress bar and history
    function updateProgress() {
        fetch('get_progress.php')
            .then(response => response.json())
            .then(data => {
                // Update progress bar
                const progressBar = document.querySelector('.progress-bar');
                progressBar.style.width = data.current_progress + '%';
                progressBar.textContent = data.current_progress + '%';

                // Update progress history
                const historyContainer = document.getElementById('progress-history');
                historyContainer.innerHTML = data.history.map(item => `
                    <div class="card mb-2 fade-in">
                        <div class="card-body p-2">
                            <small class="text-muted">${item.date}</small>
                            <p class="mb-0">${item.description}</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: ${item.progress}%">${item.progress}%</div>
                            </div>
                        </div>
                    </div>
                `).join('');

                // Update teams progress table
                const teamsTable = document.getElementById('teams-progress');
                teamsTable.innerHTML = data.teams.map(team => `
                    <tr class="fade-in">
                        <td>${team.name}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: ${team.progress}%">${team.progress}%</div>
                            </div>
                        </td>
                        <td>${team.last_update}</td>
                    </tr>
                `).join('');
            })
            .catch(error => console.error('Error:', error));
    }

    // Initial update
    updateProgress();

    // Update every 30 seconds
    setInterval(updateProgress, 30000);

    // Form submission handling
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const progressInput = document.getElementById('progress');
            const descriptionInput = document.getElementById('description');

            if (progressInput.value < 0 || progressInput.value > 100) {
                e.preventDefault();
                alert('Progress must be between 0 and 100');
                return;
            }

            if (descriptionInput.value.trim().length < 10) {
                e.preventDefault();
                alert('Description must be at least 10 characters long');
                return;
            }
        });
    }

    // Add responsive navbar toggle for mobile
    const navbarToggler = document.createElement('button');
    navbarToggler.className = 'navbar-toggler d-md-none';
    navbarToggler.type = 'button';
    navbarToggler.setAttribute('data-bs-toggle', 'collapse');
    navbarToggler.setAttribute('data-bs-target', '#navbarNav');
    navbarToggler.innerHTML = '<span class="navbar-toggler-icon"></span>';

    const navbar = document.querySelector('.navbar-nav');
    if (navbar) {
        navbar.parentElement.insertBefore(navbarToggler, navbar);
        navbar.classList.add('collapse', 'navbar-collapse');
        navbar.id = 'navbarNav';
    }
}); 