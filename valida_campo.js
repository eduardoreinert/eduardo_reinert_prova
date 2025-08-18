//funcao para garantir que os campos estejam preenchidos

function conferirCampos() {
    var nome = document.getElementById('nome'); //Cria a varivael nome puxa seu Id
    var email = document.getElementById('email'); //Cria a varivael nome puxa seu Id
    var senha = document.getElementById('senha'); //Cria a varivael nome puxa seu Id
  
    if (!nome || nome.value.trim() === "")  { //Se a variavel nome não existir (! garante isso), OU, seo valor digitado, sem contar os espaços for vazio
        alert('Campo de nome deve ser preenchido!');   //Exibe a mensagem de erro pois o campo está vazio
        return false; //Impede que os dados do formulario sejam enviados, caso não estivesse aqui, apareceria a mensagem, mas os dados seriam enviados
    }

    if (!nome || nome.value.trim() === "")  { //Se a variavel nome não existir (! garante isso), OU, seo valor digitado, sem contar os espaços for vazio
        alert('Campo de nome deve ser preenchido!');   //Exibe a mensagem de erro pois o campo está vazio
        return false; //Impede que os dados do formulario sejam enviados, caso não estivesse aqui, apareceria a mensagem, mas os dados seriam enviados
    }
  
    if (!senha || senha.value.trim() === "")  { //Se a variavel nome não existir (! garante isso), OU, seo valor digitado, sem contar os espaços for vazio
        alert('Campo de nome deve ser preenchido!'); //Exibe a mensagem de erro pois o campo está vazio
        return false; //Impede que os dados do formulario sejam enviados, caso não estivesse aqui, apareceria a mensagem, mas os dados seriam enviados
    } else {
        document.getElementById("enviar_formulario")?.addEventListener("click", () => {
            window.location.href = "enviar_forms.php";
          });
  
          return true; //Caso os campos sejam preenchidos, continua
    }
}

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
