function validarFuncionario() {
    
    let telefone = document.getElementById("telefone").value.trim();
    let email = document.getElementById("email").value;
    let nome = document.getElementById("nome_funcionario").value;

    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    if(telefone.length !== 15){
        alert('Telefone inválido!');
        event.preventDefault();
        return;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}
