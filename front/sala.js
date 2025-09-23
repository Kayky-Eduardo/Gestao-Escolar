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

async function carregarSalas() {
    const resposta = await fetch("../api/func_sala.php");
    const Salas = await resposta.json();

    tabela.innerHTML = "";

    Salas.forEach(s => {
        const tr = document.createElement("tr");
        tr.innerHTML =`
        <td>${s.id_sala}</td>
        <td>${s.nome_sala}</td>
        <td>${s.capacidade}</td>
        <td>${s.data_criacao}</td>
        <td>${s.nome_usuario}</td>
        `;
                
        // excluir
        btnExcluir = document.createElement("button");
        btnExcluir.textContent = "Excluir";
        btnExcluir.onclick = async () => {
            await fetch("../api/func_sala.php?acao=excluir", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id_sala: s.id_sala })
            });
            carregarSalas();
        }

        // // editar
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

                await fetch("../api/func)sala.php?acao=editar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        id_sala: s.id_sala,
                        campo: campo,
                        valor: valor
                        })
                });

                closeModal(modalEditar);
                limparModal();
                carregarSalas();
            })
        carregarSalas();
        }
        tr.append("| ", btnExcluir, " | ", btnEditar)
        tabela.appendChild(tr);

        
    });
}

document.getElementById("enviar").addEventListener("click", async () => {
    const nome_sala = document.getElementById("nome_sala").value.trim().toLowerCase();
    const capacidade = document.getElementById("capacidade").value.trim().toLowerCase();
    const id_responsavel = document.getElementById("id_responsavel").value.trim();

    if (!nome_sala || !capacidade || !id_responsavel) {
        alert("Preencha todos os campos!");
        return;
    }
    await fetch("../api/func_sala.php?acao=adicionar", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            nome_sala: nome_sala,
            capacidade: capacidade,
            id_responsavel: id_responsavel
        })
    });

    closeModal(modalAdd);
    limparModal();
    carregarSalas();
})

carregarSalas()
