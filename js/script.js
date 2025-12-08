// js/script.js
// Pet Care Health - JavaScript Functions

document.addEventListener('DOMContentLoaded', function() {
    
    // Handle booking form submission
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Mengirim...';
            submitBtn.disabled = true;
        });
        
        // Check for success message and show WhatsApp option
        if (window.location.search.includes('success')) {
            showSuccessWithWhatsApp();
        }
    }
    
    // Handle contact form submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Mengirim...';
            submitBtn.disabled = true;
        });
    }
    
    // Show alerts from session
    showSessionAlerts();
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
    
    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                e.target.value = value;
            } else if (value.startsWith('62')) {
                e.target.value = '0' + value.substring(2);
            }
        });
    });
});

// Validate individual field
function validateField(field) {
    if (field.validity.valid) {
        field.style.borderColor = '#48BB78';
    } else {
        field.style.borderColor = '#F56565';
    }
}

// Show session alerts
function showSessionAlerts() {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;
    
    // Check for success message in URL
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    
    if (success) {
        showAlert('success', decodeURIComponent(success));
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if (error) {
        showAlert('error', decodeURIComponent(error));
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

// Show alert message
function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;
    
    const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
    const alertHTML = `
        <div class="alert ${alertClass}">
            ${message}
        </div>
    `;
    
    alertContainer.innerHTML = alertHTML;
    
    // Scroll to alert
    alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 300);
        }
    }, 5000);
}

// Show success message with WhatsApp option
function showSuccessWithWhatsApp() {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;
    
    const message = 'Booking berhasil dikirim! Kami akan segera menghubungi Anda.';
    
    const alertHTML = `
        <div class="alert alert-success">
            <p>${message}</p>
            <p style="margin-top: 10px;">
                <button onclick="sendToWhatsApp()" class="btn btn-primary" style="display: inline-block; padding: 0.5rem 1rem;">
                    📱 Hubungi Admin via WhatsApp
                </button>
            </p>
        </div>
    `;
    
    alertContainer.innerHTML = alertHTML;
    alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Send notification to admin via WhatsApp
function sendToWhatsApp() {
    // Admin phone number (sesuaikan dengan nomor admin)
    const adminPhone = '6281234567890'; // Format: 62xxxxx
    const message = 'Halo Admin Pet Care Health, saya baru saja melakukan booking. Mohon konfirmasinya. Terima kasih!';
    const whatsappUrl = `https://wa.me/${adminPhone}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

// Format currency input
function formatCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    value = parseInt(value) || 0;
    input.value = 'Rp ' + value.toLocaleString('id-ID');
}

// Date validation - prevent past dates
const dateInputs = document.querySelectorAll('input[type="date"]');
dateInputs.forEach(input => {
    const today = new Date().toISOString().split('T')[0];
    input.setAttribute('min', today);
});

// Mobile menu toggle (untuk responsive)
function toggleMobileMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
}

// Add loading animation
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'page-loader';
    loader.innerHTML = '<div class="spinner"></div>';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.remove();
    }
}