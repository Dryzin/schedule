<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Tabela com Barra de Pesquisa</title>
	<link rel="stylesheet" href="teste - Copia.css">
	
</head>
<body>

	<nav>
        <input type="checkbox" id="nav-toggle">
        <div class="logo"> <h1>Ca<span1>l</span1><span>l</span>endar</h1></div>
       <ul class="links">
		
           
           <li><a href="/schedule/views/adm/">Calendário</a></li>
           <li><a href="/schedule/telaCursos/index.php">Cursos</a></li>
           <li><a href="#">Usuarios</a></li>
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

    
    

    <form>
        <div class="input-group">
          <input type="text" id="search" placeholder="Procurar Curso" autocomplete="off">
          <button for="search"><i class="fas fa-search"><img src="./assets/arrowright-removebg-preview.png" alt="arrow-search"></i></button>
        </div>
        
        <div class="suggestion-list hidden"></div>
    
      </form>


      <div class="icones">
        <a href="#">
            <img src="./assets/trash.png" alt="">
          </a>

          <a href="https://github.com/GuiFerrarii/pi23">
            <img src="./assets/magicpen.png" alt="">
          </a>

          <a href="#">
            <img id="add" src="./assets/addcircle.png" alt="">
          </a>


      </div> 
	
	  <div >
		<button class="botao-toggle">ADM</button>
		<button class="botao-toggle1">DOCENTE</button>
	</div>


      <div class="table">

        <h1>Usuários Cadastrados</h1>
      </div>

      <div class="app">
        <i class="Sistemas"></i>
        <p> Sistemas</p>
      </div>

      <div class="app">
        <i class="estetica"></i>
        <p>Estetica</p>
      </div>

      <div class="app">
        <i class="redes"></i>
        <p>Redes</p>
      </div>

      <script src="pi.js"></script>

	

	<div class="linha">
		<div class="coluna-18"><b>Nome:</b></div>
		<div class="coluna-18"><b>Matricula:</b></div>
		<div class="coluna-18"><b>Email:</b></div>
		<div class="coluna-18"><b>Curso Recorrente:</b></div>
		<div class="coluna-18"><b>Tipo:</b></div>
	 </div>


	 <?php
  require_once('../db-connect.php');

  // Definir codificação para exibir caracteres especiais corretamente
  $conn->set_charset("utf8");

  // Buscar a lista de turmas
  $turmas = $conn->query("SELECT nome, id, tipo, sala, turno, carga_horaria, COUNT(*) AS total FROM turma GROUP BY nome ORDER BY nome");

  // Verificar se existem turmas cadastradas
  if ($turmas->num_rows > 0) {
    echo '<table>';
    echo '<tbody id="conteudo">';

    // Loop para exibir cada turma
    while ($turma = $turmas->fetch_assoc()) {
      echo "<tr>";
      echo '<td class="btn trigger" style="width: 25%; overflow: hidden;">' . $turma['nome'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $turma['id'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $turma['tipo'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $turma['sala'] . '</td>';
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
  } else {
    echo "Nenhuma turma cadastrada.";
  }

  // Buscar a lista de usuários
  $usuarios = $conn->query("SELECT ra_user, nome, email, tipo FROM usuario");

  // Verificar se existem usuários cadastrados
  if ($usuarios->num_rows > 0) {
    echo '<table>';
    echo '<tbody id="conteudo">';

    // Loop para exibir cada usuário
    while ($usuario = $usuarios->fetch_assoc()) {
      echo "<tr>";
      echo '<td style="width: 25%; overflow: hidden;">' . $usuario['ra_user'] . '</td>';
      echo '<td class="btn trigger" style="width: 25%; overflow: hidden;">' . $usuario['nome'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $usuario['email'] . '</td>';
      echo '<td style="width: 25%; overflow: hidden;">' . $usuario['tipo'] . '</td>';
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
  } else {
    echo "Nenhum usuário cadastrado.";
  }

  // Fechar a conexão com o banco de dados
  $conn->close();
?>



		<div class="modal">
			<div class="modal-content">
			  <span class="close-button">&times;</span>
			  <h2>Detalhes</h2>
			  <div class="info">
		  
				<p id="title">Nome</p>
				<p>Técnico em Desenvolvimento de sistemas</p> 
		   
				<p id="title">Eixo</p>
				<p>Técnologico</p>
		  
				<p id="title">Código</p>
				<p>0222</p>
		  
				<p id="title">Turno</p>
				<p>matutino</p>
		  
				<p id="title">Sala</p>
				<p>18</p>
				
		  
			   
		  
		  
		  
				
			  </div>
		  
		  
			  <div class="uc">
				<h1>UCs Cadastradas</h1>
		  
				<p>Mobile</p>
		  
				<p>Mobile</p>
		  
				<p>Mobile</p>
		  
				<p>Mobile</p>
		  
				<p>Mobile</p>
		  
			   
		  
				
		  
				
		  
				<a href="#">
				  <img src="./assets/addcircle.png" alt="Descrição da imagem">
				</a>
		  
				
		  
		  
		  
			  </div>

			</div>

	</div>

	<script src="teste.js"></script>

	<div class="faq">
        <a href="#">
          <img src="./assets/🦆 icon _Help_.png">
        </a>
      </div>

	  <div class="bola">
		<img src="./assets/Img-bola.png" alt="">
	  </div>
      
	

</body>
</html>
