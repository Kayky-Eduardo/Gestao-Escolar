const lista = document.getElementById("listaResposta");

async function carregarUsuarios() {
    const resposta = await fetch("../api/func_usuarios.php");
    const Usuarios = await resposta.json();

    lista.innerHTML = "";

    Usuarios.forEach(u => {
        const li = document.createElement("li");
        li.innerHTML =`
        <strong>ID:</strong> ${u.id_user} |
        <strong>nome:</strong> ${u.nome_usuario} |
        <strong>email: </strong>${u.email} |
        <strong>cargo: </strong>${u.nome_cargo} |
        <strong>nivel: </strong>${u.id_cargo}
        `;

        // excluir
        btnExcluir = document.createElement("button");
        btnExcluir.textContent = "Excluir";
        btnExcluir.onclick = async () => {
            await fetch("../api/func_usuarios.php?acao=excluir", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id_user: u.id_user })
            });
            carregarUsuarios();
        }

        // editar
        btnEditar = document.createElement("button");
        btnEditar.textContent = "Editar";
        btnEditar.onclick = async () => {
            let campo = prompt("O que deseja editar: ").trim().toLowerCase();
            let valor = prompt(`Novo ${campo}: `).trim().toLowerCase();
            await fetch("../api/func_usuarios.php?acao=editar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id_user: u.id_user,
                campo: campo,
                valor: valor
            })
        });
        carregarUsuarios();
        }
        
        li.append("| ", btnExcluir, " | ", btnEditar)
        lista.appendChild(li);

        
    });
}

carregarUsuarios()

document.getElementById("cadastro_usuario").addEventListener("click", async  () => {
        let nome = prompt("Nome: ").trim().toLowerCase();
        let email = prompt(`Email: `).trim().toLowerCase();
        let senha = prompt("senha: ").trim();
        let id_cargo = prompt("ID do cargo: ").trim();
        await fetch("../api/func_usuarios.php?acao=adicionar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                nome: nome,
                email: email,
                senha: senha,
                id_cargo: id_cargo
            })
        });
        carregarUsuarios()

})
