
<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Lato|Playfair Display|Bebas Neue|Montserrat:wght@200">

    <title>Controle de Temperatura</title>
    <style>
        body {
            margin-top: 5px;
        }

        label {
            display: inline-block;
            margin-right: 10px;
            font-family: "Lato", sans-serif;
            font-size: 18px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-right: 20px;
        }

        .button-default-primary {
            padding: 8px 16px;
            cursor: pointer;
            margin-top: 10px;
            background-color: #73192D;
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 18px;
            border-radius: 12px;
        }

        .button-default-secondary {
            cursor: pointer;
            border-style: solid;
            border-width: 2px;
            background-color: white;
            color: #73192D;
            border-color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: "Lato", sans-serif;
            font-size: 18px;
            border-radius: 12px;
        }

        .row {
            display: flex;
            width: 100%;
            height: 100%;
            flex-direction: row;
            justify-content: start;

        }

        .row-space-around {
            display: flex;
            width: 100%;
            height: 100%;
            flex-direction: row;
            justify-content: space-around;

        }

        .row-center {
            display: flex;
            width: 100%;
            margin: 0px;

            flex-direction: row;
            justify-content: center;
        }

        .row-space-between {
            display: flex;
            width: 100%;
            margin: 0px;
            flex-direction: row;
            justify-content: space-between;
        }

        .row-auto {
            display: flex;
            width: auto;
            flex-direction: row;
            justify-content: start;
            align-items: center;
        }

        .column {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;

        }

        .column-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }



        .card {

            width: 100%;
            padding: 8px;
            margin: 10px;
            align-items: center;
            background-color: #ffffff;
            border-radius: 16px;

        }

        .card-2 {
            margin-block-end: 10px;
            width: 90px;
            height: 90px;
            padding: 12px;
            align-items: center;
            background-color: #C42B4C;

            border-radius: 30px;
            align-items: center;
        }


        .title-1 {
            font-family: "Playfair Display";
            font-size: 25px;
            margin: 8px;
        }


        .title-2 {
            font-family: "Bebas Neue";
            font-size: 35px;
        }

        .subtitle-1 {
            font-family: "Lato", sans-serif;
            font-size: 12px;
        }


        .input-group {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            font-family: 'Lato';
        }

        .hidden {
            display: none;
        }

        .text-body-1 {
            margin-left: 8px;
            margin-right: 8px;
            font-family: "Bebas Neue";
            font-size: 18px;
            color: #000000;
        }

        .text-body-2 {

            font-family: "Montserrat";
            font-size: 17px;
            font-weight: 200;
            color: #000000;

        }

        #chartContainer {
            height: 250px;
            width: 100%;

        }

        #chartContainerError {
            height: 250px;
            width: 100%;

        }

        .camera-image {
            margin: 10px;
            width: 80%;
            height: auto;
        }

        .divider {
            width: 10px;
            height: auto;
        }

        .divider-height {
            width: auto;
            height: 10px;
        }

        .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-family: 'Lato';
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container:hover input~.checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container input:checked~.checkmark {
            background-color: #4A101D;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container input:checked~.checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        input[type=number] {
            border: solid;
            border-radius: 8px;
            border-width: 1px;
            width: 60px;
            height: 25px;
            border-color: #1f1e1e;
            margin: 0px;
            font-size: 17px;
            min: 0;
            max: 100;
            text-align: center;

        }
    </style>
    <!-- Inclua o script CanvasJS -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>

