const tabela = document.getElementById("Resposta");

async function carregarSalas() {
    const resposta = await fetch("../api/func_sala_resp.php");
    const Salas = await resposta.json();

    tabela.innerHTML = "";

    Salas.forEach(s => {
        const tr = document.createElement("tr");
        tr.innerHTML =`
        <td>${s.id_sala}</td>
        <td>${s.nome_sala}</td>
        <td>${s.capacidade}</td>
        <td>${s.data_criacao}</td>
        <td>${s.id_responsavel}</td>
        <td>${s.nome_usuario}</td>
        <td><button id="verAlunos">Ver alunos</button></td>
        <td><button id="verDisciplinas">Ver disciplinas</button></td>
        `;
        
        // ver alunos na sala
        btnAlunos = document.createElement("button");
        btnAlunos.textContent = "Ver alunos";
        btnAlunos.onclick = async () => {
            const resultadoAlunos = await fetch("../api/func_sala_resp.php?acao=AlunosSala")
            const aluno = await resultadoAlunos.json();
        }

        // ver disciplinas na sala
        btnDisciplinas = document.createElement("button");
        btnDisciplinas.textContent = "Ver disciplinas";
        btnAlunos.onclick = async () => {
            const resultadoDisciplina =  await fetch("../api/func_sala_resp.php?acao=DisciplinaSala")
            const disciplinas = await resultadoDisciplina.json();
        }
            

        // excluir
        // btnExcluir = document.createElement("button");
        // btnExcluir.textContent = "Excluir";
        // btnExcluir.onclick = async () => {
        // }

        // // editar
        // btnEditar = document.createElement("button");
        // btnEditar.textContent = "Editar";
        // btnEditar.onclick = async () => {
        // }
        tr.append("| ", btnExcluir, " | ", btnEditar)
        tabela.appendChild(tr);

        
    });
}

carregarSalas()
