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
        <strong>data de nascimento: </strong>${a.data_nascimento}
        `;
        if (a.conta_ativa == 0) {
            li.style.textDecoration = "line-through";
        }

        lista.appendChild(li);

        btnEditar = document.createElement("button");
        btnEditar.textContent = "Editar";
        btnEditar.onclick = async () => {
            let opcao_editar = prompt("O que desejja editar")
            let opcoes = ["nome", "genero", "data_nascimento", "conta_ativa", "id_sala", "matricula"];
            for(i=0; i<opcoes.length; i++) {
                if (opcao_editar.trim() == opcoes) {
                    editarAluno($conn, )
                }
            }
        }
    
        
    });
}

carregarAlunos()
