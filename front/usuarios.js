const tabela = document.getElementById("Resposta");
const modal = document.getElementById("dialog")

function openModal() {
    modal.showModal();
}

function closeModal() {
    modal.close();
}

function limparModal() {
    modal.querySelectorAll("input").forEach(input => input.value = "");
}


async function carregarUsuarios() {
    const resposta = await fetch("../api/func_usuarios.php");
    const Usuarios = await resposta.json();

    tabela.innerHTML = "";

    Usuarios.forEach(u => {
        const tr = document.createElement("tr");
        tr.innerHTML =`
        <td>${u.id_user}</td>
        <td>${u.nome_usuario}</td>
        <td>${u.email}</td>
        <td>${u.nome_cargo}</td>
        <td>${u.id_cargo}</td>
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
        
        tr.append("  ", btnExcluir, "   ", btnEditar)
        tabela.appendChild(tr);

        
    });
}

carregarUsuarios()
document.getElementById("enviar").addEventListener("click", async () => {
    const nome = document.getElementById("nome").value.trim().toLowerCase();
    const email = document.getElementById("email").value.trim().toLowerCase();
    const senha = document.getElementById("senha").value.trim();
    const id_cargo = document.getElementById("id_cargo").value.trim();

    if (!nome || !email || !senha || !id_cargo) {
        alert("Preencha todos os campos!");
        return;
    }

    await fetch("../api/func_usuarios.php?acao=adicionar", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nome, email, senha, id_cargo })
    });

    closeModal();
    limparModal();
    carregarUsuarios();
})

function abrirModalEditar(u) {
    openModal();
    document.getElementById("nome").value = u.nome_usuario;
    document.getElementById("email").value = u.email;
    document.getElementById("senha").value = ""; // senha normalmente não é retornada
    document.getElementById("id_cargo").value = u.id_cargo;

    btnEnviar.onclick = async () => {
        const nome = document.getElementById("nome").value.trim().toLowerCase();
        const email = document.getElementById("email").value.trim().toLowerCase();
        const senha = document.getElementById("senha").value.trim();
        const id_cargo = document.getElementById("id_cargo").value.trim();

        if (!nome || !email || !id_cargo) {
            alert("Preencha os campos obrigatórios!");
            return;
        }

        await fetch("../api/func_usuarios.php?acao=editar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_user: u.id_user, nome, email, senha, id_cargo })
        });

        closeModal();
        limparModal();
        carregarUsuarios();
    };
}
// document.getElementById("cadastro_usuario").addEventListener("click", async  () => {
//         let nome = prompt("Nome: ").trim().toLowerCase();
//         let email = prompt(`Email: `).trim().toLowerCase();
//         let senha = prompt("senha: ").trim();
//         let id_cargo = prompt("ID do cargo: ").trim();
//         await fetch("../api/func_usuarios.php?acao=adicionar", {
//             method: "POST",
//             headers: { "Content-Type": "application/json" },
//             body: JSON.stringify({
//                 nome: nome,
//                 email: email,
//                 senha: senha,
//                 id_cargo: id_cargo
//             })
//         });
//         carregarUsuarios()

// })
