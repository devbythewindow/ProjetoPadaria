<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>Lanche dos alunos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header id="cabecalho">
    <div id="cab">
        <body>
    <div class="header">
        <h1>Café dos Alunos</h1>
        <p>Café, bolos e salgados!</p>
    </div>
    <div class="container">
        <nav class="itens">
            <ul>
            <li><a href="#cafes">Cafés</a></li>
            <li><a href="#bolos">Bolos</a></li>
            <li><a href="#salgados">Salgados</a></li>
            <li><a href="#cadastro">Cadastre-se</a></li>
            </ul>
        </nav>
    </div>

    <section id="cafes">
        <div class="product">
            <h2>Café Expresso</h2>
            <p>R$ 5,00</p>
        </div>
        <div class="product">
            <h2>Café com Leite</h2>
            <p>R$ 6,50</p>
        </div>
    </section>

    <section id="bolos">
        <div class="product">
            <h2>Bolo de Cenoura</h2>
            <p>R$ 12,00</p>
        </div>
        <div class="product">
            <h2>Bolo de Chocolate</h2>
            <p>R$ 15,00</p>
        </div>
    </section>

    <section id="salgados">
        <div class="product">
            <h2>Coxinha</h2>
            <p>R$ 4,00</p>
        </div>
        <div class="product">
            <h2>Mistão</h2>
            <p>R$ 8,00</p>
        </div>
    </section>
    <div class="footer">
        <section id="cadastro">
            <form action="" method="post">
                <h2>Coloque seu e-mail para receber novidades!</h2>
                <input type="text" name="nome" placeholder="Nome Completo" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </div>

    <footer>
        <p>© Cafeteria </p>
    </footer>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>


</body>        
        <h1>Cabeçalho</h1>        
        <div id="usuario">
            <a href="main.php">
            <p>
                <?php 
                // Start the session
                session_start();
                
                // Check if the session variable 'nome' is set
                if (isset($_SESSION['nome'])) {
                    echo "Seja bem vindo(a) " . $_SESSION['nome'];
                } else {
                    echo "Nenhum usuário logado.";
                }
                ?>
            </p>
            </a>
        </div>  
        <div id="sair">
            <button id="cadastrarProduto">
                <a href="logoff.php">Sair</a>
            </button>
        </div>  
    </div>
</header>
<br>
<br>
<main id="principal">
<button type="button" id="cadastrarProduto">
    <a href="form_produto.php">Cadastrar produto</a>
</button>
<h4>Produtos</h4>
<div id="resultado">

</div>
</main> 
<script src="../js/produtos.js"></script>   
</body>
</html>