const lista = document.getElementById("listaResposta");

async function carregarSalas() {
    const resposta = await fetch("../api/func_sala.php");
    const Salas = await resposta.json();

    lista.innerHTML = "";

    Salas.forEach(s => {
        const li = document.createElement("li");
        li.innerHTML =`
        <strong>ID:</strong> ${s.id_sala} |
        <strong>nome:</strong> ${s.nome_sala} |
        <strong>capacidade: </strong>${s.capacidade} |
        <strong>responsavel: </strong>${s.nome_usuario}
        `;
                
        // // excluir
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
            let campo = prompt("O que deseja editar: ").trim().toLowerCase();
            let valor = prompt(`Novo ${campo}: `).trim().toLowerCase();
            await fetch("../api/func_sala.php?acao=editar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id_sala: s.id_sala,
                campo: campo,
                valor: valor
            })
        });
        carregarSalas();
        }
        
        li.append("| ", btnExcluir, " | ", btnEditar)
        lista.appendChild(li);

        
    });
}

carregarSalas()

document.getElementById("cadastro_sala").addEventListener("click", async  () => {
        let nome_sala = prompt("Nome da sala: ").trim().toLowerCase();
        let capacidade = prompt(`capacidade: `).trim().toLowerCase();
        let id_responsavel = prompt("ID do responsável: ").trim();
        await fetch("../api/func_sala.php?acao=adicionar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                nome_sala: nome_sala,
                capacidade: capacidade,
                id_responsavel: id_responsavel
            })
        });
        carregarSalas()

})
