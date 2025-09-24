const tabela = document.getElementById("Resposta");

async function carregarAlunos() {
    const resposta = await fetch("../api/func_sala_resp?acao=Alunossala.php");
    const Alunos = await resposta.json();

    tabela.innerHTML = "";

    Alunos.forEach(a => {
        const tr = document.createElement("tr");
        tr.innerHTML =`
        <td>${a.nome}</td>
        `;
        tabela.appendChild(tr);

        
    });
}

carregarAlunos()
