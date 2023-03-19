<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/43dcc20e7a.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <title>TelaCursos</title>
</head>
<body>
    
    

    <nav>
        <input type="checkbox" id="nav-toggle">
        <div class="logo"> <h1>Ca<span1>l</span1><span>l</span>endar</h1></div>
       <ul class="links">
           
           <li><a href="/schedule/views/adm/">Calendário</a></li>
           <li><a href="#">Cursos</a></li>
           <li><a href="/schedule/cadastrarUsuario/teste.php">Usuarios</a></li>
           <li><a href="/schedule/">Sair</a></li>
           

           
           
       </ul>
       <label for="nav-toggle" class="icon-burger">
           <div class="line"></div>
           <div class="line"></div>
           <div class="line"></div>
       </label>
    </nav>

    <label for="nav-toggle" class="icon-burger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </label>

    
    

    <form id="search-form">
    <div class="input-group">
        <input type="text" name="campo" id="campo" placeholder="Procurar Curso" autocomplete="off">
        <button type="button" id="btn-buscar"><i class="fas fa-search"></i></button>
    </div>
    <div class="suggestion-list hidden"></div>
</form>




      <div class="icones">
        <a class="btn trigger">
            <img src="img/trash.png" alt="">
          </a>

          <a href="#">
            <img src="img/magicpen.png" alt="">
          </a>
          
          <a href="http://localhost/schedule/modals-cadastro/index.html">
            <img id="add" src="img/addcircle.png" alt="">
          </a>

      </div> 

     

    

     

      

        <h1 id="cursos">Cursos Cadastrados:</h1>

        <div class="linha">
          
          <div class="coluna-18" ><b>Nome:</b></div>
          <div class="coluna-18" ><b>Código:</b></div>
          <div class="coluna-18" ><b>Eixo de atuação:</b></div>
          <div class="coluna-18" ><b>Sala Comum:</b></div>
         </div>
      

      
         <section id="resultado">

         <?php  require_once('buscar_cursos.php') ?>
      
</section>


      <div class="faq">
        <a href="http://localhost/schedule/faq/index.html">
          <img src="img/faq.png">
        </a>
      </div>

      <img id="bola" src="img/Img-bola.png" alt="">
      

      <div class="modal">
  <div class="modal-content">
    <span class="close-button">&times;</span>
    <h2>Detalhes</h2>
    <div id="detalhes-turma" class="info">

      <p id="nome">Nome:</p>
      <p></p>

      <p id="tipo">Eixo:</p>
      <p></p>

      <p id="id">Código:</p>
      <p></p>

      <p id="turno">Turno:</p>
      <p></p>

      <p id="sala">Sala:</p>
      <p></p>

    </div>

    <div class="uc">
      <h1>UCs Cadastradas</h1>
      <div id="lista_turmas"></div>
      <a href="http://localhost/schedule/modals-cadastro/cadastro.html">
        <img src="img/addcircle.png" alt="Descrição da imagem">
      </a>
    </div>


</div>
</div>

      <script src="pi.js"></script>

    
    
</body>
</html>
