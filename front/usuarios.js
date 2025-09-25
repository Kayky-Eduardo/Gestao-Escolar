const tabela = document.getElementById("Resposta");
const modalAdd = document.getElementById("dialog-adicionar")
const modalEditar = document.getElementById("dialog-editar")

function openModal(modal) {
    modal.showModal();
}

function closeModal(modal) {
    modal.close();
}

function limparModal() {
    modalAdd.querySelectorAll("input").forEach(input => input.value = "");
    modalEditar.querySelectorAll("input").forEach(input => input.value = "");
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
        <td>${u.nivel}</td>
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
            openModal(modalEditar);
            document.getElementById("salvar").addEventListener("click", async () => {
                const campo = document.getElementById("campo").value.trim().toLowerCase();
                const valor = document.getElementById("novo-valor").value.trim().toLowerCase();

                if (!campo || !valor) {
                    alert("Preencha todos os campos!");
                    return;
                }

                await fetch("../api/func_usuarios.php?acao=editar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        id_user: u.id_user,
                        campo: campo,
                        valor: valor
                        })
                });

                closeModal(modalEditar);
                limparModal();
                carregarUsuarios();
            })
        carregarUsuarios();
        }
        const tdAcoes = document.createElement("td");
        tdAcoes.appendChild(btnEditar);
        tdAcoes.appendChild(document.createTextNode(" "));
        tdAcoes.appendChild(btnExcluir);
        tr.appendChild(tdAcoes);
        tabela.appendChild(tr);
    });

}

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
        body: JSON.stringify({ 
            nome: nome,
            email: email,
            senha: senha,
            id_cargo: id_cargo })
    });

    closeModal(modalAdd);
    limparModal();
    carregarUsuarios();
})
carregarUsuarios()
