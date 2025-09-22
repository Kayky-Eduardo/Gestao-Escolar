const lista = document.getElementById("listaResposta");

async function carregarAlunos() {
    const resposta = await fetch("../api/func_alunos.php");
    const Alunos = await resposta.json();

    lista.innerHTML = "";

    Alunos.forEach(a => {
        const li = document.createElement("li");
        li.innerHTML =`
        <strong>matrícula:</strong> ${a.matricula} |
        <strong>nome:</strong> ${a.nome} |
        <strong>genero: </strong>${a.genero} |
        <strong>data de nascimento: </strong>${a.data_nascimento} |
        <strong>Sala ID: </strong>${a.id_sala}
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
            let campo = prompt("O que deseja editar: ").trim().toLowerCase();
            let valor = prompt(`Novo ${campo}: `).trim().toLowerCase();
            await fetch("../api/func_alunos.php?acao=editar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                matricula: a.matricula,
                campo: campo,
                valor: valor
            })
        });
        carregarAlunos();
        }
        
        li.append("| ", btnEditar, " | ", btnExcluir)
        lista.appendChild(li);

        
    });
}

carregarAlunos()

document.getElementById("cadastro_alunos").addEventListener("click", async  () => {
        let nome = prompt("Nome do aluno: ").trim().toLowerCase();
        let genero = prompt(`Genero do aluno ${nome}: `).trim().toLowerCase();
        let data_nascimento = prompt("Data de nascimento(ex: aaaa-mm-dd): ").trim();
        let id_sala = prompt("ID da sala: ").trim();
        await fetch("../api/func_alunos.php?acao=adicionar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                nome: nome,
                genero: genero,
                data_nascimento: data_nascimento,
                id_sala: id_sala
            })
        });
        carregarAlunos()

})
