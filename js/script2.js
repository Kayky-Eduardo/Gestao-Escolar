async function carregarUsers() {
    const resposta = await fetch("../api/listar.php");
    const usuarios = await resposta.json();

    const lista = document.getElementById("listaResposta");
    lista.innerHTML = "";

    usuarios.forEach(u => {
        const li = document.createElement("li");
        li.textContent = u.nome_usuario
        lista.appendChild(li);
    });
}

document.getElementById("cadastrar_usuarios").addEventListener("click", () => {
    carregarUsers();
})