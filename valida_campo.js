

//funcao para retirar a possibilidade de inserir numeros ou caracteres especiais no campo

function apenasLetras(event) {
    const charCode = (event.which) ? event.which : event.keyCode;
  
    // Permite Backspace, Delete, Tab, Enter e Espaço
    if (charCode === 8 || charCode === 9 || charCode === 13 || charCode === 32 || charCode === 46) {
      return true;
    }
  
    // Letras maiúsculas (A-Z): 65 a 90
    if (charCode >= 65 && charCode <= 90) {
      return true;
    }
  
    // Letras minúsculas (a-z): 97 a 122
    if (charCode >= 97 && charCode <= 122) {
      return true;
    }
  
    // Qualquer outro caractere (números, símbolos, especiais) é bloqueado
    return false;
}

function apenasNumeros(event) {
    const charCode = (event.which) ? event.which : event.keyCode;
  
    // Permite Backspace, Delete, Tab, Enter e Espaço
    if (charCode === 8 || charCode === 9 || charCode === 13 || charCode === 32 || charCode === 46) {
      return true;
    }
  
    // Letras maiúsculas (A-Z): 65 a 90
    if (charCode >= 65 && charCode <= 90) {
      return false;
    }
  
    // Letras minúsculas (a-z): 97 a 122
    if (charCode >= 97 && charCode <= 122) {
      return false;
    }
  
    // Qualquer outro caractere (números, símbolos, especiais) é bloqueado
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