<body style="background:#F5F5F5">


    <div class="row-center">
        <h1 class="title-1">Bancada Controle de Temperatura - LABCAM</h1>
    </div>

    <div class="row-space-around">
        <div class="card">
            <h1 class="title-1"> Dados do controlador PID </h1>

            <div id="viewMode" class="row-space-between">

                <div class="divider"></div>

                <div class="column-center">
                    <span class="text-body-2">KP</span>
                    <span class="title-2" id="kpText"></span>

                </div>

                <div class="column-center">
                    <span class="text-body-2">KI</span>
                    <span class="title-2" id="kiText"></span>

                </div>

                <div class="column-center">
                    <span class="text-body-2">KD</span>
                    <span class="title-2" id="kdText"></span>
                </div>

                <div class="column-center">
                    <span class="text-body-2">Modo</span>
                    <label class="title-2" id="isCollecting" name="isCollecting"></label>
                </div>

                <div class="column-center">
                    <span class="text-body-2">Setpoint</span>
                    <span class="title-2" id="setpointText"></span>


                </div>

                <div class="column-center">
                    <button class="button-default-secondary" id="editButton">Editar</button>
                </div>

                <div class="divider"></div>

            </div>



            <div id="editMode" class="hidden">

                <div class="row-space-between">

                    <div class="divider"></div>

                    <div class="column-center">
                        <span class="text-body-2">KP</span>
                        <input type="number" id="kp" name="kp">

                    </div>

                    <div class="column-center">
                        <span class="text-body-2">KI</span>
                        <input type="number" id="ki" name="ki">
                    </div>

                    <div class="column-center">
                        <span class="text-body-2">KD</span>
                        <input type="number" id="kd" name="kd">
                    </div>
                    

                    <div class="column-center">
                        <span class="text-body-2">Modo</span>
                        <label class="container">
                            <input type="checkbox" id="mode" name="mode" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                    </div>

                    <div class="column-center">
                        <span class="text-body-2">Setpoint</span>
                        <input type="number" id="setpoint" name="setpoint">
                    </div>

                    <div class="divider"></div>


                </div>

                <div class="divider-height"></div>


                <button class="button-default-primary" id="sendButton">Enviar</button>
                <button class="button-default-secondary " id="cancelButton">Cancelar</button>



            </div>



            <div id="chartContainer"></div>
            <div id="chartContainerError"></div>
          

            <button class="button-default-primary" id="saveDataButton">Exportar Dados</button>
            <button class="button-default-secondary" id="clearButton">Limpar Gráfico</button>



        </div>

        <div class="divider"></div>

        <div class="card">
            <h1 class="title-1"> Imagem câmera </h1>
            <div class="row-center">
                <img src="https://via.placeholder.com/400x300" alt="Simulação da Câmera" class="camera-image">
            </div>

        </div>

    </div>



    <script>
        // Função para obter valores da API e preencher os campos

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

        var routeControl = "http://192.168.0.110/labserver/control"
        var routePlant = "http://192.168.0.110/labserver/plant"

        function saveDataAsTxt() {
            fetch(routePlant) // Substitua pela URL da API que retorna os dados
                .then(response => response.json())
                .then(data => {
                    // Formatar os dados como texto
                    let contentData = '';
                    let contentCabeçalho = 'TEMPO | ERROR | TEMPERATURE | SETPOINT \n'; // Cabeçalhos do arquivo
                    
                    data.forEach(item => {
                        contentData += `${item.t},${item.e},${item.t}, ${item.s} \n`; // Formata tempo e temperatura
                    });

                    let content =  contentCabeçalho + contentData;
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
            fetch(routeControl)
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

            fetch(routeControl, { // Substitua por sua URL de API
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
            fetch(routePlant, { // Substitua por sua URL de API para limpar
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
        function updateChart(chart, chartError) {
            fetch(routePlant) // Substitua pela URL da API
                .then(response => response.json())
                .then(data => {
                    // Mapeia os dados para o formato necessário para o gráfico
                    const dataPointsTemperature = data.map(item => ({
                        x: item.t,
                        y: parseFloat(item.tp)
                    }));

                    const dataPointsError = data.map(item => ({
                        x: item.t,
                        y: parseFloat(item.e)
                    }));

                     const dataPointsSetpoint = data.map(item => ({
                        x: item.t,
                        y: parseFloat(item.s)
                    }));

                     


                    chart.options.data[0].dataPoints = dataPointsTemperature;
                    chart.options.data[1].dataPoints = dataPointsError;
                    chart.options.data[2].dataPoints = dataPointsSetpoint;

                    chartError.options.data[0].dataPoints = dataPointsError;
                   

                    chartError.render();
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

        chart.render();
        chartError.render();

        // Atualiza o gráfico a cada 1 segundo
        setInterval(() => updateChart(chart, chartError), 1000);


        // Chamar fetchValues para carregar dados iniciais ao carregar a página
        fetchValues();

        document.getElementById('editButton').addEventListener('click', () => toggleEditMode(true));
        document.getElementById('sendButton').addEventListener('click', sendValues);
        document.getElementById('cancelButton').addEventListener('click', cancelEdit);

        document.getElementById('saveDataButton').addEventListener('click', saveDataAsTxt);
        document.getElementById('clearButton').addEventListener('click', clearValues);
    </script>
</body>


</html>