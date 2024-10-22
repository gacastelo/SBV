function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    
    // Altera o ícone de olho
    icon.textContent = type === 'password' ? '👁️' : '👁️‍🗨️'; // Olho fechado quando a senha está visível
}
