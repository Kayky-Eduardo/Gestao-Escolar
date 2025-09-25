const tabela = document.getElementById("Resposta");
const modalAdd = document.getElementById("dialog-adicionar")
const modalEditar = document.getElementById("dialog-editar")

function openModal(modal) {
    modal.showModal();
}

function closeModal(modal) {
    modal.close();
}
carregarAlunos()
function limparModal() {
    modalAdd.querySelectorAll("input").forEach(input => input.value = "");
    modalEditar.querySelectorAll("input").forEach(input => input.value = "");
}

async function carregarAlunos() {
    const resposta = await fetch("../api/func_alunos.php");
    const Alunos = await resposta.json();

    tabela.innerHTML = "";

    Alunos.forEach(a => {
        const tr = document.createElement("tr");
        tr.innerHTML =`
        <td>${a.matricula}</td>
        <td>${a.nome}</td>
        <td>${a.genero}</td>
        <td>${a.data_nascimento}</td>
        <td>${a.id_sala}</td>
        `;

        // excluir
        btnExcluir = document.createElement("button");
        btnExcluir.textContent = "Excluir";
        btnExcluir.onclick = async () => {
            await fetch("../api/func_alunos.php?acao=excluir", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ matricula: a.matricula })
            });
            carregarAlunos();
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

                await fetch("../api/func_alunos.php?acao=editar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        matricula: a.matricula,
                        campo: campo,
                        valor: valor
                        })
                });

                closeModal(modalEditar);
                limparModal();
                carregarAlunos();
            })
        carregarAlunos();
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
    const genero = document.getElementById("genero").value.trim().toLowerCase();
    const data_nascimento = document.getElementById("data_nascimento").value.trim();
    const id_sala = document.getElementById("id_sala").value.trim();

    if (!nome || !genero || !data_nascimento || !id_sala) {
        alert("Preencha todos os campos!");
        return;
    }

    await fetch("../api/func_alunos.php?acao=adicionar", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            nome: nome,
            genero: genero,
            data_nascimento: data_nascimento,
            id_sala: id_sala })
    });

    closeModal(modalAdd);
    limparModal();
    carregarAlunos();
})
carregarAlunos()
