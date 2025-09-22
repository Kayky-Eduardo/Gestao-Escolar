const lista = document.getElementById("listaResposta");

async function carregarUsers() {
    const resposta = await fetch("../api/func_usuarios.php");
    const users = await resposta.json();

    lista.innerHTML = "";

    users.forEach(u => {
        const li = document.createElement("li");
        li.innerHTML =`
        <strong>ID:</strong> ${u.id_user} |
        <strong>nome: </strong>${u.nome_usuario} |
        <strong>E-mail: </strong>${u.email} |
        <strong>Cargo: </strong>${u.nome} |
        `;
        if (u.conta_ativa == 0) {
            li.style.textDecoration = "line-through";
        }
        lista.appendChild(li);
    });
}

    carregarUsers();

