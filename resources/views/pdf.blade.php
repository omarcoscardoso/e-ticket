<style>
    /* Estilos CSS para formatação */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 0.5px solid gray;
        padding: 3px;
        font-size: 11px; /* Fonte menor para melhor visualização */
        font-family: Arial, Helvetica, sans-serif;
    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    .data {
        font-size: 9px;
    }
</style>

<p> e-Ticket :: Relatório de Inscrições
    <br>
    <strong>Retiro de Jovens 2025</strong>
</p>
<p class="data">{{ date("d/m/Y H:m:s") }}</p>

<table>
    <thead>
        <tr>
            <!-- <th>ID</th> -->
            <th>Nome</th>
            <!-- <th>Endereço</th> -->
            <!-- <th>Nascimento</th> -->
            <th>Celular</th>
            <!-- <th>Sexo</th> -->
            <!-- <th>Batizado</th> -->
            <th>Igreja</th>
            <!-- <th>Camiseta</th> -->
            <th>Pagamento</th>
            <th>Status</th>
            <!-- <th>Obs</th> -->
            <!-- <th>Created At</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            <tr>
                <!-- <td>{{ $record->id }}</td> -->
                <td>{{ $record->nome }}</td>
                <!-- <td>{{ $record->data_nascimento }}</td> -->
                <!-- <td>{{ $record->sexo }}</td> -->
                <!-- <td>{{ $record->endereco }}</td> -->
                <td>{{ $record->celular }}</td>
                <!-- <td>{{ $record->batizado }}</td> -->
                <td>{{ $record->igreja->nome }}</td>
                <!-- <td>{{ $record->tamanho_camiseta }}</td> -->
                <td>{{ $record->tipo_pagamento }}</td>
                <td>{{ $record->status_pagamento }}</td>
                <!-- <td>{{ $record->observacao }}</td> -->
                <!-- <td>{{ $record->created_at }}</td> -->
            </tr>
        @endforeach
    </tbody>
</table>