const tabela = document.getElementById("Resposta");
const detalhesContainer = document.getElementById("detalhesContainer");

async function mostrarAlunos(id_sala, nome_sala) {
    const resposta = await fetch("../api/func_sala_resp.php?acao=alunoSala", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_sala: id_sala })
    });
    const alunos = await resposta.json();

    let htmlConteudo = `<div class="exibir_alunos_sala"><h2>${nome_sala}</h2>`;
    if (alunos.length > 0) {
        htmlConteudo += `
            <table>
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nome</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        alunos.forEach(aluno => {
            htmlConteudo += `
                <tr>
                    <td>${aluno.matricula}</td>
                    <td>${aluno.nome}</td>
                </tr>
            `;
        });

        htmlConteudo += `</tbody></table>`;
    } else {
        htmlConteudo += '<p>Nenhum aluno encontrado nesta sala.</p>';
    }

    detalhesContainer.innerHTML = htmlConteudo;
}

async function verAlunosNotas(id_sala, id_disciplina) {
    const notasContainer = document.getElementById("notas-disciplina-container");
    notasContainer.innerHTML = '<p>Carregando notas...</p>';

    const resposta = await fetch("../api/func_sala_resp.php?acao=alunosNotasDisciplina", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            id_sala: id_sala,
            id_disciplina: id_disciplina 
        })
    });
    const dados = await resposta.json();

    // 2. Construir o HTML para os alunos e notas
    let htmlConteudo = '';
    
    if (dados && dados.length > 0) {
        htmlConteudo += `
            <table class="tabela-notas">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nome do Aluno</th>
                        <th>1º Bimestre</th>
                        <th>2º Bimestre</th>
                        <th>3º Bimestre</th>
                        <th>4º Bimestre</th>
                    </tr>
                </thead>
                <tbody>
        `;

        dados.forEach(aluno => {
            // Usa ?? '—' para exibir um traço se a nota for NULL (ainda não lançada)
            const b1 = aluno.bimestre_1 ?? '—'; 
            const b2 = aluno.bimestre_2 ?? '—';
            const b3 = aluno.bimestre_3 ?? '—';
            const b4 = aluno.bimestre_4 ?? '—';

            htmlConteudo += `
                <tr>
                    <td>${aluno.matricula}</td>
                    <td>${aluno.nome_aluno}</td>
                    <td>${b1}</td>
                    <td>${b2}</td>
                    <td>${b3}</td>
                    <td>${b4}</td>
                </tr>
            `;
        });

        htmlConteudo += `</tbody></table>`;
    } else if (dados && dados.erro) {
        htmlConteudo += `<p class="erro-api">Erro ao carregar notas: ${dados.erro}</p>`;
    } else {
        htmlConteudo += '<p>Nenhum aluno com notas encontrado para esta disciplina.</p>';
    }

    // 3. Inserir no container
    notasContainer.innerHTML = htmlConteudo;
}

async function mostrarDisciplinas(id_sala, nome_sala) {
    // 1. Chamar a API para obter as disciplinas
    const resposta = await fetch("../api/func_sala_resp.php?acao=disciplinaSala", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_sala: id_sala })
    });
    const disciplinas = await resposta.json();

    // 2. Construir o HTML para as disciplinas (com o botão de ver alunos/notas)
    let htmlConteudo = `<h2 class="home-titulo">Sala: ${nome_sala}</h2>`;
    htmlConteudo += '<div id="lista-disciplinas-container">';
    if (disciplinas.length > 0) {
        htmlConteudo += `<ul class="home-menu">`;
        disciplinas.forEach(d => {
            htmlConteudo += `
                <li>
                    ${d.disciplina}
                    <button 
                        class="btn-ver-notas" 
                        data-sala-id="${id_sala}" 
                        data-disciplina-id="${d.id_disciplina}">
                        Detalhes
                    </button>
                </li>`;
        });
        htmlConteudo += '</ul>';
        htmlConteudo += '</div>';
        htmlConteudo += '<div id="notas-disciplina-container"></div>'; 
        detalhesContainer.innerHTML = htmlConteudo;
    } else {
        htmlConteudo += '<p>Nenhuma disciplina encontrada nesta sala.</p>';
    }

    detalhesContainer.innerHTML = htmlConteudo;

    document.querySelectorAll('.btn-ver-notas').forEach(button => {
        button.addEventListener('click', () => {
            const id_sala = button.getAttribute('data-sala-id');
            const id_disciplina = button.getAttribute('data-disciplina-id');
            const listaContainer = document.getElementById('lista-disciplinas-container');
            if (listaContainer) {
                listaContainer.innerHTML = '';
            }
            verAlunosNotas(id_sala, id_disciplina);
        });
    });
}


async function carregarSalas(id_usuario_responsavel) {
    const resposta = await fetch("../api/func_sala_resp.php?acao=listarSalasResponsavel", {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id_responsavel: id_usuario_responsavel })
    });
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
        <td><button class="btn-alunos" data-id="${s.id_sala}" data-nome="${s.nome_sala}">Ver alunos</button></td>
        <td><button class="btn-disciplinas" data-id="${s.id_sala}" data-nome="${s.nome_sala}">Ver disciplinas</button></td>
        `;
        tabela.appendChild(tr);
    });

    document.querySelectorAll('.btn-alunos').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');
            mostrarAlunos(id, nome);
        });
    });

    document.querySelectorAll('.btn-disciplinas').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');
            mostrarDisciplinas(id, nome);
        });
    });
}

function inicializarSalasResponsavel() {
    const dadosUserElement = document.getElementById("dados-user");
    
    if (dadosUserElement) {
        const idUsuario = dadosUserElement.getAttribute('data-id-user');

        if (idUsuario && idUsuario !== '') {
            carregarSalas(idUsuario);
        } else {
            console.error("ID do usuário não encontrado. Verifique a sessão PHP.");
        }
    } else {
        console.error("Elemento 'dados-user' não encontrado. Certifique-se de que o PHP injetou o ID.");
    }
}
inicializarSalasResponsavel();