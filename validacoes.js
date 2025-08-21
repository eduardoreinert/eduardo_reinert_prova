function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value;
    let telefone = document.getElementById("telefone").value;
    let email = document.getElementById("email").value;

    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    let regexTelefone = /^[0-9]{10,11}$/;
    if (!regexTelefone.test(telefone)) {
        alert("Digite um telefone válido (10 ou 11 dígitos).");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}

function mascara(o, f){
    v_obj=o;
    v_fun=f;
    setTimeout("execmascara()", 1);
  }
  
  function execmascara(){
    v_obj.value=v_fun(v_obj.value);
  }
  
  function telefone(v){
    v=v.replace(/\D/g, "");
    v=v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v=v.replace(/(\d)(\d{4})$/, "$1-$2");
    return v;
  }