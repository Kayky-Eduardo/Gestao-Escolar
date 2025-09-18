const cadastro_users = document.getElementById("cadastro_profissionais");
const lista = document.getElementById("listaTarefas");

cadastro_users.addEventListener("click", async function carregarUsers() {
    const resposta = await fetch("../api/listar_users.php");
    const users = await resposta.json();

    lista.innerHTML = "";

    users.forEach(u => {
        const li = document.createElement("li");
        li.innerHTML =`
        <strong>ID:</strong> ${u.id_user}<br>
        <strong>nome: </strong>${u.nome_usuario}
        <strong><br>E-mail: </strong>${u.email}
        <br><strong>Cargo: </strong>${u.nome}
        `;
        if (u.conta_ativa == 0) {
            li.style.textDecoration = "line-through";
        }
        lista.appendChild(li);
    });
})