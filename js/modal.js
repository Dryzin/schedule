var modal = document.querySelector(".modal");
var triggers = document.querySelectorAll(".trigger");

const amareloBtn = document.getElementById('amarelo');
const azulBtn = document.getElementById('azul');

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

// adiciona um evento de clique ao botão amarelo para reiniciar a página
amareloBtn.addEventListener('click', function() {
  location.reload();
});

// adiciona um evento de clique ao botão azul para fechar a div "info"
azulBtn.addEventListener('click', function() {
  toggleModal()
});

window.addEventListener("click", windowOnClick);