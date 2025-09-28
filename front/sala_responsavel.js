const tabela = document.getElementById("Resposta");
const detalhesContainer = document.getElementById("detalhesContainer");

async function mostrarAlunos(id_sala, nome_sala) {
    // 1. Chamar a API para obter os alunos
    const resposta = await fetch("../api/func_sala_resp.php?acao=alunoSala", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_sala: id_sala })
    });
    const alunos = await resposta.json();

    // 2. Construir o HTML para os alunos
    // uso do htmlConteudo para ser mais rápido de fazer a iteração e evitar erros no innerhtml
    let htmlConteudo = `<h3>${nome_sala}</h3>`;
    htmlConteudo += "<h4>Alunos:</h4>" 
    if (alunos.length > 0) {
        htmlConteudo += '<ul>';
        alunos.forEach(aluno => {
            htmlConteudo += `<li>Matrícula: ${aluno.matricula} - Nome: ${aluno.nome}</li>`;
        });
        htmlConteudo += '</ul>';
    } else {
        htmlConteudo += '<p>Nenhum aluno encontrado nesta sala.</p>';
    }

    // 3. Inserir no container
    detalhesContainer.innerHTML = htmlConteudo;
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
    let htmlConteudo = `<h3>Disciplinas da Sala: ${nome_sala} (ID: ${id_sala})</h3>`;
    if (disciplinas.length > 0) {
        htmlConteudo += '<ul>';
        disciplinas.forEach(d => {
            htmlConteudo += `
                <li>
                    ${d.disciplina} (ID: ${d.id_disciplina})
                    <button 
                        class="btn-ver-notas" 
                        data-sala-id="${id_sala}" 
                        data-disciplina-id="${d.id_disciplina}">
                        Ver Alunos e Notas
                    </button>
                </li>`;
        });
        htmlConteudo += '</ul>';
        // Adicionar um container para as notas
        htmlConteudo += '<div id="notas-disciplina-container"></div>'; 
    } else {
        htmlConteudo += '<p>Nenhuma disciplina encontrada nesta sala.</p>';
    }

    // 3. Inserir no container
    detalhesContainer.innerHTML = htmlConteudo;

    // 4. Adicionar evento de clique para o botão "Ver Alunos e Notas"
    document.querySelectorAll('.btn-ver-notas').forEach(button => {
        button.addEventListener('click', () => {
            // OBS: Você precisará de uma nova função PHP/JS para buscar Notas
            const id_sala = button.getAttribute('data-sala-id');
            const id_disciplina = button.getAttribute('data-disciplina-id');
            // Você chamaria uma nova função aqui: ex: verAlunosNotas(id_sala, id_disciplina);
            document.getElementById('notas-disciplina-container').innerHTML = `<p>Funcionalidade de Notas para Sala ${id_sala} e Disciplina ${id_disciplina} em desenvolvimento...</p>`;
        });
    });
}

// --- Função principal de carregamento das salas ---

async function carregarSalas(id_usuario_responsavel) {
    // ... seu código de fetch para listar salas (já está correto) ...
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
        
        // Você precisa de uma nova coluna para editar e excluir, mas focando no include:
        // Exemplo:
        // tr.innerHTML += `<td> | <button>Excluir</button> | <button>Editar</button> </td>` 
        
        tabela.appendChild(tr);
    });

    // Adiciona o event listener APÓS todas as linhas terem sido criadas
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
            // Chama carregarSalas com o ID do usuário obtido do HTML
            carregarSalas(idUsuario);
        } else {
            console.error("ID do usuário não encontrado. Verifique a sessão PHP.");
        }
    } else {
        console.error("Elemento 'dados-user' não encontrado. Certifique-se de que o PHP injetou o ID.");
    }
}
inicializarSalasResponsavel();