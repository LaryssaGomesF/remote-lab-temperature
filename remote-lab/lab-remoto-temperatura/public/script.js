const apiUrl = 'http://localhost/lab-remoto-temperatura'; 

function toggleEditMode(showEdit) {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');

    if (showEdit) {
        viewMode.classList.add('hidden');
        editMode.classList.remove('hidden');
    } else {
        viewMode.classList.remove('hidden');
        editMode.classList.add('hidden');
    }
}



function cancelEdit() {
    toggleEditMode(false);
}

function saveDataAsTxt() {
    fetch(`${apiUrl}/plant`) // Substitua pela URL da API que retorna os dados
        .then(response => response.json())
        .then(data => {
            // Formatar os dados como texto
            let contentData = '';
            let contentCabeçalho = 'TEMPO | ERROR | TEMPERATURE | SETPOINT \n'; // Cabeçalhos do arquivo

            data.forEach(item => {
                contentData += `${item.t},${item.e},${item.tp},${item.s} \n`; // Formata tempo e temperatura
            });

            let content = contentCabeçalho + contentData;
            // Criar um blob com o conteúdo de texto
            const blob = new Blob([content], { type: 'text/plain' });

            // Criar um link para download
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'dados_temperatura.txt'; // Nome do arquivo

            // Clicar no link para iniciar o download
            link.click();
        })
        .catch(error => console.error('Erro ao salvar dados:', error));
}

function fetchValues() {
    fetch(`${apiUrl}/control`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('ki').value = data.ki;
            document.getElementById('kp').value = data.kp;
            document.getElementById('kd').value = data.kd;
            document.getElementById('setpoint').value = data.s;
            document.getElementById('mode').checked = data.m;


            populateTextFields(data);
        })
        .catch(error => console.error('Erro ao obter dados:', error));
}

function populateTextFields(data) {
    document.getElementById('kiText').textContent = data.ki;
    document.getElementById('kpText').textContent = data.kp;
    document.getElementById('kdText').textContent = data.kd;
    document.getElementById('setpointText').textContent = data.s
    document.getElementById('isCollecting').textContent = data.m ? "Automatico" : "Manual";
}

// Função para enviar os valores para a API
function sendValues() {
    const ki = parseFloat(document.getElementById('ki').value); // Garante que é um número
    const kp = parseFloat(document.getElementById('kp').value);
    const kd = parseFloat(document.getElementById('kd').value);
    const setpoint = parseFloat(document.getElementById('setpoint').value);
    const mode = document.getElementById('mode').checked; // Retorna booleano

    const data = {
        ki: ki,
        kd: kd,
        kp: kp,
        s: setpoint,
        m: mode
    };

    fetch(`${apiUrl}/control`, { // Substitua por sua URL de API
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            console.log('Sucesso:', data);
            fetchValues();
        })
        .catch(error => console.error('Erro ao enviar dados:', error));
    toggleEditMode(false);
}

// Função para limpar os valores
function clearValues() {
    fetch(`${apiUrl}/plant`, { // Substitua por sua URL de API para limpar
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('ki').value = '';
            document.getElementById('kp').value = '';
            document.getElementById('kd').value = '';
            document.getElementById('mode').checked = false;
            console.log('Campos limpos e API chamada:', data);
        })
        .catch(error => console.error('Erro ao limpar dados:', error));
}

 // Função para atualizar o gráfico com os dados da API
 function updateChart(chart, chartError, chartActuator) {
    fetch(`${apiUrl}/plant`) // Substitua pela URL da API
        .then(response => response.json())
        .then(data => {
            // Mapeia os dados para o formato necessário para o gráfico
            const dataPointsTemperature = data.map(item => ({
                x: (item.t / 1000.0),
                y: parseFloat(item.tp)
            }));

            const dataPointsError = data.map(item => ({
                x: (item.t / 1000.0),
                y: parseFloat(item.e)
            }));

            const dataPointsSetpoint = data.map(item => ({
                x: (item.t / 1000.0),
                y: parseFloat(item.s)
            }));


            const dataPointsActuador = data.map(item => ({
                x: (item.t / 1000.0),
                y: parseFloat(item.a)
            }));




            chart.options.data[0].dataPoints = dataPointsTemperature;
            chart.options.data[1].dataPoints = dataPointsError;
            chart.options.data[2].dataPoints = dataPointsSetpoint;

            chartError.options.data[0].dataPoints = dataPointsError;

            chartActuator.options.data[0].dataPoints = dataPointsActuador;


            chartError.render();
            chartActuator.render();
            chart.render();
        })
        .catch(error => console.error('Erro ao atualizar o gráfico:', error));
}

// Inicializa o gráfico
const chart = new CanvasJS.Chart("chartContainer", {
    zoomEnabled: true,
    title: {
        text: "Temperatura ao Longo do Tempo"
    },
    axisY: {
        title: "Temperatura (°C)"
    },
    data: [{
        type: "line",
        showInLegend: true,
        name: "Temperatura",
        dataPoints: []
    }, {
        type: "line",
        name: "Erro",
        showInLegend: true,
        dataPoints: []

    },
    {
        type: "line",
        name: "setpoint",
        showInLegend: true,
        dataPoints: []

    }]
});


const chartError = new CanvasJS.Chart("chartContainerError", {
    zoomEnabled: true,
    title: {
        text: "Erro ao Longo do Tempo"
    },
    axisY: {
        title: "Error"
    },
    data: [{
        type: "line",
        name: "Erro",
        showInLegend: true,
        dataPoints: []

    }]
});

const chartActuator = new CanvasJS.Chart("chartContainerActuador", {
    zoomEnabled: true,
    title: {
        text: "Atuador ao Longo do Tempo"
    },
    axisY: {
        title: "Atuador"
    },
    data: [{
        type: "line",
        name: "Atuador",
        showInLegend: true,
        dataPoints: []

    }]
});

chart.render();
chartError.render();
chartActuator.render();


setInterval(() => updateChart(chart, chartError, chartActuator), 1000);


    
fetchValues();

document.getElementById('editButton').addEventListener('click', () => toggleEditMode(true));
document.getElementById('sendButton').addEventListener('click', sendValues);
document.getElementById('cancelButton').addEventListener('click', cancelEdit);

document.getElementById('saveDataButton').addEventListener('click', saveDataAsTxt);
document.getElementById('clearButton').addEventListener('click', clearValues);

