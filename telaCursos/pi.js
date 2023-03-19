
// const cell = document.querySelector('.clicar');
// cell.addEventListener('click', () => {
//     alert('Tabela clicada');
// });

// var url = "modalDetalhes/index.html";
// var btn = document.querySelector(".clicar");
// function openInNewTab(url) {
//   var win = window.open(url, '_blank');
//   win.focus();
// }
// btn.addEventListener('click', function() {
    
//   open(url);
// });

// var modal = document.querySelector(".modal");
// var triggers = document.querySelectorAll(".trigger");
// var closeButton = document.querySelector(".close-button");

// function toggleModal() {
//   modal.classList.toggle("show-modal");
// }

// function windowOnClick(event) {
//   if (event.target === modal) {
//     toggleModal();
//   }
// }

// for (var i = 0, len = triggers.length; i < len; i++) {
//   triggers[i].addEventListener("click", toggleModal);
// }
// closeButton.addEventListener("click", toggleModal);
// window.addEventListener("click", windowOnClick);




// var all_apps = document.querySelectorAll('.app');
// var search = document.querySelector('#search');
// var listContainer = document.querySelector('.suggestion-list');

// var app_list = [];

// for (let i = 0; i < all_apps.length; i++) {
//   let app_title = all_apps[i].querySelector('p').innerText.toLowerCase();
//   let app_icon = all_apps[i].querySelector('i').classList.value;

//   let obj = {};
//   obj.app_title = app_title;
//   obj.app_icon = app_icon;

//   app_list.push(obj);
// }

// search.addEventListener('keyup', generateAppList);
// search.addEventListener('blur', hideAppList);

// function generateAppList(event) {
//   var fragment = document.createDocumentFragment();

//   var userInput = event.target.value.toLowerCase();

//   if (userInput.length === 0) {
//     listContainer.classList.add('hidden');
//     return false;
//   }

//   listContainer.innerHTML = '';
//   listContainer.classList.remove('hidden');

//   var filteredList = app_list.filter(function (arr) {
//     return arr.app_title.includes(userInput);
//   });

//   if (filteredList.length === 0) {
//     let paragraph = document.createElement('p');
//     paragraph.innerText = 'Nenhum curso encontrado';
//     fragment.appendChild(paragraph);
//   }

//   else {
//     for (let i = 0; i < filteredList.length; i++) {
//       let paragraph = document.createElement('p');
//       let icon = document.createElement('i');
//       let span = document.createElement('span');

//       icon.classList.value = filteredList[i].app_icon;
//       span.innerText = filteredList[i].app_title;
//       paragraph.appendChild(icon);
//       paragraph.appendChild(span);
//       fragment.appendChild(paragraph);
//     }
//   }

//   listContainer.appendChild(fragment);
// }

// function hideAppList() {
//   listContainer.classList.add('hidden');
// }

// ele busca no banco o nome do curso e ja da o reset 
$(document).ready(function(){
  // define a ação do campo de busca
  $('#campo').keyup(function(){
    // obtém o valor digitado no campo de busca
    var campo = $(this).val();
    // verifica se o campo de busca está vazio
    if (campo == "") {
      campo = "all"; // define um valor para indicar que todas as turmas devem ser buscadas
    }
    // envia uma solicitação AJAX para processa.php
    $.ajax({
      url: 'processa.php',
      method: 'post',
      dataType: 'html',
      data: {campo: campo},
      success: function(data){
        $('#resultado').html(data);
        
        // adiciona o evento de clique nos elementos da tabela
        $('#conteudo tr').click(function(){
          // obtém o valor do nome da turma clicada
          var nome = $(this).find('td:first').text();
        
          // faz a requisição AJAX para obter os detalhes da turma
          $.ajax({
            url: 'detalhes-turma.php',
            type: 'POST',
            dataType: 'json',
            data: {campo: nome},
            success: function(resultado){
              // preenche os elementos HTML com os detalhes da turma
              $('#detalhes-turma #nome + p').text(resultado[0].nome);
              $('#detalhes-turma #tipo + p').text(resultado[0].tipo);
              $('#detalhes-turma #id + p').text(resultado[0].id);
              $('#detalhes-turma #turno + p').text(resultado[0].turno);
              $('#detalhes-turma #sala + p').text(resultado[0].sala);
              $('#lista_turmas #nome_uc + p').text($(this).data('nome_uc'));
            
              // mostra o modal
              toggleModal();
            },
            
            error: function(resultado){
              alert(resultado.erro);
            }
          });
        });
        
      }
    });
  });
  
  // Ao clicar no link "Ver UCs"
  $(document).on('click', '.trigger', function(event) {
    // Evitar que a página seja recarregada
    event.preventDefault();
    // Obter o ID da turma correspondente
    var turma_id = $(this).data("turma-id");
    // Atualizar o conteúdo do modal com as informações da turma e as UCs cadastradas
    $.ajax({
      url: "buscar_ucs.php?turma_id=" + turma_id,
      success: function(result) {
        $("#lista_turmas").html(result);
        // Exibir o modal com as informações atualizadas
        $(".modal").show();
      }
    });
  });
  
});


// código do modal (mantido igual)
var modal = document.querySelector(".modal");
var triggers = document.querySelectorAll(".trigger");
var closeButton = document.querySelector(".close-button");

function toggleModal() {
modal.classList.toggle("show-modal");
}

function windowOnClick(event) {
if (event.target === modal) {
toggleModal();
}
}

for (var i = 0, len = triggers.length; i < len; i++) {
triggers[i].addEventListener("click", toggleModal);
}
closeButton.addEventListener("click", toggleModal);
window.addEventListener("click", windowOnClick);



$(document).ready(function() {
  // Adiciona evento de clique aos botões da tabela
 // Adiciona evento de clique aos botões da tabela
$('.trigger').click(function() {
  // Preenche os campos do modal com as informações da turma
  $('#detalhes-turma #nome + p').text($(this).data('nome'));
  $('#detalhes-turma #tipo + p').text($(this).data('tipo'));
  $('#detalhes-turma #id + p').text($(this).data('id'));
  $('#detalhes-turma #turno + p').text($(this).data('turno'));
  $('#detalhes-turma #sala + p').text($(this).data('sala'));

  // Seleciona o elemento HTML onde o nome da UC será exibido
  // Exibe o modal
  $('.modal').show();
});

  // Adiciona evento de clique ao botão de fechar o modal
  $('.close-button').click(function() {
    // Esconde o modal
    $('.modal').hide();
  });
});




// funçao que chama as ucs cadastradas pra cada curso
$(document).ready(function() {
  // Ao clicar no link "Ver UCs"
  $(".trigger").click(function(event) {
    // Evitar que a página seja recarregada
    event.preventDefault();
    // Obter o ID da turma correspondente
    var turma_id = $(this).data("turma-id");
    // Atualizar o conteúdo do modal com as informações da turma e as UCs cadastradas
    $.ajax({
      url: "buscar_ucs.php?turma_id=" + turma_id,
      success: function(result) {
        $("#lista_turmas").html(result);
        // Exibir o modal com as informações atualizadas
        $(".modal").show();
      }
    });
  });
});



